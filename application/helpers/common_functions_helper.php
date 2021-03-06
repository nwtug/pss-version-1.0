<?php
/**
 * This file contains functions that are used in a number of classes or views.
 *
 * @author Al Zziwa <azziwa@newwavetech.co.ug>
 * @version 1.0.0
 * @copyright PSS
 * @created 10/26/2015
 */




# Filter forwarded data to get only the passed variables
# In addition, it picks out all non-zero data from a URl array to be passed to a form
function filter_forwarded_data($obj, $urlDataArray=array(), $reroutedUrlDataArray=array(), $noOfPartsToIgnore=RETRIEVE_URL_DATA_IGNORE)
{
	# Get the passed details into the url data array if any
	$urlData = $obj->uri->uri_to_assoc($noOfPartsToIgnore, $urlDataArray);
	
	$dataArray = array();
	
	
	foreach($urlData AS $key=>$value)
	{
		if($value !== FALSE && trim($value) != '' && !array_key_exists($value, $urlData))
		{
			if($value == '_'){
				$dataArray[$key] = '';
			} else {
				$dataArray[$key] = $value;
			}
		}
	}
	
	#handle re-routed URL data
	if(!empty($reroutedUrlDataArray))
	{
		$urlInfo = $obj->uri->ruri_to_assoc(3);
		foreach($reroutedUrlDataArray AS $urlKey)
		{
			if(!empty($urlInfo[$urlKey]))
			{
				$dataArray[$urlKey] = $urlInfo[$urlKey];
			}
		}
	}
	
	return restore_bad_chars_in_array($dataArray);
}




# Restore bar chars in an array
function restore_bad_chars_in_array($goodArray)
{
	$badArray = array();
	
	foreach($goodArray AS $key=>$item)
	{
		$badArray[$key] = restore_bad_chars($item);
	}
	
	return $badArray;
}






# Replace placeholders for bad characters in a text passed in URL with their actual characters
function restore_bad_chars($goodString)
{
	$badString = '';
	$badChars = array("'", "\"", "\\", "(", ")", "/", "<", ">", "!", "#", "@", "%", "&", "?", "$", ",", " ", ":", ";", "=", "*");
	$replaceChars = array("_QUOTE_", "_DOUBLEQUOTE_", "_BACKSLASH_", "_OPENPARENTHESIS_", "_CLOSEPARENTHESIS_", "_FORWARDSLASH_", "_OPENCODE_", "_CLOSECODE_", "_EXCLAMATION_", "_HASH_", "_EACH_", "_PERCENT_", "_AND_", "_QUESTION_", "_DOLLAR_", "_COMMA_", "_SPACE_", "_FULLCOLON_", "_SEMICOLON_", "_EQUAL_", "_ASTERISK_");
	
	foreach($replaceChars AS $pos => $charEquivalent)
	{
		$badString = str_replace($charEquivalent, $badChars[$pos], $goodString);
		$goodString = $badString;
	}
	
	return $badString;
}





# Replace placeholders for bad characters in a text passed in URL with their actual characters
function replace_bad_chars($badString)
{
	$badChars = array("'", "\"", "\\", "(", ")", "/", "<", ">", "!", "#", "@", "%", "&", "?", "$", ",", " ", ":", ";", "=", "*");
	$replaceChars = array("_QUOTE_", "_DOUBLEQUOTE_", "_BACKSLASH_", "_OPENPARENTHESIS_", "_CLOSEPARENTHESIS_", "_FORWARDSLASH_", "_OPENCODE_", "_CLOSECODE_", "_EXCLAMATION_", "_HASH_", "_EACH_", "_PERCENT_", "_AND_", "_QUESTION_", "_DOLLAR_", "_COMMA_", "_SPACE_", "_FULLCOLON_", "_SEMICOLON_", "_EQUAL_", "_ASTERISK_");
	
	$goodString = $badString;
	foreach($badChars AS $pos => $char) $goodString = str_replace($char, $replaceChars[$pos], $goodString);
	
	return $goodString;
}



