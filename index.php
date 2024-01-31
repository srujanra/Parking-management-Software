<?php
include('configuration/database_config.php');

/// if park clicked redirecting to customer_login with parking id
if(isset($_GET['c_p_id'])){
    session_start();
    $_SESSION['c_p_id']=$_GET['c_p_id'];
    header('Location: customer_login.php');
}
$place_k=array('p_id','a_company_name','a_email','p_price','a_country','a_state','a_district','a_pincode');
$Go_S=array(0=>'',1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'');

/// if go clicked
if(isset($_POST['Go'])){
    for ($i=0; $i < count($place_k); $i++) { 
        $Go_S[$i]=mysqli_real_escape_string($conn, $_POST[$place_k[$i]]);
    }
}

/// getting places details to display
// $sql="SELECT p.p_id, p.a_id, p.p_price, p.p_from, p.p_till, p.v_no, a.a_email, a.a_name, a.a_company_name,a.a_email, a.a_country, a.a_state, a.a_district, a.a_address, a.a_pincode FROM place as p, admin as a WHERE p.a_id=a.a_id AND p.v_no IS NULL AND p.p_id LIKE '%$Go_S[0]%' AND a.a_company_name LIKE '%Go_S[1]%' ";

$sql="SELECT p.p_id, p.a_id, p.p_price, p.p_from, p.p_till, p.v_no, a.a_email, a.a_name, a.a_company_name,a.a_email, a.a_country, a.a_state, a.a_district, a.a_address, a.a_pincode FROM place as p, admin as a WHERE p.a_id=a.a_id AND p.v_no IS NULL AND p.p_id LIKE '%$Go_S[0]%' AND a.a_company_name LIKE '%$Go_S[1]%' AND a.a_email LIKE '%$Go_S[2]%' AND p.p_price LIKE '%$Go_S[3]%' AND a.a_country LIKE '%$Go_S[4]%' AND a.a_state LIKE '%$Go_S[5]%' AND a.a_district LIKE '%$Go_S[6]%' AND a.a_pincode LIKE '%$Go_S[7]%'";
$result=mysqli_query($conn,$sql);
// if (mysqli_query($conn,$sql)){
//     echo "yay";
// } else {
//     echo 'query error: '. mysqli_error($conn);
// }
$places=mysqli_fetch_all($result,MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_close($conn);

/// array to store data heading for in each place

?>

<?php include('template/header.php'); ?>
<title>Homepage: Easy Park</title>
<div id="home">
<h1 id="heading">EASY PARK</h1>
<p class="text">Is finding parking spot difficult and clunky during urgency and peek hours ?</p>
<p class="text">Guess What? We have made it Easy for you.</p>
<a href="#content" class="hov submit_button park">Find a Parking near Me</a>
</div>
<section>
<div id="content">
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
        <th></th>
    </tr>
</thead>
<tbody>
    <tr>
    <form action="index.php" method="post">
    <?php foreach($place_k as $k){
        $selects = array_column($places,$k);
        $selects_u = array_unique($selects);
        ?>
        <td>
            <select name="<?php echo $k; ?>" >
                <option value="">Select...</option>
            <?php foreach($selects_u as $s) { ?>
                <option value="<?php echo $s; ?>"><?php echo $s; ?></option>
            <?php } ?>
            </select>
        </td>
    <?php } ?>
    <td><input class="click hov" type="submit" value="Go" name="Go"></td>
    </form>
    <td><a class="click hov" href="index.php">Reset</a></td>
    </tr>
<?php foreach ($places as $place) {?>
    <tr>
        <?php
        foreach($place_k as $k){ ?>
            <td class="data"><?php echo htmlspecialchars($place[$k]) ; ?></td>
        <?php } ?>
        <td><a class="data click hov" href="index.php?c_p_id=<?php echo $place['p_id']; ?>">PARK</a></td>
    </tr>
<?php  }  ?>
</tbody>
</table>
</div>
<div id="bottom">
<p class="para">The usual parking management techniques are very slow and clunky and this industry requires automation.Therefore, we have created a  project which  is a website with an easy user interface for direct parking recommendation and reservation based parking. Billing is shown to the user and Admin on their homepages. A user friendly front end and with efficient backend that updates the parking details in real time is a very useful solution for parking problems in crowded areas. This system has good potential and can be extended with further enhancements.</p>
<p>Easy Park</p>
<p>Parking Management System  -  A better way to manage and park our vehicles.</p>
</div>
</section>

<?php include('template/footer.php'); ?>