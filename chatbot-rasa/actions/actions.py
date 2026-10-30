import mysql.connector
from typing import Text, List, Dict, Any
from rasa_sdk import Action, Tracker
from rasa_sdk.executor import CollectingDispatcher

# Hàm kết nối MySQL
def get_db_connection():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",  
        database="quanlycoffee",
        charset='utf8'
    )

# Hành động: Sản phẩm rẻ nhất
class ActionCheapestProduct(Action):
    def name(self):
        return "action_cheapest_product"

    def run(self, dispatcher, tracker, domain):
        conn = get_db_connection()
        cursor = conn.cursor()

        # Lấy id, tên và giá sản phẩm rẻ nhất
        cursor.execute("""
            SELECT id_sanpham, tensanpham, giasp 
            FROM tbl_sanpham 
            ORDER BY CAST(giasp AS UNSIGNED) ASC 
            LIMIT 1
        """)
        result = cursor.fetchone()
        conn.close()

        if result:
            id_sp = result[0]
            tensp = result[1]
            giasp = int(result[2])  # chuyển giá từ chuỗi sang số

            # Tạo link sản phẩm
            link_sp = f"http://localhost:3000/index.php?quanly=sanpham&id={id_sp}"

            # Trả về kèm link dưới dạng thẻ <a>
            dispatcher.utter_message(
             text=f"Sản phẩm rẻ nhất là: <b>{tensp}</b> với giá {giasp:,} VNĐ.<br>"
                 f"👉 <a href='{link_sp}' target='_blank'>Xem chi tiết sản phẩm</a>",
             parse_mode="html"
)

        else:
            dispatcher.utter_message(text="Không tìm thấy sản phẩm.")
        return []


# Hành động: Sản phẩm đắt nhất
class ActionMostExpensiveProduct(Action):
    def name(self):
        return "action_most_expensive_product"

    def run(self, dispatcher, tracker, domain):
        conn = get_db_connection()
        cursor = conn.cursor()

        # Lấy id, tên và giá sản phẩm đắt nhất
        cursor.execute("""
            SELECT id_sanpham, tensanpham, giasp 
            FROM tbl_sanpham 
            ORDER BY CAST(giasp AS UNSIGNED) DESC 
            LIMIT 1
        """)
        result = cursor.fetchone()
        conn.close()

        if result:
            id_sp = result[0]
            tensp = result[1]
            giasp = int(result[2])  # chuyển từ chuỗi sang số

            # Tạo link sản phẩm
            link_sp = f"http://localhost:3000/index.php?quanly=sanpham&id={id_sp}"

            # Gửi kèm nút bấm
            dispatcher.utter_message(
    text=f"Sản phẩm đắt nhất là: <b>{tensp}</b> với giá {giasp:,} VNĐ.<br>"
         f"👉 <a href='{link_sp}' target='_blank'>Xem chi tiết sản phẩm</a>",
    parse_mode="html"
)

        else:
            dispatcher.utter_message(text="Không tìm thấy sản phẩm.")
        return []


# Hành động: Các sản phẩm dưới 500k
class ActionProductsUnder500k(Action):
    def name(self):
        return "action_products_under_500k"

    def run(self, dispatcher, tracker, domain):
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("""
            SELECT id_sanpham, tensanpham, giasp 
            FROM tbl_sanpham 
            WHERE CAST(giasp AS UNSIGNED) < 500000
        """)
        results = cursor.fetchall()
        conn.close()

        if results:
            message = "🔽 <b>Các sản phẩm dưới 500k:</b><br>"
            for row in results:
                id_sp = row[0]
                tensp = row[1]
                giasp = int(row[2])
                link_sp = f"http://localhost:3000/index.php?quanly=sanpham&id={id_sp}"
                message += f"- <b>{tensp}</b>: {giasp:,} VNĐ <br> 👉 <a href='{link_sp}' target='_blank'>Xem chi tiết sản phẩm</a><br>"

            dispatcher.utter_message(text=message.strip(), parse_mode="html")
        else:
            dispatcher.utter_message(text="❌ Không có sản phẩm nào dưới 500k.")
        return []


# Bán chạy nhất
class ActionBestSeller(Action):
    def name(self):
        return "action_best_seller"

    def run(self, dispatcher, tracker, domain):
        conn = get_db_connection()
        cursor = conn.cursor()

        cursor.execute("""
            SELECT id_sanpham, tensanpham, soluongban
            FROM tbl_sanpham 
            ORDER BY CAST(soluongban AS UNSIGNED) DESC 
            LIMIT 1
        """)
        result = cursor.fetchone()
        conn.close()

        if result:
            id_sp = result[0]
            tensp = result[1]
            soluongban = int(result[2])

            link_sp = f"http://localhost:3000/index.php?quanly=sanpham&id={id_sp}"

            
            dispatcher.utter_message(
             text=f"Sản phẩm bán chạy của cửa hàng là: <b>{tensp}</b> với {soluongban} lượt bán.<br>"
                 f"👉 <a href='{link_sp}' target='_blank'>Xem chi tiết sản phẩm</a>",
             parse_mode="html"
)

        else:
            dispatcher.utter_message(text="❌ Không tìm thấy sản phẩm bán chạy.")
        return []


