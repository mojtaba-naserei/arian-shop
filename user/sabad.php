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
if($shop->checkAccess(1,$userId,$managerId,$conn) != 'user' && $shop->checkAccess(1,$userId,$managerId,$conn) != 'admin'){
    $_SESSION['message'] = 'شما به این صفحه دسترسی ندارید';
    header("Location: ../index.php");
    die();
}

if (isset($_SESSION['uniqid'])){
    $uniqid = $_SESSION['uniqid'];
    $order = $shop->getOrder($userId,$uniqid,$conn)[0];
    $orderId = $order['order_id'];
    $total_amount = $order['total_amount'];
    $total_disscount = $order['total_disscount'];
    if ($total_disscount <= 100)
        $calcTotal = $total_amount -  (($total_amount * $total_disscount)/100) ;
    else 
        $calcTotal = $total_amount -  (($total_amount * 100)/100) ;
    $product = $shop->products_order($orderId,null,$conn);
}

if (isset($_SESSION['sabad'])){
    echo $_SESSION['sabad'];
    unset($_SESSION['sabad']);
    echo '</br>';
}

//============================================================================= update 
if(isset($_POST) && $_POST != null){
    if(isset($_POST['order_id']) && isset($_POST['food_code']) && isset($_POST['order_number'])){
        $order_id = $_POST['order_id'];
        $food_code = $_POST['food_code'];
        $order_number = $_POST['order_number'];
        $productPrice = $_POST['update_price'];

        $porder = $shop->products_order($orderId,$food_code,$conn)[0];
        $oldPrice = $porder['order_number'] *  $productPrice;
        $oldtotalAmount = $total_amount - $oldPrice; 
        $newPrice = $order_number * $productPrice; // new amount
        $newtotalAmount = $oldtotalAmount + $newPrice;
        
        $etime = time();
        $sql1 = "UPDATE products_order SET order_number='$order_number' WHERE food_code='$food_code' AND order_id='$order_id'";

        if ($conn->query($sql1) === TRUE) {
            $sql2 = "UPDATE orders SET total_amount='$newtotalAmount',edit_time='$etime' WHERE order_id='$order_id'";
              if ($conn->query($sql2) === TRUE) {
                  echo "غذا با موفقیت بروزرسانی شد";
                  header("Location: sabad.php");
                  die();
              }
              else {
                 echo "خطا در بروز رسانی: " . $conn->error;
              }
            
        } else {
            echo "خطا در بروز رسانی: " . $conn->error;
        }
    }
    //=========================================================================== delete
    else if(isset($_POST['del'])){
        $food_code =  $_POST['del'];
        $order_id = $_POST['order_id'];
        $del_price =  $_POST['del_price'];
        $del_disscount =  $_POST['del_disscount'];
        $order_number =  $_POST['order_number'];
        $etime = time();

        $sql1 = "DELETE FROM products_order WHERE food_code='$food_code' AND order_id='$order_id'";
        if ($conn->query($sql1) === TRUE) {
            if($shop->products_order($orderId,null,$conn) == null){
                $sql2 = "DELETE FROM orders WHERE order_id='$orderId'";
                if ($conn->query($sql2) === TRUE) {
                    echo "سفارش حذف شد";
                    header("Location: ../index.php");
                    die();
                }
                else {
                    echo "خطا در حذف: " . $conn->error;
                }
            }
            else {
                $product_price = $order_number * $del_price;
                $totalPrice = $total_amount - $product_price;
                $totalDisscount = $total_disscount - $del_disscount;

                $sql2 = "UPDATE orders 
                SET total_amount='$totalPrice',total_disscount='$totalDisscount',edit_time='$etime'
                WHERE order_id='$orderId'";

                if ($conn->query($sql2) === TRUE) {
                    $_SESSION['sabad'] = 'فاکتور بروزرسانی شد';
                    header("Location: sabad.php");
                    die();
                }
                else {
                    $_SESSION['sabad'] = 'خطا در بروزرسانی فاکتور';
                    header("Location: sabad.php");
                    die();
                }
            }     
        } 
        else {
            echo "خطا در حذف: " . $conn->error;
        }
    }
}

