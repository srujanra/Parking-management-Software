<?php
include('configuration/database_config.php');
/// checking if customer id is present else redirecting to customer_login
session_start();
if (empty($_SESSION['c_id'])){
    header('Location: customer_login.php');
}
$c_id=mysqli_real_escape_string($conn, $_SESSION['c_id']);



/// if confirm park submit is pressed updating parking,vehicle,bill 
if(isset($_POST['confirm_park'])){
    // echo $_POST['confirm_park'];
    $p_id=mysqli_real_escape_string($conn, $_POST['p_id']);
    // $a_id=mysqli_real_escape_string($conn, $_POST['a_id']);
    // $b_price=mysqli_real_escape_string($conn, $_POST['b_price']);
    $from=mysqli_real_escape_string($conn, $_POST['from']);
    $till=mysqli_real_escape_string($conn, $_POST['till']);
    $v_no=mysqli_real_escape_string($conn, $_POST['v_no']);
    $sql="UPDATE `place` SET v_no='$v_no',p_from='$from',p_till='$till' WHERE p_id='$p_id'; ";
    mysqli_query($conn,$sql);
    $sql="INSERT INTO `vehicle` (`v_no`, `c_id`, `v_type`) SELECT '$v_no', '$c_id', 'car' WHERE NOT EXISTS (SELECT * FROM `vehicle` WHERE v_no='$v_no') ";
    mysqli_query($conn,$sql);
    // $sql="INSERT INTO `bill` (p_id,a_id,v_no,b_price,b_from,b_till) VALUES('$p_id','$a_id','$v_no','$b_price','$from','$till')";
    // mysqli_query($conn,$sql);
    $_SESSION['c_p_id']="";
    unset($_POST);
    header("Location: ".$_SERVER['PHP_SELF']);
}

/// if parking id present getting the details of congfirm park location
$place="";
if(!empty($_SESSION['c_p_id'])){
    // echo $_SESSION['c_p_id'];
    $p_id = mysqli_real_escape_string($conn, $_SESSION['c_p_id']);
    $sql="SELECT p.p_id, p.a_id, p.p_price, p.p_from, p.p_till, p.v_no, a.a_email, a.a_name, a.a_company_name,a.a_email, a.a_country, a.a_state, a.a_district, a.a_address, a.a_pincode FROM place as p, admin as a WHERE p.a_id=a.a_id AND p.p_id='$p_id' AND p.v_no IS NULL ";
    // AND p.v_no IS NULL not need 
    $result=mysqli_query($conn,$sql);
    $place=mysqli_fetch_assoc($result);
    mysqli_free_result($result);
}

/// for displaying current parked locations
$sql="SELECT p.p_id,p.v_no,p.p_from, p.p_till, p.p_price,a.a_id,a.a_company_name,a.a_email, a.a_country, a.a_state, a.a_district, a.a_address, a.a_pincode FROM place as p, admin as a, customer as c, vehicle as v WHERE p.a_id=a.a_id AND p.v_no=v.v_no AND v.c_id=c.c_id AND c.c_id=$c_id";
$result=mysqli_query($conn,$sql);
$current_places=mysqli_fetch_all($result,MYSQLI_ASSOC);
mysqli_free_result($result);
$c_p_k=array('p_id','v_no','p_from','p_till','p_price','a_company_name','a_email','a_country','a_state','a_district','a_address','a_pincode');

///If leave parking place clicked
if(isset($_GET['LEAVE'])){
    $p_id=mysqli_real_escape_string($conn, $_GET['LEAVE']);
    $sql="UPDATE `place` SET v_no=NULL,p_from=NULL,p_till=NULL WHERE p_id='$p_id'; ";
    mysqli_query($conn,$sql);
    $a_id="";
    $v_no="";
    $b_price="";
    $b_from="";
    foreach ($current_places as $place) {
        if ($place['p_id']==$p_id){
            $a_id=$place['a_id'];
            $v_no=$place['v_no'];
            $b_price=$place['p_price'];
            $b_from=$place['p_from'];         
        }
    }
    $a_id=mysqli_real_escape_string($conn,$a_id);
    $v_no=mysqli_real_escape_string($conn, $v_no);
    $b_price=mysqli_real_escape_string($conn, $b_price);
    $b_from=mysqli_real_escape_string($conn, $b_from);
    $sql="INSERT INTO `bill` (p_id,a_id,v_no,b_price,b_from,b_till) VALUES('$p_id','$a_id','$v_no', TIMESTAMPDIFF(HOUR, '$b_from', NOW())*'$b_price','$b_from',NOW())";
    mysqli_query($conn,$sql);
    header('Location: customer.php');
}

