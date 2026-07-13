<?php require_once __DIR__ . '/includes/htaccess_redirect.php'; ?>
<?php require_once __DIR__ . '/includes/site_helpers.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Start a project with Hope Architects. Submit build details, images, and plans for expert architecture and construction services in Nigeria.">
  <meta name="keywords" content="start construction project Nigeria, architecture consultation Lagos, building project submission, Hope Architects">
  <meta name="author" content="Hope Architects">
  <title>Start a Project | Hope Architects — Architecture & Construction</title>
  <link rel="shortcut icon" href="<?= siteEscape(siteLogoPath()) ?>" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/site-variables.css">
  <link rel="stylesheet" href="./assets/css/site-utilities.css">
  <link rel="stylesheet" href="./assets/css/list-your-property.css">
  <?php require __DIR__ . '/includes/site_bootstrap.php'; ?>
  <link rel="stylesheet" href="./assets/css/site-header.css">
  <?php require __DIR__ . '/assets/includes/site-footer-head.php'; ?>
</head>
<body>

  <?php require __DIR__ . '/assets/includes/site-chrome.php'; ?>

  <main id="main-content">
    <section class="listing-hero" aria-label="Start a project hero">
      <div class="container">
        <div class="hero-content">
          <p class="hero-eyebrow">Build with confidence</p>
          <h1>Start Your <span>Project</span> With Hope Architects</h1>
          <p class="hero-copy">
            Share your vision with our team. Submit project details, site media, and your build requirements. We review every submission before scheduling a consultation.
          </p>
          <div class="hero-actions">
            <a href="#listingForm" class="hero-btn">
              Start Submission <ion-icon name="arrow-forward-outline"></ion-icon>
            </a>
            <a href="<?= siteTelHref() ?>" class="hero-btn-outline" aria-label="Call Hope Architects at <?= siteEscape(siteContactPhone()) ?>">
              Speak With Our Team <ion-icon name="call-outline"></ion-icon>
            </a>
          </div>
        </div>
        <aside class="seller-card" aria-label="Project benefits">
          <h2 class="seller-card-title">Built for serious clients</h2>
          <ul class="seller-card-list">
            <li><ion-icon name="shield-checkmark-outline"></ion-icon><span>Every submission is reviewed before we respond to protect client trust and project quality.</span></li>
            <li><ion-icon name="videocam-outline"></ion-icon><span>Add site walkthrough videos and multiple image angles for a stronger project brief.</span></li>
            <li><ion-icon name="people-outline"></ion-icon><span>Our architects and site engineers help shape your brief into a buildable plan.</span></li>
          </ul>
        </aside>
      </div>
    </section>

    <section class="trust-strip" aria-label="Listing trust metrics">
      <div class="container">
        <div class="trust-panel">
          <div class="trust-item">
            <div class="trust-number">01</div>
            <div class="trust-label">Submit details</div>
          </div>
          <div class="trust-item">
            <div class="trust-number">02</div>
            <div class="trust-label">Upload media</div>
          </div>
          <div class="trust-item">
            <div class="trust-number">03</div>
            <div class="trust-label">Agent review</div>
          </div>
          <div class="trust-item">
            <div class="trust-number">04</div>
            <div class="trust-label">Qualified leads</div>
          </div>
        </div>
      </div>
    </section>

    <section class="listing-section" aria-label="Property listing form">
      <div class="container">
        <div class="section-heading">
          <p class="section-kicker">Property submission</p>
          <h2>Tell Us What You Want to Sell, Rent, or Lease</h2>
          <p>Complete the form below with accurate ownership, pricing, location, and media details. Quality images and videos help your property stand out and attract faster enquiries.</p>
        </div>

        <div class="listing-grid">
          <aside class="process-card">
            <h3>How it works</h3>
            <ul class="process-list">
              <li>
                <div class="process-step">1</div>
                <div><strong>Submit property details</strong><span>Share location, type, price, features, and seller contact details.</span></div>
              </li>
              <li>
                <div class="process-step">2</div>
                <div><strong>Upload photos and videos</strong><span>Add clear images and walkthrough videos so buyers can understand the property quickly.</span></div>
              </li>
              <li>
                <div class="process-step">3</div>
                <div><strong>Verification call</strong><span>A Hope Architects team member will review your submission and contact you before moving forward.</span></div>
              </li>
              <li>
                <div class="process-step">4</div>
                <div><strong>Market professionally</strong><span>Your property is positioned for interested clients through our listing channels.</span></div>
              </li>
            </ul>
            <div class="agent-note">
              <strong>Need help preparing media?</strong>
              <p>Our team can guide you on the best photos, video angles, and documents to make your property listing stronger.</p>
              <a href="<?= siteEscape(siteTelHref()) ?>"><ion-icon name="call-outline"></ion-icon> <?= siteEscape(siteContactPhone()) ?></a>
            </div>
          </aside>

          <section class="listing-form-card" id="listingForm">
            <h3>List Your Property</h3>
            <p class="form-intro">Fields marked with <span class="required">*</span> are required. After submission, your listing will be reviewed by our team before it appears on the website.</p>

            <form id="propertyListingForm" enctype="multipart/form-data" novalidate>
              <div class="form-section">
                <div class="form-section-title"><ion-icon name="person-outline"></ion-icon> Owner information</div>
                <div class="form-grid">
                  <div class="form-field">
                    <label for="ownerName">Full name <span class="required">*</span></label>
                    <input type="text" id="ownerName" name="ownerName" placeholder="Your full name" required>
                  </div>
                  <div class="form-field">
                    <label for="ownerPhone">Phone number <span class="required">*</span></label>
                    <input type="tel" id="ownerPhone" name="ownerPhone" placeholder="+234..." required>
                  </div>
                  <div class="form-field">
                    <label for="ownerEmail">Email address</label>
                    <input type="email" id="ownerEmail" name="ownerEmail" placeholder="you@example.com" required>
                  </div>
                  <div class="form-field">
                    <label for="contactMethod">Preferred contact</label>
                    <select id="contactMethod" name="contactMethod">
                      <option value="phone">Phone call</option>
                      <option value="whatsapp">WhatsApp</option>
                      <option value="email">Email</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-section">
                <div class="form-section-title"><ion-icon name="home-outline"></ion-icon> Property details</div>
                <div class="form-grid">
                  <div class="form-field full">
                    <label for="propertyTitle">Property title <span class="required">*</span></label>
                    <input type="text" id="propertyTitle" name="propertyTitle" placeholder="Example: Luxury 4 Bedroom Duplex in Lekki Phase 1" required>
                  </div>
                  <div class="form-field">
                    <label for="listingType">Listing purpose <span class="required">*</span></label>
                    <select id="listingType" name="listingType" required>
                      <option value="">Select purpose</option>
                      <option value="sale">For Sale</option>
                      <option value="rent">For Rent</option>
                      <option value="shortlet">Shortlet</option>
                      <option value="lease">Lease</option>
                    </select>
                  </div>
                  <div class="form-field">
                    <label for="propertyType">Property type <span class="required">*</span></label>
                    <select id="propertyType" name="propertyType" required>
                      <option value="">Select type</option>
                      <option value="duplex">Duplex</option>
                      <option value="bungalow">Bungalow</option>
                      <option value="apartment">Apartment</option>
                      <option value="land">Land</option>
                      <option value="commercial">Commercial property</option>
                      <option value="estate">Estate development</option>
                    </select>
                  </div>
                  <div class="form-field">
                    <label for="propertyLocation">Location / area <span class="required">*</span></label>
                    <input type="text" id="propertyLocation" name="propertyLocation" placeholder="Example: Victoria Island, Lagos" required>
                  </div>
                  <div class="form-field">
                    <label for="propertyPrice">Asking price <span class="required">*</span></label>
                    <input type="text" id="propertyPrice" name="propertyPrice" placeholder="Example: NGN 85,000,000" required>
                  </div>
                  <div class="form-field">
                    <label for="bedrooms">Bedrooms</label>
                    <input type="number" id="bedrooms" name="bedrooms" min="0" placeholder="4">
                  </div>
                  <div class="form-field">
                    <label for="bathrooms">Bathrooms</label>
                    <input type="number" id="bathrooms" name="bathrooms" min="0" placeholder="5">
                  </div>
                  <div class="form-field">
                    <label for="propertySize">Size / land measurement</label>
                    <input type="text" id="propertySize" name="propertySize" placeholder="Example: 500 sqm or 1 plot">
                  </div>
                  <div class="form-field">
                    <label for="ownershipStatus">Ownership status</label>
                    <select id="ownershipStatus" name="ownershipStatus">
                      <option value="">Select status</option>
                      <option value="owner">I am the owner</option>
                      <option value="agent">I am an authorized agent</option>
                      <option value="family">Family property</option>
                      <option value="company">Company-owned property</option>
                    </select>
                  </div>
                  <div class="form-field full">
                    <label for="propertyAddress">Full property address <span class="required">*</span></label>
                    <input type="text" id="propertyAddress" name="propertyAddress" placeholder="Street, landmark, area, city, state" required>
                  </div>
                  <div class="form-field full">
                    <label for="propertyFeatures">Key features</label>
                    <input type="text" id="propertyFeatures" name="propertyFeatures" placeholder="Example: POP ceiling, CCTV, borehole, fitted kitchen, parking">
                    <span class="hint">Separate features with commas.</span>
                  </div>
                  <div class="form-field full">
                    <label for="propertyDescription">Property description <span class="required">*</span></label>
                    <textarea id="propertyDescription" name="propertyDescription" placeholder="Describe the property, access road, neighborhood, title documents, and current condition." required></textarea>
                  </div>
                </div>
              </div>

              <div class="form-section">
                <div class="form-section-title"><ion-icon name="images-outline"></ion-icon> Media upload</div>
                <div class="upload-grid">
                  <div>
                    <label class="upload-box" for="propertyImages" data-upload-box>
                      <input type="file" id="propertyImages" name="propertyImages[]" accept="image/*" multiple required>
                      <span class="upload-content">
                        <ion-icon name="image-outline"></ion-icon>
                        <strong>Upload property images <span class="required">*</span></strong>
                        <span>Upload clear exterior, interior, kitchen, bathroom, compound, and street-view photos.</span>
                      </span>
                    </label>
                    <div class="preview-list" id="imagePreview" aria-live="polite"></div>
                  </div>
                  <div>
                    <label class="upload-box" for="propertyVideos" data-upload-box>
                      <input type="file" id="propertyVideos" name="propertyVideos[]" accept="video/*" multiple>
                      <span class="upload-content">
                        <ion-icon name="videocam-outline"></ion-icon>
                        <strong>Upload property videos <span class="required">*</span></strong>
                        <span>Add walkthrough videos, compound views, street access, and room-by-room clips.</span>
                      </span>
                    </label>
                    <div class="preview-list" id="videoPreview" aria-live="polite"></div>
                  </div>
                </div>
              </div>

              <div class="form-section">
                <div class="form-section-title"><ion-icon name="document-text-outline"></ion-icon> Documents and notes</div>
                <div class="form-grid">
                  <div class="form-field">
                    <label for="titleDocument">Title document type</label>
                    <select id="titleDocument" name="titleDocument">
                      <option value="">Select document</option>
                      <option value="c-of-o">Certificate of Occupancy</option>
                      <option value="deed">Deed of Assignment</option>
                      <option value="survey">Survey Plan</option>
                      <option value="allocation">Allocation Paper</option>
                      <option value="other">Other</option>
                    </select>
                  </div>
                  <div class="form-field">
                    <label for="inspectionAvailability">Inspection availability</label>
                    <input type="text" id="inspectionAvailability" name="inspectionAvailability" placeholder="Example: Weekdays after 2pm">
                  </div>
                  <div class="form-field full">
                    <label for="sellerNotes">Additional notes</label>
                    <textarea id="sellerNotes" name="sellerNotes" placeholder="Share anything our listing team should know before contacting you."></textarea>
                  </div>
                </div>
              </div>

              <div class="submit-row">
                <p class="privacy-note">By submitting, you confirm that the information provided is accurate and that Hope Architects may contact you to verify the project details.</p>
                <button type="submit" class="submit-btn">
                  Submit Property <ion-icon name="send-outline"></ion-icon>
                </button>
              </div>
              <div class="form-message" id="formMessage" role="status"></div>
            </form>
          </section>
        </div>
      </div>
    </section>

    <section class="marketing-section" aria-label="How Hope Architects handles project submissions">
      <div class="container">
        <div class="section-heading">
          <p class="section-kicker">World-class presentation</p>
          <h2>We Position Your Project for Success</h2>
          <p>From media review to qualified enquiry handling, our intake process is designed to make your build brief credible, premium, and ready for consultation.</p>
        </div>
        <div class="marketing-grid">
          <article class="marketing-card">
            <ion-icon name="camera-outline"></ion-icon>
            <h3>Media-led listings</h3>
            <p>Quality images and videos help clients understand the value of your property before they request inspection.</p>
          </article>
          <article class="marketing-card">
            <ion-icon name="shield-checkmark-outline"></ion-icon>
            <h3>Verified confidence</h3>
            <p>We review property details, contact information, and ownership context before moving a listing forward.</p>
          </article>
          <article class="marketing-card">
            <ion-icon name="megaphone-outline"></ion-icon>
            <h3>Premium promotion</h3>
            <p>Your property can be presented across listing channels and matched with buyers or tenants seeking that location.</p>
          </article>
        </div>
      </div>
    </section>
  </main>

  <?php require __DIR__ . '/assets/includes/site-footer.php'; ?>

