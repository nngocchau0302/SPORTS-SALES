<?php
session_start();
include("../../admincf/config/config.php");

if (isset($_POST['guibaocao']) && isset($_SESSION['id_khachhang'])) {
    $id_binhluan = intval($_POST['id_binhluan']);
    $id_khachhang = $_SESSION['id_khachhang'];
    $lydo = trim(mysqli_real_escape_string($mysqli, $_POST['lydo']));

    // Kiểm tra đã báo cáo chưa
    $sql_check = "SELECT * FROM tbl_baocao_binhluan WHERE id_binhluan = $id_binhluan AND id_khachhang = $id_khachhang";
    $check_query = mysqli_query($mysqli, $sql_check);

    if (mysqli_num_rows($check_query) == 0) {
        $sql_insert = "INSERT INTO tbl_baocao_binhluan (id_binhluan, id_khachhang, lydo) 
                       VALUES ($id_binhluan, $id_khachhang, '$lydo')";
        if (mysqli_query($mysqli, $sql_insert)) {
            // Đếm lượt báo cáo
            $count_sql = "SELECT COUNT(*) as sobc FROM tbl_baocao_binhluan WHERE id_binhluan = $id_binhluan";
            $result = mysqli_query($mysqli, $count_sql);
            $row = mysqli_fetch_assoc($result);
            $sobc = $row['sobc'];

            // Tự động ẩn nếu >=5
            if ($sobc >= 5) {
                mysqli_query($mysqli, "UPDATE tbl_binhluan SET trangthai='an' WHERE id_binhluan = $id_binhluan");
            }

            echo "<script>alert('Báo cáo đã gửi!'); window.history.back();</script>";
        } else {
            echo "<script>alert('Lỗi khi gửi báo cáo.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Bạn đã báo cáo bình luận này rồi.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Bạn cần đăng nhập để báo cáo.'); window.history.back();</script>";
}
?>
