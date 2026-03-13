<?php
/* -----------------------------------------
   InSky contact handler (minimal + safe-ish)
   Place this file next to index.html
------------------------------------------ */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method Not Allowed');
}

/* === CONFIG === */
$TO          = 'info@insky.aero';      // destination inbox
$FROM_EMAIL  = 'no-reply@insky.aero';  // must be a real mailbox on your host
$SITE_NAME   = 'InSky Website';
$REDIRECT_OK = './index.html?sent=1#contact';  // adjust if your file name/path differs
/* ============= */

function clean($s) {
  // trim + drop control chars
  $s = is_string($s) ? trim($s) : '';
  return preg_replace('/[^\P{C}\n\r\t]+/u', '', $s);
}

$name      = clean($_POST['name'] ?? '');
$email     = clean($_POST['email'] ?? '');
$message   = clean($_POST['message'] ?? '');
$honeypot  = clean($_POST['company'] ?? '');  // hidden field; must stay empty

// Basic validation
$errors = [];
if ($honeypot !== '')                          $errors[] = 'Spam detected.';
if ($name === '' || mb_strlen($name) > 120)    $errors[] = 'Invalid name.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))$errors[] = 'Invalid email.';
if ($message === '' || mb_strlen($message) > 5000) $errors[] = 'Invalid message.';

if ($errors) {
  http_response_code(422);
  echo "There was a problem with your submission.";
  exit;
}

// Build email
$subject = "New inquiry from {$SITE_NAME}";
$ip      = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$ua      = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

$body = "New message from {$SITE_NAME}\n\n"
      . "Name:  {$name}\n"
      . "Email: {$email}\n"
      . "IP:    {$ip}\n"
      . "Agent: {$ua}\n\n"
      . "Message:\n{$message}\n";

$headers = [
  "From: {$SITE_NAME} <{$FROM_EMAIL}>",
  "Reply-To: {$email}",
  "MIME-Version: 1.0",
  "Content-Type: text/plain; charset=UTF-8",
  "X-Mailer: PHP/" . phpversion()
];

// Send
$ok = @mail($TO, $subject, $body, implode("\r\n", $headers));

if ($ok) {
  // Redirect back to the contact area with a success flag
  header("Location: {$REDIRECT_OK}", true, 303);
  exit;
} else {
  http_response_code(500);
  echo "Sorry, we couldn't send your message. Please email us at {$TO}.";
  exit;
}
