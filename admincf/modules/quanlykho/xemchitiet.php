<?php


// Lấy id_sanpham từ URL
if (isset($_GET['id'])) {
    $id_sanpham = $_GET['id'];

    // Lấy thông tin sản phẩm
    $sql_sp = "SELECT tensanpham FROM tbl_sanpham WHERE id_sanpham = '$id_sanpham' LIMIT 1";
    $query_sp = mysqli_query($mysqli, $sql_sp);
    $row_sp = mysqli_fetch_array($query_sp);

    // Lấy danh sách seri
    $sql_seri = "SELECT * FROM tbl_seri WHERE id_sanpham = '$id_sanpham' ORDER BY id_seri ASC";
    $query_seri = mysqli_query($mysqli, $sql_seri);
} else {
    echo "<p style='color:red;'>Không tìm thấy sản phẩm.</p>";
    exit;
}
?>

<h2 style="text-align:center; color:#2c3e50;">Danh sách số seri - <?php echo $row_sp['tensanpham']; ?></h2>

<table border="1" style="width:60%; margin: 0 auto; border-collapse: collapse; margin-top: 20px;">
    <tr style="background-color: #3498db; color: white;">
        <th style="padding: 10px;">STT</th>
        <th style="padding: 10px;">Số Seri</th>
        <th style="padding: 10px;">Tình trạng</th>
    </tr>
    <?php
    $i = 0;
    while ($row_seri = mysqli_fetch_array($query_seri)) {
        $i++;
        echo '<tr style="text-align:center;">
                <td style="padding: 8px;">' . $i . '</td>
                <td style="padding: 8px;">' . $row_seri['seri'] . '</td>
                <td style="padding: 8px;">' . ($row_seri['tinhtrang'] == 0 ? 'Còn trong kho' : 'Đã bán') . '</td>
              </tr>';
    }
    if ($i == 0) {
        echo '<tr><td colspan="3" style="text-align:center; padding: 10px;">Chưa có số seri nào.</td></tr>';
    }
    ?>
</table>

<div style="text-align:center; margin-top: 20px;">
    <a href="index.php?action=quanlykho&query=lietke" style="text-decoration:none; color:white; background-color:#2c3e50; padding:10px 20px; border-radius:5px;">Quay lại</a>
</div>

<style>
    table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>
