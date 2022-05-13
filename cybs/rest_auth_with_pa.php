<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/unifiedCheckout/PeRestLib/RestRequest.php';

$incoming = json_decode(file_get_contents('php://input'));
try {
    $request = new stdClass();

    $processingInfo = new stdClass();
    // Dont capture a zero-value auth
    $processingInfo->capture = $incoming->order->amount>0?$incoming->order->capture:false;
    $actionList = [$incoming->paAction];
    $processingInfo->actionList = $actionList;
    $processingInfo->commerceIndicator = "internet";
    $request->processingInformation = $processingInfo;

    // Always meed the transient token
    $tokenInformation = [
        "jti" => $incoming->order->flexToken
    ];
    $request->tokenInformation = $tokenInformation;

    $request->clientReferenceInformation = new stdClass();
    $request->clientReferenceInformation->code = $incoming->order->referenceNumber;

    if($incoming->paAction == "CONSUMER_AUTHENTICATION"){
        // PA Enrollment check
        $challengeCode = "01";
        // $returnUrl = ($incoming->order->local?LOCALHOST_TARGET_ORIGIN:PRODUCTION_TARGET_ORIGIN) . "/unifiedCheckout/redirect.php";
        $returnUrl = "https://" . (strstr($_SERVER['HTTP_HOST'],LOCALHOST_TARGET_ORIGIN)?LOCALHOST_TARGET_ORIGIN:PRODUCTION_TARGET_ORIGIN) . "/unifiedCheckout/redirect.php";
        $consumerAuthenticationInformation = [
            "challengeCode"=> $challengeCode,
            "referenceId" => $incoming->referenceID,
            "returnUrl" => $returnUrl
        ];
    }else if($incoming->paAction == "VALIDATE_CONSUMER_AUTHENTICATION"){
        // PA Validation
        $consumerAuthenticationInformation = [
            "authenticationTransactionId" => $incoming->authenticationTransactionID
        ];
    }else{
        $consumerAuthenticationInformation = [];    // empty
    }
    $request->consumerAuthenticationInformation = $consumerAuthenticationInformation;

    $requestBody = json_encode($request);
    $result = ProcessRequest(MID, API_PAYMENTS, METHOD_POST, $requestBody, CHILD_MID, AUTH_TYPE_SIGNATURE );
    echo json_encode($result);
} catch (Exception $exception) {
    echo(json_encode($exception));
}
