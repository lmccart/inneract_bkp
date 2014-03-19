<?php
	
	    	
	include('config.php');

	mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE) or die(mysql_error());
	$function = $_GET['function'];					
	
	if (strcmp($function, 'update_pic') == 0) {
		update_pic();
	} else if (strcmp($function, 'update_act') == 0) {
		update_act();
	} else if (strcmp($function, 'update_loc') == 0) {
		update_loc();
	} else if (strcmp($function, 'get_users') == 0) {
		get_users();
	} else if (strcmp($function, 'get_users_optimized') == 0) {
		get_users();
	} else if (strcmp($function, 'remove_active') == 0) {
		//remove_active();
	} else {
		remove_inactives();
	}
	
	function update_pic() {
		$uid = trim($_POST['uid']);
		$image_path = "";
		
		echo "update_pic";	
		// where img file is going to be placed 
		$image_dir = "images/";
		$image = $_FILES['image'];
		if ($image["error"] <= 0) {
				
			$image_path = $image_dir . $uid . ".jpg";
								
			move_uploaded_file($image['tmp_name'], $image_path);
			echo "Stored in: " . $image_path;
		}
	}

	function update_act() {
		$uid = trim($_POST['uid']);
		$act = trim($_POST['act']);
		$locationLat = trim($_POST['locationLat']);
		$locationLon = trim($_POST['locationLon']);
		$time = time();
		
		
		$words = explode(" ", $act);
		$min_freq = -1;
		$tag = "";
		$symbols = array(".", "!", "?", "@", ":", ";", "'", "\"");
		
		
		foreach ($words as $w) {
			$w = str_replace($symbols, "", $w);
			$w = strtolower($w);
			$wordindb = mysql_fetch_assoc(mysql_query("SELECT * FROM word_freq WHERE Word='$w'"));
			$freq = $wordindb['FREQCount'];
			if (!$freq >= 1) $freq = 1;
			
		    if ($min_freq == -1 || $freq < $min_freq) {
		    	$min_freq = $freq;
		    	$tag = $w;
		    }
		}
		
		
		$result = mysql_query("SELECT * FROM active_users WHERE uid='$uid'");
		
		if (strlen($act) == 0 || strlen($tag) == 0) {// remove user from active if act cleared
			mysql_query("DELETE FROM active_users WHERE uid='$uid'");
			echo "";
		} else {
			if (mysql_num_rows($result) == 0) { // insert into active_users if not there
				mysql_query("INSERT INTO active_users (uid, locationLat, locationLon, tag, act, time) VALUES ('$uid', '$locationLat', '$locationLon', '$tag', '$act', '$time')");
			} else { // otherwise update
				mysql_query("UPDATE active_users SET locationLat='$locationLat', locationLon='$locationLon', tag='$tag', act='$act', time='$time' WHERE uid='$uid'");
			}
			
			// add to archive
			$archive_query = mysql_query("SELECT * FROM archived_actions WHERE uid='$uid' AND tag='$tag' AND act='$act'");
			if (mysql_num_rows($archive_query) == 0) {
				mysql_query("INSERT INTO archived_actions (uid, locationLat, locationLon, tag, act, time) VALUES ('$uid', '$locationLat', '$locationLon', '$tag', '$act', '$time')");
			}
			echo $tag.":end";
		}
	}

