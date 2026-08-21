<?php
/**
 * Event-driven automated email notifications for Hope Architects.
 */
declare(strict_types=1);

require_once __DIR__ . '/EmailRepository.php';
require_once __DIR__ . '/HtmlSanitizer.php';
require_once __DIR__ . '/MailService.php';
require_once __DIR__ . '/MailConfigService.php';
require_once __DIR__ . '/site_paths.php';
require_once __DIR__ . '/site_helpers.php';

final class AutomatedEmailService
{
    public const EVENT_CONTACT_RECEIVED       = 'contact_enquiry_received';
    public const EVENT_CONTACT_ADMIN        = 'contact_admin_notification';
    public const EVENT_PROPERTY_SUBMITTED   = 'property_submission_received';
    public const EVENT_PROPERTY_APPROVED    = 'property_approved';
    public const EVENT_PROPERTY_REJECTED    = 'property_rejected';
    public const EVENT_PROPERTY_SUSPENDED   = 'property_suspended';
    public const EVENT_PROPERTY_EXPIRED     = 'property_expired';
    public const EVENT_PROPERTY_REACTIVATED = 'property_reactivated';
    public const EVENT_NEWSLETTER_WELCOME   = 'newsletter_welcome';
    public const EVENT_PASSWORD_RESET       = 'password_reset';
    public const EVENT_USER_REGISTRATION    = 'user_registration';

