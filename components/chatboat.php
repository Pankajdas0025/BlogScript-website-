
  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>
  <style>
    @import url('../assets/css/root.css');

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
        // ✅ PRODUCTION LEVEL FAQ DATABASE
// Add this inside constructor after this.isLoading = false;

this.faq = [
  {
    match: /(hello|hi|hey|good morning|good evening|good afternoon)/i,
    reply: '<b>Hello 👋</b><br>Welcome to <b>BlogScript AI Assistant</b>. How can I help you today?',
    suggestions: ['How to post blogs?', 'Create account', 'Contact support']
  },
  {
    match: /(how.*post|create.*blog|publish.*blog|upload.*blog|write.*blog)/i,
    reply: '<b>To publish a blog:</b><br><br>1. Login to your account<br>2. Click <b>Create Blog</b><br>3. Add title and content<br>4. Upload thumbnail image<br>5. Click <b>Publish</b>',
    suggestions: ['How to edit blog?', 'SEO tips', 'Add images']
  },
  {
    match: /(signup|register|create.*account|new account)/i,
    reply: '<b>Create an account easily:</b><br><br>1. Click Signup<br>2. Enter your details<br>3. Verify email<br>4. Login and start blogging',
    suggestions: ['Login issue', 'Forgot password', 'Verify email']
  },
  {
    match: /(forgot.*password|reset.*password|change.*password)/i,
    reply: '<b>Password Reset Steps:</b><br><br>1. Click Forgot Password<br>2. Enter your registered email<br>3. Open reset link from email<br>4. Create a new password',
    suggestions: ['Did not receive email', 'Login issue', 'Contact support']
  },
  {
    match: /(contact|support|help|customer care|issue|problem)/i,
    reply: 'You can contact our support team through the <b>Contact Us</b> page. We usually respond within 24 hours.',
    suggestions: ['Report bug', 'Payment issue', 'Account issue']
  },

  // ===========================
  // BLOG QUESTIONS
  // ===========================

  {
    match: /(seo|rank.*blog|google ranking|optimize.*blog)/i,
    reply: '<b>SEO Tips:</b><br><br>• Use proper keywords<br>• Write attractive titles<br>• Add meta description<br>• Use headings properly<br>• Add images with alt tags',
    suggestions: ['How to add tags?', 'Best blog title tips', 'How to get traffic?']
  },
  {
    match: /(edit.*blog|update.*blog|modify.*blog)/i,
    reply: 'Go to your dashboard → My Blogs → Click <b>Edit</b> button → Update content → Save changes.',
    suggestions: ['Delete blog', 'Add images', 'SEO tips']
  },
  {
    match: /(delete.*blog|remove.*blog)/i,
    reply: 'Open Dashboard → My Blogs → Click Delete icon next to your blog. Deleted blogs cannot be recovered.',
    suggestions: ['Edit blog', 'Restore blog', 'Contact support']
  },
  {
    match: /(draft|save.*draft)/i,
    reply: 'Yes ✅ You can save blogs as draft and publish them later anytime from dashboard.',
    suggestions: ['Publish draft', 'Edit draft', 'Schedule blog']
  },
  {
    match: /(schedule.*blog|future publish)/i,
    reply: 'You can schedule your blog posts from the publish settings section before publishing.',
    suggestions: ['Draft blog', 'SEO tips', 'Best posting time']
  },

  // ===========================
  // ACCOUNT QUESTIONS
  // ===========================

  {
    match: /(login.*issue|cannot login|unable.*login)/i,
    reply: 'Please check your email and password carefully. If problem continues, reset password or contact support.',
    suggestions: ['Reset password', 'Verify account', 'Contact support']
  },
  {
    match: /(verify.*email|email verification)/i,
    reply: 'Verification email is sent after signup. Check inbox and spam folder.',
    suggestions: ['Resend email', 'Login issue', 'Contact support']
  },
  {
    match: /(logout|sign out)/i,
    reply: 'Click on your profile picture and select <b>Logout</b>.',
    suggestions: ['Login issue', 'Create account', 'Change password']
  },
  {
    match: /(change.*email|update.*email)/i,
    reply: 'Go to Account Settings → Personal Information → Update your email address.',
    suggestions: ['Change password', 'Update profile', 'Verify email']
  },
  {
    match: /(change.*username|update.*profile)/i,
    reply: 'You can update your username and profile from Dashboard → Settings → Profile.',
    suggestions: ['Upload profile photo', 'Change email', 'Privacy settings']
  },

  // ===========================
  // IMAGE / MEDIA
  // ===========================

  {
    match: /(upload.*image|add.*image|thumbnail)/i,
    reply: 'While creating blog, click the image upload button and select your thumbnail or blog image.',
    suggestions: ['Image size limit', 'Supported formats', 'SEO tips']
  },
  {
    match: /(image size|photo size|max upload)/i,
    reply: 'Maximum upload size is usually 5MB. Recommended image format: JPG, PNG or WEBP.',
    suggestions: ['Upload image', 'Supported formats', 'Compress image']
  },
  {
    match: /(video upload|embed youtube)/i,
    reply: 'You can embed YouTube videos directly inside your blog content using embed links.',
    suggestions: ['Add image', 'SEO tips', 'Formatting blog']
  },

  // ===========================
  // MONETIZATION
  // ===========================

  {
    match: /(earn money|monetization|adsense|income)/i,
    reply: 'Yes 💰 You can monetize your blog using AdSense, affiliate marketing, and sponsored content.',
    suggestions: ['SEO tips', 'Increase traffic', 'Affiliate marketing']
  },
  {
    match: /(affiliate|affiliate marketing)/i,
    reply: 'Affiliate marketing lets you earn commission by promoting products through your blog links.',
    suggestions: ['Earn money', 'SEO tips', 'Increase traffic']
  },

  // ===========================
  // TRAFFIC & PERFORMANCE
  // ===========================

  {
    match: /(increase traffic|get visitors|more views)/i,
    reply: '<b>Ways to increase traffic:</b><br><br>• Write SEO-friendly content<br>• Share on social media<br>• Post consistently<br>• Use attractive thumbnails',
    suggestions: ['SEO tips', 'Trending topics', 'Best posting time']
  },
  {
    match: /(best time.*post|when.*publish)/i,
    reply: 'Best time to publish blogs is usually between 9AM - 11AM and 6PM - 9PM depending on audience.',
    suggestions: ['Increase traffic', 'SEO tips', 'Trending topics']
  },
  {
    match: /(analytics|track views|blog stats)/i,
    reply: 'You can track views, likes, comments and performance from your dashboard analytics section.',
    suggestions: ['Increase traffic', 'SEO tips', 'Top performing blog']
  },

  // ===========================
  // SECURITY
  // ===========================

  {
    match: /(privacy|secure|data safety)/i,
    reply: 'Your data is protected using secure authentication and encrypted connections.',
    suggestions: ['Change password', 'Delete account', 'Terms and conditions']
  },
  {
    match: /(delete.*account|remove.*account)/i,
    reply: 'Go to Account Settings → Delete Account. This action is permanent.',
    suggestions: ['Privacy policy', 'Change password', 'Contact support']
  },

  // ===========================
  // GENERAL QUESTIONS
  // ===========================

  {
    match: /(what is blogscript|about.*blogscript)/i,
    reply: '<b>BlogScript</b> is a modern blogging platform where users can create, publish and manage blogs easily.',
    suggestions: ['Create account', 'How to post blog?', 'SEO tips']
  },
  {
    match: /(who created|developer|owner)/i,
    reply: 'BlogScript was developed to provide a simple and modern blogging experience for creators.',
    suggestions: ['Contact support', 'Features', 'About platform']
  },
  {
    match: /(features|what can i do)/i,
    reply: '<b>Main Features:</b><br><br>• Create blogs<br>• SEO optimization<br>• Dashboard analytics<br>• Draft system<br>• Responsive design',
    suggestions: ['SEO tips', 'Create account', 'Analytics']
  },
  {
    match: /(mobile|responsive|phone support)/i,
    reply: 'Yes ✅ BlogScript works perfectly on mobile, tablet and desktop devices.',
    suggestions: ['App availability', 'Dark mode', 'Features']
  },
  {
    match: /(dark mode|theme)/i,
    reply: 'Dark mode support may be available depending on the current version of BlogScript.',
    suggestions: ['Mobile support', 'Features', 'Customization']
  },
  {
    match: /(comment|comments system)/i,
    reply: 'Readers can leave comments on blogs if comments are enabled by the author.',
    suggestions: ['Disable comments', 'Moderate comments', 'Report spam']
  },
  {
    match: /(spam|report spam)/i,
    reply: 'Spam comments can be deleted or reported from the dashboard moderation panel.',
    suggestions: ['Moderate comments', 'Privacy settings', 'Contact support']
  },
  {
    match: /(notifications|email notifications)/i,
    reply: 'You may receive notifications for comments, updates, and important account activity.',
    suggestions: ['Disable notifications', 'Privacy settings', 'Email settings']
  },
  {
    match: /(api|developer api)/i,
    reply: 'Developer API access may be available for advanced integrations in future versions.',
    suggestions: ['Features', 'Customization', 'Contact support']
  },
  {
    match: /(terms|conditions|policy)/i,
    reply: 'You can read our Terms & Conditions and Privacy Policy from the footer section of the website.',
    suggestions: ['Privacy policy', 'Delete account', 'Contact support']
  },

  // ===========================
  // FALLBACK SMART RESPONSES
  // ===========================

  {
    match: /(thank you|thanks)/i,
    reply: 'You are welcome 😊 Happy blogging with BlogScript!',
    suggestions: ['SEO tips', 'Create blog', 'Contact support']
  },
  {
    match: /(bye|goodbye|see you)/i,
    reply: 'Goodbye 👋 Have a great day and keep creating amazing blogs!',
    suggestions: ['Create blog', 'SEO tips', 'Contact support']
  }
  ,
  {
  match: /(what is your name|your name|who are you|introduce yourself|bot name)/i,
  reply: '<b>My name is BlogScript AI Assistant 🤖</b><br><br>I am here to help you with blogging, account issues, SEO tips, publishing guides, and platform support.',
  suggestions: ['What can you do?', 'How to post blogs?', 'Contact support']
},
// ===========================
// COMMON USER TEXT INPUTS
// ===========================

{
  match: /(i need help|can you help me|help me please|need support)/i,
  reply: 'Of course 😊 Tell me what problem you are facing and I will try to help you.',
  suggestions: ['Login issue', 'How to post blogs?', 'Contact support']
},

{
  match: /(i am new here|new user|first time here)/i,
  reply: 'Welcome to BlogScript 🎉<br><br>You can create blogs, manage content, improve SEO, and grow your audience here.',
  suggestions: ['Create account', 'How to post blogs?', 'SEO tips']
},

{
  match: /(how are you|are you okay|how do you do)/i,
  reply: 'I am doing great 😄 Thanks for asking. How can I assist you today?',
  suggestions: ['Platform features', 'How to create account?', 'SEO tips']
},

{
  match: /(what can you do|your features|how can you help)/i,
  reply: '<b>I can help you with:</b><br><br>• Blog publishing<br>• SEO guidance<br>• Account issues<br>• Password reset<br>• Traffic tips<br>• Support help',
  suggestions: ['SEO tips', 'Create account', 'Analytics']
},

{
  match: /(i love this website|nice website|great platform|awesome)/i,
  reply: 'Thank you so much ❤️ We are happy that you liked BlogScript.',
  suggestions: ['Create blog', 'SEO tips', 'Platform features']
},

{
  match: /(website is slow|slow loading|lagging|performance issue)/i,
  reply: 'Sorry for the inconvenience 😔 Please refresh the page or try again after some time.',
  suggestions: ['Clear cache', 'Contact support', 'Report bug']
},

{
  match: /(bug|error|not working|something wrong)/i,
  reply: 'Please describe the issue in detail so our support team can help you better.',
  suggestions: ['Contact support', 'Login issue', 'Report problem']
},

{
  match: /(can i earn|make money here|income possible)/i,
  reply: 'Yes 💰 You can earn through blogging using ads, affiliate marketing, and sponsorships.',
  suggestions: ['SEO tips', 'Increase traffic', 'Affiliate marketing']
},

{
  match: /(which language supported|multi language|hindi support)/i,
  reply: 'BlogScript supports multiple languages including English and Hindi content writing.',
  suggestions: ['SEO tips', 'Create blog', 'Formatting help']
},

{
  match: /(can i use mobile|phone me chalega|mobile support)/i,
  reply: 'Yes ✅ BlogScript is fully responsive and works smoothly on mobile devices.',
  suggestions: ['App availability', 'Features', 'Dark mode']
},

{
  match: /(is this free|free or paid|pricing)/i,
  reply: 'Most BlogScript features are free to use. Some premium features may be added in future updates.',
  suggestions: ['Features', 'Create account', 'Premium plans']
},

{
  match: /(tell me joke|joke|funny)/i,
  reply: '😂 Why did the blogger go broke?<br><br>Because he used all his cache!',
  suggestions: ['Another joke', 'SEO tips', 'Create blog']
},

{
  match: /(good night|gn)/i,
  reply: 'Good night 🌙 Have a peaceful sleep and happy blogging tomorrow!',
  suggestions: ['SEO tips', 'Create blog', 'Contact support']
},

{
  match: /(good morning|gm)/i,
  reply: 'Good morning ☀️ Ready to create amazing blogs today?',
  suggestions: ['Trending topics', 'SEO tips', 'Create blog']
},

{
  match: /(good afternoon)/i,
  reply: 'Good afternoon 😊 Hope your blogging journey is going great today.',
  suggestions: ['How to post blogs?', 'SEO tips', 'Analytics']
},

{
  match: /(good evening|ge)/i,
  reply: 'Good evening 🌆 Need help with your blogs or account today?',
  suggestions: ['Create blog', 'SEO tips', 'Support']
},

{
  match: /(who made you|who created you|your developer)/i,
  reply: 'I was created to assist BlogScript users with instant support and guidance 🤖',
  suggestions: ['Platform features', 'Contact support', 'About BlogScript']
},

{
  match: /(can you teach seo|learn seo|seo learning)/i,
  reply: '<b>Basic SEO Tips:</b><br><br>• Use proper keywords<br>• Write quality content<br>• Add headings<br>• Optimize images<br>• Use internal links',
  suggestions: ['Advanced SEO', 'Increase traffic', 'Keyword tips']
},

{
  match: /(best niche|blog niche ideas|what should i write)/i,
  reply: '<b>Popular blog niches:</b><br><br>• Technology<br>• Education<br>• Finance<br>• Travel<br>• Health<br>• Gaming',
  suggestions: ['SEO tips', 'Trending topics', 'Start blogging']
},

{
  match: /(trending topics|viral topics|popular content)/i,
  reply: 'Trending topics usually include AI, Technology, Education, Finance, Health, and Social Media updates.',
  suggestions: ['Best niche ideas', 'SEO tips', 'Increase traffic']
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
  reply: `
    <b>Sorry 😅 I could not fully understand your question.</b><br><br>
    Here are some things I can help with:<br><br>
    • Create account<br>
    • Publish blogs<br>
    • SEO tips<br>
    • Password reset<br>
    • Analytics<br>
    • Account settings<br>
    • Monetization<br>
    • Contact support
  `,
  suggestions: [
    'How to post blogs?',
    'SEO tips',
    'Reset password',
    'Contact support'
  ]
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

