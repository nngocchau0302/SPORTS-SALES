<?php
include("./admincf/config/config.php");

if (isset($_POST['doimtakhau'])) {
    $email = $_POST['email'];
    $matkhau_cu = md5($_POST['password_cu']); // Mã hóa mật khẩu cũ
    $matkhau_moi = md5($_POST['password_moi']); // Mã hóa mật khẩu mới

    // Kiểm tra mật khẩu cũ
    $sql = "SELECT * FROM tbl_dangky WHERE email=? AND matkhau=? LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $email, $matkhau_cu);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra lỗi trong truy vấn
    if ($result === false) {
        echo 'Lỗi truy vấn: ' . mysqli_error($mysqli);
        exit(); // Dừng lại nếu có lỗi
    }

    $count = $result->num_rows;
    if ($count > 0) {
        // Cập nhật mật khẩu mới
        $sql_update = "UPDATE tbl_dangky SET matkhau=? WHERE email=?";
        $stmt_update = $mysqli->prepare($sql_update);
        $stmt_update->bind_param("ss", $matkhau_moi, $email);
        
        if ($stmt_update->execute()) {
            echo '<p style="color:green">Mật khẩu đã được thay đổi</p>';
        } else {
            echo '<p style="color:red">Lỗi cập nhật mật khẩu</p>';
        }

        // Đóng statement cập nhật
        $stmt_update->close();
    } else {
        echo '<p style="color:red">Mật khẩu cũ hoặc Email sai. Vui lòng nhập lại</p>';
    }

    // Đóng statement kiểm tra mật khẩu
    $stmt->close();
}

// Đảm bảo đóng kết nối
$mysqli->close();
?>
<body>
  <main>
   <div class="container">
    <div class="containerContent">
  
      <h1>Đổi mật khẩu</h1>
      <form id="loginForm" action="" method="POST">
           <label for="user">Email</label>
        <div class="inputRow">
          <input type="email" name="email" placeholder="Enter your Email" required />
        </div>

          <label for="password">Mật khẩu cũ</label>
        <div class="inputRow">
          <input type="password" name="password_cu" class="passWord" placeholder="Enter your current password" required/>
          <span id="password-eye"><i class="ri-eye-off-line"></i></span>
        </div>

        <label for="password">Mật khẩu mới</label>
        <div class="inputRow">
          <input type="password" name="password_moi" class="passWord" placeholder="Enter your new password" required/>
          <span id="password-eye"><i class="ri-eye-off-line"></i></span>
        </div>
        <button type="submit" name="doimtakhau" class="signupLogin">Đổi mật khẩu</button>
      </form>
    </div>
  </div>
</main>
<link rel="stylesheet" href="../../css/dangnhapkh.css" />
</body>