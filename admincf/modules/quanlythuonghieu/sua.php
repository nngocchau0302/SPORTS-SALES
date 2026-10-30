<?php
    $sql_sua_thuonghieu = "SELECT * FROM tbl_thuonghieu WHERE id_thuonghieu='$_GET[idthuonghieu]' LIMIT 1" ;
    $query_sua_thuonghieu = mysqli_query($mysqli,$sql_sua_thuonghieu);
?>
<p class="chitietsua">Sửa thương hiệu sản phẩm</p>
<table border="1" width="50%" >
 <form method="POST" action="/admincf/modules/quanlythuonghieu/xuly.php?idthuonghieu=<?php echo $_GET['idthuonghieu'] ?>">
  <?php 
    while($dong = mysqli_fetch_array($query_sua_thuonghieu)){

    
  ?>
   <tr>
     <td>Tên thương hiệu sản phẩm</td>
     <td><input type="text" value="<?php echo $dong['tenthuonghieu'] ?>" name="tenthuonghieu"></td>

    </tr>
    <tr>
      <td>Thứ tự</td>
      <td><input type="text" value="<?php echo $dong['thutu'] ?>" name="thutu"></td>

    </tr>
    <tr>

      <td colspan="2"><input class="sua" type="submit" name="suathuonghieu" value="Sửa thương hiệu sản phẩm"></td>
    </tr>
    <?php
      }
    ?>
  </form>
</table>
<style>
   tbody {
      height: 100px;
    }

    input {
      width: 70%;

    }
    .sua {
    display: block;
    width: 30%;
    margin: 0 auto;
    padding: 10px;
    text-align: center;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 8px;
  }
    .chitietsua {
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