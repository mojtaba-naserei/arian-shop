<?php 
require_once('lib/connection.php'); //connect to DB
require_once('lib/shop.php'); 
$shop = new Shop();
session_start();
//============================================ check user
if(isset($_SESSION['userid'])) $userId=  $_SESSION['userid']; else $userId= null;
if(isset($_SESSION['managerid'])) $managerId=  $_SESSION['managerid']; else $managerId= null;

if($shop->checkAccess(1,$userId,$managerId,$conn) != null){
   if($shop->checkAccess(1,$userId,$managerId,$conn) == 'manager'){
      $manager = $shop->checkAccess(0,$userId,$managerId,$conn);
        echo $manager['manager_name'].' نام کاربری '.'</br>';
        echo $manager['restaurant_name'].' نام رستوران '.'</br>';
        echo '</br>
         <ul>
        <li> <a href="manager/manage_products_menu.php" >مدیریت منو غذایی</a><br></li>
        
        </ul>
        
        ';

        //=========================================================== number of orders
        $pay = $shop->checkPayments($_SESSION['managerid'],$conn);
        echo '<table border="1">
        <tr><td>محصولات سفارش داده شد </td></tr>          
        <tr><td>تعداد  سفارش</td><td> کد محصول</td></tr>';  
        if ($pay != null){
            for($i=0;$i<count($pay);$i++){
                 echo '  <tr><td>'.array_values($pay)[$i].'</td><td>'.array_keys($pay)[$i].'</td></tr> ';    
            }
        }
        else{
            echo '<tr><td>هیچ محصولی سفارش داده نشده است.</td></tr>';
        }
        echo '</table>';
        //===========================================================
        
   }
   else {
        $user =  $shop->checkAccess(0,$userId,$managerId,$conn);
        echo $user['user_name_family'].' نام کاربری '.'</br>';
        echo 'سطح دسترسی ';
        if ($shop->checkAccess(1,$userId,$managerId,$conn) != 'admin'){
            echo 'کاربر معمولی';
        }
        else {
            echo 'مدیر کل'.'<br>';
            echo '<ul><li> <a href="manager/manager.php" >مدیریت بخش ها</a></li></ul>';        
        }

        echo '<ul><li> <a href="user/sabad.php" >سبد خرید</a></li></ul>';        
   }
   
    echo '<form action="" method="post">
         <input type="hidden" value="1" name="logout">
         <input type="submit" value="خروج کاربر" name="submit">
         </form>
        ';
   echo '<table border="1">
        <tr><td> <a href="shop/fastfood_list.php">فست فود</a> </td><td><a href="shop/restaurant_list.php"> رستوران</a> </td></tr>      
        </table>';
}
else {
    echo '
        <ul>
        <li> <a href="registers/user_register.php" > ثبت نام مشتری</a><br></li>
        <li> <a href="registers/manager_register.php" > ثبت فروشگاه</a></li>
        <li>
            <div>
                <form action="logins/user_login.php" method="post">
                نام کاربری  <input type="text" value="" placeholder="شماره همراه را وارد کنید" name="username"> 
                <input type="password" value="" placeholder="کلمه عبور را وارد کنید" name="password">کلمه عبور
                <input type="submit" value="ورود کاربر" name="submit">
                </form>
            </div>
        </li>
        <li>
            <div>
                <form action="logins/manager_login.php" method="post">
                نام کاربری  <input type="text" value="" placeholder="شماره همراه را وارد کنید" name="username"> 
                <input type="password" value="" placeholder="کلمه عبور را وارد کنید" name="password">کلمه عبور
                <input type="submit" value="ورود مدیر" name="submit">
                </form>
            </div>
        </li>
        </ul>
    ';

     echo '<table border="1">
        <tr><td> <a href="shop/fastfood_list.php">فست فود</a> </td><td><a href="shop/restaurant_list.php"> رستوران</a> </td></tr>      
        </table>';
}
if (isset($_SESSION['message'])){
    echo '<div>'.$_SESSION['message'].'</div>';
    unset($_SESSION['message']);
}

if(isset($_POST['logout'])){
    $shop->logOut($conn);
}
?>


