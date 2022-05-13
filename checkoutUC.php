<?php
include "cybs/rest_generate_capture_context.php";
$defaultEmail="";
if(isset($_REQUEST['email']) && !empty($_REQUEST['email'])) {
    $defaultEmail = $_REQUEST['email'];
}else{
    $defaultEmail = $defaultPaymentInstrument->billTo->email;
}
?>
<!DOCTYPE html>
<html lang="en-GB">
    <head>
        <!-- <meta http-equiv="Content-Security-Policy" content="script-src 'self' cdn.jsdelivr.net https://testflex.cybersource.com/ bondevans.com 'unsafe-inline' 'unsafe-eval'; style-src 'self' cdn.jsdelivr.net/ bondevans.com 'unsafe-eval' 'unsafe-inline' ; frame-src 'self' https://testflex.cybersource.com/ bondevans.com; child-src https://testflex.cybersource.com/; "> -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <title>View Basket</title>
    </head>
    <body>
        <!--Cardinal device data collection code START-->
        <iframe id="cardinal_collection_iframe" name="collectionIframe" height="1" width="1" style="display: none;"></iframe>
        <form id="cardinal_collection_form" method="POST" target="collectionIframe" action="">
            <input id="cardinal_collection_form_input" type="hidden" name="JWT" value=""/>
        </form>
        </<!--Cardinal device data collection code END-->
        <div class="container-fluid justify-content-center">
            <div class="card">
                <div class="card-body" style="width: 90vw">
                    <h5 class="card-title">Your Order</h5>
                    <div class="row">
                        <div class="col-3">
                            <h5>Total:</h5>
                        </div>
                        <div class="col-9">
                            <span><?php echo "£" . $_REQUEST['amount'];?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <h5>Email:</h5>
                        </div>
                        <div class="col-9">
                            <div id="emailSection">
                                <div id="emailText"><?php echo $defaultEmail;?></div>
                            </div>
                            <form id="emailForm" class="needs-validation" novalidate style="display:none">
                                <div class="row">
                                    <div class="col-9">
                                        <div class="form-group mb-3">
                                            <input id="bill_to_email" type="email" class="form-control" value="<?php echo $defaultEmail;?>" placeholder="Enter email" required>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="summary_billTo" style="display:none">
                        <hr class="solid">
                        <h5 class="card-title">Payment Card</h5>
                        <p id="billToText" class="card-text small" style="max-height: 999999px;"></p>
                    </div>
                </div>
            </div>
            <div id="buttonPaymentListContainer"></div>
            <div id="embeddedPaymentContainer"></div>
            <div id="authSection" style="display: none;">
                <div class="d-flex justify-content-center">
                    <div id="authSpinner" class="spinner-border"></div>
                </div>
                <BR>
                <div id="authMessage" class="align-self-center">
                    <div class="d-flex justify-content-center">
                        <div class="card">
                            <div class="card-body" style="width: 90vw; max-height: 999999px;">
                                <h5 class="card-title">Authorising</h5>
                                <p class="card-text small">We are authorizing your payment. Please be patient.  Please do not press BACK or REFRESH.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <iframe id="step_up_iframe" style="overflow: hidden; display: none; border:none; height:100vh; width:100%" name="stepUpIframe" ></iframe>
                <form id="step_up_form" name="stepup" method="POST" target="stepUpIframe" action="">
                    <input id="step_up_form_jwt_input" type="hidden" name="JWT" value=""/>
                    <input id="MD" type="hidden" name="MD" value="HELLO MUM. GET THE KETTLE ON"/>
                </form>
            </div>
            <div id="resultSection" style="display: none">
                <div id="resultText"></div>
                <div class="row">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary" onclick="window.open('index.php', '_parent')">Continue shopping</button>
                    </div>
                </div>
            </div>

        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="<?php echo $clientLibrary;?>"></script>
    <script src="js/authorise.js"></script>
    <script>
    let orderDetails = {
            referenceNumber: "<?php echo $_REQUEST['reference_number'];?>",
            amount: "<?php echo $_REQUEST['amount'];?>",
            currency: "<?php echo $_REQUEST['currency'];?>",
            local: <?php echo isset($_REQUEST['local']) && $_REQUEST['local'] === "true"?"true":"false";?>,
            flexToken: "",
            maskedPan: "",
            storeCard: false,
            capture: <?php echo isset($_REQUEST['autoCapture']) && $_REQUEST['autoCapture'] === "true"?"true":"false";?>
        };

    var captureContext="<?php echo $captureContext;?>";
    var showArgs = {
        containers: {
            paymentSelection: "#buttonPaymentListContainer",
            paymentScreen: "#embeddedPaymentContainer"
        },
    }
    document.addEventListener("DOMContentLoaded", function (e) {
        Accept(captureContext).
        then(function(accept) {
            console.log(accept);
            return accept.unifiedPayments(false);
        })
        .then(function(up) {
            console.log(up);
            return up.show(showArgs);
        })
        .then(function(tt) {
            document.getElementById('embeddedPaymentContainer').style.display="none";
            console.log(tt);
            orderDetails.flexToken = getJTI(tt);
            console.log(orderDetails.flexToken);
            // authorise using token
            getTransientTokenDetails(tt);
        })
        .catch(function(error){
            console.error(error);
            // TODO feedback to user
            location.reload();
        });
    });
    function getJTI(jwt) {
        jti = getPayload(jwt).jti;
    //  console.log("JTI:" + jti);
        return (jti);
    }
    function getCardDetails(jwt) {
        return getPayload(jwt).data;
    }
    function getPayload(jwt) {
        jwtArray = jwt.split(".");
        payloadB64 = jwtArray[1];
        payloadJ = window.atob(payloadB64);
        payload = JSON.parse(payloadJ);
    //    console.log(payload);
        return payload;
    }
    function getTransientTokenDetails(jwt){
        document.getElementById('authSection').style.display = "block";
        document.getElementById('authSpinner').style.display = "block";
        $.ajax({
            type: "POST",
            url: "cybs/rest_get_transient_token.php",
            data: JSON.stringify({
                "token": jwt
            }),
            success: function (result) {
                res = JSON.parse(result);
                console.log("\nTransient Token:\n" + JSON.stringify(res, undefined, 2));
                // If OK, set up device collection
                if (res.responseCode === 200){
                    document.getElementById('billToText').innerHTML = styleCardDetails(res.response);
                    document.getElementById('summary_billTo').style.display = 'block';
                    setUpPayerAuth();
                } else {
                    // 500 System error or anything else
                    onFinish2("GETTOKEN", status, "", false, false, res.responseCode, res.response.errorInformation.reason, res.response.errorInformation.message);
                }
            }
        });
    }
    function onFinish2(apiCalled, status, requestId, httpResponseCode, errorReason, errorMessage) {
        document.getElementById('authSection').style.display = "none";
        // document.getElementById('inputSection').style.display = "none";
        // document.getElementById('confirmSection').style.display = "none";
        let finish = {
            "referenceNumber": orderDetails.referenceNumber,
            "amount": orderDetails.amount,
            "apiCalled": apiCalled,
            "httpResponseCode": httpResponseCode,
            "status": status,
            "requestId": requestId,
            "email": "",
            "autoCapture": orderDetails.capture,
            "pan": orderDetails.maskedPan,
            "errorReason": errorReason,
            "errorMessage": errorMessage
        };
        console.log(JSON.stringify(finish, undefined, 2));
        if (status === "AUTHORIZED") {
            text = successHTML(finish);
        } else {
            text = failHTML(finish);
        }
        document.getElementById("resultText").innerHTML = text;
        document.getElementById("resultSection").style.display = "block";
    }
    function successHTML(finish){
        template =
            "<h3>Thank you for your order.  Your payment was successful</h3><br>"+
            "<div class='row'><div class='col-4'>Order Reference</div><div class='col-8'>?mr?</div></div>"+
            "<div class='row'><div class='col-4'>Request ID</div><div class='col-8'>?requestId?</div></div><br>"
            ;
        html = template.replace("?mr?", finish.referenceNumber);
        html = html.replace("?requestId?", finish.requestId);
        return html;
    }
    function failHTML(finish){
        template =
            "<h3>Oh dear. Something is not working. Please check your internet connection and try again.</h3><br>"+
            "<div class='row'><div class='col-4'>Order Reference</div><div class='col-8'>?mr?</div></div>"+
            "<div class='row'><div class='col-4'>Request ID</div><div class='col-8'>?requestId?</div></div><br>"
            ;
        html = template.replace("?mr?", finish.referenceNumber);
        html = html.replace("?requestId?", finish.requestId);
        return html;
    }
    function styleCardDetails(paymentDetails) {
        img = "";
        alt = "";
        if (paymentDetails.paymentInformation.card.type === "001" || paymentDetails.paymentInformation.card.type === "visa") {
            img = "images/Visa.svg";
            alt = "Visa card logo";
        } else if (paymentDetails.paymentInformation.card.type === "002" || paymentDetails.paymentInformation.card.type === "mastercard") {
            img = "images/Mastercard.svg";
            alt = "Mastercard logo";
        } else {
            img = "images/Amex.svg";
            alt = "Amex card logo";
        }
        html =
                "<div class=\"row\">\n" +
                    "<div class=\"col-3\">\n"+
                        "<img src=\"" + img + "\" class=\"img-fluid\" alt=\"" + alt + "\">"+
                    "</div>\n" +
                    "<div class=\"col-7 \">\n" +
                        "<ul class=\"list-unstyled\">" +
                            "<li><strong>" + paymentDetails.paymentInformation.card.number + "</strong></li>\n" +
                            "<li><small>Expires:&nbsp;" + paymentDetails.paymentInformation.card.expirationMonth + "/" +
                                paymentDetails.paymentInformation.card.expirationYear + "</small></li>\n" +
                        "</ul>\n" +
                    "</div>\n" +
                "</div>\n" +
                "<div class=\"row\">\n" +
                    "<div class=\"col-12\">\n"+
                        "<h5>Billing Address</h5>" +
                    "</div>\n" +
                "</div>" +
                "<div class=\"row\">\n" +
                    "<div class=\"col-12 \">\n" +
                        formatNameAddress(paymentDetails.orderInformation.billTo)+
                    "</div>\n" +
                "</div>\n";
        return html;
    }
    function formatNameAddress(nameAddress){
        return "<p class=\"fs-6\">" + "<b>" + xtrim(nameAddress.firstName, " ") +
                xtrim(nameAddress.lastName, "</b><br>") +
                xtrim(nameAddress.address1, ", ") +
                xtrim(nameAddress.address2, ", ") +
                xtrim(nameAddress.locality, ", ") +
                xtrim(nameAddress.postalCode, ", ") +
                xtrim(nameAddress.country, "</p>");
    }
    function xtrim(xin, suffix){
        if(xin == null){
            return "";
        }
        xout = xin.trim().replace(/,*$/, "");
        return (xout===""? "" : xout + suffix) ;
    }
    </script>
    </body>
</html>
