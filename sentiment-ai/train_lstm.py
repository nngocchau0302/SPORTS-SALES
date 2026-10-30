# # train_lstm.py (cập nhật để cải thiện chất lượng)

# import pandas as pd
# import numpy as np
# import re
# from pyvi import ViTokenizer
# from sklearn.model_selection import train_test_split
# from sklearn.preprocessing import LabelEncoder
# from sklearn.metrics import classification_report, confusion_matrix
# from tensorflow.keras.preprocessing.text import Tokenizer
# from tensorflow.keras.preprocessing.sequence import pad_sequences
# from tensorflow.keras.models import Sequential
# from tensorflow.keras.layers import Embedding, LSTM, Dense, Dropout
# from tensorflow.keras.callbacks import EarlyStopping
# import joblib
# import os

# # === CHUẨN HÓA VĂN HÓA ===
# def clean_text(text):
#     text = text.lower()
#     replacements = {
#         "sp": "sản phẩm",
#         "nv": "nhân viên",
#         "ko": "không",
#         "k ": "không ",
#         "k.": "không.",
#         "hok": "không",
#         "hong": "không",
#         "khum": "không",
#         "hông": "không",
#         "kh": "khách hàng",
#         "dc": "được",
#         "đc": "được",
#         "thik": "thích",
#         "thíc": "thích",
#         "đẹp zứ": "đẹp dữ",
#         "ẹp": "đẹp",
#         "bt": "bình thường",
#         "hịn": "xịn",
#         "tạm": "tạm được"
#     }
#     for abbr, full in replacements.items():
#         text = text.replace(abbr, full)

#     text = re.sub(r"[^a-zA-Z0-9\sà-ỹđ]", " ", text)
#     text = re.sub(r"\s+", " ", text).strip()
#     return text

# # === TẢI DỮ LIỆU ===
# df = pd.read_csv("data/sentiment_dataset.csv")
# df['comment'] = df['comment'].astype(str)
# df['processed'] = df['comment'].apply(lambda x: ViTokenizer.tokenize(clean_text(x)))

# # === MÃ HÓA NHÃN ===
# label_encoder = LabelEncoder()
# y = label_encoder.fit_transform(df['label'])
# joblib.dump(label_encoder, 'model/label_encoder.pkl')

# # === TOKENIZE ===
# tokenizer = Tokenizer(num_words=10000)
# tokenizer.fit_on_texts(df['processed'])
# X_seq = tokenizer.texts_to_sequences(df['processed'])
# X_pad = pad_sequences(X_seq, maxlen=120)
# joblib.dump(tokenizer, 'model/tokenizer.pkl')

# print(df['label'].value_counts())

# # === TẠO TẬP TRAIN/TEST ===
# X_train, X_test, y_train, y_test = train_test_split(X_pad, y, test_size=0.2, random_state=42, stratify=y)

# # === MÔ HÌNH LSTM ===
# model = Sequential()
# model.add(Embedding(input_dim=10000, output_dim=128, input_length=120))
# model.add(LSTM(128, dropout=0.3, recurrent_dropout=0.2))
# model.add(Dense(64, activation='relu'))
# model.add(Dropout(0.4))
# model.add(Dense(3, activation='softmax'))

# model.compile(optimizer='adam', loss='sparse_categorical_crossentropy', metrics=['accuracy'])

# early_stop = EarlyStopping(monitor='val_loss', patience=3, restore_best_weights=True)

# model.fit(X_train, y_train, epochs=15, batch_size=32, validation_data=(X_test, y_test), callbacks=[early_stop])

# # === ĐÁNH GIÁ ===
# y_pred = model.predict(X_test)
# y_pred_classes = np.argmax(y_pred, axis=1)
# print("\n📋 Classification Report:\n", classification_report(y_test, y_pred_classes, target_names=label_encoder.classes_))
# print("\n🧩 Confusion Matrix:\n", confusion_matrix(y_test, y_pred_classes))

