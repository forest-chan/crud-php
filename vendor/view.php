<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './db.php';
require_once './authorization.php';
$config = require_once './config.php';

if (!(isAuthorized() && isSuperUser())) {
    header('location: /vendor/401.php');
}

$id = $_SESSION['userToView'];
$db = connectToDb($config);
$user = getUserFromDb($db, $config, ['id' => $id]);

$_SESSION['userInfo'] = $user;

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View profile</title>
    <link rel="stylesheet" type="text/css" href="../css/profile.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>

<body>
    <section class="vh-100" style="background-color: #DCDCDC;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-lg-6 mb-4 mb-lg-0">
                    <div class="card mb-3" style="border-radius: .5rem;">
                        <div class="row g-0">
                            <div class="col-md-4 gradient-custom text-center text-dark" style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                <form enctype="multipart/form-data" action="./uploadavatar.php" method="post">
                                    <?php if (file_exists('../avatars/' . $user['avatar'])) { ?>
                                        <div class="logo">
                                            <img src="../avatars/<?php echo $user['avatar']; ?>" alt="Avatar" class="img-fluid my-3" style="width: 90px;user-select: none;" />
                                        </div>
                                    <?php } else { ?>
                                        <div class="logo">
                                            <img src="../avatars/default.png" alt="Avatar" class="img-fluid my-3" style="width: 90px;" />
                                        </div>
                                    <?php } ?>
                                    <div class="mb-1">
                                        <a href="./update.php" class="btn btn-outline-dark" style="--bs-btn-padding-y: .50rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem; width: 80%">
                                            Update info
                                        </a>
                                    </div>
                                    <div class="mb-1">
                                        <a href="./index.php?page=<?php echo $_SESSION['currentPage']; ?>" class="btn btn-outline-dark" style="--bs-btn-padding-y: .50rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem; width: 80%">Go back</a>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body p-6">
                                    <h6>Information</h6>
                                    <hr class="mt-0 mb-4">
                                    <div class="row pt-1">
                                        <div class="col-6 mb-4">
                                            <h6>Email</h6>
                                            <p class="text-dark"><?php echo $user['email']; ?></p>
                                        </div>
                                        <div class="col-6 mb-4">
                                            <h6>Name</h6>
                                            <p class="text-dark"><?php echo $user['name']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>