<?php
function db_connect()
{
	try {
		$conn = new PDO("mysql:host=".DB_HOST.";port=" . DB_PORT . ";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS);
		
		// set the PDO error mode to exception
		// chi dung cho development - khong dung khi day len server that
		// $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
	}
	catch(PDOException $e)
	{
		//echo "Connection failed: " . $e->getMessage();
		return FALSE;
	}
}

function db_close($conn)
{
	$conn = null;
}