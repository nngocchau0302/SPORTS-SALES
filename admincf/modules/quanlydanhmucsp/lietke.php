<?php
$sql_lietke_danhmucsp = "SELECT * FROM tbl_danhmuc ORDER BY thutu DESC";
$query_lietke_danhmucsp = mysqli_query($mysqli, $sql_lietke_danhmucsp);
?>
<p class="chitietdm">Liệt kê danh mục sản phẩm</p>
<table style="width:100%" border="1" style="border-collapse:collapse;">
    <tr>
        <th>Id</th>
        <th>Tên danh mục sản phẩm</th>
        <th>Quản lý</th>

    </tr>
    <?php
    $i = 0;
    while ($row = mysqli_fetch_array($query_lietke_danhmucsp)) {
        $i++;
    ?>
        <tr>
            <td><?php echo $i ?></td>
            <td><?php echo $row['tendanhmuc'] ?></td>
            <td>
                <a class="Xoa" href="/admincf/modules/quanlydanhmucsp/xuly.php?iddanhmuc=<?php echo  $row['id_danhmuc'] ?>" title="Xóa">
                    <i class="fas fa-trash-alt"></i>
                </a>
                <a class="Sua" href="?action=quanlydanhmucsanpham&query=sua&iddanhmuc=<?php echo  $row['id_danhmuc'] ?>" title="Sửa">
                    <i class="fas fa-edit"></i>
                </a>
            </td>

        </tr>
    <?php
    }
    ?>
    <style>
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

        tbody {
            height: 100px;
        }

        td a {
            display: inline-block;
            margin-right: 10px;
            /* Khoảng cách giữa các icon */
            color: white;
            /* Màu chữ */
            text-decoration: none;
            /* Bỏ gạch chân */
            padding: 10px;
            /* Khoảng cách bên trong nút */
            border-radius: 5px;
            /* Bo góc */
            transition: background-color 0.3s;
            /* Hiệu ứng chuyển màu */
        }

        .Xoa {
            background-color: #dc3545;
            /* Màu nền cho nút Xóa */
        }

        .Sua {
            background-color: #007bff;
            /* Màu nền cho nút Sửa */
        }

        td a:hover {
            opacity: 0.8;
            /* Hiệu ứng khi hover */
        }

        i {
            font-size: 20px;
            /* Kích thước icon */
        }

        .chitietdm {
            display: block;
            font-size: 1.3em;
            margin-block-start: 1em;
            margin-block-end: 1em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
            unicode-bidi: isolate;
            text-align: center;
            margin-top: 50px;
        }
    </style>

</table>