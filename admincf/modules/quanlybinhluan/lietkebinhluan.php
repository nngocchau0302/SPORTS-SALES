<?php


// Ẩn bình luận nếu admin bấm "Ẩn"
if (isset($_GET['an_id'])) {
    $id_binhluan = $_GET['an_id'];
    $sql_an = "UPDATE tbl_binhluan SET trangthai = 'an' WHERE id_binhluan = '$id_binhluan'";
    mysqli_query($mysqli, $sql_an);
    header("Location: lietke.php");
    exit();
}
?>

<h1>Quản lý bình luận khách hàng</h1>

<table border="1" width="100%" style="border-collapse: collapse;">
    <tr>
        <th>ID</th>
        <th>Khách hàng</th>
        <th>Sản phẩm</th>
        <th>Nội dung</th>
        <th>Rating</th>
        <th>Phân loại cảm xúc</th>
        <th>Ngày bình luận</th>
        <th>Lượt báo cáo</th>
        <th>Lý do báo cáo</th>
        <th>Thao tác</th>
    </tr>

    <?php
    $sql = "SELECT b.*, s.tensanpham FROM tbl_binhluan AS b
            LEFT JOIN tbl_sanpham AS s ON b.id_sanpham = s.id_sanpham
            ORDER BY b.ngaybinhluan DESC";
    $query = mysqli_query($mysqli, $sql);

    while ($row = mysqli_fetch_array($query)) {
        $id_binhluan = $row['id_binhluan'];

        // Đếm số lượt báo cáo + lấy lý do
        $sql_baocao = "SELECT COUNT(*) as tong, GROUP_CONCAT(DISTINCT lydo SEPARATOR '<br>') as lydobaocao 
                       FROM tbl_baocao_binhluan 
                       WHERE id_binhluan = '$id_binhluan'";
        $result_baocao = mysqli_query($mysqli, $sql_baocao);
        $row_baocao = mysqli_fetch_assoc($result_baocao);
        $so_baocao = $row_baocao['tong'];
        $lydo_chitiet = $row_baocao['lydobaocao'] ?: '0';

        echo '<tr>';
        echo '<td>' . $id_binhluan . '</td>';
        echo '<td>' . htmlspecialchars($row['ten']) . '</td>';
        echo '<td>' . htmlspecialchars($row['tensanpham']) . '</td>';
        echo '<td style="text-align: left; padding: 6px;">' . nl2br(htmlspecialchars($row['noidung'])) . '</td>';
        echo '<td>' . $row['rating'] . ' ⭐</td>';
        echo '<td>' . htmlspecialchars($row['camxuc']) . '</td>';
        echo '<td>' . date('d/m/Y H:i', strtotime($row['ngaybinhluan'])) . '</td>';
        echo '<td style="color:' . ($so_baocao > 0 ? 'red' : 'green') . ';">' . $so_baocao . '</td>';
        echo '<td style="text-align: left; font-size: 13px; color: #444;">' . $lydo_chitiet . '</td>';

        // Nút ẩn nếu có báo cáo
        echo '<td>';
        if ($so_baocao > 0 && $row['trangthai'] != 'an') {
            echo '<a href="modules/quanlybinhluan/xuly_binhluan.php?an_id=' . $id_binhluan . '" 
           onclick="return confirm(\'Bạn có chắc chắn muốn ẩn bình luận này?\')" 
           class="btn-hide">
            <i class="fa-solid fa-eye-slash"></i> Ẩn
          </a>';
        } elseif ($row['trangthai'] == 'an') {
            echo '<a href="modules/quanlybinhluan/xuly_binhluan.php?hien_id=' . $id_binhluan . '" 
           onclick="return confirm(\'Bạn có muốn hiện lại bình luận này?\')" 
           class="btn-show">
            <i class="fa-solid fa-eye"></i> Hiện
          </a>';
        } else {
            echo '-';
        }
        echo '</td>';
        // Hiện lại bình luận nếu admin bấm "Hiện"
        if (isset($_GET['hien_id'])) {
            $id_binhluan = $_GET['hien_id'];
            $sql_hien = "UPDATE tbl_binhluan SET trangthai = 'hien' WHERE id_binhluan = '$id_binhluan'";
            mysqli_query($mysqli, $sql_hien);
            header("Location: lietke.php");
            exit();
        }

        echo '</tr>';
    }
    ?>
</table>
<style>
    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    td {
        padding: 20px;
        border-bottom: 1px solid #eee;
        text-align: center;
        color: #333;
        font-size: 15px;
    }


    th {
        background: linear-gradient(to right, #3498db, #2980b9);
        color: white;
        padding: 14px 10px;
        text-transform: uppercase;
        font-size: 14px;
        border-bottom: 2px solid #ddd;
    }


    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e6f7ff;
    }

    td:last-child a {
        background-color: #ff4d4d;
        color: white;
        padding: 6px 10px;
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
    }

    td:last-child a:hover {
        background-color: #e60000;
    }


    /* Nút Ẩn - dịu hơn */
.btn-hide {
    display: inline-block;
    background-color: #f39c12; /* cam nhạt */
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn-hide:hover {
    background-color: #d68910;
    transform: scale(1.03);
}

/* Nút Hiện lại */
.btn-show {
    display: inline-block;
    background-color: #7f8c8d; /* xám đậm */
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn-show:hover {
    background-color: #626567;
    transform: scale(1.03);
}
</style>