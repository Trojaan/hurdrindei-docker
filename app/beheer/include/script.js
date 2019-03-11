function movePointer(currentElm, nextElm)
{
  var maxLength = 2;

  if(currentElm.value == '00') {
      currentElm.value = ''; 
  }

  if(currentElm.value.length == maxLength) {
    $(nextElm).focus();
  }

}