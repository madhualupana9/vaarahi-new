<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: application/json');

// 1. Validate input
$required = ['name', 'phone'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['status' => 'error', 'message' => "$field is required"]);
        exit;
    }
}

// 2. Prepare data
$data = [
    'name' => $_POST['name'],
    'phone' => $_POST['phone'],
    'service' => $_POST['service'] ?? '',
    'message' => $_POST['message'] ?? '',
];

// 3. Send SMS using Twilio (using file_get_contents instead of cURL)
try {
    if (file_exists('config.php')) {
        include_once 'config.php';
    }
    
    $account_sid = defined('TWILIO_ACCOUNT_SID') ? TWILIO_ACCOUNT_SID : '';
    $auth_token = defined('TWILIO_AUTH_TOKEN') ? TWILIO_AUTH_TOKEN : '';
    $twilio_number = defined('TWILIO_NUMBER') ? TWILIO_NUMBER : '';

    if (empty($account_sid) || empty($auth_token)) {
        throw new Exception("Twilio credentials missing");
    }
    
    // Format phone number
    $phone_number = $_POST['phone'];
    if (substr($phone_number, 0, 1) !== '+') {
        $phone_number = '+91' . $phone_number;
    }
    
    // Create message body
    $message_body = "Dear " . $_POST['name'] . ". We'll contact you shortly to confirm your appointment for " . 
                   ($_POST['service'] ?? 'your service') . ". Thank you for choosing Vaarahi Diagnostics!";
    
    // Direct API call to Twilio using file_get_contents
    $url = "https://api.twilio.com/2010-04-01/Accounts/{$account_sid}/Messages.json";
    
    $post_data = http_build_query([
        'To' => $phone_number,
        'From' => $twilio_number,
        'Body' => $message_body
    ]);
    
    // Create context for HTTP request - FIXED HEADER FORMAT
    $auth_header = 'Authorization: Basic ' . base64_encode("{$account_sid}:{$auth_token}");
    $content_header = 'Content-Type: application/x-www-form-urlencoded';
    $length_header = 'Content-Length: ' . strlen($post_data);
    
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => implode("\r\n", [$auth_header, $content_header, $length_header]),
            'content' => $post_data,
            'ignore_errors' => true // To get response even if HTTP error
        ]
    ];
    
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    
    // Check if request was successful
    if ($response !== FALSE) {
        $response_data = json_decode($response, true);
        if (isset($response_data['sid'])) {
            error_log("SMS sent to " . $phone_number . ", SID: " . $response_data['sid']);
        } else {
            error_log("Twilio API Error: " . $response);
        }
    } else {
        error_log("Failed to connect to Twilio API");
        // Get more error details
        if (isset($http_response_header)) {
            error_log("HTTP response headers: " . print_r($http_response_header, true));
        }
    }
    
} catch (Exception $e) {
    error_log("SMS Error: " . $e->getMessage());
}

// 4. Send to Google Script (your existing functionality)
$googleScriptUrl = defined('GOOGLE_SCRIPT_URL') ? GOOGLE_SCRIPT_URL : '';

if (empty($googleScriptUrl)) {
    echo json_encode(['status' => 'error', 'message' => 'Google Script URL not configured']);
    exit;
}

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($googleScriptUrl, false, $context);

if ($result === FALSE) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to connect to Google Apps Script']);
    exit;
}

// 5. Return success response
echo json_encode(['status' => 'success', 'message' => 'Form submitted successfully and SMS sent']);
?>