    /** @return array<string, array{name:string,subject:string,body_html:string,description?:string}> */
    public static function defaultEventTemplates(): array
    {
        return [
            self::EVENT_CONTACT_RECEIVED => [
                'name'        => 'Contact Enquiry Received',
                'description' => 'Auto-response when visitor submits contact form',
                'subject'     => 'We have received your enquiry — Hope Architects',
                'body_html'   => '<p>Hello {{customer_name}},</p>
{{enquiry_body}}
<p>Warm regards,<br><strong>Hope Architects Team</strong></p>',
            ],
            self::EVENT_CONTACT_ADMIN => [
                'name'        => 'Contact Enquiry Admin Notification',
                'description' => 'Notifies administrators of new contact enquiries',
                'subject'     => 'New Contact Enquiry — {{customer_name}} ({{enquiry_subject}})',
                'body_html'   => '<p>A new contact enquiry was submitted on the Hope Architects website.</p>
<table style="width:100%;font-size:14px;border-collapse:collapse;">
<tr><td style="padding:6px 0;color:#5a6b60;width:130px;">Customer Name</td><td><strong>{{customer_name}}</strong></td></tr>
<tr><td style="padding:6px 0;color:#5a6b60;">Email</td><td>{{customer_email}}</td></tr>
<tr><td style="padding:6px 0;color:#5a6b60;">Phone</td><td>{{customer_phone}}</td></tr>
<tr><td style="padding:6px 0;color:#5a6b60;">Enquiry</td><td>{{enquiry_subject}}</td></tr>
<tr><td style="padding:6px 0;color:#5a6b60;">Submitted</td><td>{{submission_date}}</td></tr>
<tr><td style="padding:6px 0;color:#5a6b60;">Reference</td><td>{{ticket_id}}</td></tr>
</table>
<p><strong>Message:</strong></p>
<p style="background:#f3f8f8;padding:14px;border-radius:8px;border:1px solid #d5e6e8;">{{message}}</p>
<p style="margin-top:16px;">Reply from Hostinger webmail (Reply-To is set to the customer), or open <strong>Admin → Inquiries</strong> to reply from the dashboard.</p>',
            ],
            self::EVENT_PROPERTY_SUBMITTED => [
                'name'        => 'Property Submission Received',
                'description' => 'Acknowledgement when owner submits a listing',
                'subject'     => 'Your Property Listing Request Has Been Received',
                'body_html'   => '<p>Hello {{owner_name}},</p>
<p>Thank you for submitting your property for listing with <strong>Hope Architects</strong>.</p>
<p>We have successfully received your property submission and supporting documents.</p>
<p>Your property is currently under review by our verification team.</p>
<p><strong>Status:</strong> Pending Approval<br>
<strong>Reference Number:</strong> {{listing_id}}<br>
<strong>Submission Date:</strong> {{submission_date}}</p>
<p>Our team will inspect and verify the submitted information and documents. Once verification is completed and approved, your property will be published on our website.</p>
<p>Thank you for choosing Hope Architects.</p>
<p>Best Regards,<br><strong>Hope Architects</strong></p>',
            ],
            self::EVENT_PROPERTY_APPROVED => [
                'name'        => 'Property Approved',
                'description' => 'Sent when admin approves a property listing',
                'subject'     => 'Congratulations! Your Property Has Been Approved',
                'body_html'   => '<p>Hello {{owner_name}},</p>
<p>We are pleased to inform you that your property submission has been reviewed and <strong>approved</strong> by our team.</p>
<p>Your property is now live and visible on the Hope Architects website.</p>
<p><a href="{{property_url}}" style="display:inline-block;background:#feee90;color:#0b4e56;padding:12px 22px;border-radius:999px;text-decoration:none;font-weight:bold;">View Your Property</a></p>
<p><strong>Property Title:</strong> {{property_title}}<br>
<strong>Approval Date:</strong> {{approval_date}}</p>
<p>Thank you for listing with Hope Architects. We wish you success in finding the right buyer or investor.</p>
<p>Best Regards,<br><strong>Hope Architects</strong></p>',
            ],
            self::EVENT_PROPERTY_REJECTED => [
                'name'        => 'Property Rejected',
                'description' => 'Sent when admin rejects a property listing',
                'subject'     => 'Property Listing Update',
                'body_html'   => '<p>Hello {{owner_name}},</p>
<p>Thank you for submitting your property listing.</p>
<p>After reviewing the submitted information and documentation, we are currently unable to approve the property for publication.</p>
<p><strong>Reason:</strong><br>{{rejection_reason}}</p>
<p>You may update the required information and resubmit your property for review. If you need assistance, please contact our support team.</p>
<p>Best Regards,<br><strong>Hope Architects</strong></p>',
            ],
            self::EVENT_PROPERTY_SUSPENDED => [
                'name'        => 'Property Suspended',
                'description' => 'Sent when a live property is suspended',
                'subject'     => 'Your Property Listing Has Been Suspended',
                'body_html'   => '<p>Hello {{owner_name}},</p>
<p>Your property listing <strong>{{property_title}}</strong> has been temporarily suspended and is no longer visible on our website.</p>
<p><strong>Reason:</strong> {{status_reason}}</p>
<p>Please contact our team if you have questions or need to restore your listing.</p>
<p>Best Regards,<br><strong>Hope Architects</strong></p>',
            ],
            self::EVENT_PROPERTY_EXPIRED => [
                'name'        => 'Property Expired',
                'description' => 'Sent when a property listing expires',
                'subject'     => 'Your Property Listing Has Expired',
                'body_html'   => '<p>Hello {{owner_name}},</p>
<p>Your property listing <strong>{{property_title}}</strong> has expired and is no longer visible on our website.</p>
<p>To keep your listing active, please contact us or resubmit your property for review.</p>
<p>Best Regards,<br><strong>Hope Architects</strong></p>',
            ],
            self::EVENT_PROPERTY_REACTIVATED => [
                'name'        => 'Property Reactivated',
                'description' => 'Sent when a suspended listing is restored',
                'subject'     => 'Your Property Listing Is Live Again',
                'body_html'   => '<p>Hello {{owner_name}},</p>
<p>Good news! Your property listing <strong>{{property_title}}</strong> has been reactivated and is visible again on Hope Architects.</p>
<p><a href="{{property_url}}">View your property</a></p>
<p>Best Regards,<br><strong>Hope Architects</strong></p>',
            ],
            self::EVENT_NEWSLETTER_WELCOME => [
                'name'        => 'Newsletter Welcome',
                'description' => 'Sent when a visitor subscribes to the newsletter',
                'subject'     => 'Welcome to Hope Architects Newsletter',
                'body_html'   => '<p>Hello {{customer_name}},</p>
<p>Thank you for subscribing to the <strong>Hope Architects</strong> newsletter.</p>
<p>You will receive updates on premium properties, market insights, and exclusive offers.</p>
<p>Best Regards,<br><strong>Hope Architects</strong></p>',
            ],
            self::EVENT_PASSWORD_RESET => [
                'name'        => 'Password Reset',
                'description' => 'Sent when a password reset is requested',
                'subject'     => 'Reset Your Password — Hope Architects',
                'body_html'   => '<p>Hello {{customer_name}},</p>
<p>We received a request to reset your password.</p>
<p><a href="{{reset_link}}" style="display:inline-block;background:#feee90;color:#0b4e56;padding:12px 22px;border-radius:999px;text-decoration:none;font-weight:bold;">Reset Password</a></p>
<p>If you did not request this, please ignore this email.</p>
<p>Best Regards,<br><strong>Hope Architects</strong></p>',
            ],
            self::EVENT_USER_REGISTRATION => [
                'name'        => 'User Registration Welcome',
                'description' => 'Sent when a new user completes registration',
                'subject'     => 'Welcome to Hope Architects',
                'body_html'   => '<p>Hello {{customer_name}},</p>
<p>Your account has been created successfully. Welcome to <strong>Hope Architects</strong>!</p>
<p>You can now explore properties, save favourites, and manage your profile.</p>
<p>Best Regards,<br><strong>Hope Architects</strong></p>',
            ],
        ];
    }

