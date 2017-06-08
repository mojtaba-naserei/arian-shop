<?php
require_once('../lib/connection.php'); //connect to DB
require_once('../lib/jdf.php'); //for shamsi time
require_once('../lib/shop.php'); 
$shop = new Shop();
session_start();
//===========================================  check access
if(isset($_SESSION['userid'])) $userId=  $_SESSION['userid']; else $userId= null;
if(isset($_SESSION['managerid'])) $managerId=  $_SESSION['managerid']; else $managerId= null;
if($shop->checkAccess(1,$userId,$managerId,$conn) == 'manager'){
    $_SESSION['message'] = 'شما به این صفحه دسترسی ندارید';
    header("Location: ../index.php");
    die();
}
$card_number = rand(1000,9000).rand(1000,9000).rand(1000,9000).rand(1000,9000);

if(isset($_POST) && $_POST != null){
     
    if(isset($_POST['order_id']) && isset($_POST['port']) && isset($_POST['score']) && isset($_POST['total_price'])){
        $order_id =  $_POST['order_id'];
        $port =  $_POST['port'];
        $score =  $_POST['score'];
        $total_price =  $_POST['total_price'];
        $ctime = time();
        if(isset($_POST['description']))
            $description =  $_POST['description'];
        else 
            $description ='بدون نظر';

            //================================== insert comments
        if($shop->getComments($order_id,$conn) != null){
            $conn->query("UPDATE comments SET score='$score',description='$description' WHERE order_id='$order_id'");
        }
        else {
            $conn->query("INSERT INTO comments (order_id, score,description, create_time) VALUES ('$order_id','$score','$description', '$ctime')");
        }
        //================================== insert comments
            
        //================================== insert payment
        if($shop->getPayments($order_id,$conn) != null){
            $conn->query("UPDATE payments SET time='$ctime',port_name='$port',price='$total_price' WHERE order_id='$order_id'");
        }
        else {
            $conn->query("INSERT INTO payments (order_id, time,port_name,price) VALUES ('$order_id', '$ctime','$port',$total_price)"); 
        }
        
        //================================== insert payment        
    }
    else if(isset($_POST['pay'])){
        $order_id =  $_POST['pay'];
    
       //============================================================== kam kardan az mojodi
       $porder = $shop->products_order($order_id,null,$conn);
       
       for($i=0;$i<count($porder);$i++){
            $menuProduct[] = $shop->getProduct($porder[$i]['food_code'],null,$conn)[0]; 
       }

        for($j=0;$j<count($porder);$j++){
            if($porder[$j]['food_code'] == $menuProduct[$j]['product_code']){
                $newValue[$j][] = $porder[$j]['food_code'];
                $newValue[$j][] = $menuProduct[$j]['product_number'] - $porder[$j]['order_number'];
            }
        }

        for($k=0;$k<count($newValue);$k++){
            $product_code  = $newValue[$k][0];
            $product_number  = $newValue[$k][1];
            $conn->query("UPDATE products_menu SET product_number='$product_number' WHERE product_code='$product_code'");
        }

        //==============================================================

        $tracking_code=  uniqid();
        $pay_id = rand(1000,9000);
        $sql3 = "UPDATE payments SET tracking_code='$tracking_code',pay_id='$pay_id',card_number='$card_number',status='1' WHERE order_id='$order_id'"; // update payment
        if($conn->query($sql3) === true){
            $i=1;
            while($i){
                $peyk_id = $shop->randomPeyk($conn);
                if($shop->getPeyk($peyk_id,$conn)[0]['status'] != 1){
                    $conn->query("UPDATE transporter SET status='1'  WHERE transporter_id='$peyk_id'"); // update  peyk
                    $conn->query("UPDATE orders SET peyk_code='$peyk_id' WHERE order_id='$order_id'"); // update orders
                    
                    $i = 0;
                }
            } 
            $conn->query("UPDATE orders SET session='' WHERE order_id='$order_id'"); // clear session
            $_SESSION['message'] = 'کد پیگیر شما'.'<br>'. $tracking_code;
            header("Location: ../index.php");
            die();   
        }
        else {
            $conn->query("UPDATE orders SET session='' WHERE order_id='$order_id'");// clear session
            $_SESSION['message'] = 'خطا در پرداخت';
            header("Location: ../index.php");
            die();
        }
    }
    else if(isset($_POST['cancel'])){
        $order_id =  $_POST['cancel'];
        $conn->query("UPDATE orders SET session='' WHERE order_id='$order_id'");// clear session
        $_SESSION['message'] = 'سفارش کنسل شده است';
        header("Location: ../index.php");
        die();
    }
}
?>

<table border="1">
<tr><td><?php echo jdate("o/m/j",time()); ?></td><td>تاریخ پرداخت</td></tr>
<tr><td><?php echo $_POST['order_id']; ?></td><td>شماره سفارش</td></tr>
<tr><td><?php echo number_format($_POST['total_price']); ?></td><td>مبلغ سفارش(ریال)</td></tr>
<tr><td><?php echo implode("-",str_split($card_number,4)); ?></td><td>شماره کارت</td></tr>
<tr><td><?php 
switch($_POST['port']){
    case 'parsian': echo 'پاریسان';break;
    case 'mellat': echo 'ملت';break;
    case 'saman': echo 'سامان';break;
}
?></td><td>نام درگاه</td></tr>

</table>
<form action="" method="post">  
<input type="hidden" name="pay" value="<?php echo $_POST['order_id']; ?>">
</table>
<input type="submit" value="پرداخت" name="submit"></td></tr> 
</form>


<form action="" method="post">  
<input type="hidden" name="cancel" value="<?php echo $_POST['order_id']; ?>">
</table>
<input type="submit" value="کنسل" name="submit"></td></tr> 
</form>

