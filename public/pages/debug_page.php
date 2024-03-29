<?php
declare(strict_types=1);
/*
* debug_page.php
* 420DW3_07278_Project
* (c) 2024 Marc-Eric Boury All rights reserved
*/

require_once "../../private/helpers/init.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Request information page</title>
</head>
<body style="width: 100%;">
<?php

echo "<h2>REQUEST_HOSTNAME: </h2> " . REQUEST_HOSTNAME . "<br/>";
echo "<h2>REQUEST METHOD: </h2> " . REQUEST_METHOD . "<br/>";
echo "<h2>REQUEST URI: </h2> " . REQUEST_URI . "<br/>";
echo "<h2>REQUEST PATH: </h2> " . REQUEST_PATH . "<br/><br/>";
echo "<h2>DEFINED PATH CONSTANTS:</h2><br/>";
echo "PRJ_ROOT_DIR: " . PRJ_ROOT_DIR . "<br/>";
echo "PRJ_ROOT_DIRNAME: " . PRJ_ROOT_DIRNAME . "<br/>";
echo "PRJ_PRIVATE_DIR: " . PRJ_PRIVATE_DIR . "<br/>";
echo "PRJ_SRC_DIR: " . PRJ_SRC_DIR . "<br/>";
echo "PRJ_FRAGMENTS_DIR: " . PRJ_FRAGMENTS_DIR . "<br/>";
echo "PRJ_PUBLIC_DIR: " . PRJ_PUBLIC_DIR . "<br/>";
echo "PRJ_CSS_DIR: " . PRJ_CSS_DIR . "<br/>";
echo "PRJ_IMAGES_DIR: " . PRJ_IMAGES_DIR . "<br/>";
echo "PRJ_JS_DIR: " . PRJ_JS_DIR . "<br/>";
echo "PRJ_PAGES_DIRE: " . PRJ_PAGES_DIR . "<br/>";
echo "WEB_ROOT_DIR: " . WEB_ROOT_DIR . "<br/>";
echo "WEB_CSS_DIR: " . WEB_CSS_DIR . "<br/>";
echo "WEB_IMAGES_DIR: " . WEB_IMAGES_DIR . "<br/>";
echo "WEB_JS_DIR: " . WEB_JS_DIR . "<br/>";
echo "WEB_PAGES_DIR: " . WEB_PAGES_DIR . "<br/>";
echo "<br/>";

echo '<h2>$_REQUEST:</h2><br/>';
Debug::debugToHtmlTable($_REQUEST);

echo "<br/><br/>";

echo '<h2>$_SERVER:</h2><br/>';
Debug::debugToHtmlTable($_SERVER);

echo "<br/><br/>";

phpinfo();

?>

</body>
</html>



