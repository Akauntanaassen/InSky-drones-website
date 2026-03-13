<?php
// config.php — handles submissions from the "UAV Quick Configuration" form

// 1. Honeypot check (spam trap)
if (!empty($_POST['company_trap'])) {
    // Bot detected — silently exit or redirect to homepage
    header("Location: index.html"); 
    exit;
}

// 2. Sanitize inputs
function clean_input($field) {
    return htmlspecialchars(trim($_POST[$field] ?? ''), ENT_QUOTES, 'UTF-8');
}

$company    = clean_input('company');
$contact    = clean_input('contact');
$email      = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$phone      = clean_input('phone');
$application= clean_input('application');
$payload    = clean_input('payload');
$endurance  = clean_input('endurance');
$range      = clean_input('range');
$obstacle   = clean_input('obstacle');
$propulsion = clean_input('propulsion');
$payload_kit= isset($_POST['payload_kit']) ? implode(", ", $_POST['payload_kit']) : '';
$training   = clean_input('training');
$support    = clean_input('support');
$regulatory = clean_input('regulatory');
$comments   = clean_input('comments');

// 3. Build email
$to      = "info@insky.aero";  // your inbox
$subject = "New UAV Configuration Request from $company";
$message = "
Company/Name: $company
Contact Person: $contact
Email: $email
Phone: $phone

Primary Application: $application

--- Preferences ---
Payload: $payload
Endurance: $endurance
Range: $range
Obstacle Avoidance: $obstacle
Propulsion: $propulsion
Payload/Mission Kit: $payload_kit

--- Optional ---
Training Required: $training
Support Level: $support
Certifications/Regulatory: $regulatory

--- Notes ---
$comments
";

$headers = "From: no-reply@insky.aero\r\n";
$headers .= "Reply-To: $email\r\n";

// 4. Send email
if (mail($to, $subject, $message, $headers)) {
    header("Location: index.html?cfg=1"); // success
} else {
    header("Location: index.html?cfg=0"); // error
}
exit;
?>
