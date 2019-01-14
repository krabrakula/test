<?php

$str = filter_input(INPUT_POST, "str");
$arr = explode(",", $str);


$pos = 0;
$max = 0;
$min = 0;


for ($index = 0; $index < count($arr); $index++) {
    if($arr[$index] === "1"){
        $pos++;
    }
    if($arr[$index] === "2"){
        $pos--;
    }
    if($pos > $max){
        $max = $pos;
    }
    if($pos < $min){
        $min = $pos;
    }
}
echo(abs($max) + abs($min) + 1);