    /** @return array<string, string> */
    public static function eventLabels(): array
    {
        return [
            self::EVENT_CONTACT_RECEIVED       => 'Contact Enquiry Received',
            self::EVENT_CONTACT_ADMIN          => 'Contact Admin Notification',
            self::EVENT_PROPERTY_SUBMITTED     => 'Property Submission Received',
            self::EVENT_PROPERTY_APPROVED      => 'Property Approved',
            self::EVENT_PROPERTY_REJECTED      => 'Property Rejected',
            self::EVENT_PROPERTY_SUSPENDED     => 'Property Suspended',
            self::EVENT_PROPERTY_EXPIRED       => 'Property Expired',
            self::EVENT_PROPERTY_REACTIVATED   => 'Property Reactivated',
            self::EVENT_NEWSLETTER_WELCOME     => 'Newsletter Welcome Email',
            self::EVENT_PASSWORD_RESET         => 'Password Reset',
            self::EVENT_USER_REGISTRATION      => 'New User Registration',
        ];
    }

    /**
     * @param array<string, string> $vars
     */
    public static function send(
        string $eventKey,
        string $toEmail,
        string $toName,
        array $vars = [],
        ?int $relatedRecordId = null,
        int $createdBy = 0,
        ?string $replyToEmail = null,
        ?string $replyToName = null
    ): bool {
        EmailRepository::ensureSchema();

        $toEmail = strtolower(trim($toEmail));
        if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $template = EmailRepository::getTemplateByEvent($eventKey);
        if ($template === null) {
            $defaults = self::defaultEventTemplates();
            if (!isset($defaults[$eventKey])) {
                return false;
            }
            $template = $defaults[$eventKey];
        }

        $vars = self::normalizeVars($vars, $toName);
        $subject = self::replaceVars((string) $template['subject'], $vars);
        $inner   = self::replaceVars((string) $template['body_html'], $vars);
        $inner   = HtmlSanitizer::sanitizeEmailHtml($inner);
        $html    = self::wrapBranded($inner);
        $plain   = HtmlSanitizer::htmlToPlain($inner);

        $ok = MailService::sendEmail($toEmail, $toName, $subject, $html, $plain, $replyToEmail, $replyToName);

        EmailRepository::logEmailExtended([
            'recipient'         => $toEmail,
            'recipient_name'    => $toName,
            'subject'           => $subject,
            'message'           => $html,
            'status'            => $ok ? 'sent' : 'failed',
            'email_type'        => $eventKey,
            'related_record_id' => $relatedRecordId,
            'admin_id'          => $createdBy > 0 ? $createdBy : null,
            'error_msg'         => $ok ? null : (MailService::getLastError() ?? 'Send failed'),
        ]);

        return $ok;
    }

