<p class="chitietdm">Thêm danh mục sản phẩm</p>
<table border="1" width="50%" class="center-table">
  <form method="POST" action="/admincf/modules/quanlydanhmucsp/xuly.php">
    <tr>
      <td>Tên danh mục sản phẩm</td>
      <td><input type="text" name="tendanhmuc"></td>

    </tr>
    <tr>
      <td>Thứ tự</td>
      <td><input type="text" name="thutu"></td>

    </tr>
    <tr>
      <td colspan="2" class="center-submit"> <input class="danhmuc" type="submit" name="Themdanhmuc" value="Thêm danh mục sản phẩm"> </td>
    </tr>
  </form>
</table>


<style>
  .chitietdm {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
  }

  .center-table {
    margin: 0 auto;
    width: 50%;
    border-collapse: collapse;
    background-color: #fdfdfd;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .center-table td {
    padding: 10px;
    border: 1px solid #ccc;
  }

  .center-table input[type="text"] {
    width: 95%;
    padding: 6px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .center-submit {
    text-align: center;
    padding: 15px;
  }
th {
        background-color:rgb(183, 225, 248) ;
        color: black;
        text-transform: uppercase;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

  .danhmuc {
    background-color: #164863;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 15px;
    border-radius: 5px;
    cursor: pointer;
  }

</style>