# from flask import Flask, request, jsonify
# from flask_cors import CORS
# import joblib
# from tensorflow.keras.models import load_model
# from tensorflow.keras.preprocessing.sequence import pad_sequences
# from pyvi import ViTokenizer
# import numpy as np
# import re

# app = Flask(__name__)
# CORS(app)

# # ✅ Hàm làm sạch văn bản
# def clean_text(text):
#     text = text.lower()
#     replacements = {
#         "sp": "sản phẩm", "nv": "nhân viên", "ko": "không", "k ": "không ",
#         "k.": "không.", "hok": "không", "hong": "không", "khum": "không", "hông": "không",
#         "kh": "khách hàng", "dc": "được", "đc": "được", "thik": "thích",
#         "thíc": "thích", "đẹp zữ": "đẹp dữ", "ẹp": "đẹp", "bt": "bình thường",
#         "hịn": "xịn", "tạm": "tạm được"
#     }
#     for abbr, full in replacements.items():
#         text = text.replace(abbr, full)
#     text = re.sub(r"[^a-zA-Z0-9\sáàảãạăắằẵặâấầẩẫậéèẻẽẹêếềểễệíìỉĩịóòỏõọôốồổỗộơớờởỡợúùủũụưứừửữựýỳỷỹỵđ]", " ", text)
#     text = re.sub(r"\s+", " ", text).strip()
#     return text

# # ✅ Load mô hình
# model = load_model("model/lstm_model.h5")
# tokenizer = joblib.load("model/tokenizer.pkl")
# label_encoder = joblib.load("model/label_encoder.pkl")


# @app.route("/api/sentiment", methods=["POST"])
# def predict_sentiment():
#     data = request.get_json()
#     comment = data.get("comment", "")
#     cleaned = clean_text(comment)
#     tokenized = ViTokenizer.tokenize(cleaned)
#     sequence = tokenizer.texts_to_sequences([tokenized])
#     padded = pad_sequences(sequence, maxlen=120)
#     prediction = model.predict(padded)
#     label_index = np.argmax(prediction)
#     label = label_encoder.inverse_transform([label_index])[0]
#     return jsonify({"sentiment": label})

# if __name__ == "__main__":
#     app.run(debug=True, host="0.0.0.0", port=5000)



from flask import Flask, request, jsonify
from flask_cors import CORS
import joblib
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing.sequence import pad_sequences
from pyvi import ViTokenizer
import numpy as np
import re

app = Flask(__name__)
CORS(app)

# ✅ Hàm làm sạch văn bản
def clean_text(text):
    text = text.lower()
    replacements = {
        "sp": "sản phẩm", "nv": "nhân viên", "ko": "không", "k ": "không ",
        "k.": "không.", "hok": "không", "hong": "không", "khum": "không", "hông": "không",
        "kh": "khách hàng", "dc": "được", "đc": "được", "thik": "thích",
        "thíc": "thích", "đẹp zữ": "đẹp dữ", "ẹp": "đẹp", "bt": "bình thường",
        "hịn": "xịn", "tạm": "tạm được"
    }
    for abbr, full in replacements.items():
        text = text.replace(abbr, full)
    text = re.sub(r"[^a-zA-Z0-9\sáàảãạăắằẵặâấầẩẫậéèẻẽẹêếềểễệíìỉĩịóòỏõọôốồổỗộơớờởỡợúùủũụưứừửữựýỳỷỹỵđ]", " ", text)
    text = re.sub(r"\s+", " ", text).strip()
    return text

# ✅ Load mô hình
model = load_model("model/lstm_model.h5")
tokenizer = joblib.load("model/tokenizer.pkl")
label_encoder = joblib.load("model/label_encoder.pkl")

# ✅ Đảm bảo đúng chiều nhãn
label_classes = list(label_encoder.classes_)  # ['negative', 'positive']

@app.route("/api/sentiment", methods=["POST"])
def predict_sentiment():
    data = request.get_json()
    comment = data.get("comment", "")
    cleaned = clean_text(comment)
    tokenized = ViTokenizer.tokenize(cleaned)
    sequence = tokenizer.texts_to_sequences([tokenized])
    padded = pad_sequences(sequence, maxlen=120)

    # ✅ Dự đoán với sigmoid (trả về giá trị từ 0 đến 1)
    prob = model.predict(padded)[0][0]

    # ✅ Ngưỡng phân loại: >= 0.5 là positive
    label_index = int(prob >= 0.5)
    label = label_classes[label_index]  # Lấy đúng thứ tự gán

    return jsonify({
        "sentiment": label,
        "probability": round(float(prob), 4)
    })

if __name__ == "__main__":
    app.run(debug=True, host="0.0.0.0", port=5000)
