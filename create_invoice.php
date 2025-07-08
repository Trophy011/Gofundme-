<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['amount'])) {
    $amount = floatval($_POST['amount']);
    if ($amount <= 0) {
        exit("❌ Invalid amount entered.");
    }

    $apiKey = 'BCMM6FS-FKY4Z4Y-N1XPCKY-BQ43YP1';

    $data = [
        'price_amount' => $amount,
        'price_currency' => 'usd',
        'pay_currency' => 'btc',
        'order_description' => 'Donation to Orphans',
        'success_url' => 'https://thankyou.page/',
        'cancel_url' => 'https://yoursite.com/'
    ];

    $ch = curl_init('https://api.nowpayments.io/v1/invoice');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'x-api-key: ' . $apiKey,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "❌ cURL error: " . curl_error($ch);
        curl_close($ch);
        exit;
    }

    curl_close($ch);
    $result = json_decode($response, true);

    if (isset($result['invoice_url'])) {
        header('Location: ' . $result['invoice_url']);
        exit;
    } else {
        echo "❌ Error creating invoice: <pre>" . htmlspecialchars($response) . "</pre>";
    }
} else {
    echo "❌ Invalid request.";
}
?>
