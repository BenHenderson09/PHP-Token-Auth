<?php require_once "./includes/header.php";?>

<div class="card-header">
    <h1> Register </h1>
</div>

<div class="card-body">
    <form method="POST" action="./services/register_service.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" placeholder="Username" 
            name="username" value="<?=!empty($_GET['username']) ? htmlspecialchars($_GET['username']) : ''?>">
        </div>
        
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" 
            placeholder="Enter email" name="email" value="<?=!empty($_GET['email']) ? htmlspecialchars($_GET['email']) : ''?>">
        </div>

        <div class="form-group">
            <label for="fullname">Fullname</label>
            <input type="text" class="form-control" id="fullname" placeholder="fullname" 
            name="fullname" value="<?=!empty($_GET['fullname']) ? htmlspecialchars($_GET['fullname']) : ''?>">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" 
            name="password" value="<?=!empty($_GET['password']) ? htmlspecialchars($_GET['password']) : ''?>">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <div class="btns text-center">
        <a href="./index.php"><button class="btn btn-primary"> Home </button></a>
        <a href="./login.php"><button class="btn btn-danger"> Login </button></a>
    </div>

<?php require_once "./includes/footer.php";?>
