<?php


require_once 'authorization.php';
session_start();


if (!empty($_SESSION)) {
    if (array_key_exists('userToView', $_SESSION)) {

        $user = [
            'id' => $_SESSION['userToView']['id'],
            'email' => $_SESSION['userToView']['email'],
            'name' => $_SESSION['userToView']['name'],
            'password' => $_SESSION['userToView']['password'],
        ];
    }

    if (isset($_SESSION['currentPage']))
        $currentPage = $_SESSION['currentPage'];
    else
        $currentPage = 1;
}

?>

<?php if (isAuthorized()) { ?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>User</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    </head>

    <body>
        <section class="vh-100" style="background-color: #DCDCDC;">
            <div class="container">
                <div class="d-flex justify-content-center align-items-center container">
                    <div class="card">
                        <div class="card-header">
                            <h3>
                                <p class="text-center">User</p>
                            </h3>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <p class="text-center"><?php echo $user['email']; ?></p>
                            </li>
                            <li class="list-group-item">
                                <p class="text-center"><?php echo $user['name']; ?></p>
                            </li>
                            <?php if (isSuperUser()) { ?>
                                <li class="list-group-item">
                                    <p class="text-center"><?php echo $user['password']; ?></p>
                                </li>
                            <?php } ?>
                        </ul>
                        <a href="index.php?page=<?php echo $currentPage; ?>" class="btn btn-outline-dark">Go back</a>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
            </div>
        </section>
    </body>

    </html>
<?php } else {
    header('location: /vendor/401.php');
} ?>