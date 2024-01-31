<?php
/// url and time for html
$page = $_SERVER['PHP_SELF'];
$sec = "03";

/// to count session ( if not present, starting session)
session_start();
if(empty($_SESSION["refreshed_round"])){
    $_SESSION["refreshed_round"]=0;
}
$_SESSION["refreshed_round"]++;
echo "User refreshed: " . $_SESSION["refreshed_round"];


// the message
$msg = "First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
if(mail("7nnabhishek@gmail.com","My subject",$msg)){
    echo "yay";
}
else{
    echo "no";
}

// http://localhost/Parking_Management_System/php_refresh.php
?>
<html>
    <head>
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    </head>
    <body>

    </body>
</html>