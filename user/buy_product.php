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
if($shop->checkAccess(1,$userId,$managerId,$conn) == 'manager' or $shop->checkAccess(1,$userId,$managerId,$conn) == null){
    $_SESSION['message'] = 'شما به این صفحه دسترسی ندارید';
    header("Location: ../index.php");
    die();
}
else {

if(isset($_POST['product_code']) && isset($_POST['restaurant_id'])){
    $uniqid = $_SESSION['uniqid'];
    $restaurant_id = $_POST['restaurant_id'];
    $ctime = time();
    
    //------------------------ product info
    $product_code = $_POST['product_code'];
    $product = $shop->getProduct($product_code,null,$conn)[0];
    $product_number = $_POST['product_number'];
    $product_price = $product['product_price'];
    $product_disscount = $product['product_disscount'];
//============================================ 

    if ($shop->getOrder($userId,$uniqid,$conn) != null){
        $order = $shop->getOrder($userId,$uniqid,$conn)[0];
        $order_id = $order['order_id'];
        $sql = "SELECT orders.*,products_order.* FROM orders 
        LEFT JOIN products_order ON orders.order_id = products_order.order_id 
        WHERE  products_order.food_code = $product_code AND products_order.order_id= $order_id ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $_SESSION['sabad'] = 'این محصول قبلا به سبد اضافه شده است';
            header("Location: ../shop/food.php?id=".$restaurant_id);
            die();
        } 
        else { 
            $total_amount = $order['total_amount'];
            $total_disscount = $order['total_disscount'];

            $newTotal = $product_number * $product_price;

            $totalPrice = $total_amount + $newTotal;
            $totalDisscount = $total_disscount + $product_disscount;
            //============================== update
            $sql1 = "UPDATE orders 
            SET total_amount='$totalPrice',total_disscount='$totalDisscount',edit_time='$ctime'
            WHERE order_id='$order_id'";
        //============================== update

            if ($conn->query($sql1) === TRUE) {
                $sql2 = "INSERT INTO products_order (order_id ,food_code, order_number)
                VALUES ('$order_id','$product_code', '$product_number')";

                if ($conn->query($sql2) === TRUE) {
                    $_SESSION['sabad'] = 'محصول به سبد اضافه شد';
                    header("Location: ../shop/food.php?id=".$restaurant_id);
                    die();
                } 
                else {
                    $_SESSION['sabad'] = 'خطا در اضافه شدن محصول به سبد';
                    header("Location: ../shop/food.php?id=".$restaurant_id);
                    die();
                }
            }
            else {
                $_SESSION['sabad'] = 'خطا در بروزرسانی سفارش';
                header("Location: ../shop/food.php?id=".$restaurant_id);
                die();
            }
        }
    }
    else {
        $totalAmount =  $product_number * $product_price;
        //============================================================== create order
        $sql3 = "INSERT INTO orders (customer_id ,total_amount, total_disscount,create_time,session)
            VALUES ('$userId','$totalAmount','$product_disscount' ,$ctime,'$uniqid')";

        if ($conn->query($sql3) === TRUE) {
            $orderId = $conn->insert_id;
            //=========================================== 
            $sql4 = "INSERT INTO products_order (order_id ,food_code, order_number)
            VALUES ('$orderId','$product_code','$product_number')";

            if ($conn->query($sql4) === TRUE) {
                $_SESSION['sabad'] = 'محصول به سبد اضافه شد';
                header("Location: ../shop/food.php?id=".$restaurant_id);
                die();
            }
            else {
                $_SESSION['sabad'] = 'سفارش ایجاد شد ولی محصول به سبد اضافه نشد';
                header("Location: ../shop/food.php?id=".$restaurant_id);
                die();
            }
        } 
        else {
            $_SESSION['sabad'] = 'خطا در ایجاد سفارش';
                header("Location: ../shop/food.php?id=".$restaurant_id);
                die();
        }   
    }   
    
}
else {
    echo 'این محصول وجود ندارد';
}
//============================================ store
  $conn->close(); 
}
?>
<form method="post"><input type="hidden" name="back" value="1"><input type="submit" value="برگشت"></form>