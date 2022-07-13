<?php

require_once 'db.php';

function isNameValid(string $name): bool
{
    // only lowercase and uppercase chars
    $pattern = '/^[A-Za-z]+$/';

    if (preg_match($pattern, $name)) {
        return true;
    }
    return false;
}

function isPasswordValid(string $password): bool
{
    // length of a password >= 6 and only lowercase and uppercase chars and digits
    $neededLength = 3;
    $pattern = '/^[A-Za-z0-9]+$/';

    return strlen($password) >= $neededLength && (preg_match($pattern, $password));

}

function isEmailValid(string $email): bool
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }

    return false;
}


function isEmailUnique(string $email, array $config): bool
{
    $db = connectToDb($config);
    $user = getUserFromDbByEmail($db, $config, $email);

    if (empty($user)) {
        return true;
    }

    return false;
}

function validateSingUpForm(array $form, array $config): array
{
    $errors = [];

    if (!isEmailValid($form['email'])) {
        $errors['email'] = 'Email isn\'t valid.';
    }
    if (!isPasswordValid($form['password'])) {
        $errors['password'] = 'Password isn\'t valid. It should contain only numbers and letters and be more than 3 symbols.';
    }
    if (!isNameValid($form['name'])) {
        $errors['name'] = 'Name isn\'t valid. It should contain only letters.';
    }
    if (!isEmailUnique($form['email'], $config)) {
        $errors['emailUnique'] = 'User with such email already exists';
    }

    return $errors;
}

function validateСreateUserForm(array $form, array $config): array
{
    $errors = [];

    if (!isEmailValid($form['email'])) {
        $errors['email'] = 'Email isn\'t valid';
    }
    if (!isEmailUnique($form['email'], $config)) {
        $errors['emailUnique'] = 'User with such email already exists';
    }
    if (!isPasswordValid($form['password'])) {
        $errors['password'] = 'Password isn\'t valid. It should contain only numbers and letters and be more than 3 symbols.';
    }
    if (!isNameValid($form['name'])) {
        $errors['name'] = 'Name isn\'t valid. It should contain only letters.';
    }

    return $errors;
}

function validateUpdateUserForm(array $form, array $config): array
{
    $errors = [];

    if (!isNameValid($form['name'])) {
        $errors['name'] = 'Name isn\'t valid. It should contain only letters.';
    }
    if(isset($form['password'])){
        if (!isPasswordValid($form['password'])) {
            $errors['password'] = 'Password isn\'t valid. It should contain only numbers and letters and be more than 3 symbols.';
        }
    }
    if(isset($form['email'])){
        if (!isEmailUnique($form['email'], $config)) {
            $errors['emailUnique'] = 'User with such email already exists';
        }
        if (!isEmailValid($form['email'])) {
            $errors['email'] = 'Email isn\'t valid';
        }
    }


    return $errors;
}

function validateSingInForm(array $form): array
{
    $errors = [];

    if (!isEmailValid($form['email']) || !isPasswordValid($form['password'])) {
        $errors['email'] = 'Incorrect email or password';
    }

    return $errors;
}
