<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style2.css">
    <link rel="stylesheet" type="text/css" href="css/danhmuc.css">

    <script type="text/javascript" src="/js/index.js"></script>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
    <script type="text/javascript" src="/js/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="/js/product_cart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" type="image/x-icon" href="/images/logoft1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href=" https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>DC SPORT </title>
    <button onclick="scrollToTop()" id="btnTop" title="Lên đầu trang">
        <i class="fa fa-arrow-up"></i>

    </button>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php
            // session_start();
            include("pages/header.php");
            include("pages/banner.php");
            include("admincf/config/config.php");
            include("pages/main.php");
            include("pages/footer.php");
            ?>
            <?php include 'pages/chat-widget.php'; ?>

        </div>



    




    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    //Thêm giỏ hàng//
    $(document).ready(function() {

        var back = $(".prev");
        var next = $(".next");
        var steps = $(".step");

        next.bind("click", function() {
            $.each(steps, function(i) {
                if (!$(steps[i]).hasClass('current') && !$(steps[i]).hasClass('done')) {
                    $(steps[i]).addClass('current');
                    $(steps[i - 1]).removeClass('current').addClass('done');
                    return false;
                }
            })
        });
        back.bind("click", function() {
            $.each(steps, function(i) {
                if ($(steps[i]).hasClass('done') && $(steps[i + 1]).hasClass('current')) {
                    $(steps[i + 1]).removeClass('current');
                    $(steps[i]).removeClass('done').addClass('current');
                    return false;
                }
            })
        });




    });

    //đánh giá sao
    $(document).ready(function() {
        // Hiệu ứng hover cho các sao
        $('#stars li').on('mouseover', function() {
            var onStar = parseInt($(this).data('value'), 10);
            $(this).parent().children('li.star').each(function(e) {
                $(this).toggleClass('hover', e < onStar);
            });
        }).on('mouseout', function() {
            $(this).parent().children('li.star').removeClass('hover');
        });

        // Xử lý sự kiện click để đánh giá
        $('#stars li').on('click', function() {
            var onStar = parseInt($(this).data('value'), 10);
            var stars = $(this).parent().children('li.star');
            var product_id = $(this).data('product_id');

            stars.removeClass('selected');
            for (let i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
            }

            var ratingValue = onStar;
            var msg = ratingValue > 1 ? "Cảm ơn! Bạn đã đánh giá " + ratingValue + " sao." : "Chúng tôi sẽ cải thiện. Bạn đã đánh giá " + ratingValue + " sao.";
            responseMessage(msg);

            // Gửi yêu cầu AJAX để lưu đánh giá
            $.ajax({
                url: "pages/main/danhgiasao.php",
                method: "POST",
                data: {
                    product_id: product_id,
                    ratingValue: ratingValue
                },
                success: function(data) {
                    alert(data === 'done' ? msg : "Đã có lỗi xảy ra khi lưu đánh giá. Vui lòng thử lại.");
                },
                error: function() {
                    alert("Lỗi kết nối. Vui lòng kiểm tra lại.");
                }
            });
        });
    });

    //yêu thích 
    function yeuthich($id) {
        var product_id = $id;
        $.ajax({
            url: "pages/main/yeuthich.php",
            method: "POST",
            data: {
                product_id: product_id
            },
            success: function(data) {
                if (data == 'Done') {
                    alert('Sản phẩm đã thêm vào yêu thích');

                } else {
                    alert('Sản phẩm đã có trong yêu thích');
                }
            }
        });
    }


    // Hàm hiển thị thông báo
    function responseMessage(msg) {
        $('.success-box').fadeIn(200);
        $('.success-box div.text-message').html("<span>" + msg + "</span>");
    }


    // Khi cuộn, hiển thị nút nếu scroll xuống 200px
    window.onscroll = function() {
        const btn = document.getElementById("btnTop");
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
            btn.style.display = "block";
        } else {
            btn.style.display = "none";
        }
    };

    // Cuộn mượt về đầu trang
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
</script>
<style>
    #btnTop {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 999;
        background-color:rgb(152, 158, 165);
        color: white;
        border: none;
        outline: none;
        padding: 8px 14px;
        border-radius: 100%;
        font-size: 18px;
        cursor: pointer;
        display: none;
        /* Ẩn mặc định */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: background-color 0.3s;
    }

    #btnTop:hover {
        background-color: #0056b3;
    }
</style>


</html>