    /** @param array<string, mixed> $inquiry */
    public static function onContactSubmitted(array $inquiry): void
    {
        $id = (int) ($inquiry['id'] ?? 0);
        $name = (string) ($inquiry['full_name'] ?? $inquiry['name'] ?? 'Customer');
        $email = (string) ($inquiry['email'] ?? '');
        $phone = (string) ($inquiry['phone'] ?? 'Not provided');
        $type = (string) ($inquiry['inquiry_type'] ?? 'general');
        $message = (string) ($inquiry['message'] ?? '');
        $subjectLabel = self::inquiryTypeLabel($type);
        $ticketId = 'HOPE-' . str_pad((string) max(0, $id), 6, '0', STR_PAD_LEFT);
        $date = date('d M Y, H:i');

        $vars = [
            'customer_name'    => $name,
            'enquiry_subject'  => $subjectLabel,
            'enquiry_type'     => $subjectLabel,
            'ticket_id'        => $ticketId,
            'reference_number' => $ticketId,
            'submission_date'  => $date,
            'customer_email'   => $email,
            'customer_phone'   => $phone,
            'message'          => nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')),
            'enquiry_body'     => self::contactAutoReplyBodyHtml($type, $name, $ticketId, $date, $subjectLabel),
        ];

        // Instant branded auto-reply tailored to the enquiry type (Hostinger SMTP via MailService).
        self::sendContactAutoReply($email, $name, $type, $vars, $id);

        $config = MailConfigService::get();
        if (!empty($config['notifyOnContact'])) {
            $notifyEmail = trim((string) ($config['notifyEmail'] ?? ''));
            if ($notifyEmail !== '' && filter_var($notifyEmail, FILTER_VALIDATE_EMAIL)) {
                // Reply-To = customer so Hostinger webmail "Reply" goes straight to them.
                self::send(
                    self::EVENT_CONTACT_ADMIN,
                    $notifyEmail,
                    'Admin',
                    $vars,
                    $id,
                    0,
                    $email !== '' ? $email : null,
                    $name
                );
            }
        }
    }

    /**
     * Send an immediate customer confirmation email with Hope branding.
     *
     * @param array<string, string> $vars
     */
    public static function sendContactAutoReply(
        string $toEmail,
        string $toName,
        string $inquiryType,
        array $vars,
        ?int $relatedRecordId = null
    ): bool {
        $toEmail = strtolower(trim($toEmail));
        if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $subject = self::contactAutoReplySubject($inquiryType);
        $safeName = htmlspecialchars($toName, ENT_QUOTES, 'UTF-8');
        $body = (string) ($vars['enquiry_body'] ?? self::contactAutoReplyBodyHtml(
            $inquiryType,
            $toName,
            (string) ($vars['ticket_id'] ?? ''),
            (string) ($vars['submission_date'] ?? date('d M Y, H:i')),
            self::inquiryTypeLabel($inquiryType)
        ));

        $inner = <<<HTML
<p style="margin:0 0 16px;font-size:16px;">Hello {$safeName},</p>
{$body}
<p style="margin:24px 0 0;">Warm regards,<br><strong>Hope Architects Team</strong></p>
HTML;

        $html = self::wrapBranded($inner);
        $plain = HtmlSanitizer::htmlToPlain($inner);
        $ok = MailService::sendEmail($toEmail, $toName, $subject, $html, $plain);

        try {
            EmailRepository::ensureSchema();
            EmailRepository::logEmailExtended([
                'recipient'         => $toEmail,
                'recipient_name'    => $toName,
                'subject'           => $subject,
                'message'           => $html,
                'status'            => $ok ? 'sent' : 'failed',
                'email_type'        => self::EVENT_CONTACT_RECEIVED,
                'related_record_id' => $relatedRecordId,
                'admin_id'          => null,
                'error_msg'         => $ok ? null : (MailService::getLastError() ?? 'Send failed'),
            ]);
        } catch (Throwable $e) {
            error_log('Contact auto-reply log failed: ' . $e->getMessage());
        }

        return $ok;
    }

