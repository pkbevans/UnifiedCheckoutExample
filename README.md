# UnifiedCheckoutExample
Example Cybs Unified Checkout Example

Edit PeRestLib/RestConstants.php:
  1. Update KEYS_PATH const - ""
  2. Update PeRestLibKeys.php with your REST Security key KEY ID and SECRET
  3. Update MID = "pemid03";            // Replace with your MID (Can be PORTFOLIO or Account-level)
  4. Update const CHILD_MID = "";       // Replace with Transacting MID if using PORTFOLIO or Account-level mid in MID
  5. Update const PRODUCTION_TARGET_ORIGIN =  "bondevans.com";  // Replace with Production URL for non-localhost testing (if necessary)
  6. const LOCALHOST_TARGET_ORIGIN =  "site.test";   // Replace with your localhost HTTPS alias.  
  
  NOTE: Your localhost testing must be https - See https://shellcreeper.com/how-to-create-valid-ssl-in-localhost-for-xampp/ for explanation how to set up HTTPS for localhost in xampp.
