<?php

$str = filter_input(INPUT_POST, "str");
$arr = explode(" ", $str);

for ($index = 0; $index < count($arr); $index++) {
  if ($arr[$index] > 300){
    array_splice($arr, $index, 1);
  }
}

function calc($arr) {
  $time = 0;
  for ($score = 0; $score < count($arr); $score++) {

    if ($time + $arr[$score] > 300){
      break;
    } 
    $time = $time + $arr[$score];
    
  } ;
  return array($score, $time);
}

$obj = new ArrayObject($arr);
$st1_arr = $obj->getArrayCopy();
asort($st1_arr);
$st1 = calc($st1_arr);
$st3 = calc(array_reverse($arr));
$st5 = calc($arr);


$chart = array ($st1, $st3, $st5);
for ($index1 = 0; $index1 < count($chart); $index1++) {
  array_push($chart[$index1], (count($chart)-$index1));
  
}


echo var_dump($chart);

echo var_dump($st1);
echo var_dump($st3);
echo var_dump($st5);