# Load labels of a page from a flat file into the variable list to be used on a view
function load_page_labels($page, $data=array())
{
	$lines = file(LABEL_DIRECTORY.$page.'.txt', FILE_IGNORE_NEW_LINES);
	$labels = array();
	
	foreach($lines AS $line)
	{
		#Read each variable and its value into the array
		if( strncmp($line,">>",2) == 0) 
		{
			$variable = substr($line, 2, (strpos($line, '=')-2) );
			$labels[$variable] = substr($line, (strpos($line, '=')+1));
		} 
		else if(!empty($labels[$variable]))  
		{
			$labels[$variable] .= '<br>'.$line;
		}
	}
	
	return array_merge($data, $labels);
}





# Encrypts the entered values
function encrypt_value($value)
{
	$num = strlen($value);
	$numIndex = $num-1;
	$newValue="";
		
	#Reverse the order of characters
	for($x=0;$x<strlen($value);$x++){
		$newValue .= substr($value,$numIndex,1);
		$numIndex--;
	}
		
	#Encode the reversed value
	$newValue = base64_encode($newValue);
	return $newValue;
}
	
	
# Decrypts the entered values
function decrypt_value($dvalue)
{
	#Decode value
	$dvalue = base64_decode($dvalue);
		
	$dnum = strlen($dvalue);
	$dnumIndex = $dnum-1;
	$newDvalue = "";
		
	#Reverse the order of characters
	for($x=0;$x<strlen($dvalue);$x++){
		$newDvalue .= substr($dvalue,$dnumIndex,1);
		$dnumIndex--;
	}
	return $newDvalue;
}








# Generates an 8-character temporary password for the user - this is a one time case and system does not keep un-encrypted copy
function generate_temp_password()
{
	$numbers = '0123456789';
	$letters = 'abcdefghijklmnopqrstuvwxyz';
	$characters = '_!-*.';
	$time = strtotime('now');
	
	$password = array();
	$password[0] = $letters[rand(0, strlen($letters)-1)];
	$password[1] = $letters[rand(0, strlen($letters)-1)];
	$password[2] = $numbers[rand(0, strlen($numbers)-1)];
	$password[3] = $characters[rand(0, strlen($characters)-1)];
	$password[4] = $time[rand(0, strlen($time)-1)];
	$password[5] = strtoupper($letters[rand(0, strlen($letters)-1)]);
	$password[6] = $letters[rand(0, strlen($letters)-1)];
	$password[7] = $time[rand(0, strlen($time)-1)];
	
	return implode('',$password);
}




# Minify a list of files
function minify_js($page, $files) 
{
	$string = "";
	# Minify and show the minified version
	if(MINIFY)
	{
		$fileLocation = HOME_URL.'assets/js/';
		# If the file exists, just return the file, else create the minified version
		if(!file_exists($fileLocation.'__'.$page.'.min.js'))
		{
			require_once(HOME_URL.'external_libraries/minifiers/JSMin.php');
			foreach($files AS $file)
			{
				$min = JSMin::minify(file_get_contents($fileLocation.$file));
  				file_put_contents($fileLocation.'__'.$page.'.min.js', $min, FILE_APPEND);
			}
		}
		$string = "<script type='text/javascript' src='".base_url()."assets/js/__".$page.".min.js'></script>"; 
	}
	# List the files out one by one
	else
	{
		foreach($files AS $file) $string .= "<script type='text/javascript' src='".base_url()."assets/js/".$file."'></script>";
	}
	
	return $string;
}


# Function to redirect a user from an iframe
function redirect_from_iframe($url)
{
	echo "<script type='text/javascript'>window.top.location.href = '".$url."';</script>";exit;
}




# Returns the AJAX constructor to a page where needed
function get_ajax_constructor($needsAjax, $extraIds=array())
{
	$ajaxString = "";
	
	if($needsAjax)
	{
		$ajaxString = "<script language=\"javascript\"  type=\"text/javascript\">".
							"var http = getHTTPObject();";
							
		if(!empty($extraIds))
		{
			foreach($extraIds AS $id)
			{
				$ajaxString .=  "var ".$id." = getHTTPObject();";
			}
		}					
		$ajaxString .=  "</script>";
	}
	return $ajaxString;
}





