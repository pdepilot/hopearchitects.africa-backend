<?php require_once __DIR__ . '/includes/htaccess_redirect.php'; ?>
<?php
require_once __DIR__ . '/includes/FaqRepository.php';
require_once __DIR__ . '/includes/site_helpers.php';
try {
    $contactFaqs = FaqRepository::getPublic();
} catch (Throwable $e) {
    $contactFaqs = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Contact Hope Architects — Nigeria's architecture and construction firm. Reach out for project consultations, design inquiries, or partnership opportunities.">
  <meta name="keywords" content="contact Hope Architects, architecture consultation Nigeria, construction Lagos, building design contact">
  <meta name="author" content="Hope Architects">
  <title>Contact Us | Hope Architects — Let's Connect</title>
  <link rel="shortcut icon" href="<?= siteEscape(siteLogoPath()) ?>" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/site-variables.css">
  <link rel="stylesheet" href="./assets/css/site-utilities.css">
  <link rel="stylesheet" href="./assets/css/contact.css">
  <?php require __DIR__ . '/includes/site_bootstrap.php'; ?>
  <link rel="stylesheet" href="./assets/css/site-header.css">
  <?php require __DIR__ . '/assets/includes/site-footer-head.php'; ?>
</head>
<body>

  <?php require __DIR__ . '/assets/includes/site-chrome.php'; ?>

  <main id="main-content">
    <!-- Hero Section -->
    <section class="contact-hero">
      <div class="container">
        <h1>Let's <span class="gold-accent">Connect</span></h1>
        <p>Whether you're planning a new build, renovation, or consultation — we're here to guide you every step of the way.</p>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
      <div class="container">
        <div class="contact-grid">
          <!-- Left Side - Contact Info -->
          <div>
            <div class="info-card">
              <div class="info-icon"><ion-icon name="call-outline"></ion-icon></div>
              <h3>Phone & WhatsApp</h3>
              <p>Call: <a href="<?= siteTelHref() ?>"><?= siteEscape(siteContactPhone()) ?></a></p>
              <p>WhatsApp: <a href="<?= siteEscape(siteWhatsAppUrl()) ?>" target="_blank" rel="noopener noreferrer"><?= siteEscape(siteWhatsAppPhone()) ?></a></p>
              <p class="info-card-hours">Mon - Sat: 8am - 6pm</p>
            </div>
            
            <div class="info-card">
              <div class="info-icon"><ion-icon name="mail-outline"></ion-icon></div>
              <h3>Email Address</h3>
              <p><a href="<?= siteMailto() ?>"><?= siteEscape(siteContactEmail()) ?></a></p>
            </div>
            
            <div class="info-card">
              <div class="info-icon"><ion-icon name="location-outline"></ion-icon></div>
              <h3>Visit Our Office</h3>
              <p><?= siteEscape(siteAddress()) ?></p>
            </div>
            
            <div class="social-links-section">
              <h3>Connect With Us</h3>
              <div class="social-icons">
                <?php if (siteSocial('facebook') !== ''): ?>
                <a href="<?= siteEscape(siteSocial('facebook')) ?>" class="social-icon-link" target="_blank" rel="noopener noreferrer"><ion-icon name="logo-facebook"></ion-icon></a>
                <?php endif; ?>
                <?php if (siteSocial('instagram') !== ''): ?>
                <a href="<?= siteEscape(siteSocial('instagram')) ?>" class="social-icon-link" target="_blank" rel="noopener noreferrer"><ion-icon name="logo-instagram"></ion-icon></a>
                <?php endif; ?>
                <?php if (siteSocial('tiktok') !== ''): ?>
                <a href="<?= siteEscape(siteSocial('tiktok')) ?>" class="social-icon-link" target="_blank" rel="noopener noreferrer"><ion-icon name="logo-youtube"></ion-icon></a>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Right Side - Contact Form -->
          <div class="form-container">
            <h2>Send Us a Message</h2>
            <p class="form-subtitle">Fill out the form below and we'll get back to you within 24 hours.</p>
            
            <form id="contactForm">
              <div class="form-group">
                <input type="text" id="fullName" placeholder="Your Full Name" required>
              </div>
              <div class="form-group">
                <input type="email" id="email" placeholder="Email Address" required>
              </div>
              <div class="form-group">
                <input type="tel" id="phone" placeholder="Phone Number">
              </div>
              <div class="form-group">
                <select id="inquiryType">
                  <option value="general">General Inquiry</option>
                  <option value="new-build">New Build Project</option>
                  <option value="renovation">Renovation / Remodel</option>
                  <option value="consultation">Design Consultation</option>
                  <option value="partnership">Partnership Opportunity</option>
                </select>
              </div>
              <div class="form-group">
                <textarea id="message" placeholder="Tell us about your architecture or construction needs..." required></textarea>
              </div>
              <button type="submit" class="submit-btn">
                <ion-icon name="send-outline"></ion-icon>
                Send Message
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
      <div class="container">
        <div class="map-container">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253682.445985963!2d3.3792!3d6.4281!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b8d2c6279b927%3A0x2c6748a2376a3033!2sLagos%2C%20Nigeria!5e0!3m2!1sen!2sng!4v1700000000000!5m2!1sen!2sng" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
      <div class="container">
        <div class="faq-header">
          <div class="faq-eyebrow">
            <div class="faq-eyebrow-line"></div>
            <span class="faq-eyebrow-label">FAQ</span>
            <div class="faq-eyebrow-line"></div>
          </div>
          <h2 class="faq-heading">Frequently Asked Questions</h2>
        </div>
        
        <div class="faq-grid" id="faqGrid">
          <?php if ($contactFaqs === []): ?>
          <div class="faq-item">
            <div class="faq-question">How can I schedule a project consultation? <ion-icon name="chevron-down-outline"></ion-icon></div>
            <div class="faq-answer">Call us at <?= siteEscape(siteContactPhone()) ?> or fill out our contact form. Our team will arrange a convenient consultation.</div>
          </div>
          <?php else: ?>
          <?php foreach ($contactFaqs as $faq): ?>
          <div class="faq-item">
            <div class="faq-question"><?= siteEscape((string) $faq['question']) ?> <ion-icon name="chevron-down-outline"></ion-icon></div>
            <div class="faq-answer"><?= siteEscape((string) $faq['answer']) ?></div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- CTA Banner -->
    <section class="cta-banner">
      <div class="container">
        <h2>Ready to Start Your Build?</h2>
        <p>Let our expert team guide you from concept through construction.</p>
        <a href="<?= pageHref('property') ?>" class="cta-button"><ion-icon name="home-outline"></ion-icon> Explore Projects</a>
      </div>
    </section>
  </main>

  <?php require __DIR__ . '/assets/includes/site-footer.php'; ?>

<script src="./assets/js/site-header.js" defer></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script>
    // FAQ Accordion
    document.querySelectorAll('.faq-item').forEach(item => {
      item.addEventListener('click', () => {
        item.classList.toggle('active');
      });
    });

    // Form submission
    const contactForm = document.getElementById('contactForm');
    
    function showToast(message) {
      const existing = document.querySelector('.toast-message');
      if (existing) existing.remove();
      const toast = document.createElement('div');
      toast.className = 'toast-message';
      toast.innerHTML = `<ion-icon name="checkmark-circle-outline"></ion-icon><span>${message}</span>`;
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 4000);
    }

    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const formData = {
        name: document.getElementById('fullName').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        inquiryType: document.getElementById('inquiryType').value,
        message: document.getElementById('message').value,
        date: new Date().toISOString()
      };
      
      // Validate
      if (!formData.name || !formData.email || !formData.message) {
        showToast('Please fill in all required fields');
        return;
      }
      
      const submitBtn = document.querySelector('.submit-btn');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<ion-icon name="hourglass-outline"></ion-icon>Sending...';
      submitBtn.disabled = true;
      
      try {
        const response = await fetch('api/contact-submit.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
          throw new Error(data.message || 'Unable to send message. Please try again.');
        }

        showToast(data.message || 'Message sent successfully! We\'ll get back to you soon.');
        contactForm.reset();

      } catch (error) {
        showToast(error.message || 'Unable to send message. Please call us at <?= siteEscape(siteContactPhone()) ?>.');
      } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      }
    });
  </script>
  <?php require __DIR__ . '/assets/includes/whatsapp-chat.php'; ?>
</body>
</html>