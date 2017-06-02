 <?php
require_once('connection.php'); //connect to DB

// $conn->close();

if(isset($_POST) && $_POST != null){
    $user_name_family = $_POST['user_name_family'];
    $user_mobile = $_POST['user_mobile'];
    $user_pass = md5($_POST['user_pass']);

//============================================ check exsit
    $sql = "SELECT * FROM users WHERE user_mobile = $userMobile";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo ' این کاربر وجود دارد . مجددا امتحان کنید';
    } 
    else {
        if(isset($user_name_family) && isset($user_mobile) && isset($user_pass)){
            $sql = "INSERT INTO users (user_name_family, user_mobile, user_pass)
            VALUES ('$user_name_family','$user_mobile', '$user_pass')";

            if ($conn->query($sql) === TRUE) {
                echo "ثبت نام با موفقیت انجام شد";
            } 
            else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
//============================================ store
  $conn->close(); 
}



?> 
<form action="" method="post">
  <div class="container">
    <label><b>نام و نام خانوادگی</b></label>
    <input type="text" placeholder="نام خود را وارد کنید" name="user_name_family" required>
<br>
    <label><b>شماره همراه</b></label>
    <input type="text" placeholder="موبایل را وارد کنید" name="user_mobile" required>
<br>
    <label><b>کلمه عبور</b></label>
    <input type="text" placeholder="کلمه عبور را وارد کنید" name="user_pass" required>
   <br>
    <div class="clearfix">
      <button type="submit" class="signupbtn">ثبت نام</button>
    </div>
  </div>
</form> 