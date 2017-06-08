<?php
require_once('../lib/connection.php'); //connect to DB
require_once('../lib/jdf.php'); //for shamsi time
require_once('../lib/shop.php'); 
$shop = new Shop();
session_start();
if(isset($_POST['back'])){
    header("Location: ../index.php");
    die();
}
//===========================================  check access
if(isset($_SESSION['userid'])) $userId=  $_SESSION['userid']; else $userId= null;
if(isset($_SESSION['managerid'])) $managerId=  $_SESSION['managerid']; else $managerId= null;
if($shop->checkAccess(1,$userId,$managerId,$conn) != 'admin' or $shop->checkAccess(1,$userId,$managerId,$conn) == null){
    $_SESSION['message'] = 'شما به این صفحه دسترسی ندارید';
    header("Location: ../index.php");
    die();
}
//===========================================  check access
?>
<ul>
    <li><a href="manage_restaurant_managers.php" >مدیریت فروشگاه ها</a></li>
    <li><a href="manage_users.php" >مدیریت مشتری</a> </li>
    <li><a href="manage_payments.php" >مدیریت پرداخت ها</a></li>
    <li><a href="manage_transporter.php" >مدیریت پیک</a></li>
    <li><a href="manage_comments.php" >مدیریت نظرات</a></li>
    <li><a href="manage_products_menu.php" >مدیریت منوهای غذایی</a></li>
    <li><a href="manage_orders.php" >مدیریت سفارشات</a></li>                
 </ul>

<form method="post"><input type="hidden" name="back" value="1"><input type="submit" value="برگشت"></form>

 