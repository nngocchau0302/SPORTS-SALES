<?php
    $sql_lietke_sp = "SELECT * FROM tbl_sanpham,tbl_yeuthich WHERE tbl_sanpham.id_sanpham=tbl_yeuthich.product_id ORDER BY tbl_yeuthich.id ASC" ;
    $query_lietke_sp = mysqli_query($mysqli, $sql_lietke_sp);
?>
<p class="chitietsp">Sản phẩm yêu thích</p>
<table  style="width:100%" border="1" style="border-collapse:collapse;">
  <tr>
    <th>Id</th>
    <th>Tên sản phẩm</th>
    <th>Hình ảnh</th>
    <th>Giá sp</th>
    <th>Số lượng</th>
  
    <th>Mã sp</th>
    <th>Tình trạng</th>


    </tr>
    <?php
    $i = 0;
    while($row = mysqli_fetch_array($query_lietke_sp)){
        $i++;
    ?>
  <tr>
    <td><?php echo $i ?></td>
    <td><?php echo $row['tensanpham'] ?></td>
    <td><img src="admincf/modules/quanlysp/uploads/<?php echo $row['hinhanh'] ?>" width="150px"></td>
    <td><?php echo $row['giasp'] ?></td>
    <td><?php echo $row['soluong'] ?></td>
  
    <td><?php echo $row['masp'] ?></td>
    <td><?php  if($row['tinhtrang']==1){ 
      echo 'Còn hàng';
    }else{
      echo 'Hết hàng';
    }
    ?>
          
  </td>
  
    <td>
        <a class="Xoa" href="pages/main/xulyyeuthich.php?idyeuthich=<?php  echo  $row['id_sanpham'] ?>" title="Xóa"><i class="fas fa-trash-alt"></i></a>

    </td>

  </tr>
   <?php
    }
    ?>
</table>
<style>
   tbody {
      height: 100px;
    }

    input {
      width: 70%;

    }
 td a {
        display: inline-block;
        margin-right: 8px; /* Khoảng cách giữa các icon */
        color: white; /* Màu chữ */
        text-decoration: none; /* Bỏ gạch chân */
        padding: 8px; /* Khoảng cách bên trong nút */
        border-radius: 5px; /* Bo góc */
        transition: background-color 0.3s; /* Hiệu ứng chuyển màu */
    }

    .Xoa {
        background-color: #dc3545; /* Màu nền cho nút Xóa */
    }


    td a:hover {
        opacity: 0.8; /* Hiệu ứng khi hover */
    }

    i {
        font-size: 15px; /* Kích thước icon */
    }
    .chitietsp {
      display: block;
      font-size: 1.3em;
      margin-block-start: 1em;
      margin-block-end: 1em;
      margin-inline-start: 0px;
      margin-inline-end: 0px;
      font-weight: bold;
      unicode-bidi: isolate;
      text-align: center;

    }
    table {
      width: 50%;
      margin: 0 auto;
    }

    th,
    td {
      padding: 8px;
      text-align: left;
      border-top: 1px solid #dee2e6;
    }

    tbody tr:nth-child(odd) {
      background-color: #f2f2f2;
    }
    footer{
      margin-top: 20px;
    }
    
    
  </style>