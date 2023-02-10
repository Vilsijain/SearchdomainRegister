<?php

// First API request to search for domain availability
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://sandbox.cosmotown.com/v1/reseller/searchdomains',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => '{
  "domains": [
    "domainRerfgvgister1.net",
    "domainRegfgvrister2.org",
    "domainRegisfrgvfteration3.com"
  ]
}',
  CURLOPT_HTTPHEADER => array(
    'X-API-TOKEN: <API Key>'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
$response_data = json_decode($response, true);

// Check if any of the domains are available
$available_domains = array();
foreach ($response_data['domains'] as $domain) {
  if ($domain['status'] == 'available') {
    $available_domains[] = $domain['domain'];
  }
}
// If any domains are available, register them with the second API
if (count($available_domains) > 0) {
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://sandbox.cosmotown.com/v1/reseller/registerdomains',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
    "coupon_id": "test-coupon",
    "items": [
      ' . implode(',', array_map(function ($domain) {
      return '{"name": "' . $domain . '", "years": 1}';
    }, $available_domains)) . '
    ]
  }',
    CURLOPT_HTTPHEADER => array(
      'X-API-TOKEN: <API Key>'
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  var_dump($response);
} else {
  echo 'No domains available for registration';
}
