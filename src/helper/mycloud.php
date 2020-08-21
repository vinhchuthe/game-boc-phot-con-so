<?php 
class mycloud
{
	private $_cloud_secret_key = CLOUD_SECRET_KEY;
	private $_request_url = CLOUD_REQUEST_URL;
	private $_response_url_img = CLOUD_RESPONSE_URL_IMAGE;
	private $_response_url_vid = CLOUD_RESPONSE_URL_VIDEO;

	function _error($result)
	{
		$result = json_decode($result, true);
		if(isset($result['status_code'])&& $result['status_code']!='200')
		{
		    show_error($result['description']);
		}
	}

	function _is_ok($result)
	{
		$chk = true;
		$result = json_decode($result, true);
		if(isset($result['status_code'])&& $result['status_code']!='200')
		{
		    $chk = false;
		}
		return $chk;
	}

	function _getext($filename)
	{
		if(false === strpos($filename, '.'))
		{
			return 'txt';
		}
		$x = explode('.', $filename);
		return end($x);
	}

	function _get_option($curl,$url)
	{
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPGET, true);
	}

	function list_all($path = '', $show_error = false)
	{
		$param = 'secret_key='.$this->_cloud_secret_key.'&dirname='.$path;
		$url = $this->_request_url.'ls?'.$param;

		$curl = curl_init();
		$this->_get_option($curl, $url);
		$result = curl_exec($curl);
		curl_close($curl);

		if($show_error) $this->_error($result);
		return json_decode($result, true);
	}

	function list_files($path, $show_error = false)
	{
		$param = 'secret_key='.$this->_cloud_secret_key.'&dirname='.$path;
		$url = $this->_request_url.'ls?'.$param;

		$curl = curl_init();
		$this->_get_option($curl, $url);
		$result = curl_exec($curl);
		curl_close($curl);

		if($show_error) $this->_error($result);
		$data = array();
		$temp = json_decode($result, true);
		if(isset($temp['files']))
		{
			$data = $temp['files'];
		}
		return $data;
	}

	function list_directories($path='', $show_error = false)
	{
		$param = 'secret_key='.$this->_cloud_secret_key.'&dirname='.$path;
		$url = $this->_request_url.'ls?'.$param;

		$curl = curl_init();
		$this->_get_option($curl, $url);
		$result = curl_exec($curl);
		curl_close($curl);

		if($show_error) $this->_error($result);
		$data = array();
		$temp = json_decode($result, true);
		if(isset($temp['directories']))
		{
			$data = $temp['directories'];
		}
		return $data;
	}

	function get_file_info($filename, $show_error = false)
	{
		$param = 'secret_key='.$this->_cloud_secret_key.'&filename='.$filename;
		$url = $this->_request_url.'get_file_info?'.$param;

		$curl = curl_init();
		$this->_get_option($curl, $url);
		$result = curl_exec($curl);
		curl_close($curl);

		if($show_error) $this->_error($result);
		return json_decode($result, true);
	}

	function upload($locpath, $rempath, $show_error = false)
	{
		// for php 5.5 or later
		//$imgInfo = getimagesize($locpath);
		//$filedata = new CurlFile($locpath, $imgInfo['mime'], $locpath);
		$filedata = new CurlFile($locpath);
		$data = array
		(
			'filename' => $rempath,
			'filedata' => $filedata,
			'overwrite'	=> 1,
			'secret_key' => $this->_cloud_secret_key,
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->_request_url . "upload");
		curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($curl);
		curl_close($curl);
		
		$chk = true;
		$arrResult = json_decode($result, true);
		if(isset($arrResult['status_code'])&& $arrResult['status_code']!='200')
		{
		    $chk = false;
		}
		
		if($show_error && !$chk)
		{
			$err_msg = '<div style="display:none;">'. $result .'</div>';
			$err_msg .= 'upload error';
			show_error($err_msg);
		}
		else
		{
			return $chk;
		}
	}

	function delete_file($filepath)
	{
		$chk = true;
		$param = 'secret_key='.$this->_cloud_secret_key.'&filename='.$filepath;
		$url = $this->_request_url.'delete?'.$param;

		$curl = curl_init();
		$this->_get_option($curl, $url);
		$result = curl_exec($curl);
		curl_close($curl);

		return $this->_is_ok($result);
	}

	function rename($old_file, $new_file, $show_error = false)
	{
		$param = 'secret_key='.$this->_cloud_secret_key.'&from='.$old_file.'&to='.$new_file;
		$url = $this->_request_url.'rename?'.$param;

		$curl = curl_init();
		$this->_get_option($curl, $url);
		$result = curl_exec($curl);
		curl_close($curl);
		
		$chk = true;
		$arrResult = json_decode($result, true);
		if(isset($arrResult['status_code'])&& $arrResult['status_code']!='200')
		{
		    $chk = false;
		}
		
		if($show_error && !$chk)
		{
			$err_msg = '<div style="display:none;">'. $result .'</div>';
			$err_msg .= 'upload product error';
			show_error($err_msg);
		}
		else
		{
			return $chk;
		}
	}

	function mkdir($path)
	{
		$param = 'secret_key='.$this->_cloud_secret_key.'&dirname='.$path;
		$url = $this->_request_url.'make_dir?'.$param;

		$curl = curl_init();
		$this->_get_option($curl, $url);
		$result = curl_exec($curl);
		curl_close($curl);

		return $this->_is_ok($result);
	}

	private function delete_empty_dir($path)
	{
		$param = 'secret_key='.$this->_cloud_secret_key.'&dirname='.$path;
		$url = $this->_request_url.'remove_dir?'.$param;

		$curl = curl_init();
		$this->_get_option($curl, $url);
		$result = curl_exec($curl);
		curl_close($curl);

	 	return $this->_is_ok($result);
	}

	function file_exists($file)
	{
		$param = 'secret_key='.$this->_cloud_secret_key.'&filename='.$file;
		$url = $this->_request_url.'get_file_info?'.$param;

		$curl = curl_init();
		$this->_get_option($curl, $url);
		$result = curl_exec($curl);
		curl_close($curl);

		return $this->_is_ok($result);
	}

	function move($old_file, $new_file, $show_error = false)
	{
		return $this->rename($old_file,$new_file, $show_error);
	}

	function directory_exists($path)
	{
		$param = 'secret_key='.$this->_cloud_secret_key.'&dirname='.$path;
		$url = $this->_request_url.'ls?'.$param;

		$curl = curl_init();
		$this->_get_option($curl, $url);
		$result = curl_exec($curl);
		curl_close($curl);
		return $this->_is_ok($result);
	}

	function download($url,$path)
	{
		/*
		$chk = true;
		$fp = fopen(FCPATH.$path, 'w');
	    $ch = curl_init($this->_response_url_img.'/'.$url);
	    curl_setopt($ch, CURLOPT_FILE, $fp);
	    $data = curl_exec($ch);
	    curl_close($ch);
	    fclose($fp);
	    if($data != 1)
	    {
	    	$chk = false;
	    }
	    return $chk;
	    */
	    /*
		Note: using full url like: https://abc.com/img.jpg
		*/
		$chk = true;
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	    $raw=curl_exec($ch);
	    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close ($ch);
	    if($code == 200)
	    {
		    $fp = fopen($path,'x');
		    fwrite($fp, $raw);
		    fclose($fp);
	    }
	    else
	    {
	    	$chk = false;
	    }
	    return $chk;
	}

	function check_empty_dir($dir)
	{
		$chk = false;
		if(count($this->list_directories($dir)) && count($this->list_files($dir)))
		{
			$chk = true;
		}
		return $chk;
	}

	function delete_dir($directory)
	{
	    if($this->check_empty_dir($directory))
		{
  			return false;
		}
	    else
		{
	        # here we attempt to delete the file/directory
	        if(!($this->delete_empty_dir($directory)))
	        {
	            # if the attempt to delete fails, get the file listing
	            $filelist = @$this->list_files($directory);
	            # loop through the file list and recursively delete the FILE in the list
	            foreach($filelist as $file)
	            {
	                $this->delete_file($directory.'/'.$file);
	            }
	            $dirlist = @$this->list_directories($directory);
	            foreach($dirlist as $dir)
	            {
	            	$this->delete_dir($dir);
	            }
	            # if the file list is empty, delete the DIRECTORY we passed
	            $this->delete_empty_dir($directory);
	        }
	    }
	    return true;
	}
}