# # === LƯU MÔ HÌNH ===
# model.save("model/lstm_model.h5")
# print("\n✅ Mô hình đã được huấn luyện và lưu tại model/lstm_model.h5")




import pandas as pd
import numpy as np
import re
from pyvi import ViTokenizer
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder
from sklearn.metrics import classification_report, confusion_matrix
from tensorflow.keras.preprocessing.text import Tokenizer
from tensorflow.keras.preprocessing.sequence import pad_sequences
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Embedding, LSTM, Dense, Dropout
from tensorflow.keras.callbacks import EarlyStopping
import joblib
import os

# === CHUẨN HÓA VĂN BẢN ===
def clean_text(text):
    text = text.lower()
    replacements = {
        "sp": "sản phẩm", "nv": "nhân viên", "ko": "không", "k ": "không ", "k.": "không.",
        "hok": "không", "hong": "không", "khum": "không", "hông": "không", "kh": "khách hàng",
        "dc": "được", "đc": "được", "thik": "thích", "thíc": "thích", "đẹp zứ": "đẹp dữ",
        "ẹp": "đẹp", "bt": "bình thường", "hịn": "xịn", "tạm": "tạm được"
    }
    for abbr, full in replacements.items():
        text = text.replace(abbr, full)

    text = re.sub(r"[^a-zA-Z0-9\sà-ỹđ]", " ", text)
    text = re.sub(r"\s+", " ", text).strip()
    return text

# === TẢI DỮ LIỆU ===
df = pd.read_csv("data/sentiment_dataset.csv")
df['comment'] = df['comment'].astype(str)

# ⚠️ Chỉ lấy 2 nhãn positive và negative
df = df[df['label'].isin(['positive', 'negative'])].reset_index(drop=True)

# Xử lý văn bản
df['processed'] = df['comment'].apply(lambda x: ViTokenizer.tokenize(clean_text(x)))

# === MÃ HÓA NHÃN ===
label_encoder = LabelEncoder()
y = label_encoder.fit_transform(df['label'])  # positive: 1, negative: 0
joblib.dump(label_encoder, 'model/label_encoder.pkl')

# === TOKENIZE ===
tokenizer = Tokenizer(num_words=10000)
tokenizer.fit_on_texts(df['processed'])
X_seq = tokenizer.texts_to_sequences(df['processed'])
X_pad = pad_sequences(X_seq, maxlen=120)
joblib.dump(tokenizer, 'model/tokenizer.pkl')

print(df['label'].value_counts())

# === TẠO TẬP TRAIN/TEST ===
X_train, X_test, y_train, y_test = train_test_split(X_pad, y, test_size=0.2, random_state=42, stratify=y)

# === MÔ HÌNH LSTM ===
model = Sequential()
model.add(Embedding(input_dim=10000, output_dim=128, input_length=120))
model.add(LSTM(128, dropout=0.3, recurrent_dropout=0.2))
model.add(Dense(64, activation='relu'))
model.add(Dropout(0.4))
model.add(Dense(1, activation='sigmoid'))  # Nhị phân

model.compile(optimizer='adam', loss='binary_crossentropy', metrics=['accuracy'])

early_stop = EarlyStopping(monitor='val_loss', patience=3, restore_best_weights=True)

model.fit(X_train, y_train, epochs=15, batch_size=32, validation_data=(X_test, y_test), callbacks=[early_stop])

# === ĐÁNH GIÁ ===
y_pred_prob = model.predict(X_test)
y_pred_classes = (y_pred_prob > 0.5).astype("int32")  # Ngưỡng 0.5

print("\n📋 Classification Report:\n", classification_report(y_test, y_pred_classes, target_names=label_encoder.classes_))
print("\n🧩 Confusion Matrix:\n", confusion_matrix(y_test, y_pred_classes))
print("✅ Label classes:", label_encoder.classes_)  # Xác minh thứ tự label

# === LƯU MÔ HÌNH ===
model.save("model/lstm_model.h5")
print("\n✅ Mô hình đã được huấn luyện và lưu tại model/lstm_model.h5")
