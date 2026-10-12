<?php require_once __DIR__ . '/includes/htaccess_redirect.php'; ?>
<?php require_once __DIR__ . '/includes/site_helpers.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Start an architecture or interior design project with Hope Architects. Share your brief, site media, and vision for a design consultation in Lagos.">
  <meta name="keywords" content="start architecture project Lagos, interior design brief, Hope Architects consultation, building design Nigeria">
  <meta name="author" content="Hope Architects">
  <title>Start a Project | Hope Architects — Architecture &amp; Interior Design</title>
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
          <p class="hero-eyebrow">Architecture &amp; interiors</p>
          <h1>Start Your <span>Design Project</span></h1>
          <p class="hero-copy">
            Tell us about the space you want to create. Submit a short project brief, site photos, and your goals — our studio reviews every request before scheduling a design consultation.
          </p>
          <div class="hero-actions">
            <a href="#listingForm" class="hero-btn">
              Submit Project Brief <ion-icon name="arrow-forward-outline"></ion-icon>
            </a>
            <a href="<?= siteTelHref() ?>" class="hero-btn-outline" aria-label="Call Hope Architects at <?= siteEscape(siteContactPhone()) ?>">
              Speak With Our Studio <ion-icon name="call-outline"></ion-icon>
            </a>
          </div>
        </div>
        <aside class="seller-card" aria-label="Project benefits">
          <h2 class="seller-card-title">Designed for serious briefs</h2>
          <ul class="seller-card-list">
            <li><ion-icon name="shield-checkmark-outline"></ion-icon><span>Every brief is reviewed by our team before we respond — so conversations stay focused and professional.</span></li>
            <li><ion-icon name="videocam-outline"></ion-icon><span>Add site photos and walkthrough videos so our architects can understand context before the first call.</span></li>
            <li><ion-icon name="people-outline"></ion-icon><span>We turn your vision into a clear design direction — architecture, interiors, or both.</span></li>
          </ul>
        </aside>
      </div>
    </section>

    <section class="trust-strip" aria-label="Project intake steps">
      <div class="container">
        <div class="trust-panel">
          <div class="trust-item">
            <div class="trust-number">01</div>
            <div class="trust-label">Share your brief</div>
          </div>
          <div class="trust-item">
            <div class="trust-number">02</div>
            <div class="trust-label">Upload site media</div>
          </div>
          <div class="trust-item">
            <div class="trust-number">03</div>
            <div class="trust-label">Studio review</div>
          </div>
          <div class="trust-item">
            <div class="trust-number">04</div>
            <div class="trust-label">Design consultation</div>
          </div>
        </div>
      </div>
    </section>

    <section class="listing-section" aria-label="Project brief form">
      <div class="container">
        <div class="section-heading">
          <p class="section-kicker">Project intake</p>
          <h2>Tell Us What You Want to Design or Build</h2>
          <p>Share accurate location, scope, and media details. Clear photos and a thoughtful brief help us prepare a stronger consultation and concept direction.</p>
        </div>

        <div class="listing-grid">
          <aside class="process-card">
            <h3>How it works</h3>
            <ul class="process-list">
              <li>
                <div class="process-step">1</div>
                <div><strong>Submit your brief</strong><span>Share location, discipline, scope, preferences, and contact details.</span></div>
              </li>
              <li>
                <div class="process-step">2</div>
                <div><strong>Upload site media</strong><span>Add photos and videos of the site or existing space so our designers can assess context.</span></div>
              </li>
              <li>
                <div class="process-step">3</div>
                <div><strong>Studio review</strong><span>A Hope Architects team member reviews your submission and contacts you to clarify scope.</span></div>
              </li>
              <li>
                <div class="process-step">4</div>
                <div><strong>Consultation &amp; concept</strong><span>We schedule a design consultation and outline next steps for architecture or interiors.</span></div>
              </li>
            </ul>
            <div class="agent-note">
              <strong>Need help preparing media?</strong>
              <p>Our team can guide you on the best site photos, angles, and reference images for a stronger design brief.</p>
              <a href="<?= siteEscape(siteTelHref()) ?>"><ion-icon name="call-outline"></ion-icon> <?= siteEscape(siteContactPhone()) ?></a>
            </div>
          </aside>

          <section class="listing-form-card" id="listingForm">
            <h3>Project Brief</h3>
            <p class="form-intro">Fields marked with <span class="required">*</span> are required. After submission, your brief is reviewed by our studio before we schedule a consultation.</p>

            <form id="propertyListingForm" enctype="multipart/form-data" novalidate>
              <div class="form-section">
                <div class="form-section-title"><ion-icon name="person-outline"></ion-icon> Client information</div>
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
                    <label for="ownerEmail">Email address <span class="required">*</span></label>
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
                <div class="form-section-title"><ion-icon name="construct-outline"></ion-icon> Project details</div>
                <div class="form-grid">
                  <div class="form-field full">
                    <label for="propertyTitle">Project title <span class="required">*</span></label>
                    <input type="text" id="propertyTitle" name="propertyTitle" placeholder="Example: Lekki Family Residence — Architecture &amp; Interiors" required>
                  </div>
                  <div class="form-field">
                    <label for="listingType">Design discipline <span class="required">*</span></label>
                    <select id="listingType" name="listingType" required>
                      <option value="">Select discipline</option>
                      <option value="sale">Architecture</option>
                      <option value="rent">Interior Design</option>
                      <option value="shortlet">Renovation / Fit-out</option>
                      <option value="lease">Commercial Design</option>
                    </select>
                  </div>
                  <div class="form-field">
                    <label for="propertyType">Project type <span class="required">*</span></label>
                    <select id="propertyType" name="propertyType" required>
                      <option value="">Select type</option>
                      <option value="residential">Residential home</option>
                      <option value="duplex">Duplex / townhouse</option>
                      <option value="apartment">Apartment / penthouse</option>
                      <option value="commercial">Commercial / office</option>
                      <option value="hospitality">Hospitality / retail</option>
                      <option value="institutional">Institutional / campus</option>
                      <option value="estate">Estate / master plan</option>
                      <option value="renovation">Renovation only</option>
                    </select>
                  </div>
                  <div class="form-field">
                    <label for="propertyLocation">Location / area <span class="required">*</span></label>
                    <input type="text" id="propertyLocation" name="propertyLocation" placeholder="Example: Victoria Island, Lagos" required>
                  </div>
                  <div class="form-field">
                    <label for="bedrooms">Rooms / spaces</label>
                    <input type="number" id="bedrooms" name="bedrooms" min="0" placeholder="e.g. 4">
                  </div>
                  <div class="form-field">
                    <label for="bathrooms">Wet areas / bathrooms</label>
                    <input type="number" id="bathrooms" name="bathrooms" min="0" placeholder="e.g. 5">
                  </div>
                  <div class="form-field">
                    <label for="propertySize">Site / floor area</label>
                    <input type="text" id="propertySize" name="propertySize" placeholder="Example: 500 sqm plot or 280 sqm built-up">
                  </div>
                  <div class="form-field">
                    <label for="ownershipStatus">Your role</label>
                    <select id="ownershipStatus" name="ownershipStatus">
                      <option value="">Select role</option>
                      <option value="owner">Property owner</option>
                      <option value="developer">Developer / investor</option>
                      <option value="tenant">Tenant / occupier</option>
                      <option value="company">Company representative</option>
                      <option value="agent">Authorized representative</option>
                    </select>
                  </div>
                  <div class="form-field full">
                    <label for="propertyAddress">Site / building address <span class="required">*</span></label>
                    <input type="text" id="propertyAddress" name="propertyAddress" placeholder="Street, landmark, area, city, state" required>
                  </div>
                  <div class="form-field full">
                    <label for="propertyFeatures">Desired finishes &amp; features</label>
                    <input type="text" id="propertyFeatures" name="propertyFeatures" placeholder="Example: open-plan living, natural light, smart systems, outdoor lounge">
                    <span class="hint">Separate items with commas.</span>
                  </div>
                  <div class="form-field full">
                    <label for="propertyDescription">Project vision &amp; brief <span class="required">*</span></label>
                    <textarea id="propertyDescription" name="propertyDescription" placeholder="Describe the project goals, lifestyle needs, style preferences, timeline, and any existing structures or constraints." required></textarea>
                  </div>
                </div>
              </div>

              <div class="form-section">
                <div class="form-section-title"><ion-icon name="images-outline"></ion-icon> Site &amp; reference media</div>
                <div class="upload-grid">
                  <div>
                    <label class="upload-box" for="propertyImages" data-upload-box>
                      <input type="file" id="propertyImages" name="propertyImages[]" accept="image/*" multiple required>
                      <span class="upload-content">
                        <ion-icon name="image-outline"></ion-icon>
                        <strong>Upload site / space photos <span class="required">*</span></strong>
                        <span>Up to 24 photos · JPG/PNG/WEBP/GIF · max 5MB each. First photo is the cover.</span>
                      </span>
                    </label>
                    <div class="preview-list" id="imagePreview" aria-live="polite"></div>
                  </div>
                  <div>
                    <label class="upload-box" for="propertyVideos" data-upload-box>
                      <input type="file" id="propertyVideos" name="propertyVideos[]" accept="video/*" multiple>
                      <span class="upload-content">
                        <ion-icon name="videocam-outline"></ion-icon>
                        <strong>Upload site walkthrough videos</strong>
                        <span>Optional: add walkthrough clips of the site or existing interiors.</span>
                      </span>
                    </label>
                    <div class="preview-list" id="videoPreview" aria-live="polite"></div>
                  </div>
                </div>
              </div>

              <div class="form-section">
                <div class="form-section-title"><ion-icon name="document-text-outline"></ion-icon> Documents &amp; notes</div>
                <div class="form-grid">
                  <div class="form-field">
                    <label for="titleDocument">Available site documents</label>
                    <select id="titleDocument" name="titleDocument">
                      <option value="">Select if available</option>
                      <option value="survey">Survey plan</option>
                      <option value="c-of-o">Certificate of Occupancy</option>
                      <option value="deed">Deed / title documents</option>
                      <option value="drawings">Existing drawings / plans</option>
                      <option value="other">Other</option>
                    </select>
                  </div>
                  <div class="form-field">
                    <label for="inspectionAvailability">Preferred site visit times</label>
                    <input type="text" id="inspectionAvailability" name="inspectionAvailability" placeholder="Example: Weekdays after 2pm">
                  </div>
                  <div class="form-field full">
                    <label for="sellerNotes">Additional notes</label>
                    <textarea id="sellerNotes" name="sellerNotes" placeholder="Budget range, timeline, access constraints, or anything our design team should know."></textarea>
                  </div>
                </div>
              </div>

              <div class="submit-row">
                <p class="privacy-note">By submitting, you confirm the information is accurate and that Hope Architects may contact you to discuss your design project.</p>
                <button type="submit" class="submit-btn">
                  Submit Project Brief <ion-icon name="send-outline"></ion-icon>
                </button>
              </div>
              <div class="form-message" id="formMessage" role="status"></div>
            </form>
          </section>
        </div>
      </div>
    </section>

    <section class="marketing-section" aria-label="How Hope Architects handles project briefs">
      <div class="container">
        <div class="section-heading">
          <p class="section-kicker">Studio process</p>
          <h2>From Brief to Design Direction</h2>
          <p>We treat every submission as a design conversation — not a sales listing. Your media and notes help us prepare a credible, premium consultation.</p>
        </div>
        <div class="marketing-grid">
          <article class="marketing-card">
            <ion-icon name="camera-outline"></ion-icon>
            <h3>Context-led design</h3>
            <p>Site photos and videos help our architects understand light, access, neighbours, and spatial opportunities before the first meeting.</p>
          </article>
          <article class="marketing-card">
            <ion-icon name="shield-checkmark-outline"></ion-icon>
            <h3>Reviewed with care</h3>
            <p>We review your brief, contact details, and project scope before responding — so next steps stay clear and professional.</p>
          </article>
          <article class="marketing-card">
            <ion-icon name="compass-outline"></ion-icon>
            <h3>Clear next steps</h3>
            <p>After review, we schedule a consultation and outline how architecture, interiors, or renovation can move your project forward.</p>
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
      console.error('Project brief form could not initialize.');
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

    const MAX_IMAGE_BYTES = 5 * 1024 * 1024;
    const MAX_GALLERY_IMAGES = 24;
    const MAX_VIDEO_BYTES = 50 * 1024 * 1024;

    function addFilesToList(fileList, incoming, filterFn, options = {}) {
      const maxBytes = options.maxBytes || 0;
      const maxCount = options.maxCount || 0;
      const label = options.label || 'file';
      const errors = [];

      Array.from(incoming || []).forEach((file) => {
        if (filterFn && !filterFn(file)) return;
        if (maxCount > 0 && fileList.files.length >= maxCount) {
          errors.push(`You can upload up to ${maxCount} ${label}s.`);
          return;
        }
        if (maxBytes > 0 && file.size > maxBytes) {
          errors.push(`"${file.name}" is too large (${formatFileSize(file.size)}). Max ${formatFileSize(maxBytes)} per ${label}.`);
          return;
        }
        fileList.items.add(file);
      });

      if (errors.length) {
        showFormMessage(errors[0], true);
      }
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
          addFilesToList(imageFileList, event.dataTransfer.files, (file) => file.type.startsWith('image/'), {
            maxBytes: MAX_IMAGE_BYTES,
            maxCount: MAX_GALLERY_IMAGES,
            label: 'photo',
          });
          syncImageUpload();
        } else if (isVideoBox) {
          addFilesToList(videoFileList, event.dataTransfer.files, (file) => file.type.startsWith('video/'), {
            maxBytes: MAX_VIDEO_BYTES,
            maxCount: 3,
            label: 'video',
          });
          syncVideoUpload();
        }
      });
    });

    imageInput.addEventListener('change', () => {
      addFilesToList(imageFileList, imageInput.files, (file) => file.type.startsWith('image/'), {
        maxBytes: MAX_IMAGE_BYTES,
        maxCount: MAX_GALLERY_IMAGES,
        label: 'photo',
      });
      syncImageUpload();
    });

    if (videoInput && videoPreview) {
      videoInput.addEventListener('change', () => {
        addFilesToList(videoFileList, videoInput.files, (file) => file.type.startsWith('video/'), {
          maxBytes: MAX_VIDEO_BYTES,
          maxCount: 3,
          label: 'video',
        });
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
        showFormMessage('Please upload at least one site or space photo before submitting.', true);
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

        showFormMessage(data.message || 'Project brief received. Our studio will review and contact you shortly.', false);
        form.reset();
        resetUploadLists();
      } catch (error) {
        showFormMessage(error.message || 'Unable to submit your project brief. Please try again.', true);
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