    private static function contactAutoReplySubject(string $type): string
    {
        return match ($type) {
            'new-build' => 'We received your new build enquiry — Hope Architects',
            'renovation' => 'We received your renovation enquiry — Hope Architects',
            'consultation' => 'Design consultation request received — Hope Architects',
            'partnership' => 'Partnership enquiry received — Hope Architects',
            'buying' => 'We received your buying enquiry — Hope Architects',
            'renting' => 'We received your renting enquiry — Hope Architects',
            'selling' => 'We received your selling enquiry — Hope Architects',
            default => 'We have received your enquiry — Hope Architects',
        };
    }

    private static function contactAutoReplyBodyHtml(
        string $type,
        string $name,
        string $ticketId,
        string $date,
        string $subjectLabel
    ): string {
        $safeTicket = htmlspecialchars($ticketId, ENT_QUOTES, 'UTF-8');
        $safeDate = htmlspecialchars($date, ENT_QUOTES, 'UTF-8');
        $safeLabel = htmlspecialchars($subjectLabel, ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars(siteContactPhone(), ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars(siteContactEmail(), ENT_QUOTES, 'UTF-8');

        $meta = <<<HTML
<p style="margin:0 0 18px;padding:14px 16px;background:#f3f8f8;border-radius:10px;border:1px solid #d5e6e8;">
  <strong>Enquiry:</strong> {$safeLabel}<br>
  <strong>Reference:</strong> {$safeTicket}<br>
  <strong>Received:</strong> {$safeDate}
</p>
HTML;

        $specific = match ($type) {
            'new-build' => <<<HTML
<p style="margin:0 0 14px;">Thank you for contacting <strong>Hope Architects</strong> about a <strong>new build project</strong>.</p>
<p style="margin:0 0 14px;">Our studio will review your brief and follow up with next steps on scope, concept direction, timeline, and how we can support design through construction.</p>
<p style="margin:0 0 14px;">If you already have drawings, a plot location, or a target budget, feel free to reply to this email with those details — it helps us prepare a sharper response.</p>
HTML,
            'renovation' => <<<HTML
<p style="margin:0 0 14px;">Thank you for contacting <strong>Hope Architects</strong> about a <strong>renovation / remodel</strong>.</p>
<p style="margin:0 0 14px;">We will assess your existing space needs and advise on design options, structural considerations, and a practical delivery plan.</p>
<p style="margin:0 0 14px;">Reply to this email with photos or a short description of the rooms/areas you want to transform if you have them ready.</p>
HTML,
            'consultation' => <<<HTML
<p style="margin:0 0 14px;">Thank you for requesting a <strong>design consultation</strong> with <strong>Hope Architects</strong>.</p>
<p style="margin:0 0 14px;">A member of our team will reach out to schedule a convenient time to discuss your vision, site conditions, and recommended next steps.</p>
<p style="margin:0 0 14px;">You can also call us directly on {$phone} if you prefer to book by phone.</p>
HTML,
            'partnership' => <<<HTML
<p style="margin:0 0 14px;">Thank you for your <strong>partnership</strong> enquiry to <strong>Hope Architects</strong>.</p>
<p style="margin:0 0 14px;">Our leadership team will review your proposal and respond with the appropriate next step for collaboration.</p>
<p style="margin:0 0 14px;">Please reply with any supporting company profile, portfolio, or proposal documents if available.</p>
HTML,
            'buying', 'selling', 'renting' => <<<HTML
<p style="margin:0 0 14px;">Thank you for contacting <strong>Hope Architects</strong>.</p>
<p style="margin:0 0 14px;">We primarily deliver architecture, interior design, and construction services. A consultant will review your message and advise on the best way we can assist with your project goals.</p>
HTML,
            default => <<<HTML
<p style="margin:0 0 14px;">Thank you for contacting <strong>Hope Architects</strong>.</p>
<p style="margin:0 0 14px;">We have received your message and one of our representatives will review it and get back to you as soon as possible.</p>
HTML,
        };

        return $specific . $meta . <<<HTML
<p style="margin:0 0 8px;">Need faster help? Reach us on <strong>{$phone}</strong> or <strong>{$email}</strong>.</p>
<p style="margin:0;">Our team can also reply manually from this same conversation thread — simply respond to this email.</p>
HTML;
    }


    /** @param array<string, mixed> $property */
    public static function onPropertySubmitted(array $property): void
    {
        $email = trim((string) ($property['ownerEmail'] ?? $property['owner_email'] ?? ''));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $id = (int) ($property['id'] ?? 0);
        $vars = [
            'owner_name'       => (string) ($property['ownerName'] ?? $property['owner_name'] ?? 'Property Owner'),
            'listing_id'       => 'LIST-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT),
            'reference_number' => 'LIST-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT),
            'submission_date'  => date('d M Y'),
            'property_title'   => (string) ($property['title'] ?? ''),
        ];

        self::send(self::EVENT_PROPERTY_SUBMITTED, $email, $vars['owner_name'], $vars, $id);
    }

    /**
     * @param array<string, mixed> $property
     */
    public static function onPropertyStatusChange(
        array $property,
        string $oldStatus,
        string $newStatus,
        int $adminId = 0,
        ?string $reason = null
    ): void {
        $email = trim((string) ($property['ownerEmail'] ?? $property['owner_email'] ?? ''));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $id = (int) ($property['id'] ?? 0);
        $ownerName = (string) ($property['ownerName'] ?? $property['owner_name'] ?? 'Property Owner');
        $title = (string) ($property['title'] ?? 'Your Property');
        $reason = $reason ?? (string) ($property['adminNotes'] ?? $property['admin_notes'] ?? 'Please contact support for details.');

        $baseVars = [
            'owner_name'       => $ownerName,
            'property_title'   => $title,
            'property_url'     => self::absoluteUrl(propertyDetailUrl($id)),
            'rejection_reason' => $reason,
            'status_reason'    => $reason,
            'approval_date'    => date('d M Y'),
            'listing_id'       => 'LIST-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT),
        ];

        $event = null;
        if ($newStatus === 'approved' && $oldStatus !== 'approved') {
            $event = $oldStatus === 'rejected'
                ? self::EVENT_PROPERTY_REACTIVATED
                : self::EVENT_PROPERTY_APPROVED;
        } elseif ($newStatus === 'rejected') {
            $event = stripos($reason, 'expir') !== false
                ? self::EVENT_PROPERTY_EXPIRED
                : self::EVENT_PROPERTY_REJECTED;
        } elseif ($newStatus === 'pending' && $oldStatus === 'approved') {
            $event = self::EVENT_PROPERTY_SUSPENDED;
        }

        if ($event !== null) {
            self::send($event, $email, $ownerName, $baseVars, $id, $adminId);
        }
    }

    public static function onNewsletterSubscribed(string $email, ?string $name = null): void
    {
        $displayName = $name !== null && $name !== '' ? $name : 'Subscriber';
        self::send(self::EVENT_NEWSLETTER_WELCOME, $email, $displayName, [
            'customer_name' => $displayName,
            'name'          => $displayName,
        ]);
    }

    public static function onPasswordResetRequested(string $email, string $name, string $resetLink): void
    {
        self::send(self::EVENT_PASSWORD_RESET, $email, $name, [
            'customer_name' => $name,
            'reset_link'    => $resetLink,
        ]);
    }

    public static function onUserRegistered(string $email, string $name): void
    {
        self::send(self::EVENT_USER_REGISTRATION, $email, $name, [
            'customer_name' => $name,
        ]);
    }

    public static function resendLog(int $logId): bool
    {
        $log = EmailRepository::getLogById($logId);
        if ($log === null) {
            throw new InvalidArgumentException('Log entry not found.');
        }

        $ok = MailService::sendEmail(
            (string) $log['recipient'],
            (string) ($log['recipient_name'] ?: 'Recipient'),
            (string) $log['subject'],
            (string) $log['message'],
            HtmlSanitizer::htmlToPlain((string) $log['message'])
        );

        EmailRepository::logEmailExtended([
            'recipient'         => (string) $log['recipient'],
            'recipient_name'    => (string) ($log['recipient_name'] ?? ''),
            'subject'           => (string) $log['subject'],
            'message'           => (string) $log['message'],
            'status'            => $ok ? 'sent' : 'failed',
            'email_type'        => (string) ($log['email_type'] ?? 'resend'),
            'related_record_id' => !empty($log['related_record_id']) ? (int) $log['related_record_id'] : null,
            'admin_id'          => (int) ($_SESSION['admin_id'] ?? 0) ?: null,
            'error_msg'         => $ok ? null : (MailService::getLastError() ?? 'Resend failed'),
        ]);

        return $ok;
    }

    public static function wrapBranded(string $innerHtml): string
    {
        $siteName = htmlspecialchars(siteName(), ENT_QUOTES, 'UTF-8');
        $contactEmail = htmlspecialchars(siteContactEmail(), ENT_QUOTES, 'UTF-8');
        $contactPhone = htmlspecialchars(siteContactPhone(), ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars(siteAddress(), ENT_QUOTES, 'UTF-8');
        $year = date('Y');

        $logoPath = publicAssetUrl('assets/images/hope_archites.png') ?? siteUrl('assets/images/hope_archites.png');
        $logoUrl = htmlspecialchars(self::absoluteUrl($logoPath), ENT_QUOTES, 'UTF-8');
        $websiteUrl = htmlspecialchars(self::absoluteUrl(siteUrl('')), ENT_QUOTES, 'UTF-8');
        $mailtoUrl = 'mailto:' . rawurlencode(siteContactEmail());
        $telDigits = preg_replace('/[^\d+]/', '', siteContactPhone()) ?? '';
        $telUrl = $telDigits !== '' ? 'tel:' . $telDigits : '#';

        $socialLinks = self::brandedSocialLinksHtml();

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{$siteName}</title>
</head>
<body style="margin:0;padding:0;background-color:#eef6f7;font-family:Arial,Helvetica,sans-serif;color:#1a2e22;-webkit-text-size-adjust:100%;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#eef6f7;">
    <tr>
      <td align="center" style="padding:28px 16px;">
        <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;border-collapse:collapse;">
          <tr>
            <td align="center" style="background-color:#0b4e56;padding:28px 32px 24px;border-radius:14px 14px 0 0;">
              <a href="{$websiteUrl}" style="text-decoration:none;display:inline-block;">
                <img src="{$logoUrl}" alt="{$siteName}" width="168" style="display:block;margin:0 auto;max-width:168px;width:168px;height:auto;border:0;outline:none;">
              </a>
              <p style="margin:14px 0 0;font-size:11px;letter-spacing:2.5px;text-transform:uppercase;color:#feee90;font-weight:600;">Architecture &amp; Interior Design</p>
            </td>
          </tr>
          <tr>
            <td style="height:4px;background:linear-gradient(90deg,#feee90,#fff6b8,#e0d070);font-size:0;line-height:0;">&nbsp;</td>
          </tr>
          <tr>
            <td style="background-color:#ffffff;padding:36px 36px 32px;font-size:15px;line-height:1.75;color:#1a2e22;">
              {$innerHtml}
            </td>
          </tr>
          <tr>
            <td style="background-color:#0b4e56;padding:28px 32px;border-radius:0 0 14px 14px;text-align:center;">
              <p style="margin:0 0 6px;font-family:Georgia,'Times New Roman',serif;font-size:18px;font-weight:700;color:#ffffff;">{$siteName}</p>
              <p style="margin:0 0 14px;font-size:12px;color:#feee90;letter-spacing:1px;text-transform:uppercase;">Architecture · Construction · Interiors</p>
              <p style="margin:0 0 8px;font-size:13px;color:rgba(255,255,255,0.72);line-height:1.6;">{$address}</p>
              <p style="margin:0 0 8px;font-size:13px;color:rgba(255,255,255,0.82);">
                <a href="{$mailtoUrl}" style="color:#fff6b8;text-decoration:none;font-weight:600;">{$contactEmail}</a>
                &nbsp;&middot;&nbsp;
                <a href="{$telUrl}" style="color:#fff6b8;text-decoration:none;font-weight:600;">{$contactPhone}</a>
              </p>
              <p style="margin:12px 0 0;font-size:13px;">
                <a href="{$websiteUrl}" style="color:#feee90;text-decoration:none;font-weight:600;">Visit hopearchitects.ng</a>
              </p>
              {$socialLinks}
              <p style="margin:20px 0 0;font-size:11px;color:rgba(255,255,255,0.45);line-height:1.6;">
                &copy; {$year} {$siteName}. Official studio communication.<br>
                You can reply to this email and our team will continue the conversation.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;
    }

    private static function brandedSocialLinksHtml(): string
    {
        $links = [];
        $networks = [
            'facebook'  => 'Facebook',
            'instagram' => 'Instagram',
            'tiktok'    => 'TikTok',
            'twitter'   => 'Twitter',
        ];

        foreach ($networks as $key => $label) {
            $url = trim(siteSocial($key));
            if ($url === '' || !preg_match('#^https?://#i', $url)) {
                continue;
            }
            $safeUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
            $safeLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
            $links[] = '<a href="' . $safeUrl . '" style="color:#feee90;text-decoration:none;margin:0 8px;font-size:12px;font-weight:600;">' . $safeLabel . '</a>';
        }

        if ($links === []) {
            return '';
        }

        return '<p style="margin:14px 0 0;font-size:12px;">' . implode('', $links) . '</p>';
    }

    /** @param array<string, string> $vars */
    private static function replaceVars(string $text, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $text = str_replace(
                ['{{' . $key . '}}', '{{' . strtoupper($key) . '}}'],
                $value,
                $text
            );
        }

        return $text;
    }

    /** @param array<string, string> $vars @return array<string, string> */
    private static function normalizeVars(array $vars, string $toName): array
    {
        $vars['name'] = $vars['name'] ?? htmlspecialchars($toName, ENT_QUOTES, 'UTF-8');
        $vars['customer_name'] = $vars['customer_name'] ?? $vars['name'];

        foreach ($vars as $k => $v) {
            // Keep HTML/body/message/url content unescaped for template injection.
            if (
                str_contains($k, 'message')
                || str_contains($k, 'url')
                || str_contains($k, 'link')
                || str_contains($k, 'body')
                || str_contains($k, 'html')
            ) {
                continue;
            }
            $vars[$k] = htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
        }

        return $vars;
    }

    private static function inquiryTypeLabel(string $type): string
    {
        $labels = [
            'general'       => 'General Inquiry',
            'new-build'     => 'New Build Project',
            'renovation'    => 'Renovation / Remodel',
            'consultation'  => 'Design Consultation',
            'partnership'   => 'Partnership Opportunity',
            'buying'        => 'Buying Enquiry',
            'renting'       => 'Renting Enquiry',
            'selling'       => 'Selling Enquiry',
        ];

        return $labels[$type] ?? ucwords(str_replace('-', ' ', $type));
    }

    private static function absoluteUrl(string $path): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return $scheme . '://' . $host . $path;
    }
}
