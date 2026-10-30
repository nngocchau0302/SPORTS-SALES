<?php
$sql_lietke_thuonghieu = "SELECT * FROM tbl_thuonghieu ORDER BY thutu DESC";
$query_lietke_thuonghieu = mysqli_query($mysqli, $sql_lietke_thuonghieu);
?>
<p class="chitietdm">Liệt kê thương hiệu</p>
<table style="width:100%" border="1" style="border-collapse:collapse;">
    <tr>
        <th>Id</th>
        <th>Tên thương hiệu</th>
        <th>Quản lý</th>

    </tr>
    <?php
    $i = 0;
    while ($row = mysqli_fetch_array($query_lietke_thuonghieu)) {
        $i++;
    ?>
        <tr>
            <td><?php echo $i ?></td>
            <td><?php echo $row['tenthuonghieu'] ?></td>
            <td>
                <a class="Xoa" href="/admincf/modules/quanlythuonghieu/xuly.php?idthuonghieu=<?php echo  $row['id_thuonghieu'] ?>" title="Xóa">
                    <i class="fas fa-trash-alt"></i>
                </a>
                <a class="Sua" href="?action=quanlythuonghieu&query=sua&idthuonghieu=<?php echo  $row['id_thuonghieu'] ?>" title="Sửa">
                    <i class="fas fa-edit"></i>
                </a>
            </td>

        </tr>
    <?php
    }
    ?>
    </table>
   <style>
  .chitietdm {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
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
    background-color: #f9f9f9;
  }

  .Xoa, .Sua {
    padding: 6px 10px;
    margin: 0 3px;
    border-radius: 4px;
    color: white;
    text-decoration: none;
    display: inline-block;
  }

  .Xoa {
    background-color: #dc3545;
  }

  .Xoa:hover {
    background-color: #c82333;
  }

  .Sua {
        background-color: #007bff; /* Màu nền cho nút Sửa */
    }



  i.fas {
    margin-right: 4px;
  }
</style>


