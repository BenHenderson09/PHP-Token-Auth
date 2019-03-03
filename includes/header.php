<?php
    require_once "C:/xampp/project_configuration/token_auth/config.php";
    require_once 'C:/xampp/htdocs/PHP/Learning/TokenAuth/util/auth.php';

    session_start();

    Auth::handleToken($dbh);
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title> Token Auth Example </title>

        <!-- CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>

    <body>
        <?php require_once "C:/xampp/htdocs/PHP/Learning/TokenAuth/includes/messaging.php";?>

        <div class="container" style="margin-top: 10px;">
            <div class="card">