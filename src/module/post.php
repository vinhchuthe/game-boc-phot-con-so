<?php 
require_once("../../glb/cfglb.php");
require_once("../config/constant.php");
require_once("../config/database.php");
require_once("../helper/token.php");
require_once("../helper/common.php");
require_once("../helper/mycloud.php");

//result
$rel = array(
	'ok'=>false,
	'msg'=> '',
	'data' => array()
);

$token = isset($_GET['token']) ? trim($_GET['token']) : '';

$isValidToken = token::validate($token, PROJECT_KEY_NAME, TOKEN_KEY, 600);//10 minute

if($isValidToken)
{
	$image64 = isset($_POST['image']) ? trim($_POST['image']) : ''; // do khong luu anh
	$nameUser = isset($_POST['nameUser']) ? trim($_POST['nameUser']) : '';
	$nameUser = removeAllTags($nameUser);
	$myMessage = isset($_POST['myMessage']) ? trim($_POST['myMessage']) : '';
	$myMessage = removeAllTags($myMessage);

	$contentImage = isset($_POST['contentImage']) ? trim($_POST['contentImage']) : '';
	$contentImage = removeAllTags($contentImage);

	$gameUrl = PROJECT_KEY_NAME;
	$gameUrlCrc = crc32($gameUrl);

	$uname_filename = url_title($nameUser);
	$content_filename = url_title($contentImage);

	$curr_time = date('Y-m-d H:i:s');
	$time = strtotime($curr_time);
	$curr_date = date('Y-m-d', $time);
	$errSaveImage = '';
	$filename = '';
	$urlImage = '';
	/* comment do khong su dung tinh nang luu anh */
	$filename = $uname_filename;
	if(strlen($filename) > 10)
	{
		$filename = substr($filename, 0, 10);
	}
	
	if($content_filename != '')
	{
		$filename .= $filename != '' ? '-' . $content_filename : '';
	}
	$filename .= $filename != '' ? '-' . uniqid() . rand(0,1000000) : uniqid() . rand(0,1000000);

	// store image on local host
	$createdTimeInfo = date_parse($curr_time);
	$yearFolder = $createdTimeInfo['year'];
	$monthFolder = $createdTimeInfo['month'];
	$monthFolder = ($monthFolder < 10) ? "0" . $monthFolder : $monthFolder;
	// check year folder exists
	$localFolder = UPLOAD_DIR . $yearFolder . '/';
	if(!is_dir($localFolder))
	{
		mkdir($localFolder, 0755);
	}

	// check month folder exists
	$localFolder = UPLOAD_DIR . $yearFolder . '/' . $monthFolder . '/';
	if(!is_dir($localFolder))
	{
		mkdir($localFolder, 0755);
	}

	// save image to local host 
	$errSaveImage = '';
	$urlImage = '';
	if (preg_match('/^data:image\/(\w+);base64,/', $image64, $extension)) 
	{
		$save_image64 = substr($image64, strpos($image64, ',') + 1);
		$image64_decode = base64_decode($save_image64);
		$extension = strtolower($extension[1]); // jpg, png, gif
		if (!in_array($extension, ['jpg', 'jpeg', 'gif', 'png'])) 
		{
			$errSaveImage = 'invalid image type';
		}
		else if ($image64_decode === false)
		{
			$errSaveImage = 'base64_decode failed';
		}
		else
		{
			$filename = $filename . '.' . $extension;
			
			//luu anh vao server
			$is_upload_server = file_put_contents($localFolder . $filename, $image64_decode);
			if ($is_upload_server > 0) 
			{
				//move image from local host to cloud
				if(CF_UPLOAD_LOCAL_GAME == 'cloud')
				{
					$mycloud = new mycloud();
					// check year folder exists
					$cloudFolder = CLOUD_IMG_REAL_PATH . $yearFolder . '/';
					if(!$mycloud->directory_exists($cloudFolder))
					{
						$mycloud->mkdir($cloudFolder);
					}

					// check month folder exists
					$cloudFolder = CLOUD_IMG_REAL_PATH . $yearFolder . '/' . $monthFolder . '/';
					if(!$mycloud->directory_exists($cloudFolder))
					{
						$mycloud->mkdir($cloudFolder);
					}
					// check file exist
					$cloudFilePath = CLOUD_IMG_REAL_PATH . $yearFolder . '/' . $monthFolder . '/' . $filename;
					$chkUploadCloudFile = $mycloud->upload($localFolder . $filename, $cloudFilePath, true);
					if($chkUploadCloudFile)
					{
						$urlImage = CLOUD_IMG_DOMAIN . $yearFolder . '/' . $monthFolder . '/' . $filename;
						$relativeUrlImage = '/' . $yearFolder . '/' . $monthFolder . '/' . $filename;
						@unlink($localFolder . $filename);
					}
					else
					{
						$errSaveImage = 'upload_cloud_image_error';
					}
				}
				else
				{
                    $relativeUrlImage = '/' . $yearFolder . '/' . $monthFolder . '/' . $filename;
					$urlImage = UPLOAD_URL . $yearFolder . '/' . $monthFolder . '/' . $filename;
				}
			}
			else
			{
				$errSaveImage = 'save_image_local_error';
			}
		}
	}
	else
	{
		$rel['ok'] = false;
		$rel['msg'] = $image64;
		$rel['data'] = [
			'image_url' => ''
		];
	}
	
	
	// luu anh thanh cong => log to database
	$errSaveDb = '';
	$lastIdInsert = null;
	if($errSaveImage == '')
	{
		$db = db_connect(); // open connect  database
		if($db !== FALSE)
		{
			$message = $myMessage;
			$flag = false;
			$game_url = PROJECT_KEY_NAME;
			$game_url_crc = crc32($game_url);
			$sql = "INSERT INTO `log_game`(`game_url`, `game_url_crc`, `image`, `message`, `created_at`, `created_dt`) VALUES (:game_url, :game_url_crc, :image, :message, :created_at, :created_dt)";
			$stmt = $db->prepare($sql);
			if($stmt)
			{
				$stmt->bindParam(':game_url', $game_url, PDO::PARAM_STR);
				$stmt->bindParam(':game_url_crc', $game_url_crc, PDO::PARAM_INT);
				$stmt->bindParam(':image', $relativeUrlImage, PDO::PARAM_STR);
				$stmt->bindParam(':message', $message, PDO::PARAM_STR);
				$stmt->bindParam(':created_at', $curr_time, PDO::PARAM_STR);
				$stmt->bindParam(':created_dt', $curr_date, PDO::PARAM_STR);
				if($stmt->execute())
				{
					$flag = true;
					$lastIdInsert = $db->lastInsertId();
					$stmt->closeCursor();
				}
			}
			db_close($db); // close database
			if(!$flag)
			{
				$errSaveDb = 'db_error_02';
			}
		}
		else
		{
			$errSaveDb = 'db_error_01';
		}
	}
	
	if ($errSaveDb == '' && $errSaveImage == '' && $lastIdInsert)
	{
		$rel['ok'] = true;
		$rel['idShare'] = $lastIdInsert;
		$rel['msg'] = '';
		$rel['data'] = [
			'image_url' => $urlImage
		];
	}
	else
	{
		$rel['ok'] = false;
		$rel['msg'] = $errSaveDb;
		$rel['msg'] .= $rel['msg'] != '' && $errSaveImage != '' ? ' - ' . $errSaveImage : $rel['msg'];
		$rel['data'] = [
			'image_url' => ''
		];
	}
}
else
{
	$rel['ok'] = false;
	$rel['msg'] = 'invalid_token';
	$rel['data'] = [
		'image_url' => ''
	];
}
echo json_encode($rel);