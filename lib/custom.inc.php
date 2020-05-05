<?php
/*
first author: indra.sadik@gmail.com 
14 March 2019
deklarasikan di sini untuk fungsi custom atau pengembangan
*/
// be sure that this file not accessed directly
if (!defined('INDEX_AUTH')) {
    die("can not access this file directly");
} elseif (INDEX_AUTH != 1) {
    die("can not access this file directly");
}

/**
 * menampilkan badge ebook yang dapat di download
 * 14 March 2019
 */
function showBadge($_biblio_id){
	
	global $dbs;
	
	$att = $dbs->query("SELECT distinct files.mime_type as mime_type, files.file_title as file_title,
  				files.file_id as file_id, files.file_desc as file_desc,
  				files.file_url as file_url, biblio_attachment.biblio_id as biblio_id
  				FROM biblio_attachment
  				LEFT JOIN files ON biblio_attachment.file_id = files.file_id
  				WHERE biblio_attachment.biblio_id = '$_biblio_id'");

	$jml_att = $att->fetch_row();

    $_mime = array( 'application/pdf', 'application/zip', 'image/jpeg', 'text/uri-list' );

	$_btn = '';
	
	$_btn_ebook = false;

    foreach ($att as $attachment_d) {
	
		if (in_array(strtolower($attachment_d['mime_type']), $_mime ) AND !$_btn_ebook ) {
    
			$_btn .= '<span style="padding:2em; border-radius: 30px; background: #4A26FD; padding: .5em; color:#fff; width:90px;" class="btn btn-success btn-sm"><i class="ion-bookmark" style="font-size: 15px; color:#fff;"></i> &nbsp; e-book</span> ';
			
			$_btn_ebook = true;
		}
	}

	#return $_btn . '<span style="padding:.5em; border-radius: 30px; background: #41C300; padding: .5em; color:#fff; width:90px;" class="btn btn-info btn-sm"><i class="ion-ios-book" style="font-size: 15px; color:#fff;"></i> &nbsp; printed</span>';
	return '';
}

// Function to get the client IP address
function get_client_ip() {
	
	$ipaddress = '';
	
	if (getenv('HTTP_CLIENT_IP')) $ipaddress = getenv('HTTP_CLIENT_IP');
	
	else if(getenv('HTTP_X_FORWARDED_FOR')) $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	
	else if(getenv('HTTP_X_FORWARDED')) $ipaddress = getenv('HTTP_X_FORWARDED');
	
	else if(getenv('HTTP_FORWARDED_FOR')) $ipaddress = getenv('HTTP_FORWARDED_FOR');
	
	else if(getenv('HTTP_FORWARDED')) $ipaddress = getenv('HTTP_FORWARDED');
	
	else if(getenv('REMOTE_ADDR')) $ipaddress = getenv('REMOTE_ADDR');
	
	else $ipaddress = 'UNKNOWN';
	
	return $ipaddress;
}

// get email spam and update counter
// default return true 
function not_email_spam($_email){
	
	global $dbs;

	$_not_spam = true;

	$query 	= sprintf('SELECT * FROM spam_email WHERE email="%s"', $_email);
	
	$_ 		= $dbs->query($query);

	foreach ($_ as $_spam) {

		if($_spam['email'] == $_email){

			$_not_spam = false;

			$_counter = (int) $_spam['counter'] + 1;
			
			$query 	= sprintf('UPDATE `spam_email` SET `counter`=%d WHERE `email`="%s"', $_counter, $_email);
		
			$dbs->query($query);
		}
	}

	return $_not_spam;
}
# to get simply patch under get variable
function uri_secure($_ = array(), $_regx = "/[^-a-z0-9\\/]+/i"){

	$_subs = '';
	
	foreach($_ as $_var=>$_val) $_[$_var] = preg_replace($_regx, $_subs, $_val);
	
	return $_;
}

function uri_check($_='', $_regx = "/[^-a-z0-9\\/]+/i"){

	return preg_match($_regx, $_);
}

function notif_comment_to_email($nama, $email, $komentar, $tgl, $cfg) {

    require LIB."phpmailer/src/PHPMailer.php";
    require LIB."phpmailer/src/SMTP.php";
    require LIB."phpmailer/src/Exception.php";

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP(); 

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

    $mail->CharSet="UTF-8";
    $mail->Host = $cfg['Host'];
    $mail->SMTPDebug = $cfg['SMTPDebug']; 
    $mail->Port = $cfg['Port'] ; //465 or 587

    $mail->SMTPSecure = $cfg['SMTPSecure'];  
    $mail->IsHTML(true);

    //Authentication
    $mail->Username = $cfg['Username'];
    
    //Set Params
    $subject = $nama .' menuliskan komentar';
    $_msg   = $nama." ( " . $email ." ) menuliskan komentar :<br />\r\n";
    $_msg .= $komentar."\r\n<br />";
    $_msg .= "pada tanggal ".$tgl."<br />\r\n";

    //Set Params
    $mail->SetFrom($cfg['SentFrom']);
    
    foreach($cfg['AddAddress_a'] as $_a) $mail->AddAddress($_a);

    $mail->Subject = $subject;
    $mail->Body = $_msg;

    if(!$mail->Send()) return $mail->ErrorInfo;
    
    else return "Message has been sent";
}