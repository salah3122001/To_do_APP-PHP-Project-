<?php
// index.php
include_once "app/middleware/guest.php";
?>

<!doctype html>
<html lang="en">

<head>
    <title>ToDo App | Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-custom {
            width: 120px;
            margin: 10px;
            border-radius: 25px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center p-5">
                    <h2 class="mb-4 text-primary">Welcome to ToDo App</h2>
                    <p class="mb-4 text-muted">Organize your tasks easily and stay productive.</p>
                    <div>
                        <form action="register.php" method="post" style="display:inline;">
                            <button class="btn btn-success btn-custom">Register</button>
                        </form>
                        <form action="login.php" method="post" style="display:inline;">
                            <button class="btn btn-primary btn-custom">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>