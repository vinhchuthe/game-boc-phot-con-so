<?php 
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

header('Content-Type: text/event-stream');
header("Cache-Control: no-cache"); // Prevent Caching

$token = isset($_GET['token']) ? trim($_GET['token']) : '';

$last_id = isset($_GET['nxt']) ? trim($_GET['nxt']) : '';
$last_id = token::decode($last_id, TOKEN_KEY, 300);
$last_id = isIdNumber($last_id) ? $last_id : 0;

$curr_date = date('Y-m-d');

$isValidToken = token::validate($token, PROJECT_KEY_NAME, TOKEN_KEY, 180);

if($isValidToken)
{
	//$last_event_id = floatval(isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : False);
	//if ($last_event_id == 0)
	//{
		//$last_event_id = floatval(isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : False);
	//}
	
	$last_event_id = isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : '';
	if ($last_event_id == '')
	{
		$last_event_id = isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : '';
	}
	$last_event_id = token::decode($last_event_id, TOKEN_KEY, 600);
	$last_event_id = isIdNumber($last_event_id) ? $last_event_id : 0;
	
	$maxId = 0;
	$game_url_crc = crc32(PROJECT_KEY_NAME);
	while(1)
	{
		$lstMsg = array();
		$db = db_connect();
		$id = $maxId;
		if($last_event_id > 0)
		{
			$id = $last_event_id;
		}
		else if($last_id > 0 && $id == 0)
		{
			$id = $last_id;
		}
		
		$stmt = $db->prepare("SELECT * FROM `log_game` WHERE `id` > :id AND `created_at` >= :start_time ORDER BY `id` ASC;");
		$stmt = $db->prepare("SELECT * FROM `log_game` WHERE `game_url_crc` = :game_url_crc AND `created_dt` = :created_dt AND `created_at` >= :start_time AND `id` > :max_id ORDER BY `id` ASC;");
		$stmt->execute(['game_url_crc'=>$game_url_crc, 'created_dt' => $curr_date, 'start_time' => $start_time_str, 'max_id' => $id]);
		while ($row = $stmt->fetch()) {
			$lstMsg[] = $row['message'];
			$maxId = $row['id'] > $maxId ? $row['id'] : $maxId;
		}
		db_close($db);
		
		echo "id: " . token::encode($maxId, TOKEN_KEY) . "\n";
		echo "data: " . json_encode($lstMsg) . "\n\n";
		echo "retry: 15000\n";
		ob_flush();
		flush();
		//sleep 1.5s
		usleep(1500000);
	}
}
else
{
	echo "data: invalid_token\n\n";
	echo "retry: 15000\n";
}