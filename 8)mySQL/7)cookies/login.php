<?php 

$errorMsg = "";
$successMsg = "";

// print_r($_POST);
// echo "<br>";

$conn = mysqli_connect("127.0.0.1:3307","root","Qwerty-123","users");

if(mysqli_connect_error())
{
    echo mysqli_connect_error()."<br>";
    //$errorMsg .= '<p class="alert alert-danger" role="alert">There was an error in creating connection!</p>';
    die('There was an error in creating connection!<br>');
}

if(isset($_COOKIE['email']))
{
    //session is set
    $Message = "Welcome back ".$_COOKIE["email"];
    header("Location: cookie.php?Message=".$Message);
}


if($_POST)
{
    if(array_key_exists("email",$_POST) && array_key_exists("password",$_POST))
    {
        if($_POST["email"]=="")
        {
            $errorMsg .= 'The email is required<br>';
        }
        if($_POST["password"]=="")
        {
            $errorMsg .= 'password is required<br>';
        }
        if ($_POST["email"]!="" && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
        {
            $errorMsg .= "This Email is invalid";
        }
        if($errorMsg != "")
        {
            $errorMsg = '<p class="alert alert-danger" role="alert"><b>There were error(s) in your form: </b><br>'.$errorMsg.'</p>';
        }
        else
        {
            $query = "SELECT * FROM user WHERE email = '".mysqli_real_escape_string($conn,$_POST["email"])."' AND password = '".mysqli_real_escape_string($conn,$_POST["password"])."'";
            if($result = mysqli_query($conn,$query))
            {
                if(mysqli_num_rows($result)>=1)   //means email already exists.
                {
                    //$errorMsg .= '<p class="alert alert-danger" role="alert">This email has already been taken. Try using another one.</p>';
                    setcookie("email",$_POST["email"],time()+60*60*24);   //cookie will expire after a day.
                    //$_SESSION["email"] = $_POST["email"];
                    $successMsg .= '<p class="alert alert-success" role="alert"><b>Successfully logged in!</b></p>';
                    $Message = urlencode("Successfully logged in!");
                    header("Location:cookie.php?Message=".$Message);
                    die;
                }
                else
                {
                    $errorMsg .= '<p class="alert alert-danger" role="alert"><b>Email or Password is incorrect</b></p>';
                }
            }
            else
            {
                $errorMsg .= '<p class="alert alert-danger" role="alert">There was an error in executing query!</p>';
            }
        }
    }
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in Form</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body style="background-color:lightgray;">

    <div class="container">
        <h1 style="text-align:center; color:blue;">Log in Form</h1>
        <?php
            if(isset($_GET['Message']))
            {
                echo '<p class="alert alert-danger" role="alert"><b>'.$_GET['Message'].'</b></p>';
            }
        ?>
        <div id="show"><?php echo $errorMsg.$successMsg; ?></div>
        <form class="row g-3" method="post">
            <div class="col-md-12">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email">
            </div>
            <div class="col-12">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Log in</button>
            </div>
        </form>
        <br>
        <p>Don't have an account? <a href="index.php">Sign up</a></p>
    </div>

    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
</body>

</html>