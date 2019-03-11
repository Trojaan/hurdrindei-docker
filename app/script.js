  var adultAgeInYears = 12;
  
  /**
   * DHTML date validation script. Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/)
   */
  // Declaring valid date character, minimum year and maximum year
  var dtCh= "/";
  var minYear=1900;
  var maxYear=2100;

  function isInteger(s){
    var i;
      for (i = 0; i < s.length; i++){   
          // Check that current character is number.
          var c = s.charAt(i);
          if (((c < "0") || (c > "9"))) return false;
      }
      // All characters are numbers.
      return true;
  }

  function stripCharsInBag(s, bag){
    var i;
      var returnString = "";
      // Search through string's characters one by one.
      // If character is not in bag, append to returnString.
      for (i = 0; i < s.length; i++){   
          var c = s.charAt(i);
          if (bag.indexOf(c) == -1) returnString += c;
      }
      return returnString;
  }

  function daysInFebruary (year){
    // February has 29 days in any year evenly divisible by four,
      // EXCEPT for centurial years which are not also divisible by 400.
      return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
  }
  function DaysArray(n) {
    for (var i = 1; i <= n; i++) {
      this[i] = 31
      if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
      if (i==2) {this[i] = 29}
     } 
     return this
  }

  function isDate(dtStr){
    var daysInMonth = DaysArray(12)
    var pos1=dtStr.indexOf(dtCh)
    var pos2=dtStr.indexOf(dtCh,pos1+1)
    var strMonth=dtStr.substring(0,pos1)
    var strDay=dtStr.substring(pos1+1,pos2)
    var strYear=dtStr.substring(pos2+1)
    strYr=strYear
    if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
    if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
    for (var i = 1; i <= 3; i++) {
      if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
    }
    month=parseInt(strMonth)
    day=parseInt(strDay)
    year=parseInt(strYr)
    if (pos1==-1 || pos2==-1){
      alert("The date format should be : mm/dd/yyyy")
      return false
    }
    if (strMonth.length<1 || month<1 || month>12){
      alert("Please enter a valid month")
      return false
    }
    if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
      alert("Please enter a valid day")
      return false
    }
    if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
      alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear)
      return false
    }
    if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
      alert("Please enter a valid date")
      return false
    }
  return true
  }

  function ValidateForm(){
    var dt=document.frmSample.txtDate
    if (isDate(dt.value)==false){
      dt.focus()
      return false
    }
      return true
   }
   
  function getAgeInYears(dateOfBirth)
  {
  	var age = qryHowOld(dateOfBirth);
  	var ageArray = age.split(' ');
    return ageArray[0];
  }
  
  function determineIfIsChildAndByThisGetsMedal()
  {
    //dateOfBirth is not filled in correcty yet.
    if($('dateBirthDay').value == 0 || $('dateBirthMonth').value == 0 || $('dateBirthYear').value == 0) {
    	return false;
    }
    var ageInYears = getAgeInYears($('dateBirthDay').value + '-' + $('dateBirthMonth').value + '-' + $('dateBirthYear').value);
    if(ageInYears > adultAgeInYears) { //when adult
		$('ja').set('checked', false);
		$('nee').set('checked', true);
	}
	else {
		$('ja').set('checked', true);
	}
	calculatePrice();
  }
  
  function calculatePrice()
  {
  	var medalPrice = 2;
    var childPrice = 4;
    var adultPrice = 4;
    var partPrice = 0;
    var totalPrice = 0;
    
    //dateOfBirth is not filled in correcty yet.
    if($('dateBirthDay').value == 0 || $('dateBirthMonth').value == 0 || $('dateBirthYear').value == 0) {
    	return false;
    }
    
    var ageInYears = getAgeInYears($('dateBirthDay').value + '-' + $('dateBirthMonth').value + '-' + $('dateBirthYear').value);
    
    if($('ja').checked == true)
	{
	  calculateAndSetPrices(ageInYears, adultAgeInYears, medalPrice, totalPrice, adultPrice, childPrice, partPrice, true);
	}
	else if($('nee').checked == true)
	{
	  calculateAndSetPrices(ageInYears, adultAgeInYears, medalPrice, totalPrice, adultPrice, childPrice, partPrice, false);
	}
  }
  
  function calculateAndSetPrices(ageInYears, adultAgeInYears, medalPrice, totalPrice, adultPrice, childPrice, partPrice, takesMedal)
  {
    //adult
    if(ageInYears > adultAgeInYears) {
      if(takesMedal) {
      	$('medal').removeClass('none');
      	totalPrice = adultPrice + medalPrice;
      } else {
      	$('medal').addClass('none');
      	totalPrice = adultPrice;
      }
      partPrice = adultPrice;
    //child
    } else {
      $('medal').addClass('none');
      // count totalprice no medalprice since its free for children
      totalPrice = childPrice;
      //set labels
      partPrice = childPrice;
    }
    applyValues(ageInYears, medalPrice, partPrice, totalPrice);
  }
  
  function applyValues(ageInYears, medalPrice, partPrice, totalPrice)
  {
  	$('age').value = ageInYears;
  	//$('medalHidden').value = medalPrice;
  	//$('metalPrice').innerHTML = ( '&#8364;&nbsp; ' + medalPrice + ',-');
  	$('partPrice').innerHTML = ( '&#8364;&nbsp; ' + partPrice + ',-');
  	$('partHidden').value = partPrice;
  	$('totalPrice').innerHTML = ( '&#8364;&nbsp; ' + totalPrice + ',-');
    $('totalHidden').value = totalPrice;
  }
    
  function countTotal(currentDate)
  {      
    var totalPrice = null;
    var dateBirth = $('dateBirthDay').value + '-' + $('dateBirthMonth').value + '-' + $('dateBirthYear').value;
    var age = qryHowOld(dateBirth);
    var ageArray = age.split(' ');
    var childAge = 12;
    var allInputs = $(document.body).getElements('input');
    
    if(ageArray[0] <= childAge) {
    //kind
      for(i=0; i < allInputs.length; i++)
      {
        if(allInputs[i].type == 'radio') {
          if(allInputs[i].checked == true) {
            if(allInputs[i].value == 'ja') {
              totalPrice = 2 + 2;
              $('metalHidden').value = 2;
              $('medal').removeClass('none');
            } else { 
              totalPrice = 2;
              $('metalHidden').value = 0;
              $('medal').addClass('none');
            }
          }
        }
      }
      $('partPrice').innerHTML = ( '&#8364;&nbsp; ' + 2 + ',-');
      $('partHidden').value = 2;
    } else {
      //volwassene
      $('age') = ageArray[0];
      for(i=0; i < allInputs.length; i++)
      {
        if(allInputs[i].type == 'radio') {
          if(allInputs[i].checked == true) {
            if(allInputs[i].value == 'ja') {
              totalPrice = 3 + 2;
              $('metalHidden').value = 2;
              $('medal').removeClass('none');
            } else { 
              totalPrice = partPrice;
              $('metalHidden').value = 0;
              $('medal').addClass('none');
            }
          }
        }
      }
      $('partPrice').innerHTML = ( '&#8364;&nbsp; ' + 3 + ',-');
      $('partHidden').value = 3;
    }
    //changelabels
    $('metalPrice').innerHTML = ( '&#8364;&nbsp; ' + 2 + ',-');
    $('totalPrice').innerHTML = ( '&#8364;&nbsp; ' + totalPrice + ',-');
    //change hiddenfields
    $('metalHidden').value = 2;
    $('totalHidden').value = totalPrice;
  }


