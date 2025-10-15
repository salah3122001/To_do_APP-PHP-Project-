<?php
session_start();
include_once 'app/classes/User.php';

if ($_POST) {
  $errors = [];

  if (empty($_POST['code'])) {
    $errors['required'] = "<div class='alert alert-danger'>Code Is Required</div>";
  } else {
    if (strlen($_POST['code']) !== 5) {
      $errors['digits'] = "<div class='alert alert-danger'>Code Must Be 5 Digits</div>";
    }
  }
  if (empty($errors)) {
    $user = new User;
    $user->setCode($_POST['code']);
    $user->setEmail($_SESSION['user-email']);
    $result = $user->checkcode();
    if ($result) {
      $user->setStatus(1);
      $user->changeStatus();
      header("location:login.php");
      exit();
    }
  } else {
    $errors['wrong'] = "<div class='alert alert-danger'>Try Again Later</div>";
  }
}





?>



<!doctype html>
<html lang="en">

<head>
  <title>Verify Code</title>
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
          <h3 class="mb-0">Email Verification</h3>
        </div>
        <div class="card-body">
          <?php
          if (!empty($errors)) {
            foreach ($errors as $value) {
              echo $value;
            }
          }
          ?>
          <form method="post" class="mt-3">
            <div class="form-group">
              <label for="code">Enter Your Verification Code</label>
              <input
                type="number"
                name="code"
                id="code"
                class="form-control <?= !empty($errors) ? 'is-invalid' : '' ?>"
                placeholder="e.g. 12345"
                min="10000"
                max="99999"
                value="<?= isset($_POST['code']) ? $_POST['code'] : '' ?>">
            </div>
            <div class="form-group text-center">
              <button type="submit" name="check" class="btn btn-primary btn-block">Verify</button>
            </div>
          </form>
        </div>
        <div class="card-footer text-center">
          <small>Didn’t receive the code? <a href="#">Resend</a></small>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>