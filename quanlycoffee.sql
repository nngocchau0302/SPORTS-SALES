-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 23, 2025 lúc 02:38 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `quanlycoffee`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `adminstatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_admin`
--

INSERT INTO `tbl_admin` (`id_admin`, `username`, `password`, `adminstatus`) VALUES
(2, 'admin', 'd95dc7927846f90ba988eda0d01646d5', 1),
(3, 'admin1', '202cb962ac59075b964b07152d234b70', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_cart`
--

CREATE TABLE `tbl_cart` (
  `id_cart` int(11) NOT NULL,
  `id_khachhang` int(11) NOT NULL,
  `code_cart` varchar(10) NOT NULL,
  `cart_status` int(11) NOT NULL,
  `cart_date` varchar(50) NOT NULL,
  `cart_payment` varchar(100) NOT NULL,
  `cart_shipping` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_cart`
--

INSERT INTO `tbl_cart` (`id_cart`, `id_khachhang`, `code_cart`, `cart_status`, `cart_date`, `cart_payment`, `cart_shipping`) VALUES
(60, 40, '1670', 3, '2025-02-25 22:07:47', 'vnpay', 8),
(61, 40, '5030', 3, '2025-02-25 22:21:57', 'vnpay', 8),
(62, 40, '1276', 3, '2025-02-28 21:59:17', 'tienmat', 8),
(63, 26, '9182', 3, '2025-03-01 22:02:58', 'momo', 10),
(64, 26, '6590', 1, '2025-03-01 22:04:16', 'momo', 10),
(65, 26, '4143', 1, '2025-03-01 22:13:17', 'momo', 10),
(71, 21, '6365', 1, '2025-03-08 22:04:57', 'vnpay', 7),
(76, 21, '6198', 2, '2025-03-08 22:20:13', 'tienmat', 7),
(77, 21, '1325', 3, '2025-03-08 22:23:57', 'chuyenkhoan', 7),
(88, 26, '4949', 3, '2025-03-09 21:47:47', 'chuyenkhoan', 10),
(95, 30, '9387', 3, '2025-03-21 16:16:38', 'tienmat', 1),
(96, 21, '9586', 1, '2025-03-22 13:01:17', 'vnpay', 7),
(97, 21, '2773', 1, '2025-03-22 13:05:27', 'vnpay', 7),
(98, 21, '4094', 1, '2025-03-22 13:16:21', 'momo', 7),
(99, 21, '2464', 3, '2025-03-22 13:19:48', 'momo', 7),
(100, 39, '329', 1, '2025-03-23 20:45:04', 'momo', 11),
(101, 39, '1355', 1, '2025-03-23 20:57:14', 'momo', 11),
(102, 39, '5847', 3, '2025-03-23 20:57:18', 'momo', 11),
(103, 39, '9890', 2, '2025-03-23 21:20:56', 'vnpay', 11),
(105, 26, '6490', 2, '2025-04-02 20:00:43', 'tienmat', 10),
(106, 25, '5257', 1, '2025-04-02 20:13:00', 'vnpay', 12),
(107, 40, '8168', 2, '2025-04-02 20:34:31', 'momo', 8),
(108, 40, '4670', 1, '2025-04-02 20:38:33', 'momo', 8);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_cart_details`
--

CREATE TABLE `tbl_cart_details` (
  `id_cart_details` int(11) NOT NULL,
  `code_cart` varchar(10) NOT NULL,
  `id_sanpham` int(11) NOT NULL,
  `soluongmua` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_cart_details`
--

INSERT INTO `tbl_cart_details` (`id_cart_details`, `code_cart`, `id_sanpham`, `soluongmua`) VALUES
(78, '1670', 27, 1),
(79, '1670', 30, 1),
(80, '1670', 37, 1),
(81, '5030', 27, 1),
(82, '1276', 47, 1),
(83, '9182', 46, 1),
(84, '6590', 46, 1),
(85, '4143', 46, 1),
(86, '4096', 46, 1),
(87, '7353', 46, 1),
(88, '1215', 46, 1),
(89, '1728', 46, 1),
(90, '5009', 46, 1),
(91, '6365', 49, 1),
(93, '2107', 49, 1),
(95, '2880', 49, 1),
(97, '8556', 27, 1),
(98, '1092', 30, 1),
(99, '6198', 36, 1),
(100, '1325', 46, 1),
(101, '6249', 48, 1),
(102, '4922', 51, 1),
(103, '8391', 51, 1),
(104, '4949', 35, 2),
(105, '1966', 56, 2),
(106, '1008', 25, 1),
(107, '4956', 35, 1),
(108, '5507', 37, 1),
(109, '4397', 51, 1),
(110, '4917', 30, 1),
(111, '9387', 58, 1),
(112, '9586', 27, 1),
(113, '9586', 60, 1),
(114, '2773', 50, 1),
(115, '4094', 34, 1),
(116, '2464', 34, 1),
(117, '329', 62, 1),
(118, '1355', 61, 1),
(119, '5847', 61, 1),
(120, '9890', 61, 1),
(121, '1615', 35, 1),
(122, '1615', 50, 1),
(123, '1615', 53, 1),
(124, '6490', 35, 1),
(125, '6490', 27, 1),
(126, '6490', 46, 1),
(127, '5257', 63, 1),
(128, '5257', 36, 2),
(129, '8168', 59, 1),
(130, '8168', 54, 1),
(131, '4670', 59, 1),
(132, '4670', 54, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_dangky`
--

CREATE TABLE `tbl_dangky` (
  `id_dangky` int(11) NOT NULL,
  `tenkhachhang` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `diachi` varchar(500) NOT NULL,
  `matkhau` varchar(100) NOT NULL,
  `dienthoai` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_dangky`
--

INSERT INTO `tbl_dangky` (`id_dangky`, `tenkhachhang`, `email`, `diachi`, `matkhau`, `dienthoai`) VALUES
(21, 'nganngan', 'ngan@gmail.com', 'Cần Thơ', '202cb962ac59075b964b07152d234b70', '0336382637'),
(22, 'Chau Chau', 'chau1@gmail.com', 'hậu giang', '21da7c575b711d26dc4e87cd982f35ec', '0273646363'),
(23, 'Ngọc Châu', 'chau2@gmail.com', 'Hậu Giang', 'aab670aac33e8fe434155bdf2e3a2ec0', '0936293728'),
(24, 'Ngọc Châu', 'chau@gmail.com', 'An Khánh', 'aab670aac33e8fe434155bdf2e3a2ec0', '0163729452'),
(25, 'Như Huỳnh', 'Huynh@gmail.com', 'Sóc Trăng', '202cb962ac59075b964b07152d234b70', '0953725637'),
(26, 'chouchou', 'chou@gmail.com', 'Hậu Giang', '202cb962ac59075b964b07152d234b70', '0236826489'),
(27, 'Mỹ Duyên', 'duyen@gmail.com', 'Hậu Giang', '1f510a85006258bd678eaf9ce19ec327', '0924872983'),
(28, 'Ngọc Xinh', 'xinh@gmail.com', 'Châu Thành A', '827ccb0eea8a706c4c34a16891f84e7b', '0936187382'),
(29, 'Chou Chou', 'chou@gmail.com', 'Kinh Cùng', '202cb962ac59075b964b07152d234b70', '0935729810'),
(30, 'Ngọc Châu', 'chau@gmail.com', 'Hậu Giang', '202cb962ac59075b964b07152d234b70', '0336382637'),
(31, 'Diễm Trúc', 'diemtruc@gmail.com', 'Châu Thành A', '202cb962ac59075b964b07152d234b70', '0939268176'),
(32, 'Mai Anh', 'maianh@gmail.com', 'Hậu Giang', '202cb962ac59075b964b07152d234b70', '0939751829'),
(34, 'Minh Lộc', 'minhloc@gmail.com', 'Hậu Giang', '202cb962ac59075b964b07152d234b70', '0939286197'),
(39, 'Phát Tài', 'pt@gmail.com', 'Hậu Giang', '202cb962ac59075b964b07152d234b70', '0938928167'),
(40, 'Thúy Huỳnh', 'th@gmail.com', 'Phụng Hiệp', '202cb962ac59075b964b07152d234b70', '0937819261'),
(42, 'Minh Tuệ', 'minhtue@gmail.com', 'Kinh Cùng', '202cb962ac59075b964b07152d234b70', '0938279162');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_danhgiasao`
--

CREATE TABLE `tbl_danhgiasao` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_danhgiasao`
--

INSERT INTO `tbl_danhgiasao` (`id`, `product_id`, `rating`) VALUES
(1, 29, 4),
(2, 29, 5),
(3, 29, 5),
(4, 49, 4),
(5, 34, 5),
(6, 50, 5),
(7, 30, 5),
(8, 62, 5),
(9, 62, 3),
(10, 63, 5),
(11, 56, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_danhmuc`
--

CREATE TABLE `tbl_danhmuc` (
  `id_danhmuc` int(11) NOT NULL,
  `tendanhmuc` varchar(100) NOT NULL,
  `thutu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_danhmuc`
--

INSERT INTO `tbl_danhmuc` (`id_danhmuc`, `tendanhmuc`, `thutu`) VALUES
(1, 'Vợt Cầu Lông', 1),
(2, 'Giày Cầu Lông', 2),
(3, 'Balo Cầu Lông', 3),
(7, 'Áo Cầu Lông', 4),
(8, 'Váy Cầu Lông', 5),
(9, 'Quần Cầu Lông', 6),
(10, 'Túi Vợt Cầu Lông', 7);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_kichthuoc`
--

CREATE TABLE `tbl_kichthuoc` (
  `id` int(11) NOT NULL,
  `id_sanpham` int(11) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `soluong` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_kichthuoc`
--

INSERT INTO `tbl_kichthuoc` (`id`, `id_sanpham`, `size`, `soluong`) VALUES
(1, 53, '38', 1),
(2, 53, '39', 2),
(3, 53, '40', 3),
(4, 53, '41', 1),
(6, 37, 'L', 2),
(7, 46, '39', 2),
(8, 46, '40', 2),
(9, 30, '39', 2),
(10, 30, '40', 3),
(11, 29, '39', 2),
(12, 29, '41', 1),
(14, 36, 'XL', 3),
(15, 51, 'L', 5),
(16, 48, 'L', 5),
(17, 38, 'XL', 2),
(19, 54, 'L', 10),
(20, 55, 'XL', 1),
(21, 56, 'L', 15),
(22, 59, 'L', 20),
(23, 60, 'L', 10),
(24, 62, '39', 6),
(25, 62, '40', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_lienhe`
--

CREATE TABLE `tbl_lienhe` (
  `id_lienhe` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `ct_address` varchar(100) NOT NULL,
  `ct_status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_lienhe`
--

INSERT INTO `tbl_lienhe` (`id_lienhe`, `email`, `username`, `phone`, `ct_address`, `ct_status`) VALUES
(1, 'chau@gmail.com', 'Võ Đoàn Ngọc Châu', '0939064919', 'Hậu Giang', 'Tôi muốn mua sỉ sản phẩm'),
(2, 'chau@gmail.com', 'Võ Đoàn Ngọc Châu', '0939064919', 'Hậu Giang', 'Tôi muốn mua sỉ sản phẩm'),
(3, 'chau@gmail.com', 'Võ Đoàn Ngọc Châu', '0939064919', 'Hậu Giang', 'Tôi muốn mua sỉ sản phẩm'),
(4, 'kh@gmail.com', 'khách hàng', '0192810293', 'an hòa ninh kiều', 'muốn hợp tác!!!'),
(6, 'nn@gmail.com', 'ngocngan', '0927192701', 'kinh cùng', 'hợp tác');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_momo`
--

CREATE TABLE `tbl_momo` (
  `id_momo` int(11) NOT NULL,
  `partner_code` varchar(50) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` varchar(50) NOT NULL,
  `order_info` varchar(100) NOT NULL,
  `order_type` varchar(50) NOT NULL,
  `trans_id` int(11) NOT NULL,
  `pay_type` varchar(50) NOT NULL,
  `code_cart` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_momo`
--

INSERT INTO `tbl_momo` (`id_momo`, `partner_code`, `order_id`, `amount`, `order_info`, `order_type`, `trans_id`, `pay_type`, `code_cart`) VALUES
(1, 'MOMOBKUN20180529', 1740839359, '1000000', 'Thanh toán qua MoMo ATM', '', 2147483647, 'napas', '9182'),
(2, 'MOMOBKUN20180529', 1740839359, '1000000', 'Thanh toán qua MoMo ATM', 'momo_wallet', 2147483647, 'napas', '6590'),
(3, 'MOMOBKUN20180529', 1740841907, '1325000', 'Thanh toán qua MoMo ATM', 'momo_wallet', 2147483647, 'napas', '4143'),
(4, 'MOMOBKUN20180529', 1740841907, '1325000', 'Thanh toán qua MoMo ATM', 'momo_wallet', 2147483647, 'napas', '4096'),
(5, 'MOMOBKUN20180529', 1740841907, '1325000', 'Thanh toán qua MoMo ATM', 'momo_wallet', 2147483647, 'napas', '7353'),
(6, 'MOMOBKUN20180529', 1740841907, '1325000', 'Thanh toán qua MoMo ATM', 'momo_wallet', 2147483647, 'napas', '1215'),
(7, 'MOMOBKUN20180529', 1740841907, '1325000', 'Thanh toán qua MoMo ATM', 'momo_wallet', 2147483647, 'napas', '1728'),
(8, 'MOMOBKUN20180529', 1740841907, '1325000', 'Thanh toán qua MoMo ATM', 'momo_wallet', 2147483647, 'napas', '5009'),
(9, 'MOMOBKUN20180529', 1742623641, '750000', 'Thanh toán qua MoMo ATM', 'momo_wallet', 2147483647, '', '4094'),
(10, 'MOMOBKUN20180529', 1742624359, '750000', 'Thanh toán qua mã QR MoMo', 'momo_wallet', 2147483647, '', '2464'),
(11, 'MOMOBKUN20180529', 1742737385, '2400000', 'Thanh toán qua MoMo ATM', 'momo_wallet', 2147483647, 'napas', '329'),
(12, 'MOMOBKUN20180529', 1742738057, '2400000', 'Thanh toán qua MoMo ATM', 'momo_wallet', 2147483647, 'napas', '1355'),
(13, 'MOMOBKUN20180529', 1742738057, '2400000', 'Thanh toán qua MoMo ATM', 'momo_wallet', 2147483647, 'napas', '5847'),
(14, 'MOMOBKUN20180529', 1743600847, '720000', 'Thanh toán qua mã QR MoMo', 'momo_wallet', 2147483647, '', '8168'),
(15, 'MOMOBKUN20180529', 1743601034, '720000', 'Thanh toán qua MoMo ATM', 'momo_wallet', 2147483647, 'napas', '4670');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_sanpham`
--

CREATE TABLE `tbl_sanpham` (
  `id_sanpham` int(11) NOT NULL,
  `tensanpham` varchar(200) NOT NULL,
  `masp` varchar(50) NOT NULL,
  `giasp` varchar(50) NOT NULL,
  `soluong` int(11) NOT NULL,
  `soluongban` int(11) DEFAULT NULL,
  `hinhanh` varchar(50) NOT NULL,
  `tinhtrang` int(11) NOT NULL,
  `id_danhmuc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_sanpham`
--

INSERT INTO `tbl_sanpham` (`id_sanpham`, `tensanpham`, `masp`, `giasp`, `soluong`, `soluongban`, `hinhanh`, `tinhtrang`, `id_danhmuc`) VALUES
(5, 'Vợt cầu lông Yonex Nanoflare Starbucks', 'V001', '1590000 ', 13, NULL, '1736230857_vot-cau-long-yonex1.webp', 1, 1),
(22, 'Set Vợt Cầu Lông Victor Iron Man Metallic', 'V002', '5500000', 10, NULL, '1736257120_set-vot-cau-long-victor-iron.webp', 1, 1),
(23, '       Vợt Cầu Lông Victor ARS 100X Ultra', 'V003', '4500000', 10, NULL, '1736230903_vot-cau-long-victor.webp', 1, 1),
(25, 'Vợt cầu lông Lining Axforce 90 Đỏ Tiger Max', 'V004', '3750000', 9, 1, '1736231011_vot-cau-long-lining2.webp', 1, 1),
(26, 'Vợt cầu lông Lining Aeronaut 7000B', 'V005', '4050000', 10, NULL, '1736231027_vot-cau-long-lining.jpg', 1, 1),
(27, 'Vợt cầu lông Yonex Astrox 01A 2024', 'V006', '959000', 9, 1, '1736231045_vot-cau-long-yonex2.webp', 1, 1),
(29, 'Giày Yonex Comfort Z3 Wide', 'G001', '2950000', 10, 1, '1743598691_giay-cau-long-yonex.jpg', 1, 2),
(30, 'Giày cầu lông Yonex SHB 840 Mid', 'G002', '2050000', 19, 1, '1736231570_giay-cau-long-yonex-shb.webp', 1, 2),
(34, 'Balo cầu lông Kumpoo KB471', 'BL01', '750000', 30, NULL, '1736340587_balo-cau-long-kumpoo.webp', 1, 3),
(35, 'Balo cầu lông Yonex BA267CR', 'BL02', '1400000', 25, 5, '1736340816_balo-cau-long-yonex.webp', 1, 3),
(36, 'Áo khoác Kamito Galaxy 1 KMAP245450', 'A001', '700000', 48, 2, '1736340966_ao-khoac-cau-long.webp', 1, 7),
(37, 'Váy cầu lông Victor Hello Kitty K-KT207 A ', 'VCL01', '800000', 19, 1, '1736342383_vay-cau-long-victo.webp', 1, 8),
(38, 'Quần cầu lông Yonex CPC 01', 'Q001', '110000', 30, 4, '1736342060_quan-cau-long-yonex.webp', 1, 9),
(46, 'Giày cầu lông Lining AYTU025-3', 'G003', '1325000', 9, 1, '1740495607_giay-cau-long-liningaytu025.png', 1, 2),
(47, 'Túi Cầu Lông Yonex 239BT001', 'T001', '750000', 5, NULL, '1740665636_tui-cau-long-yonex1.png', 1, 10),
(48, 'Quần cầu lông Vina Authentic QVN10 nữ', 'Q002', '169000', 5, NULL, '1740665821_quan-cau-long-vina.png', 1, 9),
(49, 'Set vợt cầu lông Victor Thruster K CNY 2025', 'V007', '4190000', 6, NULL, '1740666147_set-vot-cau-long-victor-thruster-.png', 1, 1),
(50, 'Vợt cầu lông Yonex Astrox 100ZZ ', 'V008', '3940000', 9, 1, '1740666363_vot-cau-long-yonex-astrox-100zz.png', 1, 1),
(51, 'Váy cầu lông Yonex 26118EX', 'VCL02', '500000', 3, 2, '1740666466_1.png', 1, 8),
(53, 'Giày cầu lông Lining AYZR008-2', 'G004', '2150000', 15, 1, '1743598645_giay-cau-long-lining-ay.png', 1, 2),
(54, 'Áo Cầu Lông Lining ATSSB64-1', 'A002', '570000', 10, NULL, '1741529009_ao_.png', 1, 7),
(55, 'Áo cầu lông Lining 3626 Nam Trắng', 'A003', '140000', 1, NULL, '1741529726_ao-cau-long-liningtrang-1.png', 1, 7),
(56, 'Quần cầu lông Lining 9682 - Đen xanh ngọc', 'Q003', '130000', 13, 2, '1741530071_quan-cau-long-lining-9682.png', 1, 9),
(57, 'Set Vợt Cầu Lông Lining Bladex 900 Master ', 'V009', '7000000', 15, NULL, '1742534764_set-vot-cau-long-lining-bladex-900.png', 1, 1),
(58, 'Balo cầu lông Victor BR5024LJZ', 'BL03', '1040000', 9, 1, '1742534724_balo-cau-long-victor-1.png', 1, 3),
(59, 'Váy cầu lông Lining 9019 ', 'VCL03', '150000', 20, NULL, '1742534937_vay-cau-long-lining-9019-trang-1.png', 1, 8),
(60, 'Áo cầu lông Kamito Galaxy 3 KMAP245350F nữ ', 'A004', '320000', 10, NULL, '1742550518_ao-cau-long-kamito.png', 1, 7),
(61, 'Túi Cầu Lông Victor BR5618 A', 'T002', '1050000', 19, 1, '1742736863_tui-cau-long-victo.png', 1, 10),
(62, 'Giày Cầu Lông Victor P8500 CNY ', 'G005', '2400000', 15, NULL, '1742736996_giay-cau-long-victor.png', 1, 2),
(63, 'Vợt cầu lông Victor DX9X', 'V010', '2900000', 14, 1, '1742738437_vot-cau-long-victor-dx9x.png', 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_shipping`
--

CREATE TABLE `tbl_shipping` (
  `id_shipping` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(300) NOT NULL,
  `note` varchar(200) NOT NULL,
  `id_dangky` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_shipping`
--

INSERT INTO `tbl_shipping` (`id_shipping`, `name`, `phone`, `address`, `note`, `id_dangky`) VALUES
(1, 'Ngọc Châu', '', 'Hậu Giang', '', 30),
(3, 'Ngọc Xinh', '0936281961', 'ChâuThànhA', 'Giao hàng nhanh', 28),
(5, 'Đào Lê Mai Anh', '0939751829', 'Hậu Giang', 'Giao Nhanh', 32),
(6, 'Võ Lê Minh Lộc ', '0939286197', 'Hậu Giang', 'Ấp 6 Thị trấn Kinh Cùng', 35),
(7, 'Võ Đoàn Ngọc Ngân', '0336382637', 'Cần Thơ', '22 Bế Văn Đàn', 21),
(8, 'Lê Thị Thúy Huỳnh', '0937819261', 'Phụng Hiệp', 'Giao cuối tuần', 40),
(10, 'Ngọc Châu', '0939064919', 'Phụng Hiệp', '201, Ấp 6, TT Kinh Cùng', 26),
(11, 'Phát Tài ', '0939201927', '201, Ấp 6', 'Giao hàng nhanh', 39),
(12, 'Ngô Như Huỳnh', '0953725637', 'TT Phú Lộc, Thạnh Trị, Sóc Trăng', 'Giao hàng nhanh', 25);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_thongke`
--

CREATE TABLE `tbl_thongke` (
  `id` int(11) NOT NULL,
  `ngaydat` varchar(30) NOT NULL,
  `donhang` int(11) NOT NULL,
  `doanhthu` varchar(100) NOT NULL,
  `soluongban` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_thongke`
--

INSERT INTO `tbl_thongke` (`id`, `ngaydat`, `donhang`, `doanhthu`, `soluongban`) VALUES
(6, '2024-08-08', 35, '350000', 40),
(7, '2024-08-09', 30, '350000', 20),
(8, '2024-08-10', 20, '300000', 34),
(9, '2024-09-01', 30, '40000', 25),
(10, '2024-09-09', 55, '600000', 15),
(11, '2024-09-12', 46, '900000', 20),
(12, '2024-10-10', 60, '1000000', 50),
(13, '2024-10-17', 22, '460000', 16),
(14, '2024-10-18', 45, '600000', 30),
(15, '2024-10-20', 25, '300000', 15),
(16, '2024-11-06', 45, '1500000', 40),
(17, '2024-11-10', 11, '200000', 20),
(18, '2024-11-28', 20, '300000', 25),
(19, '2025-03-21 ', 9387, '1040000', 1),
(20, '2025-03-09', 4949, '2800000', 2),
(21, '2025-03-08', 6365, '4190000', 1),
(22, '2025-03-01', 9182, '1325000', 1),
(23, '2025-02-28', 1276, '750000', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_vnpay`
--

CREATE TABLE `tbl_vnpay` (
  `id_vnpay` int(11) NOT NULL,
  `vnp_amount` varchar(50) NOT NULL,
  `vnp_bankCode` varchar(50) NOT NULL,
  `vnp_banktranno` varchar(50) NOT NULL,
  `vnp_cardtype` varchar(50) NOT NULL,
  `vnp_orderinfo` varchar(100) NOT NULL,
  `vnp_paydate` varchar(50) NOT NULL,
  `vnp_tmncode` varchar(50) NOT NULL,
  `vnp_transactionno` varchar(50) NOT NULL,
  `code_cart` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_vnpay`
--

INSERT INTO `tbl_vnpay` (`id_vnpay`, `vnp_amount`, `vnp_bankCode`, `vnp_banktranno`, `vnp_cardtype`, `vnp_orderinfo`, `vnp_paydate`, `vnp_tmncode`, `vnp_transactionno`, `code_cart`) VALUES
(4, '1000000', 'NCB', 'VNP14822010', 'ATM', 'Thanh toán đơn hàng đặt tại web', '20250225220804', 'FWP4FA57', '14822010', '1670'),
(5, '1000000', 'NCB', 'VNP14821992', 'ATM', 'Thanh toán đơn hàng đặt tại web', '20250225214816', 'FWP4FA57', '14821992', '5030'),
(6, '127900000', 'NCB', 'VNP14860796', 'ATM', 'Thanh toán đơn hàng đặt tại web', '20250322130150', 'FWP4FA57', '14860796', '9586'),
(7, '127900000', 'NCB', 'VNP14860796', 'ATM', 'Thanh toán đơn hàng đặt tại web', '20250322130150', 'FWP4FA57', '14860796', '9586'),
(8, '105000000', 'NCB', 'VNP14862829', 'ATM', 'Thanh toán đơn hàng đặt tại web', '20250323212155', 'FWP4FA57', '14862829', '9890'),
(9, '105000000', 'NCB', 'VNP14862829', 'ATM', 'Thanh toán đơn hàng đặt tại web', '20250323212155', 'FWP4FA57', '14862829', '9890'),
(10, '430000000', 'NCB', 'VNP14887247', 'ATM', 'Thanh toán đơn hàng đặt tại web', '20250402201403', 'FWP4FA57', '14887247', '5257'),
(11, '430000000', 'NCB', 'VNP14887247', 'ATM', 'Thanh toán đơn hàng đặt tại web', '20250402201403', 'FWP4FA57', '14887247', '5257');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_yeuthich`
--

CREATE TABLE `tbl_yeuthich` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `ip_address` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_yeuthich`
--

INSERT INTO `tbl_yeuthich` (`id`, `product_id`, `ip_address`) VALUES
(19, 46, '::1'),
(23, 60, '::1'),
(24, 55, '::1'),
(25, 47, '::1'),
(26, 59, '::1');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Chỉ mục cho bảng `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`id_cart`);

--
-- Chỉ mục cho bảng `tbl_cart_details`
--
ALTER TABLE `tbl_cart_details`
  ADD PRIMARY KEY (`id_cart_details`);

--
-- Chỉ mục cho bảng `tbl_dangky`
--
ALTER TABLE `tbl_dangky`
  ADD PRIMARY KEY (`id_dangky`);

--
-- Chỉ mục cho bảng `tbl_danhgiasao`
--
ALTER TABLE `tbl_danhgiasao`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_danhmuc`
--
ALTER TABLE `tbl_danhmuc`
  ADD PRIMARY KEY (`id_danhmuc`);

--
-- Chỉ mục cho bảng `tbl_kichthuoc`
--
ALTER TABLE `tbl_kichthuoc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sanpham` (`id_sanpham`);

--
-- Chỉ mục cho bảng `tbl_lienhe`
--
ALTER TABLE `tbl_lienhe`
  ADD PRIMARY KEY (`id_lienhe`);

--
-- Chỉ mục cho bảng `tbl_momo`
--
ALTER TABLE `tbl_momo`
  ADD PRIMARY KEY (`id_momo`);

--
-- Chỉ mục cho bảng `tbl_sanpham`
--
ALTER TABLE `tbl_sanpham`
  ADD PRIMARY KEY (`id_sanpham`);

--
-- Chỉ mục cho bảng `tbl_shipping`
--
ALTER TABLE `tbl_shipping`
  ADD PRIMARY KEY (`id_shipping`);

--
-- Chỉ mục cho bảng `tbl_thongke`
--
ALTER TABLE `tbl_thongke`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_vnpay`
--
ALTER TABLE `tbl_vnpay`
  ADD PRIMARY KEY (`id_vnpay`);

--
-- Chỉ mục cho bảng `tbl_yeuthich`
--
ALTER TABLE `tbl_yeuthich`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT cho bảng `tbl_cart_details`
--
ALTER TABLE `tbl_cart_details`
  MODIFY `id_cart_details` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT cho bảng `tbl_dangky`
--
ALTER TABLE `tbl_dangky`
  MODIFY `id_dangky` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT cho bảng `tbl_danhgiasao`
--
ALTER TABLE `tbl_danhgiasao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `tbl_danhmuc`
--
ALTER TABLE `tbl_danhmuc`
  MODIFY `id_danhmuc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `tbl_kichthuoc`
--
ALTER TABLE `tbl_kichthuoc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `tbl_lienhe`
--
ALTER TABLE `tbl_lienhe`
  MODIFY `id_lienhe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `tbl_momo`
--
ALTER TABLE `tbl_momo`
  MODIFY `id_momo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `tbl_sanpham`
--
ALTER TABLE `tbl_sanpham`
  MODIFY `id_sanpham` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT cho bảng `tbl_shipping`
--
ALTER TABLE `tbl_shipping`
  MODIFY `id_shipping` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `tbl_thongke`
--
ALTER TABLE `tbl_thongke`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `tbl_vnpay`
--
ALTER TABLE `tbl_vnpay`
  MODIFY `id_vnpay` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `tbl_yeuthich`
--
ALTER TABLE `tbl_yeuthich`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tbl_kichthuoc`
--
ALTER TABLE `tbl_kichthuoc`
  ADD CONSTRAINT `tbl_kichthuoc_ibfk_1` FOREIGN KEY (`id_sanpham`) REFERENCES `tbl_sanpham` (`id_sanpham`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