// ---------------------------------------------------------------------------|
// qryHowOld                                                                  |
// Description: How old someone is in the format:                             |
// XXX Years XX Months X Weeks X Days                                         |
// Birth Date could be specified like Date.UTC(2002,8,16,17,42,0)             |
//                                                                            |
// Arguments:                                                                 |
//    varAsOfDate: as of date                                                 |
//    varBirthDate: birth date                                                |
//                                                                            |
function qryHowOld(varBirthDate)
{
   var varAsOfDate = new Date();
   var dtAsOfDate;
   var dtBirth;
   var dtAnniversary;
   var intSpan;
   var intYears;
   var intMonths;
   var intWeeks;
   var intDays;
   var intHours;
   var intMinutes;
   var intSeconds;
   var strHowOld;
   
   //ofDate = Array();
   
   //ofDate = varAsOfDate.split('-');
   
birthDate = Array();

birthDate = varBirthDate.split('-');
    
  // get born date
  dtBirth = new Date(birthDate[2], birthDate[1], birthDate[0], 0, 0);
  
  // get as of date
  dtAsOfDate = varAsOfDate;

  // if as of date is on or after born date
  if ( dtAsOfDate >= dtBirth )
     {

     // get time span between as of time and birth time
     intSpan = ( dtAsOfDate.getUTCHours() * 3600000 +
                 dtAsOfDate.getUTCMinutes() * 60000 +
                 dtAsOfDate.getUTCSeconds() * 1000    ) -
               ( dtBirth.getUTCHours() * 3600000 +
                 dtBirth.getUTCMinutes() * 60000 +
                 dtBirth.getUTCSeconds() * 1000       )

     // start at as of date and look backwards for anniversary 

     // if as of day (date) is after birth day (date) or
     //    as of day (date) is birth day (date) and
     //    as of time is on or after birth time
     if ( dtAsOfDate.getUTCDate() > dtBirth.getUTCDate() ||
          ( dtAsOfDate.getUTCDate() == dtBirth.getUTCDate() && intSpan >= 0 ) )
        {

        // most recent day (date) anniversary is in as of month
        dtAnniversary = 
           new Date( Date.UTC( dtAsOfDate.getUTCFullYear(),
                               dtAsOfDate.getUTCMonth(),
                               dtBirth.getUTCDate(),
                               dtBirth.getUTCHours(),
                               dtBirth.getUTCMinutes(),
                               dtBirth.getUTCSeconds() ) );

        }

     // if as of day (date) is before birth day (date) or
     //    as of day (date) is birth day (date) and
     //    as of time is before birth time
     else
        {

        // most recent day (date) anniversary is in month before as of month
        dtAnniversary = 
           new Date( Date.UTC( dtAsOfDate.getUTCFullYear(),
                               dtAsOfDate.getUTCMonth() - 1,
                               dtBirth.getUTCDate(),
                               dtBirth.getUTCHours(),
                               dtBirth.getUTCMinutes(),
                               dtBirth.getUTCSeconds() ) );

        // get previous month
        intMonths = dtAsOfDate.getUTCMonth() - 1;
        if ( intMonths == -1 )
           intMonths = 11;

        // while month is not what it is supposed to be (it will be higher)
        while ( dtAnniversary.getUTCMonth() != intMonths )

           // move back one day
           dtAnniversary.setUTCDate( dtAnniversary.getUTCDate() - 1 );

        }

     // if anniversary month is on or after birth month
     if ( dtAnniversary.getUTCMonth() >= dtBirth.getUTCMonth() )
        {

        // months elapsed is anniversary month - birth month
        intMonths = dtAnniversary.getUTCMonth() - dtBirth.getUTCMonth();

        // years elapsed is anniversary year - birth year
        intYears = dtAnniversary.getUTCFullYear() - dtBirth.getUTCFullYear();

        }

     // if birth month is after anniversary month
     else
        {

        // months elapsed is months left in birth year + anniversary month
        intMonths = (11 - dtBirth.getUTCMonth()) + dtAnniversary.getUTCMonth() + 1;

        // years elapsed is year before anniversary year - birth year
        intYears = (dtAnniversary.getUTCFullYear() - 1) - dtBirth.getUTCFullYear();

        }

     // to calculate weeks, days, hours, minutes and seconds
     // we can take the difference from anniversary date and as of date

     // get time span between two dates in milliseconds
     intSpan = dtAsOfDate - dtAnniversary;

     // get number of weeks
     intWeeks = Math.floor(intSpan / 604800000);

     // subtract weeks from time span
     intSpan = intSpan - (intWeeks * 604800000);
     
     // get number of days
     intDays = Math.floor(intSpan / 86400000);

     // subtract days from time span
     intSpan = intSpan - (intDays * 86400000);

     // get number of hours
     intHours = Math.floor(intSpan / 3600000);
   
     // subtract hours from time span
     intSpan = intSpan - (intHours * 3600000);

     // get number of minutes
     intMinutes = Math.floor(intSpan / 60000);

     // subtract minutes from time span
     intSpan = intSpan - (intMinutes * 60000);

     // get number of seconds
     intSeconds = Math.floor(intSpan / 1000);

     // create output string     
     if ( intYears > 0 )
        if ( intYears > 1 )
           strHowOld = intYears.toString() + ' Years';
        else
           strHowOld = intYears.toString() + ' Year';
     else
        strHowOld = '';

     if ( intMonths > 0 )
        if ( intMonths > 1 )
           strHowOld = strHowOld + ' ' + intMonths.toString() + ' Months';
        else
           strHowOld = strHowOld + ' ' + intMonths.toString() + ' Month';
          
     if ( intWeeks > 0 )
        if ( intWeeks > 1 )
           strHowOld = strHowOld + ' ' + intWeeks.toString() + ' Weeks';
        else
           strHowOld = strHowOld + ' ' + intWeeks.toString() + ' Week';

     if ( intDays > 0 )
        if ( intDays > 1 )
           strHowOld = strHowOld + ' ' + intDays.toString() + ' Days';
        else
           strHowOld = strHowOld + ' ' + intDays.toString() + ' Day';

     if ( intHours > 0 )
        if ( intHours > 1 )
           strHowOld = strHowOld + ' ' + intHours.toString() + ' Hours';
        else
           strHowOld = strHowOld + ' ' + intHours.toString() + ' Hour';

     if ( intMinutes > 0 )
        if ( intMinutes > 1 )
           strHowOld = strHowOld + ' ' + intMinutes.toString() + ' Minutes';
        else
           strHowOld = strHowOld + ' ' + intMinutes.toString() + ' Minute';

     if ( intSeconds > 0 )
        if ( intSeconds > 1 )
           strHowOld = strHowOld + ' ' + intSeconds.toString() + ' Seconds';
        else
           strHowOld = strHowOld + ' ' + intSeconds.toString() + ' Second';

     }
  else
     strHowOld = 'Not Born Yet'

  // return string representation
  return strHowOld
  }