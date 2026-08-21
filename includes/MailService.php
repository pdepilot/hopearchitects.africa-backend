<?php

/**
 * Send transactional email via PHPMailer (SMTP) with PHP mail() fallback.
 */

declare(strict_types=1);

use PHPMailer\PHPMailer\Exception as PhpMailerException;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/MailConfigService.php';
require_once __DIR__ . '/AutomatedEmailService.php';

class MailService
{
    /** @var string|null Last error message for admin feedback */
    private static ?string $lastError = null;

    public static function getLastError(): ?string
    {
        return self::$lastError;
    }

    /** @return array<string, mixed> */
    public static function getStatus(): array
    {
        return MailConfigService::getPublic();
    }

    /**
     * Send HTML reply email to a customer (admin inquiry reply).
     */
    public static function sendInquiryReply(
        string $toEmail,
        string $toName,
        string $subject,
        string $body,
        string $originalMessage
    ): bool {
        self::$lastError = null;

        $htmlBody  = self::buildReplyTemplate($toName, $body, $originalMessage);
        $plainBody = self::htmlToPlain($body) . "\n\n---\nYour original message:\n" . self::htmlToPlain($originalMessage);

        return self::sendEmail($toEmail, $toName, $subject, $htmlBody, $plainBody);
    }

    /**
     * Notify site admin when a new contact form inquiry is received.
     *
     * @param array{full_name:string,email:string,phone?:string,inquiry_type:string,message:string,id?:int} $inquiry
     */
    public static function sendNewInquiryNotification(array $inquiry): bool
    {
        self::$lastError = null;

        $config = MailConfigService::get();
        if (empty($config['notifyOnContact'])) {
            return false;
        }

        $notifyEmail = trim((string) ($config['notifyEmail'] ?? ''));
        if ($notifyEmail === '' || !filter_var($notifyEmail, FILTER_VALIDATE_EMAIL)) {
            self::$lastError = 'Notification email is not configured.';
            return false;
        }

        $name    = (string) ($inquiry['full_name'] ?? 'Visitor');
        $email   = (string) ($inquiry['email'] ?? '');
        $phone   = (string) ($inquiry['phone'] ?? 'Not provided');
        $type    = (string) ($inquiry['inquiry_type'] ?? 'general');
        $message = (string) ($inquiry['message'] ?? '');
        $id      = (int) ($inquiry['id'] ?? 0);

        $subject = 'New contact inquiry — ' . $name . ' (' . $type . ')';
        $htmlBody = self::buildInquiryNotificationTemplate($name, $email, $phone, $type, $message, $id);
        $plainBody = "New contact inquiry\n\nName: {$name}\nEmail: {$email}\nPhone: {$phone}\nType: {$type}\n\nMessage:\n{$message}";

        return self::sendEmail($notifyEmail, 'Hope Architects Admin', $subject, $htmlBody, $plainBody);
    }

    /**
     * Send a test message to verify SMTP settings.
     */
    public static function sendTestEmail(string $toEmail, string $toName = 'Admin'): bool
    {
        self::$lastError = null;

        if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            self::$lastError = 'Invalid test email address.';
            return false;
        }

        $config = MailConfigService::getPublic();
        $subject = 'Hope Architects — Mail Test';
        $sentAt = date('Y-m-d H:i:s');
        $providerLabel = htmlspecialchars((string) $config['provider'], ENT_QUOTES, 'UTF-8');
        $inner = <<<HTML
<p style="margin:0 0 16px;font-size:16px;">Hello,</p>
<p style="margin:0 0 16px;">This is a test email from <strong>Hope Architects</strong> confirming that your mail configuration is working correctly.</p>
<p style="margin:0 0 16px;">Provider: <strong>{$providerLabel}</strong></p>
<p style="margin:0;font-size:13px;color:#6c5e4e;">Sent at {$sentAt}</p>
HTML;
        $htmlBody = AutomatedEmailService::wrapBranded($inner);

        $plainBody = 'Mail configuration OK. This is a test email from Hope Architects.';

