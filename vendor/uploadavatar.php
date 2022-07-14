<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once './authorization.php';
require_once './db.php';
$config = require_once './config.php';

function issetAvatar($user){
    if($user['avatar'] == 'default.png'){
        return false;
    }

    $path = $_SERVER['DOCUMENT_ROOT'] . '/avatars/' . $user['avatar'];
    return file_exists($path);
}

function deleteAvatar($user){
    $path = $_SERVER['DOCUMENT_ROOT'] . '/avatars/' . $user['avatar'];
    if(issetAvatar($user)){
        unlink($path);
    }
}


if (isset($_POST['submit'])) {
    if (isset($_FILES['avatar']) and $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
        if (isAuthorized()) {
            $fileTmpPath = $_FILES['avatar']['tmp_name'];
            $filename = $_SESSION['id'] . $_FILES['avatar']['name'];
            $filenameParts = explode('.', $filename);
            $fileExtension = strtolower(end($filenameParts));
            $allowExtensions = array('jpg', 'jpeg', 'png');
            $allowMimeTypes = array('image/jpeg', 'image/jpg', 'image/png');
            $mimeType = mime_content_type($fileTmpPath);

            if (in_array($fileExtension, $allowExtensions) && in_array($mimeType, $allowMimeTypes)) {
                $destination = $_SERVER['DOCUMENT_ROOT'] . '/avatars/' . $filename;
                
                if (move_uploaded_file($fileTmpPath, $destination)) {
                    $db = connectToDb($config);
                    $user = getUserFromDb($db, $config, ['id' => $_SESSION['id']]);
                   
                    if(issetAvatar($user)){
                        deleteAvatar($user);
                    }
                    
                    $fieldsToUpdate = [
                        'avatar' => $filename,
                        'id' => $user['id'],
                    ];

                    updateUserIntoDb($db, $config, $fieldsToUpdate);
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

