<?php 



?>
<ul>
<li> <a href="registers/user_register.php" > ثبت نام مشتری</a><br></li>
 <li> <a href="registers/manager_register.php" > ثبت فروشگاه</a></li>
 <li>
    <div>
        <form action="logins/user_login.php" method="post">
        نام کاربری  <input type="text" value="" name="username"> 
        <input type="password" value="" name="password">کلمه عبور
        <input type="submit" value="ورود کاربر" name="submit">
        </form>
    </div>
 </li>
<li>
    <div>
        <form action="logins/manager_login.php" method="post">
        نام کاربری  <input type="text" value="" name="username"> 
        <input type="password" value="" name="password">کلمه عبور
        <input type="submit" value="ورود مدیر" name="submit">
        </form>
    </div>
 </li>
</ul>

<table border="1">
<tr><td> <a href="shop/fastfood_list.php">فست فود</a> </td><td><a href="shop/restaurant_list.php"> رستوران</a> </td></tr>
       
 </table>