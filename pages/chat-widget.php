<!-- STYLE: Giao diện chatbot hiện đại có icon người + bot -->
<style>
/* Nút chat */
#chat-icon {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: white;
  border-radius: 50%;
  width: 65px;
  height: 65px;
  cursor: pointer;
  z-index: 9999;
  box-shadow: 0 4px 10px rgba(0,0,0,0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

#chat-icon img {
  width: 110%;
  height: 110%;
  object-fit: cover;
  border-radius: 50%;
}

/* Popup chatbot */
#chat-popup {
  position: fixed;
  bottom: 90px;
  right: 20px;
  width: 390px;
  height: 550px;
  background: white;
  border-radius: 20px;
  display: none;
  flex-direction: column;
  box-shadow: 0 10px 30px rgba(0,0,0,0.25);
  z-index: 9998;
  font-family: 'Segoe UI', sans-serif;
  overflow: hidden;
  padding: 0; /* Xoá padding */
}

#chat-header {
  background: #FF8C00;
  color: white;
  padding: 10px;
  font-weight: bold;
  font-size: 16px;
  width: 100%;
  box-sizing: border-box;
}

.chat-title-container {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 4px;
}

.chat-title {
  font-size: 16px;
  font-weight: bold;
}

.chat-status {
  display: flex;
  align-items: center;
  font-size: 13px;
  font-weight: normal;
  gap: 6px;
  margin-left: 35px;
}

.status-dot {
  width: 8px;
  height: 8px;
  background-color: #4CAF50; /* xanh lá */
  border-radius: 50%;
  display: inline-block;
}
.chat-header-top {
  display: flex;
  align-items: center;
  gap: 10px;
}

.chatbot-avatar {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  object-fit: cover;
}

.chat-title {
  font-size: 16px;
  font-weight: bold;
  color: white;
}


/* Nội dung chat */
#chat-body {
  flex: 1;
  padding: 16px;
  background: #f6f6f6;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

/* Tin nhắn */
.message {
  display: flex;
  align-items: flex-start;
  font-size: 14px;
  gap: 10px;
}

.bot-message {
  flex-direction: row;
}

.user-message {
  flex-direction: row-reverse;
}

.message-icon {
  flex-shrink: 0;
}

.message-icon img {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
}

/* Bong bóng */
.message-content {
  max-width: 75%;
  padding: 12px 16px;
  border-radius: 18px;
  line-height: 1.5;
  white-space: pre-line;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.bot-message .message-content {
  background: white;
  border-top-left-radius: 4px;
}

.user-message .message-content {
  background:  #FF8C00;
  color: white;
  border-top-right-radius: 4px;
}

/* Footer */
#chat-footer {
  padding: 12px;
  background: #fff;
  border-top: 1px solid #ddd;
  display: flex;
  gap: 8px;
}

#chat-input {
  flex: 1;
  padding: 10px 14px;
  border: 1px solid #ccc;
  border-radius: 20px;
  font-size: 14px;
}

#chat-send {
  background:  #FF8C00;
  color: white;
  border: none;
  border-radius: 20px;
  padding: 0 16px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s;
}

#chat-send:hover {
  background:  #FF8C00;
}
</style>




<!-- Nút mở chat -->
<div id="chat-icon" onclick="toggleChat()">
  <img src="../images/chatbot4.jpg" alt="Chat Icon" />
</div>

<!-- Giao diện chatbot -->
<div id="chat-popup">

 <div id="chat-header">
  <div class="chat-title-container">
    <div class="chat-header-top">
      <img src="../images/chatbot4.jpg" class="chatbot-avatar" alt="Bot Avatar">
      <span class="chat-title">Chat with Main Bot</span>
    </div>
    <div class="chat-status">
      <span class="status-dot"></span> <span>Online</span>
    </div>
  </div>
</div>

  <div id="chat-body"></div>
  <div id="chat-footer">
    <input type="text" id="chat-input" placeholder="Nhập tin nhắn..." onkeydown="if(event.key === 'Enter') sendMessage()" />
    <button id="chat-send" onclick="sendMessage()">Gửi</button>
  </div>
</div>



<!-- SCRIPT: Xử lý gửi & nhận -->
<script>
function toggleChat() {
  const chat = document.getElementById("chat-popup");
  chat.style.display = chat.style.display === "none" || chat.style.display === "" ? "flex" : "none";
}

// Kiểm tra nếu chưa từng gửi tin nhắn chào thì mới gửi
    const chatBody = document.getElementById("chat-body");
    if (chatBody.childElementCount === 0) {
      appendMessage("Hello bạn 👋 Mình là trợ lý cửa hàng. Hãy nhắn tin nếu bạn cần tư vấn điều gì nhé!", "bot-message", "../images/chatbot4.jpg");
    }
   else {
    chat.style.display = "none";
  }
async function sendMessage() {
  const input = document.getElementById("chat-input");
  const message = input.value.trim();
  if (!message) return;

  appendMessage(message, "user-message"); // không có icon
  input.value = "";

  try {
    const response = await fetch("http://localhost:5005/webhooks/rest/webhook", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ sender: "user123", message: message })
    });

    const data = await response.json();
    if (data.length === 0) {
      appendMessage("Xin lỗi, tôi chưa hiểu câu hỏi của bạn.", "bot-message", "../images/chatbot4.jpg");
    } else {
      data.forEach(msg => {
        if (msg.text) appendMessage(msg.text, "bot-message", "../images/chatbot4.jpg");
      });
    }
  } catch (error) {
    appendMessage("Không thể kết nối đến chatbot.", "bot-message", "../images/chatbot4.jpg");
  }
}

function appendMessage(text, className, iconUrl = null) {
  const chatBody = document.getElementById("chat-body");
  const msgDiv = document.createElement("div");
  msgDiv.className = "message " + className;

  msgDiv.innerHTML = iconUrl
    ? `<div class="message-icon"><img src="${iconUrl}" /></div><div class="message-content">${text}</div>`
    : `<div class="message-content">${text}</div>`;

  chatBody.appendChild(msgDiv);
  chatBody.scrollTop = chatBody.scrollHeight;
}
</script>
