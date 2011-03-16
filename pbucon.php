<?PHP
error_reporting(0);

$mtime = microtime(); 
$mtime = explode(' ', $mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime;

include('settings.php');

$uconpath = preg_replace ("/\/$/","",dirname(__FILE__));

$pbsspath = $uconpath . "/$pbssurl";
is_dir($pbsspath) or mkdir($pbsspath, 0777);

$cachepath = $uconpath . "/$cacheurl";
is_dir($cachepath) or mkdir($cachepath, 0777);

ini_set('session.use_only_cookies','1');
if (ini_get("session.use_trans_sid") == true) 
{
ini_set("url_rewriter.tags", "");
ini_set("session.use_trans_sid", false);
}
ini_set("session.gc_maxlifetime", "0");
ini_set("session.gc_divisor", "1");
ini_set("session.gc_probability", "1");
ini_set("session.cookie_lifetime", "0");

$item1="<item>\n   <title>";
$item2="</title>\n   <description><![CDATA[";
$item3="]]></description>\n   <link>"; 
$item4="</link>\n   <guid><![CDATA["; 
$item5="]]></guid>\n";
$item6="<pubDate>" . (date("r")) . "</pubDate>\n";
$item7="</item>\n";		

$_SESSION['server'] = "";
$_SESSION['befehl'] = "";

session_start();
 
if (eregi("[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\:[0-9]{1,6}",urldecode(strip_tags($_SERVER['REQUEST_URI'])))) 
{
	if (@preg_match('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\:[0-9]{1,6}/i', $_SERVER['REQUEST_URI'], $matches))
	{
		$cleanipstr = @spliti (":", trim(urldecode(strip_tags($matches[0]))), 2);

			if (intval($cleanipstr[1]) < "65535" AND intval($cleanipstr[1]) > "0")
			{
				$_SESSION['serverip'] = $cleanipstr[0];
				$_SESSION['serverport'] = $cleanipstr[1];
				$_SESSION['server'] = "$cleanipstr[0]:$cleanipstr[1]";
			}		
		
	}
}


###################### ucon server ip
if(@eregi("\/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\/",urldecode(strip_tags($_SERVER['REQUEST_URI'])))) 
{
	if (@preg_match('/\/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\//i', $_SERVER[REQUEST_URI], $matches))
	{
		$str = @preg_replace('/\//', '', $matches);
		$_SESSION['uconserver'] = $str[0];
	}
} else {
	$_SESSION['uconserver'] = $uconserver;
}


###################### befehl
$_SESSION['befehl'] = "";
if(eregi("cmd_[+_0-9a-zA-Z]{1,32}",urldecode(strip_tags($_SERVER['REQUEST_URI'])))) 
{
	if (@preg_match('/cmd_[+_0-9a-zA-Z]{1,32}/i', $_SERVER[REQUEST_URI], $matches))
	{	
		$str = @preg_replace('/\//', '', $matches[0]);
		$_SESSION['befehl'] = strip_tags($str);
	}
}

$uconport = rand($uconsrvporta, $uconsrvportb);
if ($handle = opendir('.')) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != "..") {
		if (@eregi($_SESSION[server],$file))
			{

echo '<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="0.91">
<channel>
';	
			
echo $item1;
	echo "Server in use!"; 
echo $item2;
echo $item3;
echo $item4;
	echo "Server in use!"; 
echo $item5;
echo $item7;

echo "</channel>\n";
echo '</rss>';	

die;
			}		
		if (@eregi($uconport,$file))
			{

echo '<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="0.91">
<channel>
';	
			
echo $item1;
	echo "Port in use! Retry"; 
echo $item2;
echo $item3;
echo $item4;
	echo "Port in use! Retry"; 
echo $item5;
echo $item7;

echo "</channel>\n";
echo '</rss>';	

die;
			}
		} 
	} closedir($handle);
}
$uconlog1 = "$uconpath/$uconport.$_SESSION[server].pbucon.log";
$uconlog2 = "$uconport.$_SESSION[server].pbucon2.log";
$uconfifo = "$uconport.$_SESSION[server].fifo";
######################
	
	
@preg_match('/\/check\//i', $_SERVER['REQUEST_URI'], $matches6);
if ($matches6[0] == "/check/") 
{
	if ($_SESSION['serverip'] 
	AND $_SESSION['serverport'] 
	AND $_SESSION['uconserver']
	AND $uconport 
	AND $uconlogin 
	AND $uconpass 
	AND $uconpath
	AND $_SESSION['befehl'] == "") {
	
echo '<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="0.91">
<channel>
';	
	
	shell_exec("rm $uconpath/$uconfifo");
	shell_exec("mkfifo $uconpath/$uconfifo");
	shell_exec("$uconpath/./pbucon.run server=$_SESSION[serverip]:$_SESSION[serverport] myaddr=$_SESSION[uconserver]:$uconport login=$uconlogin password=$uconpass logfile=$uconpath/pbucon.log <> $uconpath/$uconfifo > $uconpath/$uconlog2 &");
	sleep(0.1337);
	shell_exec("echo '   ' > $uconpath/$uconfifo");
	sleep(1.337);
	shell_exec("echo pb_sv_ver > $uconpath/$uconfifo");
	sleep(1.337);
	$file = file("$uconpath/$uconlog2");
	

	if (count($file) == 21)
	{
	
				echo $item1;
					echo "Offline"; 
				echo $item2;
				echo $item3;
					echo "Offline";
				echo $item4;
					echo "Offline"; 
				echo $item5;
				echo $item7;
				echo "</channel>\n";
				echo '</rss>';

	shell_exec("chmod 0755 $uconpath/$uconfifo");shell_exec("echo pbuconexit > $uconpath/$uconfifo");
	shell_exec("chmod 0755 $uconpath/$uconlog2");shell_exec("rm $uconpath/$uconlog2");
	shell_exec("rm $uconpath/$uconfifo");	
	die;	
	} elseif (count($file) == 22) {

				echo $item1;
					echo "Offline"; 
				echo $item2;
				echo $item3;
					echo "Offline";
				echo $item4;
					echo "Offline"; 
				echo $item5;
				echo $item7;
				echo "</channel>\n";
				echo '</rss>';	

	shell_exec("chmod 0755 $uconpath/$uconfifo");shell_exec("echo pbuconexit > $uconpath/$uconfifo");
	shell_exec("chmod 0755 $uconpath/$uconlog2");shell_exec("rm $uconpath/$uconlog2");
	shell_exec("rm $uconpath/$uconfifo");
	die;
	} elseif (count($file) > 22) {

		
		// big log
		$biglog = "";

		$pbssfullstring = array();		
		$i=1;			
		foreach ($file as $lastrow) {
			if ($i==25)
			{
				echo $item1;
					echo "Online"; 
				echo $item2;
				echo $item3;
					echo "Online";
				echo $item4;
					echo "Online"; 
				echo $item5;
				echo $item7;
				echo "</channel>\n";
				echo '</rss>';		 
			}
		$i++;
		}

	shell_exec("chmod 0755 $uconpath/$uconfifo");shell_exec("echo pbuconexit > $uconpath/$uconfifo");
	shell_exec("chmod 0755 $uconpath/$uconlog2");shell_exec("rm $uconpath/$uconlog2");
	shell_exec("rm $uconpath/$uconfifo");	
	die;
		}
	}
}

