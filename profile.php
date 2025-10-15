<?php
include_once "app/middleware/auth.php"; // session start in file auth.php

if (isset($_POST['tasks'])) {
  header("location:task.php");
  exit();
}

if (isset($_POST['logout'])) {
  session_destroy();
  header("location:login.php");
  exit();
}
?>

<!doctype html>
<html lang="en">

<head>
  <title>Profile</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow border-0">
          <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Welcome, <?= htmlspecialchars($_SESSION['user']->name) ?></h3>
          </div>
          <div class="card-body text-center">
            <form action="" method="post">
              <button name="tasks" class="btn btn-success btn-lg mb-3 w-100">📋 My Tasks</button>
              <button name="logout" class="btn btn-danger btn-lg w-100">🚪 Logout</button>
            </form>
          </div>
          <div class="card-footer text-muted text-center">
            <small>ToDo App - Powered by PHP & MySQL</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>