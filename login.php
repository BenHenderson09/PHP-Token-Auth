<?php 
    require_once "./includes/header.php";

    if (!empty($_SESSION['user'])){
        Auth::message([['success'=>false, 'message'=>'You are already logged in']], null, '/PHP/Learning/TokenAuth/index.php');
    }
?>

<div class="card-header">
    <h1> Login </h1>
</div>

<div class="card-body">
    <form method="POST" action="./services/login_service.php">
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" 
            placeholder="Enter email" name="email" value="<?= !empty($_GET['email']) ? $_GET['email'] : ''?>">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" name="password">
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="persistent" name="persistent">
            <label class="form-check-label" for="persistent">Remember Me</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <div class="btns text-center">
        <a href="./index.php"><button class="btn btn-primary"> Home </button></a>
        <a href="./register.php"><button class="btn btn-danger"> Register </button></a>
    </div>
</div>

<?php require_once "./includes/footer.php";?>
