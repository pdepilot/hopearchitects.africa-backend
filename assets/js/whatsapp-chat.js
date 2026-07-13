/**
 * Hope Architects — WhatsApp live chat widget (3D launcher + classic chat box)
 */
(function () {
  'use strict';

  const root = document.getElementById('ha-wa-root');
  const stage = document.getElementById('ha-wa-stage');
  const launcher = document.getElementById('ha-wa-launcher');
  const chatbox = document.getElementById('ha-wa-chatbox');
  const closeBtn = document.getElementById('ha-wa-chatbox-close');
  const typing = document.getElementById('ha-wa-typing');
  const messages = root ? root.querySelectorAll('[data-wa-msg]') : [];

  if (!root || !stage || !launcher) {
    return;
  }

  document.body.classList.add('ha-wa-present');

  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const CHATBOX_KEY = 'ha_wa_chatbox_dismissed';
  const CHATBOX_DELAY = 2800;
  const MAX_TILT = 14;

  function setBubbleTimes() {
    const now = new Date();
    const label = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    messages.forEach((bubble) => {
      const timeEl = bubble.querySelector('.ha-wa-bubble-time');
      if (timeEl) {
        timeEl.textContent = label;
        timeEl.setAttribute('datetime', now.toISOString());
      }
    });
  }

  function revealBubble(bubble) {
    bubble.hidden = false;
    requestAnimationFrame(() => {
      bubble.classList.add('is-shown');
    });
  }

  function runMessageSequence() {
    if (!chatbox || messages.length === 0) {
      return;
    }

    setBubbleTimes();

    if (reducedMotion) {
      if (typing) {
        typing.classList.add('is-hidden');
      }
      messages.forEach(revealBubble);
      return;
    }

    window.setTimeout(() => {
      if (typing) {
        typing.classList.add('is-hidden');
      }
      revealBubble(messages[0]);
    }, 1400);

    window.setTimeout(() => {
      if (messages[1]) {
        revealBubble(messages[1]);
      }
    }, 2400);
  }

  function showChatbox() {
    if (!chatbox || sessionStorage.getItem(CHATBOX_KEY) === '1') {
      return;
    }

    chatbox.hidden = false;
    document.body.classList.add('ha-wa-chat-open');
    requestAnimationFrame(() => {
      chatbox.classList.add('is-visible');
      runMessageSequence();
    });
  }

  function hideChatbox() {
    if (!chatbox) {
      return;
    }

    chatbox.classList.remove('is-visible');
    document.body.classList.remove('ha-wa-chat-open');
    sessionStorage.setItem(CHATBOX_KEY, '1');
    window.setTimeout(() => {
      chatbox.hidden = true;
    }, 500);
  }

  function setTilt(clientX, clientY) {
    const rect = stage.getBoundingClientRect();
    const cx = rect.left + rect.width / 2;
    const cy = rect.top + rect.height / 2;
    const dx = (clientX - cx) / (rect.width / 2);
    const dy = (clientY - cy) / (rect.height / 2);
    const rx = Math.max(-1, Math.min(1, -dy)) * MAX_TILT;
    const ry = Math.max(-1, Math.min(1, dx)) * MAX_TILT;

    root.style.setProperty('--ha-wa-tilt-x', `${rx.toFixed(2)}deg`);
    root.style.setProperty('--ha-wa-tilt-y', `${ry.toFixed(2)}deg`);
  }

  function resetTilt() {
    root.style.setProperty('--ha-wa-tilt-x', '0deg');
    root.style.setProperty('--ha-wa-tilt-y', '0deg');
  }

  if (!reducedMotion) {
    stage.addEventListener('pointermove', (event) => {
      setTilt(event.clientX, event.clientY);
    });

    stage.addEventListener('pointerleave', resetTilt);
  }

  launcher.addEventListener('click', () => {
    hideChatbox();
  });

  if (closeBtn) {
    closeBtn.addEventListener('click', (event) => {
      event.stopPropagation();
      hideChatbox();
    });
  }

  const cta = document.getElementById('ha-wa-chatbox-cta');
  if (cta) {
    cta.addEventListener('click', () => {
      hideChatbox();
    });
  }

  window.setTimeout(showChatbox, CHATBOX_DELAY);

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && chatbox && chatbox.classList.contains('is-visible')) {
      hideChatbox();
    }
  });
})();
