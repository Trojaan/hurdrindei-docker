<?php

/*
FIXEN VOORNAAM + TUSSENVOEGSEL

$sql = "SELECT
            `personID`,
            `firstName`
          FROM
            `[PREFIX]_persons`
       ";
$res = Xmysql_query($sql);

while($row = mysql_fetch_assoc($res))
{
  $names [$row['personID']] = $row['firstName'];
}

foreach($names as $k => $v)
{
  $name = explode(' ', $v);

  $firstName [$k] = $name[0];

  $middleName [$k] = $name[1].' '.$name[2];
}

//myPrint($middleName);

foreach($firstName as $k => $v)
{
  $middlename = '';

  if(strlen($middleName[$k]) > 0) {
    $middlename = ",`middleName` = '".$middleName[$k]."'";
  } else {
    $middlename = '';
  }

   $sql = "UPDATE
                `[PREFIX]_persons`
              SET
                `firstName` = '".$v."'
                ".$middlename."
            WHERE
                `personID` = ".$k."
          ";
   Xmysql_query($sql);

//FIXEN TIJD RESULTATEN

}
$sql = "SELECT
            `resultID`,
            `time`
          FROM
            `[PREFIX]_results`
          WHERE
            `year` = 2009
       ";
myprint($sql);
$res = Xmysql_query($sql);

while($row = mysql_fetch_assoc($res))
{
  $time [$row['resultID']] = $row['time'];
}

foreach($time as $k => $v)
{
  $timeFixed = explode(':', $v);

  $hours [$k] = $timeFixed[0];

  $minutes [$k] = $timeFixed[1];

  $seconds [$k] = $timeFixed[2];
}

foreach($hours as $k => $v)
{
  if(!is_numeric($hours[$k]))
    continue;

   $sql = "UPDATE
                `[PREFIX]_results`
              SET
                `timeFixed` = '".$hours[$k].':'.$minutes[$k].':'.$seconds[$k]."'
            WHERE
                `resultID` = ".$k."
          ";
    myPrint($sql);
   //Xmysql_query($sql);
}

*/

$sql = "SELECT
            `resultID`,
            `distanceFixed`
          FROM
            `[PREFIX]_results`
          WHERE `year` = 2009
       ";
$res = Xmysql_query($sql);

while($row = mysql_fetch_assoc($res))
{
  $dist [$row['resultID']] = $row['distanceFixed'];
}

foreach($dist as $k => $v)
{
  if($v == '8.00')
    $v = '8.00';
  else if($v == '14.5')
    $v = '14.50';
  else if($v == '21.1')
    $v = '21.10';
  else if($v == '1.5')
    $v = '1.50';

  $distFixed [$k] = $v;
}

foreach($distFixed as $k => $v)
{
   $sql = "UPDATE
                `[PREFIX]_results`
              SET
                `distanceFixed` = '".$v."'
            WHERE
                `resultID` = ".$k."
          ";
   myPrint($sql);
   Xmysql_query($sql);
}

/*
$sql = "SELECT
            `personID`,
            `postalcode`
          FROM
            `[PREFIX]_persons`
       ";
$res = Xmysql_query($sql);

while($row = mysql_fetch_assoc($res))
{
  $postalCode [$row['personID']] = $row['postalcode'];
}

foreach($postalCode as $k => $v)
{
  

   $sql = "UPDATE
                `[PREFIX]_persons`
              SET
                `postalcode` = '".str_replace(" ", "", $v)."'
            WHERE
                `personID` = ".$k."
          ";
   Xmysql_query($sql);
   myPrint($sql);
}*/
?>