# cache start
ob_start();

######################################################################################################
if ($_SESSION['serverip'] 
AND $_SESSION['serverport'] 
AND $_SESSION['uconserver']
AND $uconport 
AND $uconlogin 
AND $uconpass 
AND $uconpath
AND $_SESSION['befehl'] != ""
AND $matches6[0] != "/check/") {

	// ucon aufbau
	shell_exec("rm $uconpath/$uconfifo");
	shell_exec("mkfifo $uconpath/$uconfifo");
	shell_exec("$uconpath/./pbucon.run server=$_SESSION[serverip]:$_SESSION[serverport] myaddr=$_SESSION[uconserver]:$uconport login=$uconlogin password=$uconpass logfile=$uconpath/pbucon.log <> $uconpath/$uconfifo > $uconpath/$uconlog2 &");
	sleep(0.1337);
	shell_exec("echo pb_sv_ver > $uconpath/$uconfifo");
	sleep(1.337);
	shell_exec("echo pb_sv_plist > $uconpath/$uconfifo");
	sleep(1.337);
	sleep(1.337);
	
	$file = file("$uconpath/$uconlog2");
	
	// pb_plist array
	$meinarray = array();
		foreach ($file as &$value) 
			{	
				$value = @preg_replace('/\-\>PBSV\: /', '', $value);	
					if(substr($value, 0, 2) == 1 OR
					   substr($value, 0, 2) == 2 OR
					   substr($value, 0, 2) == 3 OR
					   substr($value, 0, 2) == 4 OR
					   substr($value, 0, 2) == 5 OR
					   substr($value, 0, 2) == 6 OR
					   substr($value, 0, 2) == 7 OR
					   substr($value, 0, 2) == 8 OR
					   substr($value, 0, 2) == 9 OR
					   substr($value, 0, 3) == 10 OR
					   substr($value, 0, 3) == 11 OR
					   substr($value, 0, 3) == 12 OR
					   substr($value, 0, 3) == 13 OR
					   substr($value, 0, 3) == 14 OR
					   substr($value, 0, 3) == 15 OR
					   substr($value, 0, 3) == 16 OR
					   substr($value, 0, 3) == 17 OR
					   substr($value, 0, 3) == 18 OR
					   substr($value, 0, 3) == 19 OR
					   substr($value, 0, 3) == 20 OR
					   substr($value, 0, 3) == 21 OR
					   substr($value, 0, 3) == 22 OR
					   substr($value, 0, 3) == 23 OR
					   substr($value, 0, 3) == 24 OR
					   substr($value, 0, 3) == 25 OR
					   substr($value, 0, 3) == 26 OR
					   substr($value, 0, 3) == 27 OR
					   substr($value, 0, 3) == 28 OR
					   substr($value, 0, 3) == 29 OR
					   substr($value, 0, 3) == 30 OR
					   substr($value, 0, 3) == 31 OR
					   substr($value, 0, 3) == 32)
						{
							$meinarray2 = array();
							$str18 = spliti ("\"", $value);
							$fullstring = spliti (" ", $value);
							$str17 = spliti (":", $fullstring[2]);
							$slot = trim(substr($value, 0, 2));
							$playerguid = @preg_replace('/[(](.*)[)]/', '', $str17[0]);
						
							array_push($meinarray2, $slot);
							array_push($meinarray2, $str18[1]);
							array_push($meinarray2, $playerguid);
							array_push($meinarray2, $fullstring[3]);

							array_chunk($meinarray2, 4);
							array_push($meinarray, $meinarray2);
						}	
				}
	
		  if (count($meinarray) > 0 AND count($meinarray) < 5) {
		$sleeptime = "1";
	} elseif (count($meinarray) > 5 AND count($meinarray) < 10) {
		$sleeptime = "2";			
	} elseif (count($meinarray) > 10 AND count($meinarray) < 15) {
		$sleeptime = "3";
	} elseif (count($meinarray) > 15 AND count($meinarray) < 20) {
		$sleeptime = "4";
	} elseif (count($meinarray) > 20 AND count($meinarray) < 25) {
		$sleeptime = "5";
	} elseif (count($meinarray) > 25 AND count($meinarray) < 30) {
		$sleeptime = "6";
	} elseif (count($meinarray) > 30 AND count($meinarray) < 35) {		
		$sleeptime = "7";
	} elseif (count($meinarray) > 35 AND count($meinarray) < 40) {
		$sleeptime = "8";
	} else {
		$sleeptime = "5"; // default
	}	
	
echo '<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="0.91">
<channel>
';	

	$pos = stripos($file, 'Received'); 
	if ($pos !== false) {
		if (count($meinarray) != 0) { // es befinden sich spieler auf dem server
				switch($_SESSION['befehl'])
				{
									
				case cmd_ss:
					shell_exec("echo pb_sv_getss > $uconpath/$uconfifo");
					sleep(10);
				break;		
				
				case cmd_bind:
					shell_exec("echo pb_sv_bindsrch attack > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch wait > $uconpath/$uconfifo");
					sleep($sleeptime);	
					shell_exec("echo pb_sv_bindsrch vstr > $uconpath/$uconfifo");
					sleep($sleeptime);	
					shell_exec("echo pb_sv_bindsrch exec > $uconpath/$uconfifo");
					sleep($sleeptime);	
					shell_exec("echo pb_sv_bindsrch set > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch bind > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch var > $uconpath/$uconfifo");
					sleep($sleeptime);					
					shell_exec("echo pb_sv_bindsrch - > $uconpath/$uconfifo");
					sleep($sleeptime);		
					//shell_exec("echo pb_sv_bindsrch + > $uconpath/$uconfifo");
					//sleep($sleeptime);			
					shell_exec("echo pb_sv_bindsrch ; > $uconpath/$uconfifo");
					sleep($sleeptime);						
				break;

				case cmd_bind_all:
					shell_exec("echo pb_sv_bindsrch a > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch b > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch c > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch d > $uconpath/$uconfifo");
					sleep($sleeptime);	
					shell_exec("echo pb_sv_bindsrch e > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch f > $uconpath/$uconfifo");
					sleep($sleeptime);		
					shell_exec("echo pb_sv_bindsrch g > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch h > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch i > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch j > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch k > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch l > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch m > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch n > $uconpath/$uconfifo");
					sleep($sleeptime);			
					shell_exec("echo pb_sv_bindsrch o > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch p > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch q > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch r > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch s > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch t > $uconpath/$uconfifo");
					sleep($sleeptime);	
					shell_exec("echo pb_sv_bindsrch u > $uconpath/$uconfifo");
					sleep($sleeptime);				
					shell_exec("echo pb_sv_bindsrch v > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch w > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch x > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch y > $uconpath/$uconfifo");
					sleep($sleeptime);
					shell_exec("echo pb_sv_bindsrch z > $uconpath/$uconfifo");
					sleep($sleeptime);	
					shell_exec("echo pb_sv_bindsrch - > $uconpath/$uconfifo");
					sleep($sleeptime);			
					shell_exec("echo pb_sv_bindsrch ; > $uconpath/$uconfifo");
					sleep($sleeptime);						
				break;
				
				case cmd_alist:
					shell_exec("echo pb_sv_alist > $uconpath/$uconfifo");
					sleep(5);
				break;		
				
				case cmd_banlist:
					shell_exec("echo pb_sv_banlist > $uconpath/$uconfifo");
					sleep(10);
				break;

				case cmd_badnamelist:
					shell_exec("echo pb_sv_BadNameList > $uconpath/$uconfifo");
					sleep(5);
				break;
				
				case cmd_cvarlist:
					shell_exec("echo pb_sv_cvarlist > $uconpath/$uconfifo");
					sleep(20);
				break;

				case cmd_powerlist:
					shell_exec("echo pb_sv_powerlist > $uconpath/$uconfifo");
					sleep(5);
				break;				
				
				case cmd_statlist:
					shell_exec("echo pb_sv_statlist > $uconpath/$uconfifo");
					sleep(5);
				break;
				
				case cmd_tasklist:
					shell_exec("echo pb_sv_tlist > $uconpath/$uconfifo");
					sleep(5);
				break;
				
				case cmd_namelocklist:
					shell_exec("echo pb_sv_namelocklist > $uconpath/$uconfifo");
					sleep(10);
				break;				

				case cmd_md5toollist:
					shell_exec("echo pb_sv_md5toollist > $uconpath/$uconfifo");
					sleep(20);
				break;	

				case cmd_uconlist:
					shell_exec("echo pb_sv_uconlist > $uconpath/$uconfifo");
					sleep(5);
				break;
				
				case cmd_usessionlist:
					shell_exec("echo pb_sv_usessionlist > $uconpath/$uconfifo");
					sleep(5);
				break;

				case cmd_uconignorelist:
					shell_exec("echo pb_sv_uconignorelist > $uconpath/$uconfifo");
					sleep(5);
				break;		

				default:
					$lbdfb = @trim(strip_tags(preg_replace('/cmd_/', '', $_SESSION[befehl])));
					if ($lbdfb != ""){
						$lbdfb = preg_replace('/\+/', ' ', $lbdfb);
						shell_exec("echo \"$lbdfb\" > $uconpath/$uconfifo");
						sleep($sleeptime);
					}
				break;
				}
		
		} else { 
			
			switch($_SESSION['befehl'])
			{
	
				case cmd_alist:
					shell_exec("echo pb_sv_alist > $uconpath/$uconfifo");
					sleep(10);
				break;
				
				case cmd_banlist:
					shell_exec("echo pb_sv_banlist > $uconpath/$uconfifo");
					sleep(30);
				break;

				case cmd_badnamelist:
					shell_exec("echo pb_sv_BadNameList > $uconpath/$uconfifo");
					sleep(4);
				break;
				
				case cmd_cvarlist:
					shell_exec("echo pb_sv_cvarlist > $uconpath/$uconfifo");
					sleep(50);
				break;

				case cmd_powerlist:
					shell_exec("echo pb_sv_powerlist > $uconpath/$uconfifo");
					sleep(4);
				break;				
				
				case cmd_statlist:
					shell_exec("echo pb_sv_statlist > $uconpath/$uconfifo");
					sleep(4);
				break;
				
				case cmd_tasklist:
					shell_exec("echo pb_sv_tlist > $uconpath/$uconfifo");
					sleep(10);
				break;
				
				case cmd_namelocklist:
					shell_exec("echo pb_sv_namelocklist > $uconpath/$uconfifo");
					sleep(10);
				break;				

				case cmd_md5toollist:
					shell_exec("echo pb_sv_md5toollist > $uconpath/$uconfifo");
					sleep(50);
				break;	

				case cmd_uconlist:
					shell_exec("echo pb_sv_uconlist > $uconpath/$uconfifo");
					sleep(3);
				break;
				
				case cmd_usessionlist:
					shell_exec("echo pb_sv_usessionlist > $uconpath/$uconfifo");
					sleep(4);
				break;

				case cmd_uconignorelist:
					shell_exec("echo pb_sv_uconignorelist > $uconpath/$uconfifo");
					sleep(4);
				break;
				
			}
		
		}		
	
		
	} elseif (count($file) > 22) {

		echo $item1;
			echo "wrong ucon acc ?"; 
		echo $item2;
		echo $item3;
		echo $item4;
			echo "wrong ucon acc ?"; 
		echo $item5;
		echo $item7;

	}
		
#############################################################################
// auswertung
	sleep(1);
	$file = file("$uconpath/$uconlog2"); 
	$file2 = file("$uconpath/pbucon.log");

		  if (count($file) == 21)
	{

		echo $item1;
			echo "failed ucon connect #1"; 
		echo $item2;
		echo $item3;
		echo $item4;
			echo "failed ucon connect #1"; 
		echo $item5;
		echo $item7;

	shell_exec("chmod 0755 $uconpath/$uconfifo");shell_exec("echo pbuconexit > $uconpath/$uconfifo");
	shell_exec("chmod 0755 $uconpath/$uconlog2");shell_exec("rm $uconpath/$uconlog2");
	shell_exec("rm $uconpath/$uconfifo");	
		
	} elseif (count($file) == 22) {

		echo $item1;
			echo "failed ucon connect #2"; 
		echo $item2;
		echo $item3;
		echo $item4;
			echo "failed ucon connect #2"; 
		echo $item5;
		echo $item7;
		
	shell_exec("chmod 0755 $uconpath/$uconfifo");shell_exec("echo pbuconexit > $uconpath/$uconfifo");
	shell_exec("chmod 0755 $uconpath/$uconlog2");shell_exec("rm $uconpath/$uconlog2");
	shell_exec("rm $uconpath/$uconfifo");
	
	} elseif (count($file) > 22) {

		$biglog = "";
		$pbssfullstring = array();		
		$i=1;	
		$header	= "$_SESSION[befehl]";
		$headeron=0;
		foreach ($file as $lastrow) {
			if ($i>=25)
			{
			$lastrow = @preg_replace('/\-\>PBSV\: /', '', $lastrow);
			$lastrow = @preg_replace('/[0-9]{1,3}\.[0-9]{1,3}\:/', '***.***:', $lastrow);
			$lastrow = @preg_replace('/[0-9a-f]{24}/', '************************', $lastrow);

			$lastrow2 = $lastrow; 
			
			$lastrow = @preg_replace('/\n/', '', $lastrow);
			
				$pos4 = stripos($lastrow, 'PB UCON');
				if ($pos4 !== false) {
				
				} else {

					if ($headeron == 0) { $headeron=1;
						echo $item1;
							echo $header;
						echo $item2;
						echo $item3;
							echo $header;
						echo $item4;
							echo $header;
						echo $item5;
						echo $item7;
					}		
					
					$biglog .= $lastrow2;
				
					echo $item1;
						echo $lastrow;
					echo $item2;
					echo $item3;
						echo $lastrow;
					echo $item4;
						echo $lastrow;
					echo $item5;
					echo $item7;	

				}

				// pb_sv_getss screens		
				if (@eregi(".png",$lastrow)) 
				{
					preg_match('/pb[0-9]{1,6}\.png/i', $lastrow, $matches);									
					$pbssfullstringcount = count($matches);
					$pbssfullstringcount2 = $pbssfullstringcount - 1;
											
					array_push($pbssfullstring, $matches[$pbssfullstringcount2]);
				}	
						
			}
		$i++;
		}
		
		$servermd5 = md5($_SESSION[server]);
		$mytime = time();
		$file = "cache/${mytime}_${_SESSION[befehl]}_${servermd5}.log";
		if ($_SESSION['befehl'] != "") {  file_put_contents($file, $biglog); }
		
		echo $item1;
			echo substr($totaltime, 0, 5);
			echo " sec.";
		echo $item2;
		echo $item3;
			echo substr($totaltime, 0, 5);
			echo " sec.";
		echo $item4;
			echo substr($totaltime, 0, 5);
			echo " sec.";
		echo $item5;
		echo $item6;
		echo $item7;	
		###################### 		



	shell_exec("chmod 0755 $uconpath/$uconfifo");shell_exec("echo pbuconexit > $uconpath/$uconfifo");
	shell_exec("chmod 0755 $uconpath/$uconlog2");shell_exec("rm $uconpath/$uconlog2");
	shell_exec("rm $uconpath/$uconfifo");

		
} elseif (count($meinarray)==0) {

	shell_exec("sleep 0.5");
	shell_exec("chmod 0755 $uconpath/$uconfifo");shell_exec("echo pbuconexit > $uconpath/$uconfifo");
	shell_exec("chmod 0755 $uconpath/$uconlog2");shell_exec("rm $uconpath/$uconlog2");
	shell_exec("rm $uconpath/$uconfifo");
	shell_exec("sleep 2");
	
} else {

	echo $item1;
	echo 'failed';
	echo $item2;
	echo $item3;
	echo $item4;
	echo 'failed';
	echo $item5;
	echo $item7;

	shell_exec("chmod 0755 $uconpath/$uconfifo");shell_exec("echo pbuconexit > $uconpath/$uconfifo");
	shell_exec("chmod 0755 $uconpath/$uconlog2");shell_exec("rm $uconpath/$uconlog2");
	shell_exec("rm $uconpath/$uconfifo");	
}

echo "</channel>\n";
echo '</rss>';	

$CacheDatei = "cache/$_SESSION[server].xml";
file_put_contents( $CacheDatei, ob_get_flush() );
	
}
?>