<?php
    require_once '../includes/header.php';
    require_once '../util/auth.php';

    if (!empty($_POST) && Auth::validate($_POST, '/PHP/Learning/TokenAuth/login.php')){
        // Check credentials
        $match = false;
        $stmt = $dbh->prepare('SELECT password FROM users WHERE email=:email');
        $stmt->execute(['email' => $_POST['email']]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($user)){
            if (password_verify($_POST['password'], $user['password'])){
                $match = true;
            }
        }

        if ($match){
            // Logout the old user
            Auth::unauthenticate($dbh);

            // Log in the new user
            Auth::authenticate($_POST, $_POST['persistent'], $dbh);

            // Go to home page
            Auth::message([['success'=>true, 'message'=>'Login successful']], null, '/PHP/Learning/TokenAuth/index.php');
        }
        else {
            $msg = [['success'=>false, 'message'=>'Email or Password is incorrect']];
            $redirect =  '../login.php';

            Auth::message($msg, $_POST, $redirect);
        }
    }