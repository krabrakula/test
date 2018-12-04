;'use strict';

const REQUEST_TYPES = ['insert', 'update', 'select'];

function checkInput(input) {
  let testList = document.getElementsByClassName('Input');

  if (testList.length !== 0 ) {
    alert('Присутствует класс Input вместо input');
    return false;
  };
  if (input.length === 0) {
    alert('Отсутствуют поля ввода');
    return false;
  };
  for (let i = 0; i < input.length; i++) {
    if (input[i].nodeName !== 'INPUT'){
      alert('Неверный узел поля ввода');
      return false;
    }
  };

  return true;
};

function checkOutput(output) {
  let testList = document.getElementById('Output');

  if (testList) {
    alert('Присутствует класс Output вместо output');
    return false;
  };
  if (!output) {
    alert('Отсутствуют метка вывода данных');
    return false;
  };
  if (output.nodeName !== 'DIV') {
    alert('Неверный узел метки вывода данных');
    return false;
  }

  return true;
};

function sendData(phpfile, log = false) {
  let input = document.getElementsByClassName('input'),
      output = document.getElementById('output'),
      xhr = new XMLHttpRequest(),
      data = '';

  if (!checkInput(input)) {
    return;
  }

  if (!checkOutput(output)) {
    return;
  }

  for (let i = 0; i < input.length; i++) {
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
};

function sendSQL(phpfile, typeSQL) {
  let input = document.getElementsByClassName('input'),
      output = document.getElementById('output'),
      xhr = new XMLHttpRequest(),
      data = '';

  if  (!REQUEST_TYPES.includes(typeSQL)) {
    alert('Не известный тип запроса');
    return;
  }

  if (!checkInput(input) && typeSQL !== 'select' ) {
    return;
  }

  if (!checkOutput(output)) {
    return;
  }

  data = `sql_type=${typeSQL}`;
  if (typeSQL !== 'select') {
    for (let i = 0; i < input.length; i++) {
      data += `&${input[i].name}=${encodeURIComponent(input[i].value)}`;
    }
  }

  xhr.open('POST', phpfile, true);
  xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  xhr.onload = () => {
    let tmpJSON = null;

    try {
      tmpJSON = JSON.parse(xhr.responseText);
    } catch (e) {
      tmpJSON = null;
      output.innerHTML = xhr.responseText;
    }

    if (tmpJSON !== null) {
      let node = document.createElement('table');

      node = node.appendChild(document.createElement('thead'));
      node = node.appendChild(document.createElement('tr'));
      tmpJSON[0].forEach((value) => {
        node.appendChild(document.createElement('td')).appendChild(document.createTextNode(value));
      });
      node = node.parentNode.parentNode;
      node = node.appendChild(document.createElement('tbody'));

      for ( let i = 1; i < tmpJSON.length; i++ ) {
        node = node.appendChild(document.createElement('tr'));
        tmpJSON[i].forEach((value) => {
          node.appendChild(document.createElement('td')).appendChild(document.createTextNode(value));
        });
        node = node.parentNode;
      }

      output.appendChild(node.parentNode);
    }
  };
  xhr.onerror = () => {
    output.innerHTML = `<font color="red" size="5">${xhr.responseText}</font>`;
  };

  xhr.send(data);
};