# Returns the passed message with the appropriate formating based on whether it is an error or not
function format_notice($obj, $msg)
{
	$style = "border-radius: 5px;
	-moz-border-radius: 5px;";
	
	# Error message. look for "WARNING:" in the message
	if(strcasecmp(substr($msg, 0, 8), 'WARNING:') == 0)
	{
		$msgString = "<table width='100%' border='0' cellspacing='0' cellpadding='5' style=\"".$style."border:0px;\">".
						"<tr><td width='1%' class='error' style='border:0px;padding:5px;min-width:0px;vertical-align:top;' nowrap>".str_replace("WARNING:", "<img src='".base_url()."assets/images/warning.png' border='0'/></td><td  class='error'  style='font-size:13px; color:#000;border:0px; word-wrap: break-word;' width='99%' valign='middle'>", $msg)."</td></tr>".
					  "</table>";
	}
	# Error message. look for "ERROR:" in the message
	else if(strcasecmp(substr($msg, 0, 6), 'ERROR:') == 0)
	{
		$msgString = "<table width='100%' border='0' cellspacing='0' cellpadding='5' style=\"".$style."border:0px;\">".
						"<tr><td class='error' style='border:0px;padding:5px;min-width:0px;vertical-align:top;' width='1%' nowrap>".str_replace("ERROR:", "<img src='".base_url()."assets/images/error.png'  border='0'/></td><td  width='99%' class='error'  style='font-size:13px;border:0px; word-wrap: break-word;' valign='middle'>", $msg)."</td></tr>".
					  "</table>";
	}
	
	#Normal Message
	else
	{
		$msgString = "<table width='100%' border='0' cellspacing='0' cellpadding='5' style=\"".$style."border:0px;\">".
						"<tr><td class='message' style='border:0px; word-wrap: break-word;'>".$msg."</td></tr>".
					  "</table>";
	}
	
	return $msgString;
}





#Function to fomart a notice string to the appropriate color
function format_status($status)
{
	$statusString = str_replace('_', ' ', $status);
	
	if(strtolower($status) == 'pending' || strtolower($status) == 'suspended' || strtolower($status) == 'inactive' || strtolower($status) == 'unopened')
	{
		$statusString = "<span class='orange'>".$status."</span>";
	}
	elseif(strtolower($status) == 'joined' || strtolower($status) == 'active' || strtolower($status) == 'already_member' || strtolower($status) == 'member' || strtolower($status) == 'accepted')
	{
		$statusString = "<span class='green'>".$status."</span>";
	}
	elseif(strtolower($status) == 'bounced' || strtolower($status) == 'blocked' || strtolower($status) == 'deleted' || strtolower($status) == 'not_eligible' || strtolower($status) == 'declined' || strtolower($status) == 'cancelled')
	{
		$statusString = "<span class='red'>".$status."</span>";
	}
	elseif(strtolower($status) == 'read' || strtolower($status) == 'clicked')
	{
		$statusString = "<span class='blue'>".$status."</span>";
	}
	
	return $statusString;
}





# Replace content links
function replace_content_links($stringWithLinks, $linkArray, $classes=array())
{
	$finalString = $stringWithLinks;
	foreach($linkArray AS $key=>$value)
	{
		$finalString = str_replace('<'.$key.'>', "<a href='".$value."' ".(!empty($classes)? "class='".implode(' ',$classes)."'": "")." >", $finalString);
		
		$finalString = str_replace('</'.$key.'>', "</a>", $finalString);
	}
	
	return $finalString;
}











#Function to format a number to a desired length and format
function format_number($number, $maxCharLength=100, $decimalPlaces=2, $singleChar=TRUE, $hasCommas=TRUE, $forceFloat=FALSE)
{
	#first strip any formatting;
    $number = (0+str_replace(",","",$number));
    #is this a number?
    if(!is_numeric($number)) return false;
	
	#now format it based on desired length and other instructions
    if($number > 1000000000000 && $maxCharLength < 13) return number_format(($number/1000000000000),$decimalPlaces, '.', ($hasCommas? ',': '')).($singleChar? 'T': ' trillion');
    else if($number > 1000000000 && $maxCharLength < 10) return number_format(($number/1000000000),$decimalPlaces, '.', ($hasCommas? ',': '')).($singleChar? 'B': ' billion');
    else if($number > 1000000 && $maxCharLength < 7) return number_format(($number/1000000),$decimalPlaces, '.', ($hasCommas? ',': '')).($singleChar? 'M': ' million');
    else if($number > 1000 && $maxCharLength < 4) return number_format(($number/1000),$decimalPlaces, '.', ($hasCommas? ',': '')).($singleChar? 'K': ' thousand');
	else return number_format($number,((is_float($number) || $forceFloat)? $decimalPlaces: 0), '.', ($hasCommas? ',': ''));
}





