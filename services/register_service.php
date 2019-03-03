<?php
    require_once '../includes/header.php';
    require_once '../util/auth.php';

    if (!empty($_POST) && Auth::validate($_POST, '/PHP/Learning/TokenAuth/register.php')){
        // Unique check
        $uniqueEmail = $dbh->prepare('SELECT id FROM users WHERE email=:email');
        $uniqueUsername = $dbh->prepare('SELECT id FROM users WHERE username=:username');

        $uniqueEmail->execute([':email' => $_POST['email']]);
        $uniqueUsername->execute([':username' => $_POST['username']]);


        if (count($uniqueEmail->fetchAll()) > 0){
            Auth::message([['success'=>false, 'message'=>'Email already in use']], $_POST, '/PHP/Learning/TokenAuth/register.php');
        }

        if (count($uniqueUsername->fetchAll()) > 0){
            Auth::message([['success'=>false, 'message'=>'Username already in use']], $_POST, '/PHP/Learning/TokenAuth/register.php');
        }
        
        // Register user
        $stmt = $dbh->prepare('INSERT INTO users VALUES(:username, :email, :fullname, :password, NULL, NULL)');
        $stmt->execute([
            ':username' =>  $_POST['username'],
            ':email'    =>  $_POST['email'], 
            ':fullname' =>  $_POST['fullname'], 
            ':password' =>  password_hash($_POST['password'], PASSWORD_DEFAULT)
        ]);

        // Logout the old user
        Auth::unauthenticate($dbh);

        // Log in the new user
        Auth::authenticate($_POST, false, $dbh);

        // Go to home page
        Auth::message([['success'=>true, 'message'=>'Registration successful']], null, '/PHP/Learning/TokenAuth/index.php');
        
    }