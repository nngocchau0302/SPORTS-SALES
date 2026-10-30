<?php

include("./admincf/config/config.php");
// Xử lý khi người dùng gửi form đăng nhập
if (isset($_POST['dangnhap'])) {
    $email = trim($_POST['email']);
    $matkhau = md5(trim($_POST['password'])); // Mã hóa mật khẩu

    $sql = "SELECT * FROM tbl_dangky WHERE email='$email' AND matkhau='$matkhau' LIMIT 1";
    $result = mysqli_query($mysqli, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row_data = mysqli_fetch_assoc($result);
        // Lưu thông tin vào session
        $_SESSION['dangky'] = $row_data['tenkhachhang'];
        $_SESSION['email'] = $row_data['email'];
        $_SESSION['id_khachhang'] = $row_data['id_dangky'];

        // Nếu có tham số redirect thì chuyển về trang đó
        if (isset($_GET['redirect']) && $_GET['redirect'] !== '') {
            $redirect_url = urldecode($_GET['redirect']);
            header("Location: $redirect_url");
        } else {
            header("Location: index.php?quanly=giohang");
        }
        exit();
    } else {
        $error = 'Mật khẩu hoặc email không đúng. Vui lòng thử lại.';
    }
}

// Nếu có redirect, lưu lại để đưa vào form
$redirect_value = isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : '';
?>


<link rel="stylesheet" href="../../css/dangnhapkh.css" />

<body>
  <main>
    <div class="containerContent">
      <h1>Đăng nhập khách hàng</h1>
      <form id="loginForm" method="POST" action="index.php?quanly=dangnhap<?php echo $redirect_value ? '&redirect=' . urlencode($redirect_value) : ''; ?>">
        <label for="user">Email</label>
        <div class="inputRow">
          <input type="username" name="email" placeholder="Enter your Email" required />
        </div>
        <label for="password">Password</label>
        <div class="inputRow">
          <input type="password" name="password" placeholder="Enter your password" required />
          <span id="password-eye"><i class="ri-eye-off-line"></i></span>
        </div>
        <div class="inputFP">
          <a href="index.php?quanly=dangky">Forgot Password?</a>
        </div>
        <button type="submit" name="dangnhap" class="signupLogin">Đăng Nhập</button>
      </form>
      <h6>Or continue with</h6>
      <div class="logins">
        <a href="#"><img src="/images/Đăng nhập/search.png" alt="google" /></a>
        <a href="#"><img src="/images/Đăng nhập/ins.jpg" alt="instagram" /></a>
        <a href="#"><img src="/images/Đăng nhập/facebook.png" alt="facebook" /></a>
      </div>
      <p>Bạn chưa có tài khoản? <a href="index.php?quanly=dangky">Sign up</a></p>
    </div>
  </main>

  <!-- <script type="text/javascript" src="/js/index.js"></script> -->

</body>
