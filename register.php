<?php

include_once 'app/requests/Validation.php';
include_once 'app/classes/User.php';
include_once 'app/sevices/mail.php';
include_once "app/middleware/guest.php";

$success = [];

if ($_POST) {

    $nameValidation = new Validation('Name', $_POST['name']);
    $nameRequiredResult = $nameValidation->required();
    if (empty($nameRequiredResult)) {
        $success['name'] = 'name';
    }
    $emailValidation = new Validation('Email', $_POST['email']);
    $emailRequiredResult = $emailValidation->required();
    $emailPattern = "/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/";

    if (empty($emailRequiredResult)) {
        $emailRegEx = $emailValidation->regex($emailPattern);
        if (empty($emailRegEx)) {
            $emailUniqueResult = $emailValidation->unique('users');
            if (empty($emailUniqueResult)) {
                $success['email'] = 'email';
            }
        }
    }

    $passwordValidation = new Validation('Password', $_POST['password']);
    $passwordRequiredResult = $passwordValidation->required();
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/";

    if (empty($passwordRequiredResult)) {
        $passwordRegEx = $passwordValidation->regex($passwordPattern);
        if (empty($passwordRegEx)) {
            $passwordConfirmationResult = $passwordValidation->confirmed($_POST['passwordConfirmaition']);
            if (empty($passwordConfirmationResult)) {
                $success['password'] = 'password';
            }
        }
    }

    if (isset($success['name']) && isset($success['email']) && isset($success['password'])) {
        $userObject = new User;
        $userObject->setName($_POST['name']);
        $userObject->setEmail($_POST['email']);
        $userObject->setPassword($_POST['password']);
        $code = rand(10000, 99999);
        $userObject->setCode($code);
        $result = $userObject->create();
        if ($result) {
            $subject = 'Verification Code';
            $body = "Hello {$_POST['name']} Your Verification Code Is {$code}";
            $mail = new mail($_POST['email'], $subject, $body);
            $mailResult = $mail->send();
            if ($mailResult) {
                $_SESSION['user-email'] = $_POST['email'];
                header("location:check-code.php");
            } else {
                $errors = "<div class='alert alert-danger'>Try Again Later<div>";
            }
        } else {
            $errors = "<div class='alert alert-danger'>Try Again Later<div>";
        }
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <title>Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container d-flex align-items-center justify-content-center" style="min-height:100vh;">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Create Account</h3>
                </div>
                <div class="card-body">
                    <?= isset($errors) ? $errors : '' ?>
                    <form method="post">

                        <!-- Name -->
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input
                                required
                                type="text"
                                name="name"
                                id="name"
                                class="form-control <?= !empty($nameRequiredResult) ? 'is-invalid' : '' ?>"
                                value="<?= isset($_POST['name']) ? $_POST['name'] : '' ?>"
                                placeholder="Enter your name">
                            <?= empty($nameRequiredResult) ? '' : "<div class='invalid-feedback'>$nameRequiredResult</div>" ?>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                required
                                type="email"
                                name="email"
                                id="email"
                                class="form-control <?= (!empty($emailRequiredResult) || !empty($emailRegEx) || !empty($emailUniqueResult)) ? 'is-invalid' : '' ?>"
                                value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>"
                                placeholder="Enter your email">
                            <?= empty($emailRequiredResult) ? '' : "<div class='invalid-feedback'>$emailRequiredResult</div>" ?>
                            <?= empty($emailRegEx) ? '' : "<div class='invalid-feedback'>$emailRegEx</div>" ?>
                            <?= empty($emailUniqueResult) ? '' : "<div class='invalid-feedback'>$emailUniqueResult</div>" ?>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input
                                required
                                type="password"
                                name="password"
                                id="password"
                                class="form-control <?= (!empty($passwordRequiredResult) || !empty($passwordRegEx) || !empty($passwordConfirmationResult)) ? 'is-invalid' : '' ?>"
                                placeholder="Enter your password">
                            <?= empty($passwordRequiredResult) ? '' : "<div class='invalid-feedback'>$passwordRequiredResult</div>" ?>
                            <?= empty($passwordRegEx) ? '' : "<div class='invalid-feedback'>Minimum 8-15 characters, at least one uppercase, one lowercase, one number, one special character</div>" ?>
                            <?= empty($passwordConfirmationResult) ? '' : "<div class='invalid-feedback'>$passwordConfirmationResult</div>" ?>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="passwordConfirmaition">Confirm Password</label>
                            <input
                                required
                                type="password"
                                name="passwordConfirmaition"
                                id="passwordConfirmaition"
                                class="form-control"
                                placeholder="Re-enter your password">
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <small>Already have an account? <a href="login.php">Login</a></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>