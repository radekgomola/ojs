<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Váš e-mail byl odeslán</title>
    <meta http-equiv="refresh" content="1;url=https://journals.muni.cz/pedor">
</head>
<body>
<?php
require '../PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer();
// Set PHPMailer to use the sendmail transport
$mail->isSendmail();
//Set who the message is to be sent from
$mail->setFrom('pedor_predplatne@journals.muni.cz', 'Pedagogicka orientace');
//Set who the message is to be sent to
$mail->addAddress('admin@cpds.cz', 'Admin CPDS');
//$mail->addAddress('radek.gomola@gmail.com', 'Admin CPDS');
//Set the subject line
$mail->Subject = '[PedOr] Predplatne';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$volume = '';
for($i=0; $i<count($_POST['fvol']); $i++){
  $volume .= $_POST['fvol'][$i]. ' <br />';
}    

$body = 
'Jméno a příjmení: ' . $_POST['title_before'] . ' ' . $_POST['fname'] . ' ' . $_POST['fsurname'] .', '.$_POST['title_after'].'<br /><br />'.
'Organizace: '.$_POST['organisation'].'<br /><br />'.
'Adresa: <br />'.
$_POST['fstreet'].' <br />'.
$_POST['fpsc'].', '.$_POST['ftown'].'<br />'.
$_POST['state'].'<br /><br />'.
'Telefon: '.$_POST['phone_number'].'<br />'.
'E-mail: '.$_POST['femail'].'<br /><br />'.
'Ročníky: <br />'.
$volume.' <br />'.
'Způsob doručení: '.$_POST['ftype'].' <br />'.
'Doručovací adresa: <br />'.$_POST['address'].' <br /><br />'.
'Komentář: <br />'.$_POST['comment'].' <br /><br />'.
'------------------------------------------------ <br />'.
'Toto je automaticky generovaný e-mail. Neodpovídejte na něj!' ;

$mail->WordWrap = 80;
$mail->msgHTML($body);

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Vaše objednávka byla přijata! <br /> <br />";
    echo "Pokud nebudete přesměrováni automaticky pokračujte na hlavní stránku <a href='https://journals.muni.cz/pedor' target='_self'>Pedagogické orientace</a> ";
}
?>
</body>
</html>