# Format telephone for display
function format_telephone($number)
{
	if(strlen($number) > 10) {
		$oldNumber = $number;
		$number = substr($number, -10);
		$countryCode = str_replace($number, '', $oldNumber);
	}
	
	if(preg_match( '/^(\d{3})(\d{3})(\d{4})$/', $number,  $matches))
	{
    	$result = (!empty($countryCode)? '+'.$countryCode.' ': '').'('.$matches[1] . ') '.$matches[2] .'-'.$matches[3];
    	return $result;
	}
	else return $number;
}



# Format ID for display
function format_id($id)
{
	return !empty($id)? "SS".str_pad(dechex($id),5,'0',STR_PAD_LEFT): "";
}


# Extract an ID from the API user friendly ID
function extract_id($id)
{
	return !empty($id)? hexdec(substr($id, 2)): "";
}


# generate certificate id
function generate_certificate_number($id)
{
	return !empty($id)? strtoupper("SS".strrev(@date('Y').str_pad(dechex($id),8,'0',STR_PAD_LEFT))): "";
}

# extract organization id from certificate number
function extract_certificate_number($number)
{
	return !empty($number)? hexdec(substr(strrev(substr($number, 2)), 4)*1): "";
}




#Remove an array item from the given items and return the final array
function remove_item($item, $fullArray)
{
	#First remove the item from the array list
	unset($fullArray[array_search($item, $fullArray)]);
	
	return $fullArray;
}




	
# Remove commas 
function remove_commas($number)
{
	return str_replace(",","",$number);
}


# apply the link open type by getting the real html code equivalents
function apply_open_type($type, $extraClasses='')
{
	if($type == 'pop_up') return " class='shadowbox".(!empty($extraClasses)? ' '.$extraClasses: '')."' ";
	else if($type == 'new_window') return " target='_blank' ";
	else return '';
}

	
	


