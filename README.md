# UnifiedCheckoutExample
Example Cybs Unified Checkout Example

Edit PeRestLib/RestConstants.php:
  1. Update const KEYS_PATH = "/";                          // Move the PeRestLibKeys.php to non-accessable location if necessary
  3. Update MID = "";                                       // Replace with your MID (Can be PORTFOLIO or Account-level)
  4. Update const CHILD_MID = "";                           // Replace with Transacting MID if using PORTFOLIO or Account-level mid in MID
  5. Update const PRODUCTION_TARGET_ORIGIN =  "test.com";   // Replace with Production URL for non-localhost testing (if necessary)
  6. const LOCALHOST_TARGET_ORIGIN =  "site.test";          // Replace with your localhost HTTPS alias.  

Edit PeRestLibKeys.php.  Add your REST Security key KEY ID and SECRET.
  
NOTE: Your localhost testing must use https - See https://shellcreeper.com/how-to-create-valid-ssl-in-localhost-for-xampp/ for explanation how to set up HTTPS for localhost in xampp.
