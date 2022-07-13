<?php

function connectToDb(array $config): PDO
{

    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {

        $db = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['username'], $config['password'], $opt);
        return $db;
    } catch (PDOException $e) {
        echo "An error occurs when connecting to db: " . $e->getMessage();
        exit();
    }
}

function insertUserIntoDb(PDO $db, array $config, array $userInfo)
{
    $sql = "INSERT INTO {$config['tablename']} VALUES (NULL,?,?,?,?,?,0)";
    $stmt = $db->prepare($sql);

    try {
        $db->beginTransaction();
        $stmt->execute(array($userInfo['email'], $userInfo['name'], $userInfo['password'], $userInfo['token'], $userInfo['status']));
        $db->commit();
    } catch (PDOException $e) {
        echo 'An error:' . $e->getMessage();
        $db->rollBack();
    }
}

function updateUserIntoDb(PDO $db, array $config, array $userInfo)
{
    $sql = "UPDATE {$config['tablename']} SET email=?, name=?, password=? WHERE id=?";
    $stmt = $db->prepare($sql);

    try {
        $db->beginTransaction();
        $stmt->execute(array($userInfo['email'], $userInfo['name'], $userInfo['password'], $userInfo['id']));
        $db->commit();
    } catch (PDOException $e) {
        echo 'An error:' . $e->getMessage();
        die;
        $db->rollBack();
    }
}

function updateUserAvatarIntoDb(PDO $db, array $config, array $userInfo){
    $sql = "UPDATE {$config['tablename']} SET avatar=? WHERE id=?";
    $stmt = $db->prepare($sql);

    try {
        $db->beginTransaction();
        $stmt->execute(array($userInfo['avatar'], $userInfo['id']));
        $db->commit();
    } catch (PDOException $e) {
        echo 'An error:' . $e->getMessage();
        die;
        $db->rollBack();
    }
}

function deleteUserFromDb(PDO $db, array $config, int $id)
{
    $sql = "UPDATE {$config['tablename']} SET is_deleted=1 WHERE id=?";
    $stmt = $db->prepare($sql);

    try {
        $db->beginTransaction();
        $stmt->execute(array($id));
        $db->commit();
    } catch (PDOException $e) {
        echo 'An error:' . $e->getMessage();
        $db->rollBack();
    }
}

function getUserFromDbById(PDO $db, array $config, int $id)
{
    $user = [];
    $sql = "SELECT * FROM {$config['tablename']} WHERE id=?";
    $stmt = $db->prepare($sql);

    try {
        $db->beginTransaction();
        $stmt->execute(array($id));
        $db->commit();
    } catch (PDOException $e) {
        echo 'An error:' . $e->getMessage();
        $db->rollBack();
    }


    $user = $stmt->fetch(PDO::FETCH_LAZY);
    $user = [
        'id' => $user['id'],
        'email' => $user['email'],
        'name' => $user['name'],
        'password' => $user['password'],
        'token' => $user['token'],
        'status' => $user['status'],
        'is_deleted' =>$user['is_deleted'],
        'avatar' => $user['avatar'],
    ];


    return $user;
}

function getUserFromDbByEmail(PDO $db, array $config, string $email)
{
    $user = [];
    $sql = "SELECT * FROM {$config['tablename']} WHERE email=?";
    $stmt = $db->prepare($sql);

    try {
        $db->beginTransaction();
        $stmt->execute(array($email));
        $db->commit();
    } catch (PDOException $e) {
        echo 'An error:' . $e->getMessage();
        $db->rollBack();
    }


    if ($stmt->rowCount()) {
        $user = $stmt->fetch(PDO::FETCH_LAZY);
        $user = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'password' => $user['password'],
            'token' => $user['token'],
            'status' => $user['status'],
            'is_deleted' =>$user['is_deleted'],
            'avatar' => $user['avatar'],
        ];
    }


    return $user;
}

function getUserFromDbByLogin(PDO $db, array $config, $email, $password)
{
    $user = [];
    $sql = "SELECT * FROM {$config['tablename']} WHERE `email`=? and `password`=?";
    $stmt = $db->prepare($sql);

    try {
        $db->beginTransaction();
        $stmt->execute(array($email, $password));
        $db->commit();
    } catch (PDOException $e) {
        echo 'An error:' . $e->getMessage();
        $db->rollBack();
    }

    if ($stmt->rowCount()) {
        $user = $stmt->fetch(PDO::FETCH_LAZY);
        $user = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'password' => $user['password'],
            'token' => $user['token'],
            'status' => $user['status'],
            'is_deleted' =>$user['is_deleted'],
            'avatar' => $user['avatar'],
        ];
    }


    return $user;
}

function getAllUsersFromDb(PDO $db, array $config)
{
    $users = [];
    $sql = "SELECT * FROM {$config['tablename']}";
    $stmt = $db->query($sql);

    while ($user = $stmt->fetch(PDO::FETCH_LAZY)) {
        $users[] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'password' => $user['password'],
            'token' => $user['token'],
            'status' => $user['status'],
            'is_deleted' =>$user['is_deleted'],
            'avatar' => $user['avatar'],
        ];
    }

    return $users;
}


function getUsersPerPageFromDb(PDO $db, array $config, int $recordsOnPage, int $from){
    $users = [];
    $sql = "SELECT * FROM {$config['tablename']} WHERE id>0 AND is_deleted=0 LIMIT $from,$recordsOnPage";
    $stmt = $db->query($sql);
    
    while ($user = $stmt->fetch(PDO::FETCH_LAZY)) {
        $users[] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'password' => $user['password'],
            'token' => $user['token'],
            'status' => $user['status'],
            'is_deleted' =>$user['is_deleted'],
            'avatar' => $user['avatar'],
        ];
    }


    return $users;
}

function getCountOfUsersFromDb(PDO $db, array $config){
    $count = 0;
    $sql = "SELECT COUNT(*) as count FROM {$config['tablename']}";
    $stmt = $db->query($sql);
    $count = $stmt->fetch(PDO::FETCH_LAZY)['count'];

    return $count;
}