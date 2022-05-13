<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/unifiedCheckout/PeRestLib/RestRequest.php';

if($_REQUEST['local'] == "true"){
    $targetOrigin = "https://site.test";
}else{
    $targetOrigin = TARGET_ORIGIN;
}

$request = [
    "targetOrigins" => [
        $targetOrigin
    ],
    "clientVersion" => "0.8",
    "allowedCardNetworks" => ["VISA", "MASTERCARD"],
    "allowedPaymentTypes" => ["PANENTRY", "SRC"],
    "country" => "GB",
    "locale" => "en_gb",
    "captureMandate" => [
        "billingType" => "FULL",
        "requestEmail" => true,
        "requestPhone" => false,
        "requestShipping" => false,
        "shipToCountries" => ["GB"],
        "showAcceptedNetworkIcons" => true,
    ],
    "orderInformation" => [
        "amountDetails" => [
            "totalAmount" => "19.19",
            "currency" => "GBP"
        ],
        "billTo" => [
            "firstName" => "Paul",
            "lastName" => "Evans",
            "buildingNumber" => "",
            "address1" => "Hathaway House",
            "address2" => "Inkberrow",
            "locality" => "Worcestershire",
            "administrativeArea" => "",
            "postalCode" => "WR7 4DZ",
            "country" => "GB",
            "email" => "pkbevans@gmail.com"
        ]
    ]
];
$requestBody = json_encode($request);

$result = ProcessRequest(PORTFOLIO, API_UNIFIED_CHECKOUT_CAPTURE_CONTEXTS, METHOD_POST, $requestBody, MID, AUTH_TYPE_SIGNATURE );
$captureContext="";
$clientLibrary="";
if($result->responseCode == "201"){
    $captureContext=$result->rawResponse;
    $splitArr = explode(".", $captureContext, 5);
    $payloadB64 = $splitArr[1];
    $payload=base64_decode($payloadB64);
    $payloadJ=json_decode($payload);
    $clientLibrary=$payloadJ->ctx[0]->data->clientLibrary;
    // echo "<BR>CaptureContext=".$captureContext."<BR>";
    // echo "<BR>Clientlibrary=".$clientLibrary."<BR>";
}else{
    echo "<PRE>CAPTURE CONTEXT ERROR: " . json_encode($result, JSON_PRETTY_PRINT) . "</PRE>";
}
