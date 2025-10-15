<?php
include_once "app/middleware/auth.php";
include_once "app/classes/Task.php";

if (isset($_POST['profile'])) {
    header("location:profile.php");
    exit();
}

$task = new Task;

?>

<!doctype html>
<html lang="en">

<head>
    <title>Task Manager</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h3>Task Manager</h3>
            </div>
            <div class="card-body">

                <!-- Main buttons -->
                <form action="" method="post" class="text-center mb-4">
                    <button name="add" class="btn btn-success mr-2">➕ Add Task</button>
                    <button name="show" class="btn btn-info mr-2">📋 Show My Tasks</button>
                    <button name="profile" class="btn btn-secondary">⬅ Back To Profile</button>
                </form>

                <?php
                if (isset($_POST['insert'])) {
                    if (empty($_POST['title'])) {
                        $titleRequird = "<div class='alert alert-danger'>Title Is Required</div>";
                    } else {
                        $taskexist = new Task;
                        $taskexist->setTitle($_POST['title']);
                        $taskexist->setUserId($_SESSION['user']->id);
                        $checkresult = $taskexist->checkexists();
                        if ($checkresult && $checkresult->num_rows > 0) {
                            $titleexists = "<div class='alert alert-danger'>Title Already Exists!</div>";
                        } else {
                            $task->setTitle($_POST['title']);
                            $task->setIsCompleted($_POST['iscompleted']);
                            $task->setUserId($_SESSION['user']->id);
                            $result = $task->create();
                            if ($result) {
                                echo "<div class='alert alert-success'>✅ Added Successfully</div>";
                            } else {
                                echo "<div class='alert alert-danger'>❌ Try Again Later</div>";
                            }
                        }
                    }
                }

                // Add form
                if (isset($_POST['add']) || isset($titleRequird) || isset($titleexists)) { ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Add New Task</h5>
                            <form method="post" class="form-inline">
                                <input type="text" name="title" class="form-control mr-2 mb-2" placeholder="Task Title" required>
                                <?= isset($titleRequird) ? $titleRequird : '' ?>
                                <?= isset($titleexists) ? $titleexists : '' ?>

                                <select name="iscompleted" class="form-control mr-2 mb-2">
                                    <option value="0">Not Completed</option>
                                    <option value="1">Completed</option>
                                </select>
                                <button name="insert" class="btn btn-primary mb-2">Add Task</button>
                            </form>
                        </div>
                    </div>
                    <?php }

                // Show tasks
                if (isset($_POST['show'])) {
                    $task->setId($_SESSION['user']->id);
                    $result = $task->read();
                    $tasks = $result->fetch_all(MYSQLI_ASSOC);

                    if (!empty($tasks)) { ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Task</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tasks as $taskUser) { ?>
                                        <tr>
                                            <td><?= $_SESSION['user']->name ?></td>
                                            <td><?= $taskUser['title'] ?></td>
                                            <td>
                                                <?php if ($taskUser['is_completed'] == 0) { ?>
                                                    <span class="badge badge-warning">Not Completed</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-success">Completed</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <form action="" method="post" class="d-inline">
                                                    <input type="hidden" name="task_id" value="<?= $taskUser['id'] ?>">
                                                    <input type="hidden" name="old_title" value="<?= $taskUser['title'] ?>">
                                                    <input type="hidden" name="old_completed" value="<?= $taskUser['is_completed'] ?>">
                                                    <button name="edit" class="btn btn-sm btn-warning">✏ Edit</button>
                                                </form>
                                                <form action="" method="post" class="d-inline">
                                                    <input type="hidden" name="task_id" value="<?= $taskUser['id'] ?>">
                                                    <button name="delete" class="btn btn-sm btn-danger">🗑 Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php }
                }

                // Delete task
                if (isset($_POST['delete'])) {
                    $task->setId($_POST['task_id']);
                    $task->setUserId($_SESSION['user']->id);
                    $deleted = $task->delete();
                    if ($deleted) {
                        echo "<div class='alert alert-success'>Deleted Successfully ✅</div>";
                    } else {
                        echo "<div class='alert alert-danger'>❌ Try Again Later</div>";
                    }
                }
                // Update task
                if (isset($_POST['update'])) {
                    if (empty($_POST['newtitle'])) {
                        $newTitltRequired = "<div class='alert alert-danger'>❌ Title is required</div>";
                    } else {
                        $task->setId($_POST['task_id']);
                        $task->setTitle($_POST['newtitle']);
                        $task->setIsCompleted($_POST['newcompleted']);
                        $result = $task->update();
                        if ($result) {
                            echo "<div class='alert alert-success'>Updated Successfully ✅</div>";
                        } else {
                            echo "<div class='alert alert-danger'>❌ Try Again Later</div>";
                        }
                    }
                }

                // Edit form
                if (isset($_POST['edit']) || isset($newTitltRequired)) { ?>
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">Edit Task</h5>
                            <form method="post" class="form-inline">
                                <input type="hidden" name="task_id" value="<?= $_POST['task_id'] ?>">
                                <input type="text" name="newtitle" class="form-control mr-2 mb-2" value="<?= isset($_POST['old_title']) ? $_POST['old_title'] : '' ?>" required>
                                <?= isset($newTitltRequired) ? $newTitltRequired : '' ?>
                                <select name="newcompleted" class="form-control mr-2 mb-2">
                                    <option value="0" <?= isset($_POST['old_completed']) == 0 ? 'selected' : '' ?>>Not Completed</option>
                                    <option value="1" <?= isset($_POST['old_completed']) == 1 ? 'selected' : '' ?>>Completed</option>
                                </select>
                                <button name="update" class="btn btn-success mb-2">Update</button>
                            </form>
                        </div>
                    </div>
                <?php }


                ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>