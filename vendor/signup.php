<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'validation.php';
require_once 'authorization.php';
require_once 'db.php';
$config = require_once 'config.php';



if (!empty($_POST)) {

    if (
        array_key_exists('signUp', $_POST) && array_key_exists('email', $_POST)
        && array_key_exists('name', $_POST) && array_key_exists('password', $_POST)
        && array_key_exists('repeatPassword', $_POST)
    ) {

        extract($_POST);

        $form = [
            'email' => $email,
            'name' => $name,
            'password' => $password,
            'repeatPassword' => $repeatPassword,
        ];

        if ($password === $repeatPassword) {

            $errors = validateSingUpForm($form, $config);

            if ($errors) {
                $_SESSION['message'] = $errors;
                $_SESSION['userInfo'] = $form;
                header('location: /register.php');
            } else {
                $form['password'] = md5($form['password']);
                $form['token'] = getUniqueToken();
                $form['status'] = 0;
                $db = connectToDb($config);
                insertUserIntoDb($db, $config, $form);
                header('location: /');
            }

        } else {
            $_SESSION['message'] = ['Passwords don\'t match'];
            $_SESSION['userInfo'] = $form;
            header('location: ../register.php');
        }
    }
}
