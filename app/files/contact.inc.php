<?php
include_once ( 'layout/header.inc.php' );

$mail = new PHPMailer();

$showForm = true;
$showError = false;
	
if(is_array($_POST['contact']) && isset($_POST['submitForm'])) {
  $contact = $_POST['contact'];
  
  if
    (
      strlen($contact['name']) > 0 && trim($contact['name']) &&
      strlen($contact['subject']) > 0 && trim($contact['subject']) &&
      strlen($contact['email']) > 0 && trim($contact['email']) &&
      strlen($contact['message']) > 0 && trim($contact['message']) &&
      isValidEmail($contact['email'])
    )
   {
        $mail = new PHPMailer();
        
        $mail->IsSMTP();  // telling the class to use SMTP
        $mail->Host     = "mailout.one.com"; // SMTP server
        
        $mail->From     = $contact['email'];
        $mail->FromName = $contact['name'];
        $mail->AddAddress('durk.hibma@ziggo.nl');
        $mail->AddAddress("arjenvanputten@gmail.com");
        
        $mail->Subject  = "Contactformulier ingevuld op Kubaarderhurdrindei.nl";
        $mail->Body     = $contact['message'];
        
        if($mail->Send()) {
        $showForm = false;
        echo 'Uw bericht is succesvol verzonden, u kunt zo spoedig mogelijk antwoord van ons verwachten.';
      } else {
        echo 'Excuses, het bericht kon niet worden verzonden. Probeer het op een later tijdstip nog eens.';
      }
     
   } else {
     $showError = true;
   }
}

if($showForm) {
?>
  <?php if($showError) { ?>
    <div class="ErrorMsg">
      U heeft een van de velden niet correct ingevuld. Controleer of u de verplichte velden heeft ingevuld en of u een juist e-mailadres heeft ingevoerd.
    </div>
<?php } ?>
  <h2>Contactgegevens</h2>
  <form action="<?=$_SERVER['REQUEST_URI'];?>" method="post" name="contactForm">
    <table class="signup">
      <tr>
        <th><label for="name">Naam *</label></th>
        <td><input type="text" name="contact[name]" value="<?=$contact['name']?>" class="large"/></td>
      </tr>
       <tr>
        <th><label for="name">Onderwerp *</label></th>
        <td><input type="text" name="contact[subject]" value="<?=$contact['subject']?>" class="large"/></td>
      </tr>     
      <tr>
        <th><label for="email">Emailadres *</label></th>
        <td><input type="text" name="contact[email]" value="<?=$contact['email']?>" class="large"/></td>
      </tr>
      <tr>
        <th><label for="message">Bericht *</label></th>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><textarea name="contact[message]"><?=$contact['message']?></textarea></td>
      </tr>
    </table>
      <input type="submit" name="submitForm" value="Verzenden" class="button"/>
    </form>
<?php
}
	include_once ( 'layout/footer.inc.php' );
?>