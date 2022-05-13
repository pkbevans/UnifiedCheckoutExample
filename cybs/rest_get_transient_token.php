<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/unifiedCheckout/PeRestLib/RestRequest.php';
$incoming = json_decode(file_get_contents('php://input'));
$token=$incoming->token;
// $token=$_REQUEST['token'];

$api = API_PAYMENT_DETAILS . "/". $token;
$result = ProcessRequest(MID, $api, METHOD_GET, "", CHILD_MID, AUTH_TYPE_SIGNATURE );
echo json_encode($result);
