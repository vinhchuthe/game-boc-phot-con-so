<?php 
// ajax init get list message
require_once("../../glb/cfglb.php");
require_once("../config/constant.php");
require_once("../config/database.php");
require_once("../helper/token.php");
require_once("../helper/common.php");

session_start();
$start_time = 0;
if(isset($_SESSION[SESSION_START_TIME_NAME]))
{
	$start_time = $_SESSION[SESSION_START_TIME_NAME];
	$chkStartTime = date('Y-m-d H:i:s', $start_time);
	$start_time = $chkStartTime === -1 || $chkStartTime === FALSE ? 0 : $start_time;
}
session_write_close();

$start_time_str = $start_time > 0 ? date('Y-m-d H:i:s', $start_time) : '';
$curr_date = date('Y-m-d');

$token = isset($_GET['token']) ? trim($_GET['token']) : '';

$isValidToken = token::validate($token, PROJECT_KEY_NAME, TOKEN_KEY, 300);

$rel = array(
	'next' => '',
	'errmsg' => '',
	'token' => '',
	'data' => []
);
if($isValidToken)
{
	$time_expire_next_key = 300;
	
	$maxId = 0;
	$max_str = isset($_GET['nxt']) ? trim($_GET['nxt']) : '';
	$chkFirst = false;
	if($max_str != '')
	{
		$max_str = token::decode($max_str, TOKEN_KEY, $time_expire_next_key);
		$max_str = isIdNumber($max_str) ? $max_str : 0;
		$maxId = $max_str > $maxId ? $max_str : $maxId;
	}
	else
	{
		$chkFirst = true;
	}
	
	$game_url_crc = crc32(PROJECT_KEY_NAME);
	$lstMsg = array();
	$db = db_connect();
	
	$stmt = $db->prepare("SELECT * FROM `log_game` WHERE `game_url_crc` = :game_url_crc AND `created_dt` = :created_dt AND `created_at` >= :start_time AND `id` > :max_id ORDER BY `id` ASC;");
	$stmt->execute(['game_url_crc'=>$game_url_crc, 'created_dt' => $curr_date, 'start_time' => $start_time_str, 'max_id' => $maxId]);
	while ($row = $stmt->fetch()) {
		$lstMsg[] = $row['message'];
		$maxId = $row['id'] > $maxId ? $row['id'] : $maxId;
	}
	
	// neu lay theo time join game ma khong du 100 ban ghi
	// thi check xem neu la lan dau tien ket noi gam => se lay lai top 100 ban ghi moi nhat
	if(count($lstMsg) < 100)
	{
		if($chkFirst)
		{
			$chkFirst = false;
			$maxId = 0;
			$lstMsg = [];
			$stmt = $db->prepare("SELECT * FROM `log_game` WHERE `game_url_crc` = :game_url_crc ORDER BY `id` DESC LIMIT 0,100;");
			$stmt->execute(['game_url_crc'=>$game_url_crc]);
			while ($row = $stmt->fetch()) 
			{
				$lstMsg[] = $row['message'];
				$maxId = $row['id'] > $maxId ? $row['id'] : $maxId;
			}
			
			// sap sep lai mang thoi gian tu cu toi moi
			$lstMsg = array_reverse($lstMsg);
		}
	}
	
	db_close($db);
	
	$rel['errmsg'] = '';
	$rel['token'] = token::generate(PROJECT_KEY_NAME, TOKEN_KEY);
	$rel['next'] = token::encode($maxId, TOKEN_KEY);
	$rel['data'] = $lstMsg;
}
else
{
	$rel['next'] = '';
	$rel['token'] = '';
	$rel['errmsg'] = 'INVALID_TOKEN';
	$rel['data'] = [];
}

echo json_encode($rel);