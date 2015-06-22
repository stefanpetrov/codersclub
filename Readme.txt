==============
    Radio
==============
Script requires PHP >= 5.3.0.


Open "radio.php" in browser and jQuery will execute the script.

============
Installation
============
1. Copy folder lib in DOCUMENT_ROOT
1. Provide path to xml source and DB credentials in DOCUMENT_ROOT/lib/index.php.
2. Include jquery, jquery UI from DOCUMENT_ROOT/lib
3. Include codersClub.js from DOCUMENT_ROOT/lib


==================
 Execute via cron
==================
1. Set $updateViaAjax to false on line 1 in ./js/codersClub.js
       var $updateViaAjax = false;
2. Setup cron to execute ./lib/index.php?key=save
