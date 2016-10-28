<?php 
/* 	
If you see this text in your browser, PHP is not configured correctly on this webhost. 
Contact your hosting provider regarding PHP configuration for your site.
*/

require_once('form_throttle.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (formthrottle_too_many_submissions($_SERVER["REMOTE_ADDR"]))
	{
		echo '{"MusePHPFormResponse": { "success": false,"error": "Too many recent submissions from this IP"}}';
	} 
	else 
	{
		emailFormSubmission();
	}
} 

function emailFormSubmission()
{
	$to = 'pankov2003@mail.ru';
	$subject = 'Заказ с сайта ProLanguage';
	
	$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/><title>' . htmlentities($subject,ENT_COMPAT,'UTF-8') . '</title></head>';
	$message .= '<body style="background-color: #ffffff; color: #000000; font-style: normal; font-variant: normal; font-weight: normal; font-size: 12px; line-height: 18px; font-family: helvetica, arial, verdana, sans-serif;">';
	$message .= '<h2 style="background-color: #eeeeee;">Новый заказ</h2><table cellspacing="0" cellpadding="0" width="100%" style="background-color: #ffffff;">'; 
	$message .= '<tr><td valign="top" style="background-color: #ffffff;"><b>Имя:</b></td><td>' . htmlentities($_REQUEST["name"],ENT_COMPAT,'UTF-8') . '</td></tr>';
	$message .= '<tr><td valign="top" style="background-color: #ffffff;"><b>Email:</b></td><td>' . htmlentities($_REQUEST["email"],ENT_COMPAT,'UTF-8') . '</td></tr>';
	$message .= '<tr><td valign="top" style="background-color: #ffffff;"><b>Телефон:</b></td><td>' . htmlentities($_REQUEST["phone"],ENT_COMPAT,'UTF-8') . '</td></tr>';

	$message .= '</table><br/><br/>';
	$message .= '<div style="background-color: #eeeeee; font-size: 10px; line-height: 11px;">Сайт: ' . htmlentities($_SERVER["SERVER_NAME"],ENT_COMPAT,'UTF-8') . '</div>';
	$message .= '<div style="background-color: #eeeeee; font-size: 10px; line-height: 11px;">IP отправителя: ' . htmlentities($_SERVER["REMOTE_ADDR"],ENT_COMPAT,'UTF-8') . '</div>';
	$message .= '</body></html>';
	$message = cleanupMessage($message);
	
	$formEmail = cleanupEmail($_REQUEST['Email']);
	$headers = 'From:   support@lorem-ipsum.com.ua' . "\r\n" . 'Reply-To: ' . $formEmail .  "\r\n" .'X-Mailer: Adobe Muse 7.4.30 with PHP/' . phpversion() . "\r\n" . 'Content-type: text/html; charset=utf-8' . "\r\n";
	
	$sent = @mail($to, $subject, $message, $headers);
	
	if($sent)
	{
		echo '{"FormResponse": { "success": true}}';

	}
	else
	{
		echo '{"MusePHPFormResponse": { "success": false,"error": "Failed to send email"}}';
	}
}

function cleanupEmail($email)
{
	$email = htmlentities($email,ENT_COMPAT,'UTF-8');
	$email = preg_replace('=((<CR>|<LF>|0x0A/%0A|0x0D/%0D|\\n|\\r)\S).*=i', null, $email);
	return $email;
}

function cleanupMessage($message)
{
	$message = wordwrap($message, 70, "\r\n");
	return $message;
}

require_once('../class/Curl.php');
if(isset($_POST['Email'])){
 
 $curl = new Curl();
 
 $data = array('grant_type'=>'client_credentials', 'client_id'=>'04915d98f53264e28e12530c09febd33', 'client_secret'=>'863e2a5b102b87bcc7c016e164cb097c');
 
 $token = $curl->post('https://api.sendpulse.com/oauth/access_token', $data);
 
 $token = json_decode($token, true);


$newAddress = array(
     'access_token' => $token['access_token'],
     'emails' => '[{"email" : "'.$_POST["email"].'", "variables" : {"Имя" : "'.$_POST['name'].'", "Телефон" : "'.$_POST["phone"].'"}}]'
 );
    
 $curl->post('https://api.sendpulse.com/addressbooks/405860/emails', $data=$curl->postEncode($newAddress));
 
}
?>