#Function to get current user's IP address
function get_ip_address()
{
	$ip = "";
	if ( isset($_SERVER["REMOTE_ADDR"]) )    
	{
    	$ip = ''.$_SERVER["REMOTE_ADDR"];
	} 
	else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) )    
	{
    	$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	} 
	else if ( isset($_SERVER["HTTP_CLIENT_IP"]) )
	{
    	$ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	
	return (ENVIRONMENT == 'development' || (!empty($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') !== FALSE))? '': $ip;
}





# Get the user device
function get_user_device()
{
	if(stripos($_SERVER['HTTP_USER_AGENT'],"iPod")) return 'iPod';
	if(stripos($_SERVER['HTTP_USER_AGENT'],"iPhone")) return 'iPhone';
	if(stripos($_SERVER['HTTP_USER_AGENT'],"iPad")) return 'iPad';
	if(stripos($_SERVER['HTTP_USER_AGENT'],"Android")) return 'Android';
	if(stripos($_SERVER['HTTP_USER_AGENT'],"web")) return 'Web';
	
	return 'Other';
}



# Get the user browser
# IMPORTED from stackoverflow
function get_user_browser()
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
	 $ub = ""; 

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return "userAgent:".$u_agent.",name:".$bname.",version:".$version.",platform:".$platform.",pattern:".$pattern;
} 







# Add real html tags
function real_tags($string)
{
	$tags = array('<b red>', '<b green>', '<b>', '</b>');
	$realTags = array("<span class='bold red'>", "<span class='bold green'>", "<span class='bold'>", '</span>');	
		
	$finalString = $string;
	foreach($tags AS $index=>$tag){
		$finalString = str_replace($tag, $realTags[$index], $finalString);
	}
	
	return $finalString;
}





#Checks whether an array key that begins or ends in a value is in the passed array
function array_key_contains($keyPart, $array)
{
	$keys = array_keys($array);
	$theKey = '';
	$exists = FALSE;
	
	foreach($keys AS $key)
	{
		if(strpos($key, $keyPart) !== FALSE)
		{
			$exists = TRUE;
			$theKey = $key;
			break;
		}
	}
	
	return array('bool'=>$exists, 'key'=>$theKey);
}






# Formats an epoch date for display on the UI
function format_epoch_date($epochDate, $format)
{
	$epochDate = trim($epochDate);
	
	if(!empty($epochDate)){
		$dateObj = new DateTime();
		$dateObj->setTimestamp(intval($epochDate));
		return $dateObj->format($format);
	}
	else return '&nbsp;';
}



# Get column as array from a multi-row array
function get_column_from_multi_array($array, $column)
{
	$final = array();
	$firstItem = current($array);
	
	if(!empty($firstItem[$column])){
		foreach($array AS $row) array_push($final, $row[$column]);
	}

	return $final;
}





# Get the message stored in the session to be shown at the given area
function get_session_msg($obj)
{
	$msg = $obj->native_session->get('msg')? $obj->native_session->get('msg'): "";
	$obj->native_session->delete('msg');
	
	return $msg;
}



	
	
# Format text for inline-edit
function format_inline_edit($category, $string, $id)
{
	$matches = array();
	preg_match_all("^\[(.*?)\]^",$string, $matches, PREG_PATTERN_ORDER);
	
	foreach($matches[1] AS $key=>$phrase){
		$keyValue = explode('=', $phrase);
		$keyArray = explode('|', $keyValue[0]);
		$valueArray = explode('|', $keyValue[1]);
		
		$fieldHTML = "<a href='javascript:;' data-id='edit_".$keyArray[0]."_".$id."' class='edit-in-line' data-actionurl='".$category."/update_list_value/t/".$keyArray[0]."/v/".replace_bad_chars($valueArray[0])."/d/".$id.(!empty($valueArray[1])? "/h/".replace_bad_chars($valueArray[0]): '').(!empty($keyArray[1])? '/w/'.$keyArray[1]: '')."' title='Click to edit'>".str_replace(',', ', ', $valueArray[0])."</a>"; 
		
		$string = str_replace($matches[0][$key], $fieldHTML, $string);
	}
	
	return $string;
}






# Add data to unchageable user session
function add_to_user_session($obj, $data, $prefix='__', $ignore=array())
{
	foreach($data AS $key=>$value) if(!in_array($key, $ignore)) $obj->native_session->set($prefix.$key, $value);
}






#limit string length
function limit_string_length($string, $maxLength, $ignoreSpaces=TRUE, $endString='..')
{
    if (strlen(html_entity_decode($string, ENT_QUOTES)) <= $maxLength) return $string;
	
	if(!$ignoreSpaces)
	{
    	$newString = substr($string, 0, $maxLength);
		$newString = (substr($newString, -1, 1) != ' ')?substr($newString, 0, strrpos($newString, " ")) : $string;
	}
	else
	{
		$newString = substr(html_entity_decode($string, ENT_QUOTES), 0, $maxLength);
		if(strpos($newString, '&') !== FALSE)
		{
			$newString = substr($newString, 0, strrpos($newString, " "));
		}
	}
	
    return $newString.$endString;
}




# Function to upload a temporary file. 
# NOTE: This file could be removed by an optimization cron job when aged. 
# Use the upload_file function below for permanent file upload
function upload_temp_file($postData, $fileField, $newFileStub, $allowedExtensions='jpeg,jpg')
{
	# Check if the temp folder exists in the uploads folder. If it does not, create it
	if(!is_dir(UPLOAD_DIRECTORY.'temp')) mkdir(UPLOAD_DIRECTORY.'temp', 0777); 
	$extension =  strtolower(pathinfo($postData[$fileField]['name'],PATHINFO_EXTENSION));
	
	if(in_array($extension, explode(',',$allowedExtensions))) {
		$fileName = $newFileStub.strtotime('now').'.'.$extension;
		if (move_uploaded_file($postData[$fileField]["tmp_name"], UPLOAD_DIRECTORY.'temp/'.$fileName)){
			return $fileName;
		}
		else return "";
	}
}







# Function to upload a permanent file
function upload_file($postData, $fileField, $newFileStub, $allowedExtensions='jpeg,jpg')
{
	$extension =  strtolower(pathinfo($postData[$fileField]['name'],PATHINFO_EXTENSION));
	if(in_array($extension, explode(',',$allowedExtensions))) {
		$fileName = $newFileStub.strtotime('now').'.'.$extension;
		if (move_uploaded_file($postData[$fileField]["tmp_name"], UPLOAD_DIRECTORY.$fileName)){
			return $fileName;
		}
		else return "";
	}
}







# Function to upload many files at once
function upload_many_files($postData, $fileField, $newFileStub, $allowedExtensions='jpeg,jpg')
{
	# to store the new uploaded file names
	$newFiles = array();
	$count = 0;
	foreach($postData AS $file){
		$extension = !empty($file['name'])? strtolower(pathinfo($file['name'],PATHINFO_EXTENSION)): '';
		# check the extension
		if(in_array($extension, explode(',',$allowedExtensions))) {
			$fileName = $newFileStub.(strtotime('now')+$count).'.'.$extension;
			if (move_uploaded_file($file["tmp_name"], UPLOAD_DIRECTORY.$fileName)) array_push($newFiles, $fileName);
			$count++;
		}
	}
	
	return $newFiles;
}







#Function to provide the difference of two dates in a desired format
# Key to stop showing unless it is zero and then the next lowest will be returned
function format_date_interval($startDate, $endDate, $returnArray=TRUE, $minKey='days')
{
    $datetime1 = date_create($startDate);
	$datetime2 = (!empty($endDate)? date_create($endDate): date_create());
	$interval = date_diff($datetime1, $datetime2);
	
	$date['years'] = $interval->y;
	$date['months'] = $interval->m;
	$date['days'] = $interval->d;
	$date['hours'] = $interval->h;
	$date['minutes'] = $interval->i;
	
	$finalDate = array();
	$passedMinKey = FALSE;
	
	foreach($date AS $key=>$value) {
		if($key != $minKey && !empty($value)) {
			$finalDate[$key] = $value;
			if($passedMinKey) break;
		}
		
		if($key == $minKey) {
			$passedMinKey = TRUE;
			if(!empty($value)) $finalDate[$key] = $value;
		}
	}
	
	if(!$returnArray){
		$dateString = "";
		foreach($finalDate AS $key=>$value) $dateString .= $value.' '.$key.',';
		
		return trim($dateString, ',');
	}
	else return $finalDate;
}





# Create an image from the text given
function create_image_from_text($fileName, $text, $size = array('width'=>80, 'height'=>40))
{
	$imageObject = imagecreate($size['width'], $size['height']);
	# Create white background and black text with roboto font
	$bg = imagecolorallocate($imageObject, 255, 255, 255);
	$textColor = imagecolorallocate($imageObject, 0, 0, 0);
	$font = 5;
	
	# Write the string at the top left
	imagestring($imageObject, $font, 15, 15, $text, $textColor);
	imagepng($imageObject, UPLOAD_DIRECTORY.$fileName, 9);
}





# Make date a us date
function make_us_date($date)
{
	$datePart = strtok($date, ' ');
	$allParts = explode(' ', $date);
	
	# format 31/12/2015 ...
	if(strlen($datePart) == 10){
		$parts = explode('/',$datePart);
		return implode('/',array($parts[1],$parts[0],$parts[2])).' '.(count($allParts) > 1? $allParts[1]: '');
	}
	# format 31/Dec/2015 ...
	else if(strlen($datePart) == 11){
		return date('m/d/Y',strtotime(str_replace('/',' ',$datePart))).' '.(count($allParts) > 1? $allParts[1]: '');
	}
	else return $date;
}

#Validate an email address. If the email address is not required, then an empty string will be an acceptable
#value for the email address
function is_valid_email($email, $isRequired = true)
{
	$isValid = true;
	$atIndex = strrpos($email, "@");

	#if email is not required and is an empty string, do not check it. Return True.
	if(!$isRequired && empty($email)){
		return true;
	}
	if (is_bool($atIndex) && !$atIndex){
		$isValid = false;
	} else {
		$domain = substr($email, $atIndex+1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);

		if ($localLen < 1 || $localLen > 64) {
			# local part length exceeded
			$isValid = false;
		} else if ($domainLen < 1 || $domainLen > 255) {
			# domain part length exceeded
			$isValid = false;
		}  else if ($local[0] == '.' || $local[$localLen-1] == '.') {
			# local part starts or ends with '.'
			$isValid = false;
		} else if (preg_match('/\\.\\./', $local)) {
			# local part has two consecutive dots
			$isValid = false;
		} else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
			# character not valid in domain part
			$isValid = false;
		} else if (preg_match('/\\.\\./', $domain)) {
			# domain part has two consecutive dots
			$isValid = false;
		} else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
			# character not valid in local part unless
			# local part is quoted
			if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
				$isValid = false;
			}
		} else if (strpos($domain, '.') === FALSE) {
			# domain has no period
			$isValid = false;
		}

		/* if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
            # domain not found in DNS
            $isValid = false;
         } */
	}
	#return true if all above pass
	return $isValid;
}






