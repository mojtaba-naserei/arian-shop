<?php
require_once('../lib/connection.php'); //connect to DB
require_once('../lib/shop.php'); 
$shop = new Shop();
//=============================================

if(($_GET['id'] != null) && (is_numeric($_GET['id']))) {
    $restaurant_id = $_GET['id'];
    $restaurant = $shop->getProduct(null,$restaurant_id,$conn);
    if($restaurant != null){
        echo '<table border="1">';
        echo '<tr><td>شناسه محصول</td><td>نام محصول</td><td>نوع محصول</td><td>تعداد موجودی</td><td>قیمت(ریال)</td><td>تخفیف(درصد)</td><td>َتصویر</td><td>َتوضیحات</td><td>تعداد سفارش </td><td>عملیات</td> </tr>';
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
            echo '<form action="" method="post">  
                    <td><input type="text" name="number"></td> 
                    <input type="hidden" name="product_code">
                    <td><input type="submit" value="خرید" name="submit"></td></tr> 
                </form>';
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