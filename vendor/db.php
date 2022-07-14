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
    $sql = "INSERT INTO {$config['tablename']} VALUES (NULL,?,?,?,?,?,0, 'default.png')";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($userInfo['email'], $userInfo['name'], $userInfo['password'], $userInfo['token'], $userInfo['status']));
}


function updateUserIntoDb(PDO $db, array $config, array $fieldsToUpdate)
{
    // id should be the last field!
    // creating sql query string
    $sql = "UPDATE `{$config['tablename']}` SET ";
    foreach ($fieldsToUpdate as $key => $value) {
        if($key!='id'){
            $sql .= "`$key`=?, ";
        }
    }
    $sql = trim($sql, ', ') . ' WHERE `id`=?';

    $stmt = $db->prepare($sql);
    $stmt->execute(array_values($fieldsToUpdate));
}

function getUserFromDb($db, $config, $fieldsToGet)
{
    // creating sql query string
    $sql = "SELECT * FROM `{$config['tablename']}` WHERE ";
    foreach ($fieldsToGet as $key => $value) {
        $sql .= "`$key`=?and ";
    }
    $sql = trim($sql, 'and ');

    // preparing and execution sql query
    $user = [];
    $stmt = $db->prepare($sql);
    $stmt->execute(array_values($fieldsToGet));

    if ($stmt->rowCount()) {
        $user = $stmt->fetch(PDO::FETCH_LAZY);
        $user = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'password' => $user['password'],
            'token' => $user['token'],
            'status' => $user['status'],
            'is_deleted' => $user['is_deleted'],
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
            'is_deleted' => $user['is_deleted'],
            'avatar' => $user['avatar'],
        ];
    }

    return $users;
}


function getUsersPerPageFromDb(PDO $db, array $config, int $recordsOnPage, int $from)
{
    $users = [];
    $sql = "SELECT * FROM {$config['tablename']} WHERE (id>0 AND is_deleted=0) ORDER BY id DESC LIMIT $from,$recordsOnPage";
    $stmt = $db->query($sql);

    while ($user = $stmt->fetch(PDO::FETCH_LAZY)) {
        $users[] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'password' => $user['password'],
            'token' => $user['token'],
            'status' => $user['status'],
            'is_deleted' => $user['is_deleted'],
            'avatar' => $user['avatar'],
        ];
    }


    return $users;
}


function getCountOfUsersFromDb(PDO $db, array $config)
{
    $count = 0;
    $sql = "SELECT COUNT(*) as count FROM {$config['tablename']}";
    $stmt = $db->query($sql);
    $count = $stmt->fetch(PDO::FETCH_LAZY)['count'];

    return $count;
}
