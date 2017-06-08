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
//===========================================
if (isset($_SESSION['sabad'])){
    echo $_SESSION['sabad'];
    unset($_SESSION['sabad']);
    echo '</br>';
}

if($shop->checkAccess(1,$userId,$managerId,$conn) == 'admin' && $shop->checkAccess(1,$userId,$managerId,$conn) == 'user'){
    echo '<ul><li> <a href="../user/sabad.php" >سبد خرید</a></li></ul>'; 
}
       

if(($_GET['id'] != null) && (is_numeric($_GET['id']))) {
    
    $restaurant_id = $_GET['id'];
    $restaurant = $shop->getProduct(null,$restaurant_id,$conn);
    if($restaurant != null){
        echo 'نام فروشگاه'.'=='.$shop->getShop($restaurant_id,null,$conn)[0]['restaurant_name'].'<hr>';
        echo '<table border="1">';
        echo '<tr><td>شناسه محصول</td><td>نام محصول</td><td>نوع محصول</td><td>تعداد موجودی</td><td>قیمت(ریال)</td><td>تخفیف(درصد)</td><td>َتصویر</td><td>َتوضیحات</td>';
        if($shop->checkAccess(1,$userId,$managerId,$conn) == 'user' or $shop->checkAccess(1,$userId,$managerId,$conn) == 'admin'){
            echo '<td>تعداد سفارش </td><td>عملیات</td> ';
        }
        echo '</tr>';
        for($i=0;$i<count($restaurant);$i++){
            echo '<tr><td>'.$restaurant[$i]['product_code'] .'</td>';
            echo '<td>'.$restaurant[$i]['product_name'] .'</td>';
            echo '<td>'.$restaurant[$i]['product_type'] .'</td>';
            echo '<td>'.$restaurant[$i]['product_number'] .'</td>';
            echo '<td>'.number_format($restaurant[$i]['product_price']) .'</td>';
            echo '<td>'.$restaurant[$i]['product_disscount'] .'%</td>';
            if($restaurant[$i]["product_pic"] != 'empty')
                    echo '<td><img  src="../uploads/'.$restaurant[$i]["product_pic"].'" height="100" width="100"></td>';
                else
                    echo '<td>بدون تصویر</td>';
            echo '<td>'.$restaurant[$i]['product_description'] .'</td>';
            if($shop->checkAccess(1,$userId,$managerId,$conn) == 'user' or $shop->checkAccess(1,$userId,$managerId,$conn) == 'admin'){
                echo '<form action="../user/buy_product.php" method="post">  
                    <td><input type="text" name="product_number"></td> 
                    <input type="hidden" name="product_code" value="'.$restaurant[$i]['product_code'].'">
                    <input type="hidden" name="restaurant_id" value="'.$restaurant_id.'">
                    <td><input type="submit" value="خرید" name="submit"></td></tr> 
                    </form>';
            }
        }
        echo '</table>';
    }
    else {
        echo 'هیچ محصولی در این فروشگاه وجود ندارد';
    }
}
else {
    session_start();
    $_SESSION['message'] = 'این رستوران وجود ندارد';
    header("Location: restaurant_list.php");
}

?>

<form method="post"><input type="hidden" name="back" value="1"><input type="submit" value="برگشت"></form>