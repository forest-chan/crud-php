<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once 'authorization.php';
require_once 'db.php';
require_once 'validation.php';
$config = require_once 'config.php';

if (isset($_SESSION['currentPage']))
    $currentPage = $_SESSION['currentPage'];
else
    $currentPage = 1;

$db = connectToDb($config);

if (!empty($_POST)) {
    if (
        array_key_exists('submit', $_POST) && array_key_exists('email', $_POST)
        && array_key_exists('name', $_POST) && array_key_exists('password', $_POST)
    ) {
        if (!(isAuthorized() && isSuperUser())) {
            header('location: ./401.php');
        }

        extract($_POST);

        $form = [
            'email' => $email,
            'name' => $name,
            'password' => $password,
        ];

        $errors = validateĞ¡reateUserForm($form, $config);

        if (empty($errors)) {
            $form['password'] = md5($password);
            $form['token'] = getUniqueToken();

            if (isset($isAdmin))
                $form['status'] = 1;
            else
                $form['status'] = 0;

            insertUserIntoDb($db, $config, $form);
            header('location: /vendor/index.php' . '?page=' . $currentPage);
        } else {
            $_SESSION['userInfo'] = $form;
            $_SESSION['message'] = $errors;
            header('location: /vendor/create.php');
        }
    } elseif (
        array_key_exists('update', $_POST) && array_key_exists('email', $_POST)
        && array_key_exists('name', $_POST) && array_key_exists('password', $_POST)
    ) {

        if (isAuthorized()) {

            extract($_POST);

            //id should be the last field
            $form = [
                'name' => $name,
                'email' => $email,
                'password' => $password,
            ];

            $prevUserInfo = getUserFromDb($db, $config, ['id' => $id]);

            if ($prevUserInfo['email'] == $email) {
                unset($form['email']);
            }
            if (empty($form['password'])) {
                unset($form['password']);
            }

            $errors = validateUpdateUserForm($form, $config);

            if (isset($form['password'])) {
                $form['password'] = md5($form['password']);
            } else {
                $form['password'] = $prevUserInfo['password'];
            }
            if (!isset($form['email'])) {
                $form['email'] = $prevUserInfo['email'];
            }

            if (empty($errors)) {
                $form['id'] = $id;
                updateUserIntoDb($db, $config, $form);
                header('location: /vendor/index.php' . '?page=' . $currentPage);
            } else {
                $_SESSION['message'] = $errors;
                $_SESSION['userInfo'] = $form;
                header('location: /vendor/update.php');
            }
        } else {
            header('location: ./401.php');
        }
    }
} elseif (!empty($_GET)) {
    if (array_key_exists('upd', $_GET)) {

        if (!(isAuthorized() && isSuperUser())) {
            header('location: /vendor/401.php');
        } else {
            $id = $_GET['upd'];
            $user = getUserFromDb($db, $config, ['id' => $id]);
            $_SESSION['userInfo'] = $user;
            header('location: /vendor/update.php');
        }
    } elseif (array_key_exists('view', $_GET)) {
        if (!isAuthorized()) {
            header('location: /vendor/401.php');
        } else {
            $id = $_GET['view'];
            $_SESSION['userToView'] = $id;

            header('location: /vendor/view.php');
        }
    } elseif (array_key_exists('del', $_GET)) {

        if (!(isAuthorized() && isSuperUser())) {
            header('location: /vendor/401.php');
        } else {
            $id = $_GET['del'];
            if ($id != $_SESSION['id']) {

                $fieldsToUpdate = [
                    'is_deleted' => 1,
                    'id' => $id
                ];
                updateUserIntoDb($db, $config, $fieldsToUpdate);
                // deleteUserFromDb($db, $config, $id);
            }
            header('location: /vendor/index.php' . '?page=' . $currentPage);
        }
    }
}
