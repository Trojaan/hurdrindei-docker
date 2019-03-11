<?php

include_once ( 'layout/header.inc.php' );

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

//use League\OAuth2\Client\Provider\Google;

// require 'include/phpmailer/Exception.php';
// require 'include/phpmailer/PHPMailer.php';
// require 'include/phpmailer/SMTP.php';
// require 'include/phpmailer/OAuth.php';

$showForm = true;
$showError = false;

$adultAgeInYears = 12;
 
function isAdult($ageInYears, $adultAgeInYears)
{
	if(!isset($ageInYears))
	{
		return true;
	}
	return $ageInYears > $adultAgeInYears;
}

//Controleren of formulier is verzonden.
if(is_array($_POST['signup']) && isset($_POST['submitForm'])) {
  $signup = $_POST['signup'];
	
  //Controleren of verplichte velden zijn ingevuld en of email juist is ingevuld.
  if
    (
      strlen($signup['firstName']) > 0 && trim($signup['firstName']) &&
      strlen($signup['lastName']) > 0 && trim($signup['lastName']) &&
      strlen($signup['street']) > 0 && trim($signup['street']) &&
      strlen($signup['houseNr']) > 0 && trim($signup['houseNr']) &&
      strlen($signup['postalcode']) == 6 && trim($signup['postalcode']) &&
      strlen($signup['place']) > 0 && trim($signup['place']) &&
      strlen($signup['distance']) > 0 && trim($signup['distance']) &&
      strlen($signup['medal']) > 0 && trim($signup['medal']) &&
      $signup['dateBirthDay'] != 0 &&
      $signup['dateBirthMonth'] != 0 &&
      $signup['dateBirthYear'] != 0 &&
  isValidEmail($signup['email'])
    )
   {
     $showDate = $signup['dateBirthDay'].'-'.$signup['dateBirthMonth'].'-'.$signup['dateBirthYear'];
     
     $signup['dateBirth'] = date('Y-m-d H:i:s',mktime(0,0,0,$signup['dateBirthMonth'],$signup['dateBirthDay'],$signup['dateBirthYear']));

     $sql = "INSERT INTO
                      `[PREFIX]_signups`                     
                     SET
                      `firstName` = '".addslashes($signup['firstName'])."',
                      `lastName` = '".addslashes($signup['lastName'])."',
                      `middleName` = '".addslashes($signup['middleName'])."',
                      `street` = '".addslashes($signup['street'])."',
                      `houseNr` = '".addslashes($signup['houseNr'])."',
                      `postalcode` = '".addslashes($signup['postalcode'])."',
                      `gender` = '".addslashes($signup['gender'])."',
                      `place` = '".addslashes($signup['place'])."',
                      `dateBirth` = '".addslashes($signup['dateBirth'])."',
                      `email` = '".addslashes($signup['email'])."',
                      `distance` = '".addslashes($signup['distance'])."',
                      `medal` = '".addslashes($signup['medal'])."'    
            ";
      
      //Gegevens toevoegen aan database
      $resSignups = Xmysql_query($sql);
      
      //Wanneer gegevens succesvol zijn ingevuld
      if($resSignups) {
        
        if(strlen($signup['email']) > 0) {
          $email = '<tr>
                      <td style="font-weight: bold;">Adres</th>
                      <td>'.$signup['email'].'</td>
                    </tr>';
          $emailAlt = 'Email: '.$signup['email'];
        }else {
          $email = '';
          $emailAlt = '';
        }
        if(isAdult($signup['age'], $adultAgeInYears) && $signup['medal'] == 'ja') {
          $medal = '<tr>
                      <td style="font-weight: bold;">Medaille</td>
                      <td>&#8364;&nbsp; '.$signup['metalPrice'].',-</td>
                    </tr>';
          $medalAlt = 'Medaille: '.$signup['metalPrice'];
        }else {
          $medal = '';
          $medalAlt = '';
        }

        if($signup['gender'] == 'm')
          $gender = 'Man';
        else 
          $gender = 'Vrouw';

        //Maak het object PHPMailer aan.
        $mail = new PHPMailer();
        $mail->SetLanguage( 'nl', 'include/languages/' );

       // $mail->SMTPDebug = 2;
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "send.one.com"; 
        $mail->Port = 587;
        $mail->Username = 'noreply@kubaarderhurdrindei.nl';
        $mail->Password = 'QbrdCty#06'; 
    
        
        
        // $mail->Username = "kubaarderhurdrindei1987@gmail.com";
        // $mail->Password = "QbrCty2017!"; 
        
        $mail->From     = "noreply@kubaarderhurdrindei.nl";
        $mail->FromName = SITE_NAME;
        $mail->AddAddress($signup['email']);
        $mail->IsHTML(true);
        
        $mail->Subject  = "Aanmelding Kubaarder Hurdrindei " . date('Y');

        //Opbouwen van HTMLMail
        $mail->Body     = "
                           <h1>Aanmelding Kubaarder Hurdrindei " . date('Y')."</h1>
                           <p>Hartelijk dank voor uw aanmelding. Uw aanmelding is verwerkt in onze administratie. Neem onderstaande gegevens mee op de dag van de loop!</p>
                           <h2>Contactgegevens</h2>
                           <table style=\"text-align: left;\">
                            <tr>
                              <td style=\"font-weight: bold; width: 270px\">Naam</th>
                              <td>". $signup['firstName'] . ( isset($signup['middleName']) ? ' '.$signup['middleName'] : '' ) . ' ' . $signup['lastName'] ."</td>
                            </tr>
                            <tr>
                              <td style=\"font-weight: bold;\">Adres</th>
                              <td>".$signup['street'] . ' ' . $signup['houseNr']."</td>
                            </tr>
                            <tr>
                              <td style=\"font-weight: bold;\">Postcode / Woonplaats</th>
                              <td>".$signup['postalcode'] . ' ' . $signup['place']."</td>
                            </tr>
                            <tr>
                              <td style=\"font-weight: bold;\">Geboortedatum</th>
                              <td>".$showDate."</td>
                            </tr>
                            <tr>
                              <td style=\"font-weight: bold;\">Geslacht</th>
                              <td>".$gender."</td>
                            </tr>
                            ".$email.
                           "</table>
                           <h2>Loopinformatie</h2>
                          <table style=\"text-align: left;\">
                            <tr>
                              <td style=\"font-weight: bold; width: 270px\">Afstand</th>
                              <td>".$signup['distance']."</td>
                            </tr>
							<tr>
                              <td style=\"font-weight: bold;\">Medaille</td>
                              <td>".ucfirst($signup['medal'])."</td>
                            </tr>
							</table>
                          <h2>Prijs</h2>
                          <table style=\"text-align: left;\">
                            <tr>
                              <td style=\"font-weight: bold; width: 270px;\">Deelname</td><td>&#8364;&nbsp; ".$signup['partPrice'].",-</td>
                            </tr>
                            ".$medal."
                            <tr style=\"font-weight: bold;\">
                              <td>Totaal</td><td>&#8364;&nbsp; ". $signup['totalPrice'] .",-</td>
                            </tr>
                          </table>";

        // Opbouwen email in plaintext
        $mail->AltBody= "Aanmelding Kubaarder Hurdrindei " . date('Y')."

Hartelijk dank voor uw aanmelding. Uw aanmelding is verwerkt in onze administratie. Neem een afdruk van uw gegevens mee en lever deze in bij de inschrijving.

--------------------------------------------------
Contactgegevens
--------------------------------------------------
Name: ". $signup['firstName'] . ( isset($signup['middleName']) ? ' '.$signup['middleName'] : '' ) . ' ' . $signup['lastName'] ."
Adres: ".$signup['street'] . ' ' . $signup['houseNr']."
Postcode/ Woonplaats: ".$signup['postalcode'] . ' ' . $signup['place']."
Geboortedatum: ".$showDate."
Geslacht: ".$gender."
".$emailAlt."
--------------------------------------------------
Loopinformatie
--------------------------------------------------
Afstand: ".$signup['distance']."
Medaille: ".ucfirst($signup['medal'])."
--------------------------------------------------
Prijs
--------------------------------------------------
Deelname: ".$signup['partPrice']."
".$medalAlt."
Totaal: ".$signup['totalPrice']."
";

        $mail->WordWrap = 50;
        
        //Wanneer email niet kon worden verzonden
        if(!$mail->Send()) {
          echo 'Email kon niet worden verzonden. \r\n';
          echo 'Mailer error: ' . $mail->ErrorInfo;
        } else {

          //Wanneer email kon worden verzonden, nieuwe email opstellen
          $mail = new PHPMailer();
          $mail->SetLanguage( 'nl', 'include/languages/' );
          
         // $mail->SMTPDebug = 2;  
          $mail->IsSMTP();
          $mail->SMTPAuth = true;
          $mail->SMTPSecure = "tls";
          $mail->Host = "send.one.com"; 
          $mail->Port = 587;
          $mail->Username = 'noreply@kubaarderhurdrindei.nl';
          $mail->Password = 'QbrdCty#06';  
          
          $mail->From     = "noreply@kubaarderhurdrindei.nl";
          $mail->FromName = SITE_NAME;
          $mail->AddAddress('mail@kubaarderhurdrindei.nl');
          //$mail->AddAddress("arjenvanputten@gmail.com");
          
          $mail->Subject  = "Nieuwe aanmelding voor Kubaarder Hurdrindei " . date('Y');
          $mail->Body     = "Nieuwe aanmelding voor Kubaarder Hurdrindei " . date('Y') . " van " . $signup['firstName'] . ( isset($signup['middleName']) ? ' ' . $signup['middleName'] : '' ) . ' ' . $signup['lastName'];
          $mail->WordWrap = 50;
          
          
          //Wanneer email niet kon worden verzonden
          if(!$mail->Send()) {
            echo 'Email kon niet worden verzonden';
            echo 'Mailer error 2: ' . $mail->ErrorInfo;
          } else {
?>
    <p>Uw aanmelding is succesvol verwerkt. Graag een print maken en deze bij de start inleveren waar u tevens kunt betalen. Er is een e-mail verzonden naar uw e-mailadres, zodat u ook later deze aanmelding kunt uitprinten.</p>

    <h2>Contactgegevens</h2>
     <table class="signup">
      <tr>
        <th>Naam</th>
        <td><?= $signup['firstName'] . ( isset($signup['middleName']) ? ' '.$signup['middleName'] : '' ) . ' ' . $signup['lastName']?></td>
      </tr>
      <tr>
        <th>Adres</th>
        <td><?=$signup['street'] . ' ' . $signup['houseNr']?></td>
      </tr>
      <tr>
        <th>Postcode / Woonplaats</th>
        <td><?=$signup['postalcode'] . ' ' . $signup['place']?></td>
      </tr>
      <tr>
        <th>Geboortedatum</th>
        <td><?=$showDate?></td>
      </tr>
      <tr>
        <th>Geslacht</th>
        <td>
          <?=$gender?>
        </td>
      </tr>
<?php 
  if (isset($signup['email'])) { 
?>
      <tr>
        <th>Emailadres</th>
        <td><?=$signup['email']?></td>
      </tr>
<?php
}
?>
    </table>
    <h2>Loopinformatie</h2>
    <table class="signup" style="margin-bottom: 20px">
      <tr>
        <th style="width: 247px;">Afstand</th>
        <td><?= $signup['distance'] ?></td>
      </tr>
      <tr>
        <td>Medaille</td>
        <td><?= ucfirst($signup['medal']) ?></td>
      </tr>
    </table>
    <table class="signup" style="width: 250px">
      <tr>
        <th>Prijs</th>
      </tr>
      <tr id="part">
        <td colspan="2">Deelname</td><td>&#8364;&nbsp; 4,-</td>
      </tr>
<?php if (isAdult($signup['age'], $adultAgeInYears) && $signup['medal'] == 'ja') { ?>
      <tr id="medal">
        <td colspan="2">Medaille</td><td>&#8364;&nbsp; <?= $signup['metalPrice'] ?>,-</td>
      </tr>
<?php } ?>
      <tr id="total" style="font-weight: bold;">
        <td colspan="2">Totaal</td><td>&#8364;&nbsp; <?= $signup['totalPrice'] ?>,-</td>
      </tr>
    </table>
    
      <a href="#" class="print" onclick="print()">Afdrukken</a>
<?php
            $showForm = false;
          }       
        }
      }
   } else {
     $showError = true;
   }
}
$months = array('01'=>'Januari','02'=>'Februari','03'=>'Maart','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Augustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'December' );

for($i=1; $i<32; $i++)
{
  if($i < 10)
    $i = '0'.$i;
  $dayMonths [] = $i;
}

for($i=1910; $i<(date('Y')+1); $i++)
{
  $years [] = $i;
}

if($showForm) {
?>
  <?php if($showError) { ?>
    <div class="ErrorMsg">
      U heeft een van de velden niet correct ingevuld. Controleer of u de verplichte velden heeft ingevuld en of u een juist e-mailadres en postcode heeft ingevoerd. 
    </div>
<?php } ?>
  <p>Deelname aan de K&#251;baarder Hurdrindei kost voor iedere deelnemer 4,- euro. Voor deze prijs is bij kinderen tot en met 12 jaar de medaille inbegrepen. Voor volwassenen en kinderen boven de 12 jaar, kost dit 2,- euro extra.</p>
  
  <h2>Contactgegevens</h2>
  
  <form action="<?=$_SERVER['REQUEST_URI'];?>" method="post" name="signupForm" id="signupForm">
    <table class="signup">
      <tr>
        <th><label for="firstName">Voornaam</label></th>
        <td><input type="text" name="signup[firstName]" value="<?=$signup['firstName']?>" class="large"/></td>
      </tr>
      <tr>
        <th><label for="middleName">Tussenvoegsel</label></th>
        <th><input type="text" name="signup[middleName]" value="<?=$signup['middleName']?>" class="small"/> <label for="lastName">Achternaam</label> <input type="text" name="signup[lastName]" value="<?=$signup['lastName']?>" class="middleLarge"/></th>
      </tr>
      <tr>
        <th><label for="street">Straat</label></th>
        <th><input type="text" name="signup[street]" value="<?=$signup['street']?>" class="middleLarge" style="margin-right: 6px;"/> <label for="houseNr">Huisnummer</label> <input type="text" name="signup[houseNr]" value="<?=$signup['houseNr']?>" class="small" style="width: 54px;"/></th>
      </tr>
      <tr>
        <th><label for="postalcode">Postcode</label></th>
        <th><input type="text" name="signup[postalcode]" value="<?=$signup['postalcode']?>" class="small" maxlength="6"/> <label for="place">Woonplaats</label> <input type="text" name="signup[place]" value="<?=$signup['place']?>" class="middleLarge" style="width: 210px;"/></th>
      </tr>
      <tr>
        <th><label for="postalcode">Geslacht</label></th>
        <td>
          <select name="signup[gender]">
            <option value="m" <?= $signup['gender'] == 'm' ? 'selected="selected"' : ''?>>Man</option>
            <option value="v" <?= $signup['gender'] == 'v' ? 'selected="selected"' : ''?>>Vrouw</option>
          </select>
        </td>
      </tr>
      <tr>
        <th><label for="dateBirth">Geboortedatum</label></th>
        <td>
            <select name="signup[dateBirthDay]" id="dateBirthDay" onchange="determineIfIsChildAndByThisGetsMedal()">
              <option value="0">&nbsp;</option>
<?php
    foreach($dayMonths as $day)
    {
?>
              <option value="<?=$day?>" <?=$signup['dateBirthDay'] == $day ? 'selected="selected"' : ''?>><?=$day?></option>
<?php
    }
?>
            </select>
            <select name="signup[dateBirthMonth]" id="dateBirthMonth" onchange="determineIfIsChildAndByThisGetsMedal()">
              <option value="0">&nbsp;</option>
<?php
    foreach($months as $key => $monthLabel)
    {
?>
              <option value="<?=$key?>" <?=$signup['dateBirthMonth'] == $key ? 'selected="selected"' : ''?>><?=$monthLabel?></option>
<?php
    }
?>
            </select>
            <select name="signup[dateBirthYear]" id="dateBirthYear" onchange="determineIfIsChildAndByThisGetsMedal()">
              <option value="0">&nbsp;</option>
<?php
    foreach($years as $year)
    {
?>
              <option value="<?=$year?>" <?=$signup['dateBirthYear'] == $year ? 'selected="selected"' : ''?>><?=$year?></option>
<?php
    }
?>
            </select>
        </td>
      </tr>
      <tr>
        <th><label for="email">Emailadres</label></th>
        <td><input type="text" name="signup[email]" value="<?=$signup['email']?>" class="large"/></td>
      </tr>
    </table>
    <h2>Loopinformatie</h2>
    <table class="signup">
      <tr>
        <th>Afstand</th>
      </tr>
      <tr>
        <td>
          <label for="km1.5">1,5 km</label><input type="radio" id="km1.5" name="signup[distance]" value="1.5km"<?= $signup['distance'] == '1.5km' ? ' checked="checked"' : ''?>/>
          <label for="km8">8 km</label><input type="radio" id="km8" name="signup[distance]" value="8km"<?= $signup['distance'] == '8km' ? ' checked="checked"' : ''?>/>
          <label for="km14.5">14,5 km</label><input type="radio" id="km14.5" name="signup[distance]" value="14.5km"<?= $signup['distance'] == '14.5km' ? ' checked="checked"' : ''?>/>
          <label for="km21.1">21,1 km</label><input type="radio" id="km21.1" name="signup[distance]" value="21.1km"<?= $signup['distance'] == '21.1km' ? ' checked="checked"' : ''?>/>
        </td>
      </tr>
      <tr>
        <td>Medaille</td>
      </tr>
      <tr>
        <td>
          <label for="ja">Ja</label><input type="radio" id="ja" class="medal" name="signup[medal]" value="ja" <?= $signup['medal'] == 'ja' ? ' checked="checked"' : ''?> onclick="calculatePrice()"/>
          <label for="nee">Nee</label><input type="radio" id="nee" class="medal" name="signup[medal]" value="nee" <?= $signup['medal'] == 'nee' || !isset($signup['medal']) ? ' checked="checked"' : ''?> onclick="calculatePrice()"/></td>
      </tr>
    </table>
<?php
  $totalPrice = 0;
  $partPrice = 4;
  $medalPrice = 2;
 
  if(isAdult($signup['age'], $adultAgeInYears)) 
  {
  	if($signup['medal'] == 'ja')
	{
		$totalPrice += $medalPrice;
	}
  } 
  $totalPrice += $partPrice;

?>
    <table class="signup" style="width: 250px">
      <tr id="part">
        <td colspan="2">Deelnameprijs</td>
        <td>
        	<span id="partPrice">&#8364;&nbsp; 4,-</span>
        	<input type="hidden" id="partHidden" value="4" name="signup[partPrice]"/>
        </td>
      </tr>
      <tr id="medal" <?= !isset($signup['age']) || !isAdult($signup['age'], $adultAgeInYears) || $signup['medal'] == 'nee' ? 'class="none"' : ''?>>
        <td colspan="2">Medaille</td>
        <td><span id="metalPrice">
        	&#8364;&nbsp; <?php echo $medalPrice ?>,-</span>
        	<input type="hidden" id="metalHidden" value="<?php echo $medalPrice ?>" name="signup[metalPrice]"/>
        </td>
      </tr>
      <tr id="total" style="font-weight: bold;">
        <td colspan="2">Totaal</td>
        <td>
        	<span id="totalPrice">&#8364;&nbsp; <?php echo $totalPrice ?>,-</span>
        	<input type="hidden" id="totalHidden" value="<?php echo $totalPrice ?>" name="signup[totalPrice]"/>
        </td>
      </tr>
      </table>
      <input type="hidden" id="age" value="<?=$signup['age']?>" name="signup[age]"/>
      <input type="submit" name="submitForm" value="Verzenden" class="button"/>
    </form>
<?php
}
	include_once ( 'layout/footer.inc.php' );
?>