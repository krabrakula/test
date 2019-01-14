;'use strict';

const REQUEST_TYPES = ['insert', 'update', 'select'];

/** @param {string} className */
function filtredInput(className){
  if (className.length === 0) {
    alert('Пустое имя класса');
    return null;
  }
  className = className.toLowerCase();
      
  let uClassName = className.substr(0,1).toUpperCase() + className.substr(1),
      testList = document.getElementsByClassName(uClassName);      
  if (testList.length !== 0 ) {
    alert(`Присутствует класс ${uClassName} вместо ${className}`);
    return null;
  };
  
  let input = document.getElementsByClassName(className);
  if (input.length === 0) {
    alert('Отсутствуют поля ввода');
    return null;
  };
  for (let i = 0; i < input.length; i++) {
    if (input[i].nodeName !== 'INPUT'){
      alert('Неверный узел поля ввода');
      return null;
    }
  };

  return input;
};

/** @param {string} idName */
function filtredOutput(idName){
  if (idName.length === 0) {
    alert('Пустой ID');
    return null;
  }
  idName = idName.toLowerCase();

  let uIdName = idName.substr(0,1).toUpperCase() + idName.substr(1),
      testList = document.getElementById(uIdName);
  if (testList) {
    alert(`Присутствует ID ${uIdName} вместо ${idName}`);
    return null;
  };

  let output = document.getElementById(idName);
  if (!output) {
    alert('Отсутствуют метка вывода данных');
    return null;
  };
  if (output.nodeName !== 'DIV') {
    alert('Неверный узел метки вывода данных');
    return null;
  };

  return output;
};

/** @param {Object} node */
function weightNode(node) {
  node.weight = 0;
  node.position = 0;
  let elem = null;

  for ( let i = 0; i < node.children.length; i++ ) {
    elem = node.children[i];
    weightNode(elem);
    node.weight += elem.weight;
    if (i === 0) {
      node.position += elem.weight - elem.position;
    } else {
      if (i === (node.children.length - 1)) {
        node.position += elem.position;
      } else {
        node.position += elem.weight;
      };
    };
  };
  if (node.weight === 0) {
    node.weight = 1;
    node.position = 1;
  } else {
    node.position = Math.ceil(node.position / 2) + node.children[0].position;
  };
};

/** @param {Node} output
 *  @param {Object} data  */
function drowGraph(output, data){
  try {
    weightNode(data);
  } catch (e) {
    alert('Ошибка входный данных!');
    return;
  };

  while (output.firstChild) {
    output.removeChild(output.firstChild);
  };

  let cell = 48,
      canvas = document.createElement('canvas'),
      img = canvas.getContext('2d'),
      depth = -1,
      maxDepth = -1,
      levelShift = [],
      letterShift = 0;

  canvas.width = cell * data.weight;
  canvas.height = cell;

  let drowNode = (node, depth, parent) => {
    depth++;
    if (depth > maxDepth) {
      maxDepth++;
      canvas.height = cell * (2 * maxDepth + 1);
      img.strokeStyle = "#000000";
      img.font = `bold ${cell / 3}px Arial`;
      levelShift.push(0);
    }
    node.children.forEach( (item) => {
      drowNode(item, depth, node);
    });

    if (parent !== null) {
      img.beginPath();
      img.moveTo((levelShift[depth] + node.position - 0.5) * cell, (2 * depth + 0.5) * cell);
      img.lineTo((levelShift[depth - 1] + parent.position - 0.5) * cell, (2 * (depth - 1) + 0.5) * cell);
      img.stroke();
    };

    let x = (levelShift[depth] + node.position - 0.90) * cell,
        y = (2 * depth + 0.10) * cell;
    img.fillStyle = "#008000";
    img.fillRect(x, y, cell * 0.8, cell * 0.8);
    img.fillStyle = "#ffffff";
    let letCount = String(node.data).length;
    if (letCount < 3) {
      letterShift = cell * (0.5 - 0.125 * letCount);
    } else {
      letterShift = cell * 0.125;
    };
    img.fillText(node.data, x + letterShift, y + (cell / 2));

    levelShift[depth] += node.weight;    
  };
  
  drowNode(data, depth, null);
  output.appendChild(canvas);
};