# Khuyến mãi
class ActionPromotionProducts(Action):
    def name(self):
        return "action_promotion_products"

    def run(self, dispatcher, tracker, domain):
        conn = get_db_connection()
        cursor = conn.cursor()

        # Lấy sản phẩm có khuyến mãi
        cursor.execute("""
            SELECT s.id_sanpham, s.tensanpham, k.phantramgiam 
            FROM tbl_sanpham s
            INNER JOIN tbl_khuyenmai k ON s.id_sanpham = k.id_sanpham
            WHERE k.phantramgiam > 0
        """)
        results = cursor.fetchall()
        conn.close()

        if results:
            message = "🎉 <b>Các sản phẩm đang khuyến mãi:</b><br><br>"
            for row in results[:5]:  # lấy tối đa 5 sản phẩm
                id_sp = row[0]
                tensp = row[1]
                giam = row[2]

                link_sp = f"http://localhost:3000/index.php?quanly=sanpham&id={id_sp}"
                message += f"🔹 <b>{tensp}</b> - Giảm {giam}% <br>👉 <a href='{link_sp}' target='_blank'>Xem chi tiết</a><br><br>"

            dispatcher.utter_message(text=message, parse_mode="html")
        else:
            dispatcher.utter_message(text="❌ Hiện tại không có sản phẩm nào đang khuyến mãi.")

        return []



# Sản phẩm tốt nhất 
class ActionBestProduct(Action):
    def name(self):
        return "action_best_product"

    def run(self, dispatcher, tracker, domain):
        conn = get_db_connection()
        cursor = conn.cursor()

        query = """
            SELECT sp.id_sanpham, sp.tensanpham, AVG(bl.rating) AS diem_tb
            FROM tbl_binhluan bl
            JOIN tbl_sanpham sp ON bl.id_sanpham = sp.id_sanpham
            GROUP BY bl.id_sanpham
            ORDER BY diem_tb DESC
            LIMIT 1
        """
        cursor.execute(query)
        result = cursor.fetchone()
        conn.close()

        if result:
            id_sp = result[0]
            tensp = result[1]
            diem_tb = round(result[2], 1)

            link_sp = f"http://localhost:3000/index.php?quanly=sanpham&id={id_sp}"

            
            dispatcher.utter_message(
             text=f"Sản phẩm tốt nhất là: <b>{tensp}</b> với mức đánh giá trung bình là <b>{diem_tb}<b>/5<br>"
                 f"👉 <a href='{link_sp}' target='_blank'>Xem chi tiết sản phẩm</a>",
             parse_mode="html"
)
        else:
            dispatcher.utter_message(text="❌ Không tìm thấy sản phẩm tốt nhất.")
        return []

#Sản phẩm mới nhất
class ActionAskNewestProduct(Action):
    def name(self) -> Text:
        return "action_ask_newest_product"

    def run(self, dispatcher: CollectingDispatcher,
            tracker: Tracker,
            domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        connection = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="quanlycoffee"
        )
        cursor = connection.cursor()
        cursor.execute("SELECT id_sanpham, tensanpham FROM tbl_sanpham ORDER BY id_sanpham DESC LIMIT 1")
        result = cursor.fetchone()
        connection.close()

        if result:
            id_sp = result[0]
            tensp = result[1]
            link_sp = f"http://localhost:3000/index.php?quanly=sanpham&id={id_sp}"

            dispatcher.utter_message(
             text=f"Sản phẩm mới nhất của cửa hàng là: <b>{tensp}</b><br>"
                 f"👉 <a href='{link_sp}' target='_blank'>Xem chi tiết sản phẩm</a>",
             parse_mode="html"
)
        else:
            dispatcher.utter_message(text="❌ Hiện tại chưa có sản phẩm nào.")
        return []


    
# # Sản phẩm yêu thích nhất
# class ActionMostFavoritedProduct(Action):
#     def name(self): return "action_most_favorited_product"

#     def run(self, dispatcher, tracker, domain):
#         conn = get_db_connection()
#         cursor = conn.cursor()
#         cursor.execute("""
#             SELECT sp.tensanpham, COUNT(*) AS so_luot_yeu_thich
#             FROM tbl_yeuthich yt
#             JOIN tbl_sanpham sp ON yt.product_id = sp.id_sanpham
#             GROUP BY yt.product_id
#             ORDER BY so_luot_yeu_thich DESC
#             LIMIT 1
#         """)
#         result = cursor.fetchone()
#         conn.close()

#         if result:
#             dispatcher.utter_message(text=f"Sản phẩm được yêu thích nhất là: {result[0]} với {result[1]} lượt yêu thích.")
#         else:
#             dispatcher.utter_message(text="Không tìm thấy sản phẩm được yêu thích.")
#         return []