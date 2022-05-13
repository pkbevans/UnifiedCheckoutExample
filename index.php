<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/unifiedCheckout/PeRestLib/RestConstants.php';
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
        <title>Unified Checkout - View Basket</title>
    </head>
    <body>
        <div class="container-fluid justify-content-center">
        <div class="row">
            <div id="formSection">
            <form class="needs-validation" id="checkout_form" name="checkout" method="POST" target="checkout_iframe" action="checkoutUC.php" novalidate >
                <label for="amount" class="form-label">Amount</label><input id="amount" class="form-control" type="text" name="amount" value="63.99" required/>
                <label for="reference_number" class="form-label">Order Reference</label><input id="reference_number" class="form-control" type="text" name="reference_number" value="<?php echo uniqid("UC", false);?>" required/>
                <label for="email" class="form-label">Email</label><input id="email" class="form-control" type="email" name="email" value="test@gmail.com" />
                <input id="currency" type="hidden" name="currency" value="GBP"/>
                <label for="autoCapture" class="form-label">Auto Capture</label>
                <select id="autoCapture" class="form-select" name="autoCapture">
                    <option value="true" selected>Yes</option>
                    <option value="false" selected>No</option>
                </select>
                <BR>
                <button type="button" class="btn btn-primary" onclick="validateForm()">Checkout</button>
            </form>
            </div>
            <iframe id="checkoutIframe" name="checkout_iframe" src="about:blank" class="responsive-iframe" style="overflow: hidden; display: none; border:none; height:90vh; width:100vw" ></iframe>
        </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
    function buttonClicked(){
        document.getElementById('formSection').style.display="none";
        document.getElementById('checkoutIframe').style.display="block";
        var checkout_form = document.getElementById('checkout_form');
        if(checkout_form){
            checkout_form.submit();
        }
    }
    function validateForm(){
      var form = document.getElementById('checkout_form');

        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
          form.classList.add('was-validated');
        }else{
            buttonClicked();
        }
    }
    </script>
    </body>
</html>
