<?php namespace ProcessWire;

require_once dirname(__FILE__) . "/ApiHelper.php";

class ContactForm
{
  public static function submit($data)
  {
    \TD::fireLog($data);
    ApiHelper::checkRequiredParameters($data, ['name', 'email', 'message']);

    // Sanitize
    $data->name = wire('sanitizer')->string($data->name);
    $data->email = wire('sanitizer')->string($data->email);
    $data->message = wire('sanitizer')->string($data->message);

    $mailContent = "";
		$mailContent .= $data->message . "\n";
		$mailContent .= "\n";
    $mailContent .= "– " . $data->name;
    
    $mail = wireMail();
		$mail->from($data->email);
		$mail->to("post@thomas-aull.de");
		$mail->subject("Kontaktformular von: " . $data->name);
		$mail->body($mailContent);
    $mail->bodyHTML(str_replace("\n", "<br/>", $mailContent));
    
    $sent = $mail->send();

    // Log in ProcessWire
    wire('log')->save('contactform', "$data->message – $data->name ($data->email)");
    
    if($sent == 0) throw new \Exception('Error sending mail', 500);

    return 'email sent';
  }
}
