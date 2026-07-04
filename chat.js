// ==========================================================
// AI Chat page behavior: send message, typing indicator,
// auto-scroll, render bubbles via AJAX to chat_process.php
// ==========================================================

document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('chatForm');
  const input = document.getElementById('chatInput');
  const messages = document.getElementById('chatMessages');

  if (!form) return;

  function scrollToBottom() {
    messages.scrollTop = messages.scrollHeight;
  }

  function appendMessage(sender, text) {
    const row = document.createElement('div');
    row.className = 'msg-row ' + sender;

    const avatar = document.createElement('div');
    avatar.className = 'msg-avatar ' + sender;
    avatar.textContent = sender === 'bot' ? '🤖' : '🙂';

    const bubble = document.createElement('div');
    bubble.className = 'msg-bubble';
    bubble.innerHTML = text;

    row.appendChild(avatar);
    row.appendChild(bubble);
    messages.appendChild(row);
    scrollToBottom();
  }

  function showTyping() {
    const row = document.createElement('div');
    row.className = 'msg-row bot';
    row.id = 'typingRow';
    row.innerHTML = `
      <div class="msg-avatar bot">🤖</div>
      <div class="typing-indicator"><span></span><span></span><span></span></div>
    `;
    messages.appendChild(row);
    scrollToBottom();
  }

  function removeTyping() {
    const row = document.getElementById('typingRow');
    if (row) row.remove();
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    const text = input.value.trim();
    if (!text) return;

    appendMessage('user', text);
    input.value = '';
    input.focus();
    showTyping();

    const formData = new FormData();
    formData.append('message', text);

    fetch('chat_process.php', {
      method: 'POST',
      body: formData
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        removeTyping();
        // Small delay so the typing indicator feels natural
        setTimeout(function () {
          appendMessage('bot', data.reply);
        }, 300);
      })
      .catch(function () {
        removeTyping();
        appendMessage('bot', "Sorry, something went wrong. Please try again.");
      });
  });

  scrollToBottom();
});
