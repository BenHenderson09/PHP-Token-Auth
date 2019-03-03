<?php
    require_once '../includes/header.php';
    require_once '../util/auth.php';

    Auth::unauthenticate($dbh);
    Auth::message([['success'=>true, 'message'=>'Logout successful']], null, '/PHP/Learning/TokenAuth/index.php');