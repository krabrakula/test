;'use strict';
function sendData(phpfile, log = false){
  let input = document.getElementsByClassName('input'),
      output = document.getElementById('output'),
      xhr = new XMLHttpRequest(),
      data = '',
      testList;
      
  testList = document.getElementsByClassName('Input');
  if (testList.length !== 0 ){
    alert('Присутствует класс Input вместо input');
    return;
  };
  if (input.length === 0){
    alert('Отсутствуют поля ввода');
    return;
  };
  for (i = 0; i < input.length; i++){
    if (input[i].nodeName !== 'INPUT'){
      alert('Неверный узел поля ввода');
      return;
    }
  }
  
  testList = document.getElementById('Output');
  if (testList){
    alert('Присутствует класс Output вместо output');
    return;
  };
  if (!output){
    alert('Отсутствуют метка вывода данных');
    return;
  };
  if (output.nodeName !== 'DIV'){
    alert('Неверный узел метки вывода данных');
    return;
  }
  
  for (let i = 0; i < input.length; i++){
    data += `${input[i].name}=${encodeURIComponent(input[i].value)}`;
    if (i !== (input.length - 1)){
      data += '&';
    }
  }
  
  xhr.open('POST', phpfile, true);
  xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  xhr.onload = () => {
    if ( log ){
      output.innerHTML += xhr.responseText + '<br>';
    } else {
      output.innerHTML = xhr.responseText;
    }
  };
  xhr.onerror = () => {
    output.innerHTML = `<font color="red" size="5">${xhr.responseText}</font>`;
  };
  xhr.send(data);
}