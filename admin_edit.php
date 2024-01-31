<?php
include('configuration/database_config.php');

session_start();
if (empty($_SESSION['a_id'])){
    header('Location: admin_login.php');
}
$a_id = mysqli_real_escape_string($conn, $_SESSION['a_id']);

if(isset($_POST['u_password'])){
    $password=mysqli_real_escape_string($conn, $_POST['n_password']);
    $sql="UPDATE `admin` SET a_password = '$password' WHERE a_id='$a_id'";
    if(mysqli_query($conn, $sql)){
        // success
        header('Location: admin_edit.php');
    } else {
        echo 'query error: '. mysqli_error($conn);
    }
}
if(isset($_POST['u_u'])){
    $_SESSION['name']=$_POST['n_name'];
    $email=mysqli_real_escape_string($conn, $_POST['n_email']);
    $name=mysqli_real_escape_string($conn, $_POST['n_name']);
    $address=mysqli_real_escape_string($conn, $_POST['n_address']);
    $pincode=mysqli_real_escape_string($conn, $_POST['n_pincode']);
    $state=mysqli_real_escape_string($conn, $_POST['n_state']);
    $district=mysqli_real_escape_string($conn, $_POST['n_district']);
    $com_name=mysqli_real_escape_string($conn, $_POST['n_c_name']);
    $country=mysqli_real_escape_string($conn, $_POST['n_country']);
    $sql="UPDATE `admin` SET a_email='$email',a_name='$name',a_address='$address',a_pincode='$pincode',a_state='$state',a_district='$district',a_company_name='$com_name',a_country='$country' WHERE a_id='$a_id'";
    if(mysqli_query($conn, $sql)){
        // success
        header('Location: admin_edit.php');
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
    <h3>New Company Name</h3>
    <input type="text" name="n_c_name" >
    <h3>New Country</h3>
    <input type="text" name="n_country" >
    <h3>New State</h3>
    <input type="text" name="n_state" >
    <h3>New District</h3>
    <input type="text" name="n_district" >
    <h3>New Address</h3>
    <input type="text" name="n_address" >
    <h3>New Pincode</h3>
    <input type="text" name="n_pincode" >
    <h3>Current password</h3>
    <input type="text" name="password" >
    <div class="button_div">
        <input class="hov submit_button" type="submit" value="update all" name="u_u">
    </div>
</form>
</div>


<?php include('template/footer.php'); ?>