# Generate a PDF document
function generate_pdf($document, $url, $action, $paperSetting=array('size'=>'A4','orientation'=>'portrait'))
{
	# get the external library that generates the PDF
	require_once(HOME_URL."external_libraries/dompdf/dompdf_config.inc.php");

	# Strip slashes if this PHP version supports get_magic_quotes
	$document = get_magic_quotes_gpc()? stripslashes($document): $document;
		
	$dompdf = new DOMPDF();
	$dompdf->load_html($document);
	$dompdf->set_paper($paperSetting['size'], $paperSetting['orientation']);
	$dompdf->render();
	
	# Store the entire PDF as a string in $pdf
	$pdf = $dompdf->output();
	# Write $pdf to disk
	file_put_contents($url, $pdf);

	# If the user wants to download the file, then stream it; otherwise display it in the browser as is.
	if($action == 'download')
	{
		$dompdf->stream($filename, array("Attachment" => true));
		exit(0);
	}
}






# Force file download
function force_download($folder, $file)
{
	if(file_exists(UPLOAD_DIRECTORY.$folder."/".$file))
	{
		if(strtolower(strrchr($file,".")) == '.pdf')
		{
			header('Content-disposition: attachment; filename="'.$file.'"');
			header('Content-type: application/pdf');
			readfile(UPLOAD_DIRECTORY.$folder."/".$file);
		}
		if(strtolower(strrchr($file,".")) == '.zip')
		{
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Description: File Transfer');
			header('Content-Disposition: attachment; filename="'.strtotime('now').str_replace('.','',get_ip_address()).'.zip"');
			header('Content-Transfer-Encoding: binary');
			header('Vary: Accept-Encoding');
			header('Content-Encoding: gzip');
			header('Keep-Alive: timeout=5, max=100');
			header('Connection: Keep-Alive');
			header('Transfer-Encoding: chunked');
			header('Content-Type: application/octet-stream');
			apache_setenv('no-gzip', '1');

		}
		else
		{
			redirect(base_url()."assets/uploads/".$folder."/".$file);
		}
	}
}




