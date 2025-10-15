<?php
include_once "app/middleware/guest.php"; // sessionstart in this file guest.php
include_once "app/classes/user.php";
include_once "app/requests/Validation.php";


if ($_POST) {
  $success = [];
  $emailValidation = new Validation('Email', $_POST['email']);
  $emailRequiredRequest = $emailValidation->required();
  $emailPattern = "/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/";

  if (empty($emailRequiredRequest)) {
    $emailRegEx = $emailValidation->regex($emailPattern);
    if (empty($emailRegEx)) {
      $success['email'] = "email";
    }
  }
  $passwordValidation = new Validation('Password', $_POST['password']);
  $passwordRequiredResult = $passwordValidation->required();
  $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/";
  if (empty($passwordRequiredResult)) {
    $passwordRegExResult = $passwordValidation->regex($passwordPattern);
    if (empty($passwordRegExResult)) {
      $success['password'] = "password";
    }
  }


  if (isset($success['email']) && isset($success['password'])) {
    $userObject = new User;

    $userObject->setEmail($_POST['email']);
    $userObject->setPassword($_POST['password']);
    $result = $userObject->login();
    if ($result) {
      $user = $result->fetch_object();
      if ($user->status == 1) {
        $_SESSION['user'] = $user;
        header("location:profile.php");
        exit();
      }
    } else {
      $incorrectpassword = "Incorrct Password";
    }
  } else {
    $error = "<div class='alert alert-danger'>Email Or Password Is Incorrect Or Your Account Is Not Verified</div>";
  }
}


?>



<!doctype html>
<html lang="en">

<head>
  <title>Login</title>
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
          <h3 class="mb-0">Welcome Back</h3>
          <small>Please login to your account</small>
        </div>
        <div class="card-body">

          <?php if (isset($error)) echo $error; ?>

          <form method="post" class="mt-3">
            <!-- Email -->
            <div class="form-group">
              <label for="email">Email address</label>
              <input
                type="email"
                class="form-control <?= (!empty($emailRequiredRequest) || !empty($emailRegEx)) ? 'is-invalid' : '' ?>"
                id="email"
                name="email"
                value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>"
                placeholder="Enter your email">
              <?php if (!empty($emailRequiredRequest)) echo "<div class='invalid-feedback'>$emailRequiredRequest</div>"; ?>
              <?php if (!empty($emailRegEx)) echo "<div class='invalid-feedback'>$emailRegEx</div>"; ?>
            </div>

            <!-- Password -->
            <div class="form-group">
              <label for="password">Password</label>
              <input
                type="password"
                class="form-control <?= (!empty($passwordRequiredResult) || !empty($passwordRegExResult)) ? 'is-invalid' : '' ?>"
                id="password"
                name="password"
                placeholder="Enter your password">
              <?php if (!empty($passwordRequiredResult)) echo "<div class='invalid-feedback'>$passwordRequiredResult</div>"; ?>
              <?php if (!empty($passwordRegExResult)) echo "<div class='invalid-feedback'>Minimum 8–15 chars, at least one uppercase, one lowercase, one number and one special char</div>"; ?>
              <?= isset($incorrectpassword) ? "<div class='alert alert-danger'>$incorrectpassword</div>" : '' ?>
            </div>

            <!-- Submit -->
            <div class="form-group text-center">
              <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
            </div>
          </form>
        </div>
        <div class="card-footer text-center">
          <small>Don’t have an account? <a href="register.php">Register here</a></small>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>