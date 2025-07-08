<?php
// Get amount from form
$amount = $_POST['amount'];
$apiKey = 'BCMM6FS-FKY4Z4Y-N1XPCKY-BQ43YP1'; // Your NowPayments API Key

// Prepare invoice request
$data = [
    'price_amount' => $amount,
    'price_currency' => 'usd',
    'pay_currency' => 'btc',
    'order_description' => 'Donation to Orphans',
    'success_url' => 'https://thankyou.page/',  // You can customize this
    'cancel_url' => 'https://yoursite.com/'      // You can customize this
];

// Call NowPayments API
$ch = curl_init('https://api.nowpayments.io/v1/invoice');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'x-api-key: ' . $apiKey,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

// Redirect to invoice if successful
if (isset($result['invoice_url'])) {
    header('Location: ' . $result['invoice_url']);
    exit;
} else {
    echo "❌ Error creating invoice. Please try again later.";
}
?>
