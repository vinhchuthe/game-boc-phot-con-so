<?php 
class token {
    /*
    * Generate random iv for encrypt
    */
    private static function _randiv($length = 16, $add_dashes = false, $available_sets = 'luds')
    {
    	$sets = array();
		if(strpos($available_sets, 'l') !== false)
			$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		if(strpos($available_sets, 'u') !== false)
			$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		if(strpos($available_sets, 'd') !== false)
			$sets[] = '23456789';
		if(strpos($available_sets, 's') !== false)
			$sets[] = '!@#$%&*?';
		$all = '';
		$iv = '';
		foreach($sets as $set)
		{
			$iv .= $set[array_rand(str_split($set))];
			$all .= $set;
		}
		$all = str_split($all);
		for($i = 0; $i < $length - count($sets); $i++)
		{
			$iv .= $all[array_rand($all)];
		}
		$iv = str_shuffle($iv);
		if(!$add_dashes)
		{
			return $iv;
		}
		$dash_len = floor(sqrt($length));
		$dash_str = '';
		while(strlen($iv) > $dash_len)
		{
			$dash_str .= substr($iv, 0, $dash_len) . '-';
			$iv = substr($iv, $dash_len);
		}
		$dash_str .= $iv;
		return $dash_str;
    }
    
    /*
    * Generate new token
    */
    public static function generate($product, $private_key)
    {
    	$str_encrypt = date('Y-m-d H:i:s');
		$str_encrypt .= $product;
		$iv = self::_randiv(16);
		$encrypt_key = hash_hmac('sha256', $private_key, $iv);
		$iv_for_iv = substr(md5($private_key), 0, 16);
		$token = openssl_encrypt($str_encrypt , 'AES-256-CBC', $encrypt_key, OPENSSL_RAW_DATA, $iv); 
		$encoded = base64_encode($token);
		$encoded = str_replace(array('+', '/'), array('-', '_'), $encoded);
		
		//$iv = base64_encode($iv);
		$iv = openssl_encrypt($iv , 'AES-256-CBC', $private_key, OPENSSL_RAW_DATA, $iv_for_iv);
		$iv = base64_encode($iv);
		$iv = str_replace(array('+', '/'), array('-', '_'), $iv);
		return $encoded . $iv;
    }
    
    /*
    * Validate token
    */
    public static function validate($token, $product, $private_key, $expire_time = 0)
    {
    	$token = trim($token);
		$token_len = strlen($token);
		if($token_len <= 44)
		{
			return false;
		}
		
		$iv_encrypt = substr($token, -44);
		$iv_encrypt = str_replace(array('-', '_'), array('+', '/'), $iv_encrypt);
		$iv_encrypt = base64_decode($iv_encrypt);
		
		$token = substr($token, 0, -44);
		$token = str_replace(array('-', '_'), array('+', '/'), $token);
		$token = base64_decode($token);
		
		$iv_for_iv = substr(md5($private_key), 0, 16);
		$iv = openssl_decrypt($iv_encrypt, 'AES-256-CBC', $private_key, OPENSSL_RAW_DATA, $iv_for_iv);
		
		$encrypt_key = hash_hmac('sha256', $private_key, $iv);
		$str_decrypted = openssl_decrypt($token, 'AES-256-CBC', $encrypt_key, OPENSSL_RAW_DATA, $iv);
		
		$product_strlen = strlen($product);
		$product_strlen = 0 - $product_strlen;
		// check time
		$time = substr($str_decrypted, 0, $product_strlen);
		$time = strtotime($time);
		if($time ===-1 || $time === false)
		{
			return false;
		}
		$currTime = time();
		// expire time: 
		// -1: dont check expire
		if($expire_time != -1)
		{			
			$expire_time = $expire_time > 0 ? $expire_time : 180;
			if($currTime - $time > $expire_time)
			{
				return false;
			}
		}
		
		// check product
		$product_valid = substr($str_decrypted, $product_strlen);
		if($product != $product_valid)
		{
			return false;
		}
		return true;
   	}
    // end new token
	
	public static function encode($str_data, $private_key)
	{
		$str_encrypt = date('Y-m-d H:i:s');
		$str_encrypt .= '|' . $str_data;
		$iv = self::_randiv(16);
		$encrypt_key = hash_hmac('sha256', $private_key, $iv);
		$iv_for_iv = substr(md5($private_key), 0, 16);
		$token = openssl_encrypt($str_encrypt , 'AES-256-CBC', $encrypt_key, OPENSSL_RAW_DATA, $iv); 
		$encoded = base64_encode($token);
		$encoded = str_replace(array('+', '/'), array('-', '_'), $encoded);
		
		//$iv = base64_encode($iv);
		$iv = openssl_encrypt($iv , 'AES-256-CBC', $private_key, OPENSSL_RAW_DATA, $iv_for_iv);
		$iv = base64_encode($iv);
		$iv = str_replace(array('+', '/'), array('-', '_'), $iv);
		return $encoded . $iv;
	}
	
	public static function decode($token, $private_key, $expire_time = 0)
	{
		$rel = FALSE;
		$token = trim($token);
		$token_len = strlen($token);
		if($token_len <= 44)
		{
			return FALSE;
		}
		
		$iv_encrypt = substr($token, -44);
		$iv_encrypt = str_replace(array('-', '_'), array('+', '/'), $iv_encrypt);
		$iv_encrypt = base64_decode($iv_encrypt);
		
		$token = substr($token, 0, -44);
		$token = str_replace(array('-', '_'), array('+', '/'), $token);
		$token = base64_decode($token);
		
		$iv_for_iv = substr(md5($private_key), 0, 16);
		$iv = openssl_decrypt($iv_encrypt, 'AES-256-CBC', $private_key, OPENSSL_RAW_DATA, $iv_for_iv);
		
		$encrypt_key = hash_hmac('sha256', $private_key, $iv);
		$str_decrypted = openssl_decrypt($token, 'AES-256-CBC', $encrypt_key, OPENSSL_RAW_DATA, $iv);
		
		$separator_pos = stripos($str_decrypted, '|');
		if($separator_pos === FALSE)
		{
			return FALSE;
		}
		
		// check time
		$time = substr($str_decrypted, 0, $separator_pos);
		$time = strtotime($time);
		if($time ===-1 || $time === false)
		{
			return FALSE;
		}
		$currTime = time();
		// expire time: 
		// -1: dont check expire
		if($expire_time != -1)
		{			
			$expire_time = $expire_time > 0 ? $expire_time : 180;
			if($currTime - $time > $expire_time)
			{
				return FALSE;
			}
		}
		
		$rel = substr($str_decrypted, $separator_pos + 1);
		
		return $rel;
	}
}