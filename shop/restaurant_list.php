<?php
require_once('../lib/connection.php'); //connect to DB
require_once('../lib/shop.php'); 
$shop = new Shop();
if(isset($_POST['back'])){
    header("Location: ../index.php");
    die();
}
//=============================================
session_start();
if(!empty($_SESSION['message'])) {
  echo '<p class="message"> '.$_SESSION['message'].'</p>';
    unset($_SESSION['message']);
}


$restaurant = $shop->getShop(null,1,$conn);

echo '<table border="1">';
echo '<tr><td> نام رستوران</td></tr>';
for($i=0;$i<count($restaurant);$i++){
    echo '<tr><td><a href="food.php?id='.$restaurant[$i]['restaurant_id'].'">'.$restaurant[$i]['restaurant_name'].'</a></td></tr>';
}
echo '</table>';

?>

<form method="post"><input type="hidden" name="back" value="1"><input type="submit" value="برگشت"></form>