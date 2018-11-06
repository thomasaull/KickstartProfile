<?php namespace ProcessWire;

class ContactForm
{
  public static function submit($data)
  {
    RestApiHelper::checkAndSanitizeRequiredParameters($data, ['name|string', 'email|string', 'message|string']);

    $mailContent = "";
		$mailContent .= $data->message . "\n";
		$mailContent .= "\n";
    $mailContent .= "– " . $data->name;

    $email = wire('pages')->get(1029)->email;

    $mail = wireMail();
		$mail->from($data->email);
		$mail->to($email);
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
