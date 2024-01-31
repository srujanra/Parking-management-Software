<?php
include('configuration/database_config.php');

/// checking if admin id is present else redirecting to admin_login
session_start();
if (empty($_SESSION['a_id'])){
    header('Location: admin_login.php');
}
$a_id = mysqli_real_escape_string($conn, $_SESSION['a_id']);

/// insering new values
if(isset($_POST['price_submit'])){
    $p_price=mysqli_real_escape_string($conn, $_POST['price']);
    $sql="INSERT INTO `place` (`a_id`,`p_price`,`p_from`,`p_till`,`v_no`) VALUES('$a_id','$p_price',NULL,NULL,NULL)";
    if (mysqli_query($conn,$sql)){
        header('Location: admin.php');
    } else {
        echo 'query error: '. mysqli_error($conn);
    }
    // header('Location: admin.php');
}

/// all parking place data of admin
$sql="SELECT p.p_id, p.p_price, p.p_from, p.p_till, p.v_no from place as p where p.a_id='$a_id' ORDER BY p.v_no desc";
$result=mysqli_query($conn,$sql);
$places=mysqli_fetch_all($result,MYSQLI_ASSOC);
mysqli_free_result($result);
$place_k=array("p_id","p_price","p_from","p_till","v_no");

/// deleting parking data
if(isset($_GET['REMOVE'])){
    $p_id=mysqli_real_escape_string($conn, $_GET['REMOVE']);
    $sql="UPDATE `place` SET v_no=NULL,p_from=NULL,p_till=NULL WHERE p_id='$p_id'; ";
    mysqli_query($conn,$sql);
    $v_no="";
    $b_price="";
    $b_from="";
    foreach ($places as $place) {
        if ($place['p_id']==$p_id){
            $v_no=$place['v_no'];
            $b_price=$place['p_price'];
            $b_from=$place['p_from'];         
        }
    }
    $v_no=mysqli_real_escape_string($conn, $v_no);
    $b_price=mysqli_real_escape_string($conn, $b_price);
    $b_from=mysqli_real_escape_string($conn, $b_from);
    $sql="INSERT INTO `bill` (p_id,a_id,v_no,b_price,b_from,b_till) VALUES('$p_id','$a_id','$v_no', TIMESTAMPDIFF(HOUR, '$b_from', NOW())*'$b_price','$b_from',NOW())";
    mysqli_query($conn,$sql);

    header('Location: admin.php');

}

/// getting details of all previous bills for admin
$sql="SELECT b.v_no, b.b_price,b.b_from, b.b_till,c.c_name,c.c_email,c.c_address,b.p_id FROM place as p, admin as a, bill as b, customer as c, vehicle as v WHERE b.p_id=p.p_id AND b.a_id=a.a_id AND b.v_no=v.v_no AND v.c_id=c.c_id AND b.a_id='$a_id'";
$result=mysqli_query($conn,$sql);
$bills=mysqli_fetch_all($result,MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_close($conn);

/// array to store data heading for in each bill
$bill_k=array('p_id','v_no','b_price','b_from','b_till','c_name','c_email','c_address');


?>

<?php include('template/header.php'); ?>
<title>Admin: Easy Park</title>
<section>
<h1>admin page</h1>
<h3><a class="click hov" href="admin_edit.php">Edit Info</a></h3>
<div class="centre_t">
<h2>Add place with price</h2>
<form action="admin.php" method="post">
<input type="number" name="price" >
<input class="click hov" type="submit" value="Add place" name="price_submit">
</form>
</div>



<div >
<h1>parking locations</h1>
<?php if(!empty($places)){ ?>
    <table class="centre_t">
    <thead>
        <tr>
            <th class="data" >Place Id</th>
            <th class="data" >Price per hour</th>
            <th class="data" >From</th>
            <th class="data" >Till</th>
            <th class="data" >Vehicle No</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($places as $place) {?>
        <tr>
        <?php foreach($place_k as $k) { ?>
            <td class="data"><?php echo htmlspecialchars($place[$k]) ; ?></td>
        <?php } ?>
        <?php if (!empty($place['v_no'])){ ?>
            <td><a class="data click hov" href="admin.php?REMOVE=<?php echo $place['p_id']; ?>">REMOVE CUSTOMER</a></td>
        <?php } else{ ?>
            <td>not parked yet</td>
        <?php } ?>
        </tr>
    </tbody>
    <?php  }  ?>
    </table>
<?php }else{ ?>
<h4 class="centre_t">no parking location data found</h4>
<?php } ?>
</div>



<div >
<h1>Previous Parking Bills</h1>
<?php if(!empty($bills)){ ?>
    <table class="centre_t">
    <thead>
        <tr>
            <th>Place Id</th>
            <th>Vehicle No</th>
            <th>Total Price</th>
            <th>From</th>
            <th>Till</th>
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Customer Address</th>

        </tr>
    </thead>
    <tbody>
    <?php foreach ($bills as $bill) {?>
        <tr>
        <?php foreach($bill_k as $k) { ?>
            <td class="data"><?php echo htmlspecialchars($bill[$k]) ; ?></td>
        <?php } ?>
        
        </tr>
    </tbody>
    <?php  }  ?>
    </table>
    <?php }else{ ?>
<h4 class="centre_t">no previous bill data found</h4>
<?php } ?>
</div>


</section>
<?php include('template/footer.php'); ?>

