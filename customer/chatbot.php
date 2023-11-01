<style>
    .message {
        max-width: 100%;
        word-wrap: break-word;
    }

    .message-received {
        background-color: #f1f0f0;
        border-radius: 15px;
        padding: 5px 5px;
        margin-right: auto;
        margin-left: 0;
    }

    .message-user {
        background-color: lightgreen;
        border-radius: 15px;
        padding: 5px 5px;
        margin-left: auto;
        margin-right: 0;
    }

    .chat-message {
        display: flex;
        justify-content: flex-end;
    }

    .chatbot-container {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        max-width: 400px; /* ปรับขนาดความกว้างสูงสุดของ chatbot container */
    }

    .card {
        width: 100%;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-footer input {
        flex: 1;
        margin-right: 10px;
    }
</style>


<button id="show-chatbot" class="btn btn-primary mt-3 open-chatbot-button" style="position: fixed; bottom: 20px; right: 20px;">Open ChatBot</button>

<div class="chatbot-container">
    <div class="card">
        <div class="card-header bg-primary text-white">ChatBot
            <button id="close-chatbot" class="btn btn-light btn-sm close-chatbot-button">Close</button>
        </div>
        <div class="card-body" id="chat-container" style="overflow-y: auto; max-height: 400px;">
            <div class="chat-message">
                <div class="message message-received" style="text-align: left;">มีอะไรให้ฉันช่วยไหม</div>
            </div>            
        </div>
        <div class="card-footer">
            <input type="text" id="user-message" class="form-control" placeholder="Type your message...">
            <button id="send-button" class="btn btn-primary mt-3">Send</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        const showChatbotButton = $('#show-chatbot');
        const closeChatbotButton = $('#close-chatbot');
        const userMessageInput = $('#user-message');
        const sendButton = $('#send-button');
        const chatContainer = $('#chat-container');

        showChatbotButton.on('click', function () {
            $('.chatbot-container').css('display', 'block');
            showChatbotButton.css('display', 'none');
            chatContainer.scrollTop(chatContainer.prop("scrollHeight"));
        });

        closeChatbotButton.on('click', function () {
            $('.chatbot-container').css('display', 'none');
            showChatbotButton.css('display', 'block');
        });

        sendButton.on('click', sendMessage);

        function sendMessage() {
            const userMessage = userMessageInput.val();
            if (userMessage.trim() === '') return;

            addMessage(userMessage, 'user');

            const loadingMessage = `<div class="message message-loading"><p>Loading...</p></div>`;
            addMessage(loadingMessage, 'bot');

            const requestData = {
                query: userMessage
            };

            $.ajax({
                type: 'POST',
                url: 'http://localhost:5003/chat',
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                success: function (data) {
                    console.log(data);
                    const dataArray = data.data;
                    let botResponse = '';

                    if (dataArray.length > 0) {
                        botResponse += '<div class="message message-received"><p>Data:</p><ul>';
                        dataArray.forEach((item, index) => {
                            botResponse += `
                                <li>
                                    <b>หน้าปก:</b><br>
                                    <a href="search_content.php?bookid=${item.book_id}">
                                        <img src="${item.image_url}" alt="book-image" style="max-width: 200px; max-height: 200px;">
                                    </a><br>
                                    <b>ชื่อเรื่อง:</b> ${item.book_name}<br>
                                    <b>เรื่องย่อ:</b> ${item.book_summary}<br>
                                </li>
                            `;
                        });
                        botResponse += '</ul></div>';
                    }

                    addMessage(botResponse, 'bot');
                },
                error: function (error) {
                    console.error('Error:', error);
                    const errorMessage = `<div class="message message-received"><p>An error occurred. Please try again later.</p></div>`;
                    addMessage(errorMessage, 'bot');
                },
                complete: function () {
                    $('.message-loading').remove();
                    chatContainer.scrollTop(chatContainer.prop("scrollHeight"));
                }
            });

            userMessageInput.val('');
        }

        function addMessage(message, sender) {
            const messageElement = `
                <div class="chat-message message message-${sender.toLowerCase()}">
                    <div class="message message-${sender.toLowerCase()}" style="text-align: ${sender === 'user' ? 'right' : 'left'}; width: 100%;">
                        <p>${message}</p>
                    </div>
                </div>`;
            chatContainer.append(messageElement);
        }
    });
</script>