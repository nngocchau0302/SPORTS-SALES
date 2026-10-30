<?php
$sql_km = "SELECT km.*, sp.tensanpham FROM tbl_khuyenmai AS km
           JOIN tbl_sanpham AS sp ON km.id_sanpham = sp.id_sanpham
           ORDER BY km.id_khuyenmai DESC";
$query_km = mysqli_query($mysqli, $sql_km);
?>

<h3 class="title-km">📋 Danh sách khuyến mãi</h3>
<div class="table-container">
    <table class="styled-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Giảm (%)</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Quản lý</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            while($row = mysqli_fetch_array($query_km)){
                $i++;
            ?>
            <tr>
                <td><?php echo $i ?></td>
                <td><?php echo $row['tensanpham'] ?></td>
                <td><span class="discount-badge"><?php echo $row['phantramgiam'] ?>%</span></td>
                <td><?php echo date("d/m/Y", strtotime($row['ngaybatdau'])) ?></td>
                <td><?php echo date("d/m/Y", strtotime($row['ngayketthuc'])) ?></td>
                <td>
                    <a href="modules/khuyenmai/xoa.php?id=<?php echo $row['id_khuyenmai'] ?>" class="delete-btn">Xóa</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<style>
    .title-km {
        text-align: center;
        font-size: 24px;
        color: #2c3e50;
        margin: 30px 0 15px;
        font-weight: bold;
    }

    .table-container {
        overflow-x: auto;
        margin: auto;
        max-width: 1000px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
    }

    .styled-table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 8px;
        overflow: hidden;
    }

    .styled-table thead tr {
        background-color: #3498db;
        color: #ffffff;
        text-align: center;
        font-weight: bold;
    }

    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        text-align: center;
    }

    .styled-table tbody tr {
        background-color: #ffffff;
        transition: background-color 0.3s ease;
    }

    .styled-table tbody tr:hover {
        background-color: #f9f9f9;
    }

    .delete-btn {
        color: white;
        background-color: #e74c3c;
        padding: 6px 12px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }

    .delete-btn:hover {
        background-color: #c0392b;
    }

    .discount-badge {
        background-color: #2ecc71;
        color: white;
        padding: 4px 10px;
        border-radius: 15px;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .title-km {
            font-size: 20px;
        }

        .styled-table th,
        .styled-table td {
            font-size: 14px;
            padding: 10px;
        }

        .delete-btn {
            font-size: 13px;
            padding: 5px 10px;
        }
    }
</style>