/** @param {string} phpFile
  * @param {boolean} log */
function sendData(phpFile, log = false) {
  let input = filtredInput('input'),
      output = filtredOutput('output'),
      xhr = new XMLHttpRequest(),
      data = '';

  if (!input) {
    return;
  }

  if (!output) {
    return;
  }

  input.forEach = Array.prototype.forEach;
  input.forEach( (item, i) => {
    data += `${item.name}=${encodeURIComponent(item.value)}`;
    if (i !== (item.length - 1)){
      data += '&';
    }
  });

  xhr.open('POST', phpFile, true);
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

/** @param {string} phpFile
  * @param {string} typeSQL */
function sendSQL(phpFile, typeSQL) {
  let input = filtredInput('input'),
      output = filtredOutput('output'),
      xhr = new XMLHttpRequest(),
      data = '';

  if  (!REQUEST_TYPES.includes(typeSQL)) {
    alert('Не известный тип запроса');
    return;
  };

  if (typeSQL !== 'select') {
    if (!input) {
      return;
    };
  };

  if (!output) {
    return;
  };

  data = `sql_type=${typeSQL}`;
  if (typeSQL !== 'select') {
    input.forEach = Array.prototype.forEach;
    input.forEach( (item) => {
      data += `&${item.name}=${encodeURIComponent(item.value)}`;
    });
  };

  xhr.open('POST', phpFile, true);
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
      if ( output.childElementCount ) {
        output.firstChild.remove();
      }

      let node = document.createElement('table');

      node.addEventListener('click', (data) => {
        let node = data.target;
        let txt = '';
        if (node.nodeName === 'TD'){
          txt = `row => ${node.parentNode.rowIndex}; col => ${node.cellIndex}; val =>${node.textContent}`;
          alert(txt);
        };
      });

      let row = node.createTHead();
      row = row.insertRow();
      tmpJSON[0].forEach((value) => {
        row.insertCell().textContent = value;
      });
      row = node.createTBody();
      for ( let i = 1; i < tmpJSON.length; i++ ) {
        row = row.insertRow();
        tmpJSON[i].forEach((value) => {
          row.insertCell().textContent = value;
        });
        row = row.parentNode;
      };

      output.appendChild(node);

//      node = node.appendChild(document.createElement('thead'));
//      node = node.appendChild(document.createElement('tr'));
//      tmpJSON[0].forEach((value) => {
//        node.appendChild(document.createElement('td')).appendChild(document.createTextNode(value));
//      });
//
//      node = node.parentNode.parentNode;
//      node = node.appendChild(document.createElement('tbody'));
//      for ( let i = 1; i < tmpJSON.length; i++ ) {
//        node = node.appendChild(document.createElement('tr'));
//        tmpJSON[i].forEach((value) => {
//          node.appendChild(document.createElement('td')).appendChild(document.createTextNode(value));
//        });
//        node = node.parentNode;
//      }
//
//      output.appendChild(node.parentNode);
    }
  };
  xhr.onerror = () => {
    output.innerHTML = `<font color="red" size="5">${xhr.responseText}</font>`;
  };

  xhr.send(data);
};

/** @param {string} phpFile */
function sendToGraph(phpFile){
  let input = filtredInput('input'),
      output = filtredOutput('output'),
      xhr = new XMLHttpRequest(),
      data = '';

  if (!input) {
    return;
  }

  if (!output) {
    return;
  }

  input.forEach = Array.prototype.forEach;
  input.forEach( (item, i) => {
    data += `${item.name}=${encodeURIComponent(item.value)}`;
    if (i !== (item.length - 1)){
      data += '&';
    }
  });

  xhr.open('POST', phpFile, true);
  xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  xhr.onload = () => {
    let tmpJSON;
    try {
      tmpJSON = JSON.parse(xhr.responseText);
    } catch (e) {
      output.innerHTML = xhr.responseText;
      return;
    }
    drowGraph(output, tmpJSON);
  };
  xhr.onerror = () => {
    output.innerHTML = `<font color="red" size="5">${xhr.responseText}</font>`;
  };
  xhr.send(data);
};