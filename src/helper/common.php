<?php 
function isIdNumber($str)
{
    $len = strlen($str);
    if ($len >= 1)
    {
        if (preg_match('/^[1-9][0-9]*$/', $str))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    else
    {
        if (preg_match('/^[0-9]+$/', $str))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
}

function removeAllTags($text)
{
    $text = rawurldecode($text);
    $text = htmlspecialchars_decode(html_entity_decode($text, ENT_QUOTES | ENT_IGNORE, "UTF-8"),
                                    ENT_QUOTES | ENT_IGNORE);
    $text = trim($text);
    // PHP's strip_tags() function will remove tags, but it
    // doesn't remove scripts, styles, and other unwanted
    // invisible text between tags.  Also, as a prelude to
    // tokenizing the text, we need to insure that when
    // block-level tags (such as <p> or <div>) are removed,
    // neighboring words aren't joined.
    $text = preg_replace(
        array(
            // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',

            // Add line breaks before & after blocks
            '@<((br)|(hr))@iu',
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
        ),
        array(
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            "\n\$0",
            "\n\$0",
            "\n\$0",
            "\n\$0",
            "\n\$0",
            "\n\$0",
            "\n\$0",
            "\n\$0",
        ),
        $text);
	$text = preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
    // Remove all remaining tags and comments and return.
    return strip_tags($text);
}

function removeVietChars($str)
{
	$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
	$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
	$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
	$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
	$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
	$str = preg_replace("/(ỳ|ý|y|ỷ|ỹ)/", 'y', $str);
	$str = preg_replace("/(đ)/", 'd', $str);
	$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
	$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
	$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
	$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
	$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
	$str = preg_replace("/(Ỳ|Ý|Y|Ỷ|Ỹ)/", 'Y', $str);
	$str = preg_replace("/(Đ)/", 'D', $str);
	//$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
	return $str;
}

function removeInvalidCharacter($str)
{
	$patterns[] = '/(,)/';
	$patterns[] = '/(\.)/';
	$patterns[] = '/(?)/';
	$patterns[] = '/(:)/';
	$patterns[] = "/(:|'|`|!|@|#|\\$|%|\^|\&|\*|\(|\)|\+|=|}|{|\\\|\/)/";
	$patterns[] = '/"/';
	$patterns[] = '/\|/';
	$patterns[] = '/(~)/';
	$str = preg_replace($patterns, '', $str);
	$str = preg_replace('!\s+!', ' ', $str);
	$str = preg_replace('/[^A-Za-z0-9\-]/', '', $str);
	return $str;
}

function url_title($title, $ext = '')
{
	$title = removeAllTags($title);
	$title = mb_strtolower($title, 'UTF-8');
	$title = str_replace(array("&nbsp;", "\0", "\0x00"), "", $title);
	$title = str_replace(" ", "-", str_replace("&*#39;","",$title));
	$title = removeVietChars($title);
	$title = removeInvalidCharacter($title);
	$len = strlen($title);
	// ascii code: a = 97 -> z = 122, "-" = 45, "_" = 95, 0 = 48, 9= 57
	$validStr = "";
	for($i=0; $i < $len; $i++)
	{
		$asciiCode = ord($title[$i]);
		if($asciiCode == 45 || $asciiCode == 95 || ($asciiCode >= 97 && $asciiCode <= 122) || ($asciiCode >= 48 && $asciiCode <= 57))
		{
			$validStr .= $title[$i];
		}
	}
	$validStr = $validStr != '' && $ext != '' ? $validStr . $ext : $validStr;
	return $validStr;
}