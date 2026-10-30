<?php
    session_start();
    include('config/config.php');
    if(isset($_POST['dangnhap'])){
        $taikhoan = $_POST['username'];
        $matkhau = md5($_POST['password']);
        $sql = "SELECT * FROM tbl_admin WHERE username='".$taikhoan."' AND password='".$matkhau."' LIMIT 1";
        $row = mysqli_query($mysqli,$sql);
        $count = mysqli_num_rows($row);
        if($count>0){
            $_SESSION['dangnhap'] = $taikhoan;
            header("Location:index.php");
        }else{
            echo '<script> alert("Tài khoản hoặc mật khẩu không đúng .Vui lòng nhập lại. ");</script>';
            header("Location:login.php");
        }
    }
?>
<style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #4f46e5, #3b82f6);
      min-height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }
    main {
    height: 663px;
    width: 100%;
    max-width: 437px;
    background: rgb(239 240 240 / 95%);
    border-radius: 1.5rem;
    box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3);
    padding: 21rem -16.5rem;
    box-sizing: border-box;
}
    
        h1 {
      font-weight: 800;
      font-size: 2.5rem;
      color: #4338ca;
      text-align: center;
      margin-bottom: 2.5rem;
      letter-spacing: 0.05em;
    }
     label {
      display: block;
      font-weight: 600;
      color: #3730a3;
      margin-bottom: 0.5rem;
      font-size: 1.1rem;
    }
    input[type="username"],
    input[type="password"] {
      width: 100%;
      padding: 0.75rem 1.25rem;
      border: 2px solid rgb(251, 251, 252);
      border-radius: 0.75rem;
      font-size: 1rem;
      color: #3730a3;
      transition: border-color 0.3s ease;
      box-sizing: border-box;
    }
     input[type="username"]:focus,
    input[type="password"]:focus {
      outline: none;
      border-color: #4338ca;
      box-shadow: 0 0 8px rgba(67, 56, 202, 0.5);
    }
    .inputRow {
      position: relative;
      margin-bottom: 1.75rem;
    }
    #password-eye {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #7c3aed;
      font-size: 1.3rem;
      transition: color 0.3s ease;
      user-select: none;
    }
    #password-eye:hover {
      color: #5b21b6;
    }
      .inputFP a {
      color: #5b21b6;
      font-weight: 600;
      font-size: 0.9rem;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    .inputFP a:hover {
      color: #4338ca;
      text-decoration: underline;
    }
    .signupLogin {
      width: 100%;
      background-color: #4338ca;
      color: white;
      font-weight: 700;
      font-size: 1.2rem;
      padding: 0.85rem 0;
      border-radius: 1rem;
      border: none;
      cursor: pointer;
      box-shadow: 0 8px 15px rgba(67, 56, 202, 0.4);
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }
    .signupLogin:hover {
      background-color: #5b21b6;
      box-shadow: 0 12px 20px rgba(18, 88, 145, 0.6);
    }
     h6 {
      text-align: center;
      margin-top: 3rem;
      margin-bottom: 1.5rem;
      font-weight: 600;
      color: #4338ca;
      font-size: 1rem;
      letter-spacing: 0.05em;
    }
     .logins {
      display: flex;
      justify-content: center;
      gap: 2rem;
    }
   
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/dangnhap.css" />
    
    <link rel="icon" type="image/x-icon" href="/images/logoft1.png">
    <title>AdminDC </title>
</head>

<body>
  <main>
   <div class="container">
    <div class="containerContent">
  
      <h1>LOGIN</h1>
      <form id="loginForm" action="" method="POST">
          <label for="user">Username</label>

        <div class="inputRow">
          <input type="username" name="username" class="Email" placeholder="Enter your Username" required/>
        </div>

          <label for="password">Password</label>
        <div class="inputRow">
          <input type="password" name="password" class="passWord" placeholder="Enter your password" required/>
          <span id="password-eye"><i class="ri-eye-off-line"></i></span>
        </div>

        <div class="inputFP">
          <a href="dangki.html">Forgot Password?</a>
        </div>
        
        <button type="submit" name="dangnhap" class="signupLogin" >Đăng Nhập</button>
         
      </form>
      <!-- <h6>Or continue with</h6>
      <div class="logins">
        <a href="#"><img src="/images/Đăng nhập/search.png" alt="google" /></a>
        <a href="#"><img src="/images/Đăng nhập/ins1.jpg" alt="instagram" /></a>
        <a href="#"><img src="/images/Đăng nhập/facebook.png" alt="facebook" /></a>
      </div> -->
      
    </div>
   
  </div>
</main>


       

<script type="text/javascript" src="/js/index.js"></script>
<script type="text/javascript" defer src="/js/jquery-3.7.1.min.js"></script>
</body>
</html>