//=========================================================================================
//=========================================================================================
?>

<?php   
    if($product != null){
        echo '<table border="1">';
        echo '<tr><td>شناسه محصول</td><td>نام محصول</td><td>قیمت(ریال)</td><td>تخفیف(درصد)</td><td>َتصویر</td>';
        if($shop->checkAccess(1,$userId,$managerId,$conn) != 'manager'){
            echo '<td>تعداد سفارش </td><td>عملیات</td> ';
        }
        echo '</tr>';
        for($i=0;$i<count($product);$i++){
            echo '<tr><td>'.$product[$i]['food_code'] .'</td>';
            echo '<td>'.$shop->getProduct($product[$i]['food_code'],null,$conn)[0]['product_name'] .'</td>';
            echo '<td>'.$shop->getProduct($product[$i]['food_code'],null,$conn)[0]['product_price'] .'</td>';
            echo '<td>'.$shop->getProduct($product[$i]['food_code'],null,$conn)[0]['product_disscount'] .'</td>';
            if($shop->getProduct($product[$i]['food_code'],null,$conn)[0]['product_pic'] != 'empty')
                    echo '<td><img  src="../uploads/'.$shop->getProduct($product[$i]['food_code'],null,$conn)[0]['product_pic'].'" height="100" width="100"></td>';
                else
                    echo '<td>بدون تصویر</td>';
            if($shop->checkAccess(1,$userId,$managerId,$conn) != 'manager'){
                echo '<form action="" method="post">  
                    <td><input type="text" name="order_number" value="'.$product[$i]['order_number'].'"></td> 
                    <input type="hidden" name="order_id" value="'.$product[$i]['order_id'].'">
                    <input type="hidden" name="food_code" value="'.$product[$i]['food_code'].'">
                    <input type="hidden" name="update_price" value="'.$shop->getProduct($product[$i]['food_code'],null,$conn)[0]['product_price'].'">
                    <td><input type="submit" value="بروزرسانی" name="submit">
                    </form>';
                echo '<form action="" method="post">  
                    <input type="hidden" name="del" value="'.$product[$i]['food_code'].'">
                    <input type="hidden" name="order_id" value="'.$product[$i]['order_id'].'">
                    <input type="hidden" name="order_number" value="'.$product[$i]['order_number'].'">
                    <input type="hidden" name="del_price" value="'.$shop->getProduct($product[$i]['food_code'],null,$conn)[0]['product_price'].'">
                    <input type="hidden" name="del_disscount" value="'.$shop->getProduct($product[$i]['food_code'],null,$conn)[0]['product_disscount'].'">
                    <input type="submit" value="حذف" name="submit"></td></tr> 
                    </form>';
            }
        }
        echo '</table>';

        echo '<table border="1">';
        echo '<tr><td>َجمع کل(ریال)</td><td>مبلغ قابل پرداخت با تخفیف(ریال)</td> </tr>';
        echo '<tr><td>'.number_format($total_amount) .'</td><td>'.number_format($calcTotal).'</td></tr>';
        echo '<tr><td>درگاه</td><td>امتیاز</td><td>نظر</td></tr>';
        echo '<form action="payment.php" method="post">  
            <input type="hidden" name="order_id" value="'.$orderId.'">
            <input type="hidden" name="total_price" value="'.$calcTotal.'">
            <td> <select name="port" required>
                    <option value="saman" selected>سامان</option>
                    <option value="mellat" >ملت</option>  
                    <option value="parsian" >پارسیان</option> 
            </select></td>
            <td> <select name="score" required>
                    <option value="1" selected>1</option>
                    <option value="2" >2</option>  
                    <option value="3" >3</option> 
                    <option value="4" >4</option>  
                    <option value="5" >5</option> 
            </select></td>
            
            <td><textarea rows="5" cols="30" placeholder="نظر خود را وارد کنید"  name="description"></textarea></td>';
        echo '</table>
        <input type="submit" value="پرداخت" name="submit"></td></tr> 
            </form>';
    }
    else {
        echo 'هیچ محصولی در این سبد وجود ندارد';
    }

?>

<form method="post"><input type="hidden" name="back" value="1"><input type="submit" value="برگشت"></form>