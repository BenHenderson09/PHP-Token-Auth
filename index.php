<?php require_once "./includes/header.php"; ?>

<div class="card-header">
    <h3>Token Auth Example</h3>
</div>

<div class="card-body">
    <div class="jumbotron text-center">
        <h1> Logged In: <?=!empty($_SESSION['user']) ? 'Yes' : 'No'?> </h1>

        <?php if (!empty($_SESSION['user'])):?>
            <h2>Username: <?=$_SESSION['user']['username']?></h2>
            <h2>Email: <?=$_SESSION['user']['email']?></h2>
        <?php endif?>
    </div>

    <div class="btns text-center">
        <a href="./login.php"><button class="btn btn-primary"> Login </button></a>
        <a href="./services/logout_service.php"><button class="btn btn-success"> Logout </button></a>
        <a href="./register.php"><button class="btn btn-danger"> Register </button></a>
    </div>
</div>

<?php require_once "./includes/footer.php";?>
