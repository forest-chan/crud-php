<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'validation.php';
require_once 'db.php';
$config = require_once 'config.php';



if (!empty($_POST)) {

    if (
        array_key_exists('signIn', $_POST) && array_key_exists('email', $_POST)
        && array_key_exists('password', $_POST)
    ) {


        extract($_POST);


        $form = [
            'email' => $email,
            'password' => $password,
        ];

        $errors = validateSingInForm($form, $config);

        if (!empty($errors)) {
            $_SESSION['userInfo'] = $form;
            $_SESSION['message'] = $errors;
            header('location: /');
        } else {
            $password = md5($password);
            $db = connectToDb($config);
            $user = getUserFromDbByLogin($db, $config, $email, $password);

            if (!empty($user) && $user['is_deleted'] === 0) {
                $_SESSION["token"] = $user['token'];
                $_SESSION['status'] = $user['status'];
                $_SESSION['id'] = $user['id'];
                $_SESSION['email'] = $user['email']; 
                header('location: /vendor/index.php');
            } else {
                $_SESSION['userInfo'] = $form;
                $_SESSION['message'] = ['Incorrect email or password'];
                header('location: ../');
            }
        }
    }
}
