<?php
function calc($a, $oper, $b) {
	switch ($oper){
		case "+" :
			$result = $a + $b;
			break;
		case "-" :
			$result = $a - $b;
			break;
		case "*":
			$result = $a * $b;
			break;
		case  "/":
			$result = $a / $b;
			break;
		case  "^":
			$result = $a ** $b;
			break;
  };
  return $result;
}

$expression = filter_input(INPUT_POST, "expression");
$operandS = array('+', '-', '*', '/', '^');

//удаление запрещенных символов
$expression = preg_replace("/[^\d*\/+-^]/", "", $expression);

//удаление лишних операндов
$expression = preg_replace("/([+\-*\/^])?[+\-*\/^]*(\d+)/", "$1$2", $expression);

//Выполение операций согласно приоретета операндов. Создание массива с выражением
$expression = preg_replace("/[+\-*\/^]|\d+/", "$0 ", $expression);
$calc_array = explode(" ", $expression);

// приведение массива к общем у виду
array_pop($calc_array);
if (in_array($calc_array[0], $operandS)) {
	array_unshift($calc_array, "0");
}
if (in_array($calc_array[count($calc_array)-1], $operandS)){
	array_pop($calc_array);
}

// вычисление
echo implode("=>", $calc_array)."<br>";
for ($i = 1; $i < count($calc_array); $i += 2) {
	if ($calc_array[$i] === "^") {
		$temp = calc($calc_array[$i-1],$calc_array[$i],$calc_array[$i+1]);
		array_splice($calc_array, $i-1, 3, $temp);
		$i -= 2;
		echo implode(" ", $calc_array)."<br>";
	}
}
for ($i = 1; $i < count($calc_array); $i += 2) {
	if (($calc_array[$i] === "*") or ($calc_array[$i] === "/")) {
		$temp = calc($calc_array[$i-1],$calc_array[$i],$calc_array[$i+1]);
		array_splice($calc_array, $i-1, 3, $temp);
		$i -= 2;
		echo implode(" ", $calc_array)."<br>";
	}
}
for ($i = 1; $i < count($calc_array); $i += 2) {
	if (($calc_array[$i] === "+") or ($calc_array[$i] === "-")) {
		$temp = calc($calc_array[$i-1],$calc_array[$i],$calc_array[$i+1]);
		array_splice($calc_array, $i-1, 3, $temp);
		$i -= 2;
		echo implode(" ", $calc_array)."<br>";
	}
}
// линейное вычисление, без учета приоретета операндов 
// $position_operand = -1;
// $prev_pos_oper = -1;
// $oper = "+";
// $result = 0;
// for ($i = 0; $i < $size; $i++) {
//   if (in_array($expression{$i}, $operandS) or ($i === $size - 1)) {
//     $position_operand = $i;
//     if ($i === $size - 1) {
//     	$position_operand = $position_operand + 1;
//     }
//     $b = substr($expression, $prev_pos_oper + 1, $position_operand - $prev_pos_oper);
//     $result = calc ($result, $oper, $b);
//     $oper = $expression{$position_operand};
//     $prev_pos_oper = $position_operand;
//   }
// }

// echo $expression."=".$result;