/// getting details of all previous bills for customer
// $_SESSION['c_id']="";
// echo $c_id;
// $c_id="!=NULL";
$sql="SELECT b.p_id,b.v_no,b.b_from, b.b_till, b.b_price,a.a_company_name,a.a_email, a.a_country, a.a_state, a.a_district, a.a_address, a.a_pincode FROM place as p, admin as a, bill as b, customer as c, vehicle as v WHERE b.p_id=p.p_id AND b.a_id=a.a_id AND b.v_no=v.v_no AND v.c_id=c.c_id AND v.c_id=$c_id";
$result=mysqli_query($conn,$sql);
$bills=mysqli_fetch_all($result,MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_close($conn);
$bill_k=array('p_id','v_no','b_from','b_till','b_price','a_company_name','a_email','a_country','a_state','a_district','a_address','a_pincode');

?>


<?php include('template/header.php'); ?>
<title>Customer: Easy Park</title>
<section>
<h1>customer page</h1>
<h3><a class="click hov" href="customer_edit.php">Edit Info</a></h3>

<?php if (!empty($place)){?>
<div><h1>confirm parking</h1>
<table class="centre_t">
<thead>
    <tr>
        <th>Place Id</th>
        <th>Name</th>
        <th>Email</th>
        <th>Price per Hour</th>
        <th>Country</th>
        <th>State</th>
        <th>District</th>
        <th>Pincode</th>
        <th>From</th>
        <th>Till</th>
        <th>Vehicle No</th>
        <th></th>
    </tr>
</thead>
<tbody>
    <tr>
        <td class="data"><?php echo htmlspecialchars($place['p_id']) ; ?></td>
        <td class="data"><?php echo htmlspecialchars($place['a_company_name']) ; ?></td>
        <td class="data"><?php echo htmlspecialchars($place['a_email']) ; ?></td>
        <td class="data"><?php echo htmlspecialchars($place['p_price']) ; ?></td>
        <td class="data"><?php echo htmlspecialchars($place['a_country']) ; ?></td>
        <td class="data"><?php echo htmlspecialchars($place['a_state']) ; ?></td>
        <td class="data"><?php echo htmlspecialchars($place['a_district']) ; ?></td>
        <td class="data"><?php echo htmlspecialchars($place['a_pincode']) ; ?></td>
        <form action="customer.php" method="post">
        <td><input type="datetime-local" name="from" ></td>
        <td><input type="datetime-local" name="till" ></td>

        <td><input type="text" name="v_no" ></td>
        <input type="hidden" name="p_id" value="<?php echo $place['p_id']; ?>">
        <!-- <input type="hidden" name="a_id" value="<?php echo $place['a_id']; ?>"> -->
        <!-- <input type="hidden" name="b_price" value="<?php echo $place['p_price']; ?>"> -->
        <td><input class="click hov" type="submit" name="confirm_park" value="confirm_park" ></td>
        </form>
    </tr>
</tbody>
</table>
</div>
<?php } ?>


<div>
<h1>current parking locations</h1>
<?php if(!empty($current_places)){ ?>
    <table class="centre_t">
    <thead>
        <tr>
            <th>Place Id</th>
            <th>Vehicle No</th>
            <th>From</th>
            <th>Till</th>
            <th>Price per Hour</th>
            <th>Company Name</th>
            <th>Company Email</th>
            <th>Country</th>
            <th>State</th>
            <th>District</th>
            <th>Address</th>
            <th>Pincode</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($current_places as $place) {?>
        <tr>
            <?php foreach($c_p_k as $k) {?>
                <td class="data"><?php echo htmlspecialchars($place[$k]) ; ?></td>
            <?php } ?>
            <td><a class="data click hov" href="customer.php?LEAVE=<?php echo $place['p_id']; ?>">LEAVE</a></td>
        </tr>
    </tbody>
    <?php  }  ?>
    </table>
    <?php }else{ ?>
<h4 class="centre_t">no current parking data found</h4>
<?php } ?>
</div>



<div >
<h1>previously parked bills</h1>
<?php if(!empty($bills)) { ?>

<table class="centre_t">
<thead>
    <tr>
        <th>Place Id</th>
        <th>Vehicle No</th>
        <th>From</th>
        <th>Till</th>
        <th>Total Price</th>
        <th>Company Name</th>
        <th>Company Email</th>
        <th>Country</th>
        <th>State</th>
        <th>District</th>
        <th>Address</th>
        <th>Pincode</th>
    </tr>
</thead>
<tbody>
<?php foreach ($bills as $bill) {?>
    <tr>
        <?php foreach($bill_k as $k) {?>
            <td class="data"><?php echo htmlspecialchars($bill[$k]) ; ?></td>
        <?php } ?>
    </tr>
</tbody>
<?php  }  ?>
</table>
<?php } else { ?>
<h4>no previous bill data found</h4>
<?php } ?>
</div>

</section>
<?php include('template/footer.php'); ?>
