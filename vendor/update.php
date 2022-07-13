<?php
require_once 'authorization.php';

session_start();

?>

<?php if (isAuthorized()) { ?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Update User</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    </head>

    <body>
        <section class="vh-100" style="background-color: #DCDCDC;">
            <div class="d-flex justify-content-center align-items-center container">
                <div class="row">
                    <div class="container">
                        <?php if (isset($_SESSION['message'])) { ?>
                            <?php foreach ($_SESSION['message'] as $message) { ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <p class="text-center">
                                        <strong><?php echo $message; ?></strong>
                                    </p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php }
                            unset($_SESSION['message']);
                            ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center container ">
                <div class="row">
                    <form action="./script.php" method="POST" class="form-control">
                        <div class="form-group">
                            <div class="mb-4">
                                <input type="hidden">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <input type="hidden" name="id" value="<?php if (isset($_SESSION['userInfo']['id'])) echo $_SESSION['userInfo']['id']; ?>" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <input type="email" name="email" placeholder="Enter new email" value="<?php if (isset($_SESSION['userInfo']['email'])) echo $_SESSION['userInfo']['email']; ?>" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <input type="text" name="name" placeholder="Enter new name" value="<?php if (isset($_SESSION['userInfo']['name'])) echo $_SESSION['userInfo']['name']; ?>" required class="form-control" id="exampleInputPassword1">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-4">
                                <input type="password" name="password" placeholder="Enter new password" class="form-control" id="exampleInputPassword1">
                                <div id="emailHelp" class="form-text">If the password is empty, the old one will be used</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-center align-items-center container">
                                <button type="submit" name="update" required class="btn btn-outline-dark">Update</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-center align-items-center container">
                                <p>Missclick? -
                                    <a href="/vendor/update.php">Refresh</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    </body>
    <?php if (isset($_SESSION['userInfo'])) {
        unset($_SESSION['userInfo']);
    } ?>

    </html>
<?php } else {
    header('location: ./401.php');
} ?>