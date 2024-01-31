<?php
include('configuration/database_config.php');
/// array to keep login errors
$error = array('email'=>'','password'=>'');

/// verifying login and redirecting to customer.php with parking_id if submit is clicked
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
        $sql="SELECT c_name,c_id,c_password FROM `customer` WHERE c_email='$email'";

        $result=mysqli_query($conn, $sql);
        $c=mysqli_fetch_assoc($result);

        if ($c){
            if ($password==$c['c_password']){
                session_start();
                $_SESSION['c_id']=$c['c_id'];
                $_SESSION['name']=$c['c_name'];
                header('Location: customer.php');
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
<title>Customer Login: Easy Park</title>
<section>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="centre_t form_grid">
        <h1>Customer Login</h1>
        <h3>Email</h3>
        <input type="text" name="email">
        <div class="error"><?php  echo htmlspecialchars($error['email']); ?></div>
        <h3>Password</h3>
        <input type="text" name="password" >
        <div class="error"><?php  echo htmlspecialchars($error['password']); ?></div>
        <div class="button_div">
            <input class="hov submit_button" type="submit" value="submit" name="submit">
            <a class="hov submit_button" href="customer_create.php" class="submit_button">Sign Up</a>
        </div>
    </form>
</section>



<?php include('template/footer.php'); ?>