        return self::sendEmail($toEmail, $toName, $subject, $htmlBody, $plainBody);
    }

    /**
     * @param string|null $replyToEmail Optional Reply-To override (e.g. customer email on admin alerts)
     * @param string|null $replyToName  Optional Reply-To display name
     */
    public static function sendEmail(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlBody,
        string $plainBody,
        ?string $replyToEmail = null,
        ?string $replyToName = null
    ): bool {
        self::$lastError = null;

        $config = MailConfigService::get();

        if (!empty($config['useSmtp'])) {
            if (!MailConfigService::isReady()) {
                self::$lastError = 'SMTP is not fully configured. Set credentials in Admin → Settings → Email.';
                return self::sendViaPhpMail($toEmail, $subject, $htmlBody, $plainBody, $config, $replyToEmail, $replyToName);
            }

            return self::sendViaSmtp($toEmail, $toName, $subject, $htmlBody, $plainBody, $config, $replyToEmail, $replyToName);
        }

        return self::sendViaPhpMail($toEmail, $subject, $htmlBody, $plainBody, $config, $replyToEmail, $replyToName);
    }

    /** @param array<string, mixed> $config */
    private static function sendViaSmtp(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlBody,
        string $plainBody,
        array $config,
        ?string $replyToEmail = null,
        ?string $replyToName = null
    ): bool {
        $autoload = dirname(__DIR__) . '/vendor/autoload.php';

        if (!is_readable($autoload)) {
            self::$lastError = 'Composer dependencies missing. Run: composer install';
            error_log('MailService: vendor/autoload.php not found');
            return self::sendViaPhpMail($toEmail, $subject, $htmlBody, $plainBody, $config);
        }

        require_once $autoload;

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host     = (string) $config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = (string) $config['username'];
            $mail->Password = (string) $config['password'];
            $mail->Port     = (int) $config['port'];
            $mail->Timeout  = (int) $config['timeout'];
            $mail->CharSet  = PHPMailer::CHARSET_UTF8;

            $encryption = strtolower((string) $config['encryption']);
            if ($encryption === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($encryption === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                $mail->SMTPSecure  = '';
                $mail->SMTPAutoTLS = false;
            }

            // Shared hosting (e.g. Hostinger) occasionally needs relaxed SSL verification.
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => true,
                    'verify_peer_name'  => true,
                    'allow_self_signed' => false,
                ],
            ];

            $fromEmail = trim((string) $config['fromEmail']);
            $fromName  = (string) $config['fromName'];
            $replyTo   = trim((string) ($replyToEmail ?? $config['replyTo'] ?? ''));
            $replyName = trim((string) ($replyToName ?? $fromName));
            $username  = trim((string) $config['username']);

            if ($fromEmail === '') {
                $fromEmail = $username;
            }

            if ($fromEmail === '' || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
                throw new PhpMailerException('From email is not configured.');
            }

            if ($replyTo === '' || !filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
                $replyTo = $fromEmail;
                $replyName = $fromName;
            }

            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($toEmail, $toName);
            $mail->addReplyTo($replyTo, $replyName !== '' ? $replyName : $fromName);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = $plainBody;

            $mail->send();
            return true;
        } catch (PhpMailerException $e) {
            self::$lastError = self::formatSmtpError($mail->ErrorInfo ?: $e->getMessage(), $config);
            error_log('MailService SMTP error: ' . self::$lastError);
            return false;
        }
    }

    /** @param array<string, mixed> $config */
    private static function formatSmtpError(string $error, array $config): string
    {
        $error = trim($error);
        if (stripos($error, 'authenticate') === false && stripos($error, 'authentication') === false) {
            return $error;
        }

        $provider = (string) ($config['provider'] ?? 'custom');
        $username = (string) ($config['username'] ?? '');
        $fromEmail = (string) ($config['fromEmail'] ?? '');

        $hints = ['SMTP authentication failed.'];

        if ($provider === 'gmail') {
            $hints[] = 'Gmail requires a 16-character App Password (not your normal Gmail password).';
            $hints[] = 'Create one at: https://myaccount.google.com/apppasswords';
            $hints[] = 'SMTP username must be your full Gmail address.';
            if ($fromEmail !== '' && strcasecmp($fromEmail, $username) !== 0) {
                $hints[] = 'From email should match the Gmail account, unless "Send mail as" is configured in Gmail.';
            }
        } elseif ($provider === 'hostinger' || str_contains((string) ($config['host'] ?? ''), 'hostinger')) {
            $hints[] = 'Use your full Hostinger mailbox email as SMTP username (e.g. agent@yourdomain.com).';
            $hints[] = 'Use the mailbox password from hPanel → Emails.';
            $hints[] = 'Host: smtp.hostinger.com, Port: 465, Encryption: SSL.';
        } else {
            $hints[] = 'Verify SMTP username, password, host, port, and encryption match your email provider.';
            if ($fromEmail !== '' && $username !== '' && strcasecmp($fromEmail, $username) !== 0) {
                $hints[] = 'From email and SMTP username should usually be the same mailbox.';
            }
        }

        return $error . ' — ' . implode(' ', $hints);
    }

    /** @param array<string, mixed> $config */
    private static function sendViaPhpMail(
        string $toEmail,
        string $subject,
        string $htmlBody,
        string $plainBody,
        array $config,
        ?string $replyToEmail = null,
        ?string $replyToName = null
    ): bool {
        $fromEmail   = (string) ($config['fromEmail'] ?? '');
        $fromName    = (string) ($config['fromName'] ?? 'Hope Architects');
        $replyTo     = trim((string) ($replyToEmail ?? $config['replyTo'] ?? $fromEmail));
        if ($replyTo === '' || !filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $replyTo = $fromEmail;
        }
        $replyName   = trim((string) ($replyToName ?? $fromName));
        if ($replyName === '') {
            $replyName = $fromName;
        }
        $safeSubject = self::escapeHeader($subject);
        $boundary    = 'bre_' . bin2hex(random_bytes(8));

        $headers = [
            'MIME-Version: 1.0',
            'From: ' . self::escapeHeader($fromName) . ' <' . $fromEmail . '>',
            'Reply-To: ' . self::escapeHeader($replyName) . ' <' . $replyTo . '>',
            'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
        ];

        $message  = "--{$boundary}\r\nContent-Type: text/plain; charset=UTF-8\r\n\r\n{$plainBody}\r\n\r\n";
        $message .= "--{$boundary}\r\nContent-Type: text/html; charset=UTF-8\r\n\r\n{$htmlBody}\r\n\r\n";
        $message .= "--{$boundary}--";

        $sent = @mail($toEmail, $safeSubject, $message, implode("\r\n", $headers));

        if (!$sent) {
            self::$lastError = self::$lastError ?? 'PHP mail() failed. Configure SMTP in Admin → Settings → Email.';
        }

        return $sent;
    }

    private static function buildReplyTemplate(
        string $customerName,
        string $replyBody,
        string $originalMessage
    ): string {
        $name     = htmlspecialchars($customerName, ENT_QUOTES, 'UTF-8');
        $reply    = nl2br(htmlspecialchars($replyBody, ENT_QUOTES, 'UTF-8'));
        $original = nl2br(htmlspecialchars($originalMessage, ENT_QUOTES, 'UTF-8'));

        $inner = <<<HTML
<p style="margin:0 0 16px;font-size:16px;">Dear {$name},</p>
<div style="line-height:1.75;margin:0 0 24px;">{$reply}</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;margin-top:8px;">
  <tr>
    <td style="padding:16px 18px;background-color:#f9f7f2;border-radius:10px;border:1px solid #e9e5dc;">
      <p style="margin:0 0 8px;font-size:12px;color:#6c5e4e;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">Your original message</p>
      <p style="margin:0;font-size:13px;color:#6c5e4e;line-height:1.6;">{$original}</p>
    </td>
  </tr>
</table>
<p style="margin:24px 0 0;font-size:14px;color:#2c2418;">Warm regards,<br><strong>Hope Architects Team</strong></p>
HTML;

        return AutomatedEmailService::wrapBranded($inner);
    }

    private static function buildInquiryNotificationTemplate(
        string $name,
        string $email,
        string $phone,
        string $type,
        string $message,
        int $id
    ): string {
        $eName    = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $eEmail   = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $ePhone   = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
        $eType    = htmlspecialchars(ucwords(str_replace('-', ' ', $type)), ENT_QUOTES, 'UTF-8');
        $eMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
        $adminUrl = htmlspecialchars(self::adminContactUrl($id), ENT_QUOTES, 'UTF-8');

        $inner = <<<HTML
<h2 style="margin:0 0 8px;font-family:Georgia,serif;color:#0b4e56;font-size:22px;">New Contact Enquiry</h2>
<p style="margin:0 0 18px;color:#5a6b60;">A visitor submitted the contact form on your website.</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;font-size:14px;">
  <tr><td style="padding:8px 0;color:#5a6b60;width:120px;">Name</td><td style="padding:8px 0;"><strong>{$eName}</strong></td></tr>
  <tr><td style="padding:8px 0;color:#5a6b60;">Email</td><td style="padding:8px 0;"><a href="mailto:{$eEmail}" style="color:#0b4e56;">{$eEmail}</a></td></tr>
  <tr><td style="padding:8px 0;color:#5a6b60;">Phone</td><td style="padding:8px 0;">{$ePhone}</td></tr>
  <tr><td style="padding:8px 0;color:#5a6b60;">Type</td><td style="padding:8px 0;">{$eType}</td></tr>
</table>
<p style="margin:20px 0 8px;font-weight:bold;color:#0b4e56;">Message</p>
<p style="margin:0 0 22px;background:#f3f8f8;padding:14px;border-radius:8px;line-height:1.6;border:1px solid #d5e6e8;">{$eMessage}</p>
<p style="margin:0;"><a href="{$adminUrl}" style="display:inline-block;background:#feee90;color:#0b4e56;padding:12px 22px;border-radius:999px;text-decoration:none;font-weight:bold;">Open in Admin &amp; Reply</a></p>
HTML;

        return AutomatedEmailService::wrapBranded($inner);
    }

    private static function adminContactUrl(int $id): string
    {
        require_once __DIR__ . '/site_paths.php';

        $base = siteRootPath();
        $path = ($base !== '' ? $base : '') . '/admin/admin-contact.php';

        return $id > 0 ? $path . '?id=' . $id : $path;
    }

    private static function htmlToPlain(string $text): string
    {
        return strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $text));
    }

    private static function escapeHeader(string $value): string
    {
        return str_replace(["\r", "\n"], '', $value);
    }
}
