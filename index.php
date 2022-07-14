<?php

require_once 'vendor/authorization.php';
session_start();

if (isAuthorized()) {
    session_destroy();
    session_start();
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In</title>
    <link type="text/css" rel="stylesheet" href="assets/css/main.css" />
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
                <form action="vendor/signin.php" method="POST" class="form-control">
                    <div class="form-group">
                        <div class="mb-4">
                            <input type="hidden">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-4">
                            <input type="email" name="email" placeholder="Enter email" value="<?php if (isset($_SESSION['userInfo']['email'])) echo $_SESSION['userInfo']['email']; ?>" class="form-control" id="inputUserName" aria-labelledby="emailnotification">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-4">
                            <input type="password" name="password" placeholder="Enter password" class="form-control" id="inputPassword" aria-labelledby="passwordnotification">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="d-flex justify-content-center align-items-center container">
                            <input type="submit" name="signIn" value="Sign in" class="btn btn-outline-dark">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="d-flex justify-content-center align-items-center container">
                            <p>Not registered yet? -
                                <a href="register.php">Sign up</a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
<?php if (isset($_SESSION['userInfo'])) {
    unset($_SESSION['userInfo']);
} ?>

</html>