<script src="./assets/js/site-header.js" defer></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script>
    'use strict';

    const form = document.getElementById('propertyListingForm');
    const imageInput = document.getElementById('propertyImages');
    const videoInput = document.getElementById('propertyVideos');
    const imagePreview = document.getElementById('imagePreview');
    const videoPreview = document.getElementById('videoPreview');
    const formMessage = document.getElementById('formMessage');

    if (!form || !imageInput || !formMessage) {
      console.error('List Your Property form could not initialize.');
    } else {

    function showFormMessage(text, isError) {
      formMessage.textContent = text;
      formMessage.className = 'form-message show';
      if (isError) {
        formMessage.style.background = 'rgba(249, 90, 90, 0.1)';
        formMessage.style.borderColor = 'rgba(249, 90, 90, 0.18)';
        formMessage.style.color = '#a12727';
      } else {
        formMessage.style.background = 'rgba(16,183,89,0.1)';
        formMessage.style.borderColor = 'rgba(16,183,89,0.18)';
        formMessage.style.color = '#087a3d';
      }
      formMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function formatFileSize(bytes) {
      if (!bytes) return '0 KB';
      const units = ['B', 'KB', 'MB', 'GB'];
      const index = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
      return `${(bytes / Math.pow(1024, index)).toFixed(index === 0 ? 0 : 1)} ${units[index]}`;
    }

    function removeFileAtIndex(fileList, index) {
      const next = new DataTransfer();
      Array.from(fileList.files || []).forEach((file, i) => {
        if (i !== index) next.items.add(file);
      });
      return next;
    }

    function renderPreviews(fileList, target, type, onRemove) {
      target.innerHTML = '';
      Array.from(fileList.files || []).forEach((file, index) => {
        const item = document.createElement('div');
        item.className = 'preview-item';

        if (type === 'image') {
          const img = document.createElement('img');
          img.className = 'preview-thumb';
          img.alt = file.name;
          img.src = URL.createObjectURL(file);
          item.appendChild(img);
        } else {
          const video = document.createElement('video');
          video.className = 'preview-thumb';
          video.controls = true;
          video.src = URL.createObjectURL(file);
          video.style.width = '120px';
          video.style.height = '80px';
          video.style.objectFit = 'cover';
          item.appendChild(video);
        }

        const text = document.createElement('span');
        text.textContent = `${file.name} - ${formatFileSize(file.size)}`;
        item.appendChild(text);

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'preview-remove';
        removeBtn.setAttribute('aria-label', `Remove ${file.name}`);
        removeBtn.textContent = '\u00d7';
        removeBtn.addEventListener('click', () => onRemove(index));
        item.appendChild(removeBtn);

        target.appendChild(item);
      });
    }

    let imageFileList = new DataTransfer();
    let videoFileList = new DataTransfer();

    function syncImageUpload() {
      imageInput.files = imageFileList.files;
      renderPreviews(imageFileList, imagePreview, 'image', (index) => {
        imageFileList = removeFileAtIndex(imageFileList, index);
        syncImageUpload();
      });
    }

    function syncVideoUpload() {
      if (!videoInput || !videoPreview) return;
      videoInput.files = videoFileList.files;
      renderPreviews(videoFileList, videoPreview, 'video', (index) => {
        videoFileList = removeFileAtIndex(videoFileList, index);
        syncVideoUpload();
      });
    }

    function addFilesToList(fileList, incoming, filterFn) {
      Array.from(incoming || []).forEach((file) => {
        if (!filterFn || filterFn(file)) {
          fileList.items.add(file);
        }
      });
    }

    function resetUploadLists() {
      imageFileList = new DataTransfer();
      videoFileList = new DataTransfer();
      imagePreview.innerHTML = '';
      if (videoPreview) videoPreview.innerHTML = '';
    }

    function buildSubmitFormData() {
      const formData = new FormData(form);
      formData.delete('propertyImages[]');
      formData.delete('propertyImages');
      formData.delete('propertyVideos[]');
      formData.delete('propertyVideos');
      Array.from(imageInput.files || []).forEach((file) => formData.append('propertyImages[]', file));
      if (videoInput) {
        Array.from(videoInput.files || []).forEach((file) => formData.append('propertyVideos[]', file));
      }
      return formData;
    }

    document.querySelectorAll('[data-upload-box]').forEach((box) => {
      const input = box.querySelector('input[type="file"]');
      const isImageBox = input && input.id === 'propertyImages';
      const isVideoBox = input && input.id === 'propertyVideos';

      ['dragenter', 'dragover'].forEach((eventName) => {
        box.addEventListener(eventName, (event) => {
          event.preventDefault();
          box.classList.add('dragover');
        });
      });
      ['dragleave', 'drop'].forEach((eventName) => {
        box.addEventListener(eventName, () => box.classList.remove('dragover'));
      });
      box.addEventListener('drop', (event) => {
        event.preventDefault();
        if (!event.dataTransfer.files.length) return;

        if (isImageBox) {
          addFilesToList(imageFileList, event.dataTransfer.files, (file) => file.type.startsWith('image/'));
          syncImageUpload();
        } else if (isVideoBox) {
          addFilesToList(videoFileList, event.dataTransfer.files, (file) => file.type.startsWith('video/'));
          syncVideoUpload();
        }
      });
    });

    imageInput.addEventListener('change', () => {
      addFilesToList(imageFileList, imageInput.files, (file) => file.type.startsWith('image/'));
      syncImageUpload();
    });

    if (videoInput && videoPreview) {
      videoInput.addEventListener('change', () => {
        addFilesToList(videoFileList, videoInput.files, (file) => file.type.startsWith('video/'));
        syncVideoUpload();
      });
    }

    form.addEventListener('submit', async (event) => {
      event.preventDefault();

      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      if (!imageInput.files.length) {
        showFormMessage('Please upload at least one property image before submitting.', true);
        return;
      }

      const submitBtn = form.querySelector('.submit-btn');
      const originalText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = 'Submitting...';

      try {
        const response = await fetch('api/property-submit.php', {
          method: 'POST',
          body: buildSubmitFormData()
        });
        const raw = await response.text();
        let data = {};
        try {
          data = raw ? JSON.parse(raw) : {};
        } catch (parseError) {
          throw new Error('Server returned an invalid response. Please try again.');
        }

        if (!response.ok || data.success === false) {
          throw new Error(data.message || 'Submission failed.');
        }

        showFormMessage(data.message || 'Listing sent successfully. Waiting for admin approval.', false);
        form.reset();
        resetUploadLists();
      } catch (error) {
        showFormMessage(error.message || 'Unable to submit your listing. Please try again.', true);
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
      }
    });

    }
  </script>
  <?php require __DIR__ . '/assets/includes/whatsapp-chat.php'; ?>
</body>
</html>
