<?php

	include('config.php');
	
	
	mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE) or die(mysql_error());
	

	$result = mysql_query("LOAD DATA LOCAL INFILE './SUBTLEXusfrequencyabove1.csv' INTO TABLE word_freq FIELDS TERMINATED BY ';' ENCLOSED BY '' LINES TERMINATED BY '\n' (Word, FREQCount, CDCount, FREQLow, CDLow, SUBTLWF, Lg10WF, SUBTLCD, Lg10CD)");
	
	echo $result;
?>