<?php
/**
 * Hope Architects — WhatsApp live chat (replaces AI chatbot on public pages)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/site_helpers.php';
require_once dirname(__DIR__, 2) . '/includes/site_paths.php';

$waUrl = siteWhatsAppUrl(
    'Hello ' . siteName() . ', I would like to discuss a project. '
);
$cssUrl = siteUrl('assets/css/whatsapp-chat.css');
$jsUrl = siteUrl('assets/js/whatsapp-chat.js');
$agentName = siteEscape(siteName());
$hours = siteEscape(siteBusinessHours());
$avatarUrl = siteEscape(siteUrl('assets/images/hope_archites.png'));
?>
<link rel="stylesheet" href="<?= siteEscape($cssUrl) ?>">
<div
  id="ha-wa-root"
  class="ha-wa-root"
  data-whatsapp-url="<?= siteEscape($waUrl) ?>"
  aria-live="polite"
>
  <aside
    class="ha-wa-chatbox"
    id="ha-wa-chatbox"
    role="dialog"
    aria-label="Welcome chat"
    aria-modal="false"
    hidden
  >
    <div class="ha-wa-chatbox-panel">
      <header class="ha-wa-chatbox-header">
        <div class="ha-wa-chatbox-agent">
          <span class="ha-wa-chatbox-avatar-wrap">
            <img class="ha-wa-chatbox-avatar" src="<?= $avatarUrl ?>" alt="" width="44" height="44">
            <span class="ha-wa-chatbox-avatar-ring" aria-hidden="true"></span>
          </span>
          <div class="ha-wa-chatbox-meta">
            <strong class="ha-wa-chatbox-name"><?= $agentName ?></strong>
            <span class="ha-wa-chatbox-status">
              <span class="ha-wa-chatbox-status-dot" aria-hidden="true"></span>
              Online · typically replies in minutes
            </span>
          </div>
        </div>
        <button type="button" class="ha-wa-chatbox-close" id="ha-wa-chatbox-close" aria-label="Close chat">
          <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
        </button>
      </header>

      <div class="ha-wa-chatbox-body">
        <div class="ha-wa-chatbox-thread" id="ha-wa-chatbox-thread">
          <div class="ha-wa-typing" id="ha-wa-typing" aria-hidden="true">
            <span class="ha-wa-bubble ha-wa-bubble--in ha-wa-bubble--typing">
              <span class="ha-wa-typing-dots"><span></span><span></span><span></span></span>
            </span>
          </div>

          <div class="ha-wa-bubble ha-wa-bubble--in" data-wa-msg="1" hidden>
            <p>Hello 👋 Welcome to <strong><?= $agentName ?></strong>.</p>
            <time class="ha-wa-bubble-time" datetime="">Just now</time>
          </div>

          <div class="ha-wa-bubble ha-wa-bubble--in" data-wa-msg="2" hidden>
            <p>How can we help with your project today — <em>design</em>, <em>construction</em>, or a <em>site consultation</em>?</p>
            <time class="ha-wa-bubble-time" datetime="">Just now</time>
          </div>
        </div>
      </div>

      <footer class="ha-wa-chatbox-footer">
        <a href="<?= siteEscape($waUrl) ?>" class="ha-wa-chatbox-cta" id="ha-wa-chatbox-cta" target="_blank" rel="noopener noreferrer">
          <svg class="ha-wa-cta-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
          </svg>
          Continue on WhatsApp
        </a>
        <span class="ha-wa-chatbox-hours"><?= $hours ?></span>
      </footer>
    </div>
  </aside>

  <div class="ha-wa-stage" id="ha-wa-stage">
    <div class="ha-wa-orbit ha-wa-orbit--a" aria-hidden="true"></div>
    <div class="ha-wa-orbit ha-wa-orbit--b" aria-hidden="true"></div>
    <div class="ha-wa-orbit ha-wa-orbit--c" aria-hidden="true"></div>
    <div class="ha-wa-sonar" aria-hidden="true"></div>
    <div class="ha-wa-sonar ha-wa-sonar--delay" aria-hidden="true"></div>

    <a
      href="<?= siteEscape($waUrl) ?>"
      id="ha-wa-launcher"
      class="ha-wa-launcher"
      target="_blank"
      rel="noopener noreferrer"
      aria-label="Chat with <?= $agentName ?> on WhatsApp"
    >
      <span class="ha-wa-cube" aria-hidden="true">
        <span class="ha-wa-cube-face ha-wa-cube-face--front">
          <svg class="ha-wa-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
          </svg>
        </span>
        <span class="ha-wa-cube-face ha-wa-cube-face--back"></span>
        <span class="ha-wa-cube-face ha-wa-cube-face--right"></span>
        <span class="ha-wa-cube-face ha-wa-cube-face--left"></span>
        <span class="ha-wa-cube-face ha-wa-cube-face--top"></span>
        <span class="ha-wa-cube-face ha-wa-cube-face--bottom"></span>
      </span>
      <span class="ha-wa-launcher-glow" aria-hidden="true"></span>
      <span class="ha-wa-online" aria-hidden="true"></span>
    </a>
  </div>
</div>
<script src="<?= siteEscape($jsUrl) ?>" defer></script>
