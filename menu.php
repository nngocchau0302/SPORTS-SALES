<ul class="admincf_list">
    <?php
    if (isset($_GET['dangxuat']) && $_GET['dangxuat'] == 1) {
        unset($_SESSION['dangnhap']);
        header('Location:login.php');
    }
    ?>
    <li><a style="color:#71b1bc;" href="index.php?dangxuat=1">Đăng xuất:
            <?php if (isset($_SESSION['dangnhap'])) {
                echo $_SESSION['dangnhap'];
            } ?>
        </a></li>

    <!-- Quản lý sản phẩm -->
    <li class="has-submenu">
        <a href="index.php?action=quanlysp&query=them">Quản lý sản phẩm <i class="fa fa-caret-down"></i></a>

        <ul class="submenu">

            <li> <a href="index.php?action=quanlydanhmucsanpham&query=them">Quản lý danh mục sản phẩm </a></li>
            <li> <a href="index.php?action=quanlykhuyenmai&query=them">Quản lý khuyến mãi</a></li>
            <li><a href="index.php?action=quanlythuonghieu&query=them">Quản lý thương hiệu </a></li>
        </ul>
    </li>
    <li><a href="index.php?action=quanlybaohanh&query=them">Quản lý bảo hành</a></li>
    <li><a href="index.php?action=quanlykho&query=lietke">Quản lý kho hàng</a></li>

    <!--Quản lý khách hàng -->
    <li class="has-submenu">
        <a href="index.php?action=quanlykhachhang&query=lietke">Quản lý khách hàng <i class="fa fa-caret-down"></i></a>
        <ul class="submenu">
            <li><a href="index.php?action=quanlybinhluan&query=lietke">Quản lý bình luận </a></li>
        </ul>
    </li>

    <!-- Quản lý đặt hàng-->
    <li><a href="index.php?action=quanlydathang&query=lietke">Quản lý đặt hàng</a></li>

    <!-- Thống kê -->
    <li class="has-submenu">
        <a href="index.php?action=quanlythongke&query=thongke">Thống kê <i class="fa fa-caret-down"></i></a>
        <ul class="submenu">
            <li><a href="index.php?action=quanlythongke&query=thongkesanpham">Thống kê sản phẩm </a></li>
            <!-- <li><a href="index.php?action=quanlythongke&query=theothang">Thống kê theo tháng</a></li>
            <li><a href="index.php?action=quanlythongke&query=theonam">Thống kê theo năm</a></li> -->
        </ul>
    </li>
</ul>

<style>
    .admincf_list {
        position: relative;
        list-style: none;
        padding: 0;
    }

    .admincf_list>li {
        position: relative;
        margin-bottom: 10px;
        /* cách các mục chính */
    }

    .admincf_list li a {
        display: block;
        padding: 8px 12px;
        background-color: #f1f1f1;
        text-decoration: none;
        color: #333;
    }

    .admincf_list .submenu {
        display: none;
        position: absolute;
        top: 100;
        left: 0;
        /* điều chỉnh khoảng cách sang phải */
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 0;
        list-style: none;
        min-width: 180px;
        z-index: 999;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .admincf_list .submenu li a {
        padding: 10px;
        background-color: #fff;
        color: #333;
    }

    .admincf_list .submenu li a:hover {
        background-color: #f5f5f5;
    }

    /* Hiển thị submenu khi hover */
    .admincf_list .has-submenu:hover .submenu {
        display: block;
    }
</style>