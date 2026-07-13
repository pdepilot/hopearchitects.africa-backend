<?php

declare(strict_types=1);

/**
 * Local SMTP credentials — do not commit to git.
 *
 * Hostinger mailbox: agent@biverroyaltyhomesltd.com
 * hPanel → Emails → Manage → Configuration → SMTP settings
 */
define('MAIL_PROVIDER', 'hostinger');
define('MAIL_USE_SMTP', true);
define('MAIL_SMTP_HOST', 'smtp.hostinger.com');
define('MAIL_SMTP_PORT', 465);
define('MAIL_SMTP_ENCRYPTION', 'ssl');
define('MAIL_SMTP_USERNAME', 'agent@biverroyaltyhomesltd.com');
define('MAIL_SMTP_PASSWORD', 'Echefu10@321@');
define('MAIL_SMTP_TIMEOUT', 30);

define('MAIL_FROM_EMAIL', 'agent@biverroyaltyhomesltd.com');
define('MAIL_FROM_NAME', 'Biver Royalty Homes');
define('MAIL_REPLY_TO', 'agent@biverroyaltyhomesltd.com');
define('MAIL_NOTIFY_EMAIL', 'agent@biverroyaltyhomesltd.com');
define('MAIL_NOTIFY_ON_CONTACT', true);
