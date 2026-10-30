<?php


// Lấy id_sanpham từ URL
if (isset($_GET['id'])) {
    $id_sanpham = $_GET['id'];

    // Lấy tên sản phẩm
    $sql_sp = "SELECT tensanpham FROM tbl_sanpham WHERE id_sanpham = '$id_sanpham' LIMIT 1";
    $query_sp = mysqli_query($mysqli, $sql_sp);
    $row_sp = mysqli_fetch_array($query_sp);

    // Lấy danh sách size
    $sql_size = "SELECT * FROM tbl_kichthuoc WHERE id_sanpham = '$id_sanpham' ORDER BY size ASC";
    $query_size = mysqli_query($mysqli, $sql_size);
} else {
    echo "<p style='color:red;'>Không tìm thấy sản phẩm.</p>";
    exit;
}
?>

<h2 style="text-align:center; color:#2c3e50;">Tồn kho theo size - <?php echo $row_sp['tensanpham']; ?></h2>

<table border="1" style="width:60%; margin: 0 auto; border-collapse: collapse; margin-top: 20px;">
    <tr style="background-color: #3498db; color: white;">
        <th style="padding: 10px;">STT</th>
        <th style="padding: 10px;">Size</th>
        <th style="padding: 10px;">Số lượng tồn</th>
        <th style="padding: 10px;">Hành động</th>
    </tr>
    <?php
    $i = 0;
    while ($row_size = mysqli_fetch_array($query_size)) {
        $i++;
        $id_kichthuoc = $row_size['id'];
        echo '<tr style="text-align:center;">
    <td style="padding: 8px;">' . $i . '</td>
    <td style="padding: 8px;">' . $row_size['size'] . '</td>
    <td style="padding: 8px; font-weight: 600; color:' . ($row_size['soluong'] == 0 ? 'red' : '#2c3e50') . ';">' . $row_size['soluong'] . '</td>
    <td style="padding: 8px;">
        <div style="display: inline-flex; align-items: center; justify-content: center;">
            
            <a href="xuly_kichthuoc.php?action=cong&id=' . $id_kichthuoc . '&idsp=' . $id_sanpham . '" 
               title="Tăng số lượng"
               style="display: inline-block; width: 30px; height: 30px; background-color: #2ecc71; color: white; 
                      text-align: center; line-height: 30px; border-radius: 6px; font-weight: bold; font-size: 18px;
                      transition: 0.2s; margin-right: 5px;">
                +
            </a>

            <a href="xuly_kichthuoc.php?action=tru&id=' . $id_kichthuoc . '&idsp=' . $id_sanpham . '" 
               title="Giảm số lượng"
               style="display: inline-block; width: 30px; height: 30px; background-color: #e74c3c; color: white; 
                      text-align: center; line-height: 30px; border-radius: 6px; font-weight: bold; font-size: 18px;
                      transition: 0.2s;">
                -
            </a>
        </div>
    </td>
</tr>';
    }

    if ($i == 0) {
        echo '<tr><td colspan="4" style="text-align:center; padding: 10px;">Chưa có size nào được thêm.</td></tr>';
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