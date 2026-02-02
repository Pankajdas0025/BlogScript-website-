<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8' />
  <meta name='viewport' content='width=device-width, initial-scale=1.0' />
  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>

  <style>
    @import url('style/root.css');

    .chatbot-widget { position: fixed; bottom: 20px; right: 20px; z-index: 1000; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
    .chatbot-toggle { width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--primary) , var(--secondary)); border: 1px solid white; color: white; font-size: 1.5rem; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.15); transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; animation: pulseShadow 2s infinite; }

    @keyframes pulseShadow { 0% {box-shadow: 0 0 0 0 rgba(244, 63, 94, 1);} 70%{box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);} 100% {box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);} }

    .chatbot-toggle:hover { transform: rotate(360deg); box-shadow: 0 6px 16px rgba(0,0,0,0.2); }
    .chatbot-toggle:active { transform: scale(0.95); }

    .chatbot-popup { position: fixed; bottom: 10px; right: 10px; width: 350px; height: 500px; background: white; border-radius: 12px; box-shadow: 0 5px 40px rgba(0,0,0,0.16); display: flex; flex-direction: column; animation: slideUp 0.3s ease; z-index: 1001; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px);} to { opacity: 1; transform: translateY(0);} }

    .chatbot-header { background: linear-gradient(135deg, var(--primary) , var(--secondary)); color: white; padding: 1.5rem; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center; }
    .chatbot-header h3 { margin: 0; font-size: 1.1rem; font-weight: 600; }
    .chatbot-close { background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; }
    .chatbot-close:hover { opacity: 0.8; }

    .chatbot-messages { flex: 1; overflow-y: auto; padding: 1.5rem; background: #f8f9fa; }
    .chatbot-message { margin-bottom: 1rem; display: flex; animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px);} to { opacity: 1; transform: translateY(0);} }

    .message-bot { justify-content: flex-start; }
    .message-user { justify-content: flex-end; }

    .message-content { max-width: 80%; padding: 0.75rem 1rem; border-radius: 12px; word-wrap: break-word; line-height: 1.4; font-size: 0.95rem; }
    .message-bot .message-content { background: white; color: #333; border: 1px solid #e0e0e0; }
    .message-user .message-content { background: var(--secondary); color: white; }

    .chatbot-input-area { padding: 1rem; border-top: 1px solid #e0e0e0; background: white; border-radius: 0 0 12px 12px; }
    .chatbot-input-group { display: flex; gap: 0.5rem; }
    .chatbot-input { flex: 1; border: 1px solid #e0e0e0; border-radius: 20px; padding: 0.6rem 1rem; font-size: 0.95rem; outline: none; transition: all 0.2s ease; }
    .chatbot-input:focus { border-color: var(--secondary); box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1); }

    .chatbot-send { background: var(--secondary); border: none; color: white; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; font-size: 1rem; }
    .chatbot-send:active { transform: scale(0.95); }

    .chatbot-suggestions { display: flex; flex-direction: column; gap: 0.5rem; margin-top: 1rem; }
    .suggestion-btn { background: white; border: 1px solid #e0e0e0; padding: 0.6rem 1rem; border-radius: 8px; cursor: pointer; text-align: left; font-size: 0.9rem; transition: all 0.2s ease; color: #333; }
    .suggestion-btn:hover { background: #f8f9fa; border-color: var(--secondary); color: var(--secondary); }

    .typing-indicator { display: flex; gap: 4px; padding: 0.75rem 1rem; }
    .typing-dot { width: 8px; height: 8px; border-radius: 50%; background: #ccc; animation: typing 1.4s infinite; }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typing { 0%,60%,100% { opacity: 0.5; transform: translateY(0);} 30% { opacity: 1; transform: translateY(-10px);} }

    @media (max-width: 480px) {
      .chatbot-widget { bottom: 30px; }
      .chatbot-popup { width: 90vw; margin: 0 auto; height: 500px; bottom: 20px; right: 0; left: 0; }
      .message-content { max-width: 90%; }
      .chatbot-input { width: 60vw; }
      .chatbot-send { width: 30px; height: 30px; margin-top: 4px; }
    }

    .chatbot-messages::-webkit-scrollbar { width: 6px; }
    .chatbot-messages::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
  </style>
</head>

<body>
  <div class='chatbot-widget'>
    <button class='chatbot-toggle' id='chatbotToggle' title='Open Chat'>😎</button>

    <div class='chatbot-popup' id='chatbotPopup' style='display: none;'>
      <div class='chatbot-header'>
        <div class='spinner-grow text-success'></div>
        <h3 style='margin-right:80px;'>AI Assistant</h3>
        <button class='chatbot-close' id='chatbotClose' title='Close'>✕</button>
      </div>

      <div class='chatbot-messages' id='chatbotMessages'>
        <div class='chatbot-message message-bot'>
          <div class='message-content'>
            Hello! 👋 Welcome to BlogScript. How can I help you today?
          </div>
        </div>
      </div>

      <div class='chatbot-input-area'>
        <div class='chatbot-input-group'>
          <input type='text' class='chatbot-input' id='chatbotInput' placeholder='Type your message...' autocomplete='off'>
          <button class='chatbot-send' id='chatbotSend' title='Send'>➤</button>
        </div>

        <div class='chatbot-suggestions' id='chatbotSuggestions'>
          <button class='suggestion-btn' data-message='How to post blogs?'>How to post blogs?</button>
          <button class='suggestion-btn' data-message='How to create an account?'>How to create an account?</button>
          <button class='suggestion-btn' data-message='Contact Support'>Contact Support</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    class ChatbotWidget {
      constructor() {
        this.toggle = document.getElementById('chatbotToggle');
        this.popup = document.getElementById('chatbotPopup');
        this.closeBtn = document.getElementById('chatbotClose');
        this.input = document.getElementById('chatbotInput');
        this.sendBtn = document.getElementById('chatbotSend');
        this.messagesContainer = document.getElementById('chatbotMessages');
        this.suggestionsContainer = document.getElementById('chatbotSuggestions');

        this.isOpen = false;
        this.isLoading = false;

        // ✅ OFFLINE FAQ (no API)
        this.faq = [
          {
            match: /(hello|hi|hey|greetings|good morning|good afternoon|good evening)/i,
            reply: '<b>Hello!</b> Welcome to <b>BlogScript</b>. How can I help you today?',
            suggestions: ['How to create an account?', 'How to post blogs?', 'Contact Support']
          },
          {
            match: /(how.*post|post.*blog|create.*blog|publish|upload.*blog)/i,
            reply: '<b>To post a blog on BlogScript:</b><br><br>1. Login<br>2. Go to <b>Create</b><br>3. Add Title + Content<br>4. Add Image (optional)<br>5. Click <b>Publish</b>',
            suggestions: ['How to add images?', 'SEO tips for blog', 'How to edit blog?']
          },
          {
            match: /(how.*create.*account|signup|register|create account)/i,
            reply: '<b>To create an account:</b><br><br>1. Click <b>Signup</b><br>2. Enter Name, Email, Password<br>3. Verify email if asked<br>4. Login and start posting',
            suggestions: ['Login issue', 'Forgot password', 'Contact Support']
          },
          {
            match: /(reset.*password|forgot.*password|change.*password|password.*help)/i,
            reply: '<b>Password Reset:</b><br><br>1. Click <b>Forgot password?</b><br>2. Enter your email<br>3. Check email for reset link<br>4. Set a new password and login',
            suggestions: ['I did not receive email', 'Login issue', 'Contact Support']
          },
          {
            match: /(contact|support|help|email|problem|issue|bug|report)/i,
            reply: 'You can contact support using the <b>Contact Us</b> link in the footer. Tell me your issue and I will guide you.',
            suggestions: ['Login issue', 'Blog not saving', 'Account verification']
          }
        ];
      }

      init() {
        this.toggle.addEventListener('click', () => this.toggleChat());
        this.closeBtn.addEventListener('click', () => this.closeChat());
        this.sendBtn.addEventListener('click', () => this.sendMessage());

        this.input.addEventListener('keypress', (e) => {
          if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            this.sendMessage();
          }
        });

        document.querySelectorAll('.suggestion-btn').forEach(btn => {
          btn.addEventListener('click', (e) => {
            this.input.value = e.target.dataset.message;
            this.input.focus();
          });
        });
      }

      toggleChat() {
        this.isOpen ? this.closeChat() : this.openChat();
      }

      openChat() {
        this.popup.style.display = 'flex';
        this.isOpen = true;
        this.input.focus();
      }

      closeChat() {
        this.popup.style.display = 'none';
        this.isOpen = false;
      }

      sanitizeBotHtml(html) {
        return String(html).replace(/<(?!br\s*\/?|b\s*\/?|\/b\s*>|\/?br\s*>)[^>]*>/gi, '');
      }

      sendMessage() {
        const message = this.input.value.trim();
        if (!message || this.isLoading) return;

        this.addMessage(message, 'user');
        this.input.value = '';
        this.showTypingIndicator();

        // ✅ offline response
        setTimeout(() => {
          this.removeTypingIndicator();
          this.isLoading = false;

          const { reply, suggestions } = this.getOfflineReply(message);
          this.addMessage(reply, 'bot');
          this.updateSuggestions(suggestions);
        }, 500);
      }

      getOfflineReply(message) {
        const text = String(message).trim();

        for (const item of this.faq) {
          if (item.match.test(text)) {
            return {
              reply: item.reply,
              suggestions: item.suggestions
            };
          }
        }

        return {
          reply: 'Sorry!. I can help with:<br><br>1. How to post blogs<br>2. How to create an account<br>3. Password reset<br>4. Contact support',
          suggestions: ['How to post blogs?', 'How to create an account?', 'Contact Support']
        };
      }

      addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message message-${sender}`;

        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';

        if (sender === 'bot') {
          contentDiv.innerHTML = this.sanitizeBotHtml(text);
        } else {
          contentDiv.textContent = text;
        }

        messageDiv.appendChild(contentDiv);
        this.messagesContainer.appendChild(messageDiv);
        this.scrollToBottom();
      }

      showTypingIndicator() {
        this.isLoading = true;

        const messageDiv = document.createElement('div');
        messageDiv.className = 'chatbot-message message-bot';
        messageDiv.id = 'typing-indicator';

        const dotsDiv = document.createElement('div');
        dotsDiv.className = 'typing-indicator';
        dotsDiv.innerHTML = '<span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>';

        messageDiv.appendChild(dotsDiv);
        this.messagesContainer.appendChild(messageDiv);
        this.scrollToBottom();
      }

      removeTypingIndicator() {
        const indicator = document.getElementById('typing-indicator');
        if (indicator) indicator.remove();
      }

      updateSuggestions(suggestions) {
        this.suggestionsContainer.innerHTML = '';

        suggestions.forEach(suggestion => {
          const btn = document.createElement('button');
          btn.className = 'suggestion-btn';
          btn.dataset.message = suggestion;
          btn.textContent = suggestion;

          btn.addEventListener('click', () => {
            this.input.value = suggestion;
            this.input.focus();
          });

          this.suggestionsContainer.appendChild(btn);
        });
      }

      scrollToBottom() {
        this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      const bot = new ChatbotWidget();
      bot.init();
    });
  </script>
</body>
</html>
