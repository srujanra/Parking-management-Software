<?php
include('configuration/database_config.php');
session_start();
if (empty($_SESSION['c_id'])){
    header('Location: customer_login.php');
}
$c_id=mysqli_real_escape_string($conn, $_SESSION['c_id']);
if(isset($_POST['u_password'])){
    $password=mysqli_real_escape_string($conn, $_POST['n_password']);
    $sql="UPDATE `customer` SET c_password = '$password' WHERE c_id='$c_id'";
    if(mysqli_query($conn, $sql)){
        // success
        header('Location: customer_edit.php');
    } else {
        echo 'query error: '. mysqli_error($conn);
    }
}
if(isset($_POST['u_u'])){
    $_SESSION['name']=$_POST['n_name'];
    $email=mysqli_real_escape_string($conn, $_POST['n_email']);
    $name=mysqli_real_escape_string($conn, $_POST['n_name']);
    $address=mysqli_real_escape_string($conn, $_POST['n_address']);
    $sql="UPDATE `customer` SET c_email='$email',c_name='$name',c_address='$address' WHERE c_id='$c_id'";
    if(mysqli_query($conn, $sql)){
        // success
        header('Location: customer_edit.php');
    } else {
        echo 'query error: '. mysqli_error($conn);
    }
}
?>


<?php include('template/header.php'); ?>
<title>Customer Edit: Easy Park</title>
<div class="center_t">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="centre_t form_grid">
    <h1>Change Password</h1>
    <h3>Email</h3>
    <input type="text" name="email">
    <h3>Current Password</h3>
    <input type="text" name="password" >
    <h3>New Password</h3>
    <input type="text" name="n_password" >
    <h3>Retype New Password</h3>
    <input type="text" name="r_password" >
    <div class="button_div">
        <input class="hov submit_button" type="submit" value="update" name="u_password">
    </div>
</form>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="centre_t form_grid">
    <h1>Update all Details</h1>
    <h3>Current Email</h3>
    <input type="text" name="email">
    <h3>New Email</h3>
    <input type="text" name="n_email">
    <h3>New Name</h3>
    <input type="text" name="n_name" >
    <h3>New address</h3>
    <input type="text" name="n_address" >
    <h3>Current password</h3>
    <input type="text" name="password" >
    <div class="button_div">
        <input class="hov submit_button" type="submit" value="update all" name="u_u">
    </div>
</form>
</div>


<?php include('template/footer.php'); ?>