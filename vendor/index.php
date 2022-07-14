<?php

session_start();

require_once 'authorization.php';
require_once 'script.php';
require_once 'db.php';
$config = require 'config.php';
$currentPage = 1;


if(isset($_SESSION['userInfo'])){
    unset($_SESSION['userInfo']);
}

if (!empty($_GET)) {
    if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
        $currentPage = $_GET['page'];
    }
}

$_SESSION['currentPage'] = $currentPage;

$recordsOnPage = 8;
$countOfPages = ceil(getCountOfUsersFromDb($db, $config) / $recordsOnPage);

if ($currentPage > $countOfPages) {
    $currentPage = 1;
}

$from = ($currentPage - 1) * $recordsOnPage;


$db = connectToDb($config);
$users = getUsersPerPageFromDb($db, $config, $recordsOnPage, $from);


?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Users</title>
    <link>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>

<body>
    <section class="vh-100" style="background-color: #DCDCDC;">
        <?php if (isAuthorized() && isSuperUser()) { ?>
            <div class="container">
                <ul class="nav justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link disabled"><?php if (isset($_SESSION['email'])) echo $_SESSION['email'] . ' (admin)'; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-dark" aria-current="page" href="./profile.php">My profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-dark" aria-current="page" href="./create.php">Create user</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-dark" aria-current="page" href="../">Logout</a>
                    </li>
                </ul>
                <div class="d-flex justify-content-center align-items-center container">
                    <table class="table table-light table-striped">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Email</th>
                                <th scope="col">Name</th>
                                <th scope="col">Operations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)) { ?>
                                <?php foreach ($users as $user) { ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td><?php echo $user['email']; ?></td>
                                        <td><?php echo $user['name']; ?></td>
                                        <td>
                                            <?php if ($_SESSION['id'] != $user['id']) { ?>
                                                <a href="<?php echo "script.php?del={$user['id']}"; ?>" class="btn btn-outline-dark">Delete</a>
                                                <a href="<?php echo "script.php?view={$user['id']}"; ?>" class="btn btn-outline-dark">View</a>
                                            <?php } else { ?>
                                                <a href="" class="btn btn-outline-dark disabled">Delete<a>
                                                        <a href="" class="btn btn-outline-dark disabled">View<a>
                                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1) { ?>
                            <li class="page-item">
                                <a href="./index.php?page=<?php echo $currentPage - 1; ?>" class="page-link">Previous</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="/vendor/index.php">1</a>
                            </li>
                        <?php } else { ?>
                            <li class="page-item disabled">
                                <a class="page-link">Previous</a>
                            </li>
                            <li class="page-item disabled">
                                <a class="page-link" href="/vendor/index.php">1</a>
                            </li>
                        <?php } ?>
                        <li class="page-item active" aria-current="page"><a class="page-link" href=""><?php echo $currentPage; ?></a></li>
                        <?php if ($currentPage < $countOfPages) { ?>
                            <li class="page-item">
                                <a class="page-link" href="/vendor/index.php?page=<?php echo $countOfPages; ?>"><?php echo $countOfPages; ?></a>
                            </li>
                            <li class="page-item">
                                <a href="./index.php?page=<?php echo $currentPage + 1; ?>" class="page-link">Next</a>
                            </li>
                        <?php } else { ?>
                            <li class="page-item disabled">
                                <a class="page-link" href="/vendor/index.php?page=<?php echo $countOfPages; ?>"><?php echo $countOfPages; ?></a>
                            </li>
                            <li class="page-item disabled">
                                <a class="page-link">Next</a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
            </div>
        <?php } elseif (isAuthorized()) { ?>
            <div class="container">
                <ul class="nav justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link disabled"><?php if (isset($_SESSION['email'])) echo $_SESSION['email']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-dark" aria-current="page" href="./profile.php">My profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-dark" aria-current="page" href="../">Logout</a>
                </ul>
                <div class="d-flex justify-content-center align-items-center container">
                    <table class="table table-light table-striped">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Email</th>
                                <th scope="col">Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)) { ?>
                                <?php foreach ($users as $user) { ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td><?php echo $user['email']; ?></td>
                                        <td><?php echo $user['name']; ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1) { ?>
                            <li class="page-item">
                                <a href="./index.php?page=<?php echo $currentPage - 1; ?>" class="page-link">Previous</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="/vendor/index.php">1</a>
                            </li>
                        <?php } else { ?>
                            <li class="page-item disabled">
                                <a class="page-link">Previous</a>
                            </li>
                            <li class="page-item disabled">
                                <a class="page-link" href="/vendor/index.php">1</a>
                            </li>
                        <?php } ?>
                        <li class="page-item active" aria-current="page"><a class="page-link" href=""><?php echo $currentPage; ?></a></li>
                        <?php if ($currentPage < $countOfPages) { ?>
                            <li class="page-item">
                                <a class="page-link" href="/vendor/index.php?page=<?php echo $countOfPages; ?>"><?php echo $countOfPages; ?></a>
                            </li>
                            <li class="page-item">
                                <a href="./index.php?page=<?php echo $currentPage + 1; ?>" class="page-link">Next</a>
                            </li>
                        <?php } else { ?>
                            <li class="page-item disabled">
                                <a class="page-link" href="/vendor/index.php?page=<?php echo $countOfPages; ?>"><?php echo $countOfPages; ?></a>
                            </li>
                            <li class="page-item disabled">
                                <a class="page-link">Next</a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    </div>
<?php } else {
            header('location: ./401.php');
        } ?>



</body>

</html>