function update_loc() {
	$uid = trim($_POST['uid']);
	$act = trim($_POST['act']);
	$locationLat = trim($_POST['locationLat']);
	$locationLon = trim($_POST['locationLon']);
	$time = time();
	
	
	$words = explode(" ", $act);
	$min_freq = -1;
	$tag = "";
	$symbols = array(".", "!", "?", "@", ":", ";", "'", "\"");
	
	
	foreach ($words as $w) {
		$w = str_replace($symbols, "", $w);
		$w = strtolower($w);
		$wordindb = mysql_fetch_assoc(mysql_query("SELECT * FROM word_freq WHERE Word='$w'"));
		$freq = $wordindb['FREQCount'];
		if (!$freq >= 1) $freq = 1;
		
	    if ($min_freq == -1 || $freq < $min_freq) {
	    	$min_freq = $freq;
	    	$tag = $w;
	    }
	}
	
	
	$result = mysql_query("SELECT * FROM active_users WHERE uid='$uid'");
	
	if (strlen($act) == 0 || strlen($tag) == 0) {// remove user from active if act cleared
		mysql_query("DELETE FROM active_users WHERE uid='$uid'");
		echo "";
	} else {
		if (mysql_num_rows($result) == 0) { // insert into active_users if not there
			mysql_query("INSERT INTO active_users (uid, locationLat, locationLon, tag, act, time) VALUES ('$uid', '$locationLat', '$locationLon', '$tag', '$act', '$time')");
		} else { // otherwise update
			mysql_query("UPDATE active_users SET locationLat='$locationLat', locationLon='$locationLon', tag='$tag', act='$act', time='$time' WHERE uid='$uid'");
		}
		
		// add to archive
		$archive_query = mysql_query("SELECT * FROM archived_actions WHERE uid='$uid' AND tag='$tag' AND act='$act'");
		if (mysql_num_rows($archive_query) == 0) {
			mysql_query("INSERT INTO archived_actions (uid, locationLat, locationLon, tag, act, time) VALUES ('$uid', '$locationLat', '$locationLon', '$tag', '$act', '$time')");
		}
		echo $tag.":end";
	}
}

	
	function get_users() {

	
		$locationLat = trim($_POST['locationLat']);
		$locationLon = trim($_POST['locationLon']);

		$result = mysql_query("SELECT * FROM active_users");
		
		
		while ($row = mysql_fetch_assoc($result)) {
			$d = distance($row['locationLat'], $row['locationLon'], $locationLat, $locationLon, k);
			$nearby = 0;
			if ($d < 3.0)
				$nearby = 1;

			echo "user:".$row['uid']."~".$row['tag']."~".$row['act']."~".$row['locationLat']."~".$row['locationLon']."~".$nearby.":end";
		}
	}
	
	function get_users_optimized() {
	
		date_default_timezone_set(date_default_timezone_get());
		
		$cache_expire = 5 * 60; # 5 mins
		
		$cache_file ="user_cache.txt";
		
		// if cache not expired, echo from that
		if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_expire) {
			echo readfile($cache_file);
		    exit();
		}      
	
		
		$locationLat = trim($_POST['locationLat']);
		$locationLon = trim($_POST['locationLon']);
	
		$result = mysql_query("SELECT * FROM active_users");
			
		// start output buffer for cache
		ob_start();	
		while ($row = mysql_fetch_assoc($result)) {
			$d = distance($row['locationLat'], $row['locationLon'], $locationLat, $locationLon, k);
			$nearby = 0;
			if ($d < 3.0)
				$nearby = 1;
	
			echo "user:".$row['uid']."~".$row['tag']."~".$row['act']."~".$row['locationLat']."~".$row['locationLon']."~".$nearby.":end";
		}
		$content = ob_get_clean();
		$f = fopen($cache_file, 'w');
		fwrite($f, $content);
		fclose($f);
		echo $content;
	}
	
	function distance($lat1, $lon1, $lat2, $lon2, $unit) { 

	  $theta = $lon1 - $lon2; 
	  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
	  $dist = acos($dist); 
	  $dist = rad2deg($dist); 
	  $miles = $dist * 60 * 1.1515;
	  $unit = strtoupper($unit);
	
	  if ($unit == "K") {
	    return ($miles * 1.609344); 
	  } else if ($unit == "N") {
	      return ($miles * 0.8684);
	    } else {
	        return $miles;
	      }
	}
	
	function remove_active() {
		$uid = $_POST['uid'];
		mysql_query("DELETE FROM active_users WHERE uid='$uid'");
	}
	
	function remove_inactives() { // remove those with no loc updates for 2 hrs, can presume them dead
		$time = time();
		$result = mysql_query("SELECT * FROM active_users");
		
		while ($row = mysql_fetch_assoc($result)) {
			echo $time - $row['time'];
			if ($time - $row['time'] > 7200) {
				$uid = $row['uid'];
				mysql_query("DELETE FROM active_users WHERE uid='$uid'");
			}
		}		
	}

?>




