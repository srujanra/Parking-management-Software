<?php
include('configuration/database_config.php');
/// array to keep login errors
$error = array('Email'=>'','Your_Password'=>'','Retype_Password'=>'','Name'=>'','Address'=>'');

/// array to store mysqli_real_escape_string
$form= array();

/// array to keep all form names
$arr=array_keys($error);

/// verifying and creating customer if submit is clicked
if(isset($_POST['submit'])){
    if(empty($_POST['Email'])){
        $error['Email']='provide Email';
    }
    else{
        $Email=$_POST['Email'];
        if(!filter_var($Email,FILTER_VALIDATE_EMAIL)){
            $error['Email']='enter a valid Email address';   
        }
    }
    for ($i=1; $i < count($arr); $i++) { 
        if (empty($_POST[$arr[$i]])){
            $error[$arr[$i]]='provide '.$arr[$i];
        }
    }
    if (empty($error['Your_Password']) and empty($error['Retype_Password']) and $_POST['Your_Password']!=$_POST['Retype_Password']){
        $error['Your_Password']='';
        $error['Retype_Password']='Passwords not Matching';
    }
    if(!array_filter($error)){
        for ($i=0; $i < count($arr); $i++) { 
            array_push($form,mysqli_real_escape_string($conn, $_POST[$arr[$i]]));
        }
        $sql = "INSERT INTO `customer` (c_email,c_password,c_name,c_address) VALUES('$form[0]','$form[1]','$form[3]','$form[4]')";

        if(mysqli_query($conn, $sql)){
            header('Location: customer_login.php');
        } else {
            echo 'query error: '. mysqli_error($conn);
        }
    }

}

?>

<?php include('template/header.php'); ?>
<title>Create Customer: Easy Park</title>
<section>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="centre_t form_grid" >
        <h1>Create Customer Account</h1>
        <?php for ($i=0; $i < count($arr); $i++) { ?>
            <h3><?php echo $arr[$i]; ?></h3>
            <input type="text" name="<?php echo $arr[$i]; ?>">
            <div class="error"><?php  echo htmlspecialchars($error[$arr[$i]]); ?></div>
        <?php } ?>
        <div class="button_div">
            <input class="hov submit_button" type="submit" value="submit" name="submit">
        </div>
    </form>
</section>


<?php include('template/footer.php'); ?>