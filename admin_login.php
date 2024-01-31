<?php
include('configuration/database_config.php');
/// array to keep login errors
$error = array('email'=>'','password'=>'');

/// verifying login and redirecting to admin.php with admin_id if submit is clicked
if(isset($_POST['submit'])){
    if(empty($_POST['email'])){
        $error['email']='provide email';
    }
    else{
        $email=$_POST['email'];
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $error['email']='enter a valid email address';   
        }
    }
    if(empty($_POST['password'])){
        $error['password']='provide password';
    }
    else if (empty($error['email'])){
        $password=$_POST['password'];
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $sql="SELECT a_name,a_id,a_password FROM `admin` WHERE a_email='$email'";
        $result=mysqli_query($conn, $sql);
        $a=mysqli_fetch_assoc($result);

        if ($a){
            if ($password==$a['a_password']){
                session_start();
                $_SESSION['a_id']=$a['a_id'];
                $_SESSION['name']=$a['a_name'];
                header('Location: admin.php');
            }
            else{
                $error['password']='wrong password';
            }
        }
        else{
            if (empty($error['email'])){
                $error['email']="wrong email";
            }
        }

    }
}

?>

<?php include('template/header.php'); ?>
<title>Admin Login: Easy Park</title>
<section>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="centre_t form_grid" >
        <h1>Admin Login</h1>
        <h3>Email</h3>
        <input type="text" name="email">
        <div class="error"><?php  echo htmlspecialchars($error['email']); ?></div>
        <h3>Password</h3>
        <input type="text" name="password" >
        <div class="error"><?php  echo htmlspecialchars($error['password']); ?></div>
        <div class="button_div">
            <input class="hov submit_button" type="submit" value="submit" name="submit">
            <a class="hov submit_button" href="admin_create.php" class="submit_button">Sign Up</a>
        </div>


    </form>
</section>



<?php include('template/footer.php'); ?>