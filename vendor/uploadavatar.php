<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once './authorization.php';
require_once './db.php';
$config = require_once './config.php';

if (isset($_POST['submit'])) {
    if (isset($_FILES['avatar']) and $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
        if (isAuthorized()) {
            $fileTmpPath = $_FILES['avatar']['tmp_name'];
            $filename = $_FILES['avatar']['name'];
            $filenameParts = explode('.', $filename);
            $fileExtension = strtolower(end($filenameParts));
            $allowExtensions = array('jpg', 'jpeg', 'png');

            if (in_array($fileExtension, $allowExtensions)) {
                $destination = $_SERVER['DOCUMENT_ROOT'] . '/avatars/' . $filename;

                if (move_uploaded_file($fileTmpPath, $destination)) {
                    $db = connectToDb($config);

                    $userInfo = [
                        'id' => $_SESSION['id'],
                        'avatar' => $filename,
                    ];

                    updateUserAvatarIntoDb($db, $config, $userInfo);
                    header('location: ./profile.php');
                } else{
                    header('location: ./profile.php');
                }
            } else{
                header('location: ./profile.php');
            }
        } else{
            header('location: ./profile.php');
        }
    } else{
        header('location: ./profile.php');
    }
} else{
    header('location: ./profile.php');
}

