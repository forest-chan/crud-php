<?php

session_start();

function getUniqueToken() : string {
    $token = (string)(time() . rand());
    return $token;
}

function isSuperUser() : bool {
    if (!empty($_SESSION)) {
    
        if(array_key_exists('status', $_SESSION) && $_SESSION['status'] == 1){
            return true;
        }
    }
    return false;
}


function isAuthorized() : bool {
    if (!empty($_SESSION)) {
    
        if(array_key_exists('token', $_SESSION)){
            return true;
        }
    }
    return false;
}