# Send download headers
function send_download_headers($filename) 
{
    # disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    # force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    # disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}



# Convert an array to csv
function array2csv(array &$array)
{
   if(count($array) == 0) 
   {
     return null;
   }
   ob_start();
   $df = fopen("php://output", 'w');
   fputcsv($df, array_keys(reset($array)));
   
   foreach($array AS $row) 
   {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}






	
# Function checks all values to see if they are all true and returns the value TRUE or FALSE
function get_decision($values_array, $defaultTo=FALSE)
{
	$decision = empty($values_array)? $defaultTo: TRUE;
	
	if(empty($values_array))
	{
		foreach($values_array AS $value)
		{
			if(!$value)
			{
				$decision = FALSE;
				break;
			}
		}
	}
	
	return $decision;
}






# get the document class to show the icon
function document_class($url)
{
	$pathParts = pathinfo(UPLOAD_DIRECTORY.$url);
	$extension = $pathParts['extension'];
	$class = '';
	
	switch($extension){
		case 'pdf':
			$class = 'pdf';
		break;
		
		case 'ppt':
		case 'pptx':
			$class = 'msppt';
		break;
		
		case 'doc':
		case 'docx':
			$class = 'msdoc';
		break;
		
		case 'xls':
		case 'xlsx':
			$class = 'msxls';
		break;
		
		default:
			$class = 'file';
		break;
	}
	
	return $class;
}




# get the quarter dates based on the desired formats
function get_quarter_date($quarter, $type)
{
	$quarterParts = explode('-',$quarter);
	switch($quarterParts[2]){
        case "third":
			if($type == 'start') return $quarterParts[1]."-01-01";
        	else return date('Y-m-d', strtotime($quarterParts[1]."-03 next month - 1 hour"));
        break;
		
		case "fourth":
			if($type == 'start') return $quarterParts[1]."-04-01";
        	else return date('Y-m-d', strtotime($quarterParts[1]."-06 next month - 1 hour"));
		 break;
		
		case "first":
        	if($type == 'start') return $quarterParts[0]."-07-01";
        	else return date('Y-m-d', strtotime($quarterParts[0]."-09 next month - 1 hour"));
		break;
		
		case "second":
        	if($type == 'start') return $quarterParts[0]."-10-01";
        	else return date('Y-m-d', strtotime($quarterParts[0]."-12-31"));
		break;
		
		case "all":
        	if($type == 'start') return $quarterParts[0]."-07-01";
        	else return date('Y-m-d', strtotime($quarterParts[1]."-06 next month - 1 hour"));
		break;
    }
}





# get current quarter
function get_current_quarter($return = 'year_quarter')
{
	# get today's month and determine which qarter it falls into
	$month = @date('n');
	$year = @date('Y');
	
	if($month < 4) {
		$quarter = 'third';
		$fy = ($year - 1).'-'.$year;
		
	} else if($month > 3 && $month < 7) {
		$quarter = 'fourth';
		$fy = ($year - 1).'-'.$year;
		
	} else if($month > 6 && $month < 10) {
		$quarter = 'first';
		$fy = $year.'-'.($year + 1);
		
	} else {
		$quarter = 'second';
		$fy = $year.'-'.($year + 1);
	}
	
	if($return == 'quarter') return $quarter;
	else if($return == 'financial_year') return $fy;
	else return $fy.'-'.$quarter;
}




# check if all items in this array are empty 
function is_empty_row($row){
	foreach($row AS $item) if(!empty($item)) return FALSE;
	
	# if you get here all items in the array are empty
	return TRUE;
}





# get a redirection url based on the given code
function get_redirect_url($code)
{
	$url = '';
	
	if($code == 'download_certificate') $url = 'organizations/settings/view/Y';
	else if($code == 'view_subscription') $url = 'organizations/settings/view/Y'; #TODO: Update to real subscription page
	
	return $url;
}











# checks user access rights to a certain area of the system
function check_access($obj, $permission='', $categories=array())
{
	# simply checking if the user is logged in
	if(empty($permission) && empty($categories)){
		$userId = $obj->native_session->get('__user_id');
		return !empty($userId);
	}
	
	# checking for real permission
	else {
		$list = $obj->native_session->get('__permissions');
		# checking access - go through the user's permissions
		if(!empty($list)){
			foreach($list AS $category=>$permissions){
				if(!empty($categories) && in_array($category, $categories)) return TRUE;
				if(!empty($permission) && in_array($permission, $permissions)) return TRUE;
			}
		}
	}
	return FALSE;
}



function logout_invalid_user($obj)
{
	if(!check_access($obj)){
		if(!$obj->native_session->get('__user_id')) $obj->native_session->set('msg','WARNING: You do not have a valid user session.');
		redirect(BASE_URL.'accounts/logout');
	}
}



?>