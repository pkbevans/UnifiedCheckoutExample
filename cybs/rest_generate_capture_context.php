<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/unifiedCheckout/PeRestLib/RestRequest.php';

$targetOrigin = "https://" . (strstr($_SERVER['HTTP_HOST'],LOCALHOST_TARGET_ORIGIN)?LOCALHOST_TARGET_ORIGIN:PRODUCTION_TARGET_ORIGIN);
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
            "totalAmount" => $_REQUEST['amount'],
            "currency" =>  $_REQUEST['currency']
        ],
        "billTo" => [
            // Default values for testing purposes to avoid having to type in every time
            "firstName" => "Harry",
            "lastName" => "Bellafonte",
            "buildingNumber" => "",
            "address1" => "The Big House",
            "address2" => "Inkberrow",
            "locality" => "Worcestershire",
            "administrativeArea" => "",
            "postalCode" => "WR5 8FP",
            "country" => "GB",
            "email" => $_REQUEST['email']
        ]
    ]
];
$requestBody = json_encode($request);

$result = ProcessRequest(MID, API_UNIFIED_CHECKOUT_CAPTURE_CONTEXTS, METHOD_POST, $requestBody, CHILD_MID, AUTH_TYPE_SIGNATURE );
// echo "<PRE>CAPTURE CONTEXT RESPONSE: " . json_encode($result, JSON_PRETTY_PRINT) . "</PRE>";
$captureContext="";
$clientLibrary="";
if($result->responseCode == "201"){
    $captureContext=$result->rawResponse;
    $splitArr = explode(".", $captureContext, 5);
    $payloadB64 = $splitArr[1];
    $payload=base64_decode($payloadB64);
    $payloadJ=json_decode($payload);
    // echo "<BR>CaptureContext=".$captureContext."<BR>";
    $clientLibrary=$payloadJ->ctx[0]->data->clientLibrary;
    // echo "<BR>Clientlibrary=".$clientLibrary."<BR>";
}else{
    echo "<PRE>CAPTURE CONTEXT ERROR: " . json_encode($result, JSON_PRETTY_PRINT) . "</PRE>";
}
