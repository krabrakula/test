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
$allow_char = array("0","1","2","3","4","5","6","7","8","9","+","-","*","/","^");
$size = strlen($expression);

//удаление запрещенных символов
for ($i = $size-1; $i; $i--) {
	if (!in_array($expression{$i}, $allow_char)) {
		$expression = substr_replace($expression, "", $i, 1);
	}
}
//удаление лишних операндов
$size = strlen($expression);
$del_char = 0;
for ($i = 0; $i < $size; $i++) {
	if (in_array($expression{$i}, $operandS)) {
		$del_char++;
	} else {
		$del_char = 0;
	}
	if ($del_char > 1) {
		$expression = substr_replace($expression, "", $i, 1);
	  $i--;
	  $size--;
	}
}
//Выполение операций согласно приоретета операндов. Создание массива с выражением
$calc_array = array();
$size = strlen($expression);
$is_1st_char = TRUE;
for ($i = 0; $i < $size; $i++) {
	if (in_array($expression{$i}, $operandS)) {
		$calc_array[] = $expression[$i];
		$is_1st_char = TRUE;
	} else {
		if ($is_1st_char === TRUE) {
			$calc_array[] = $expression[$i];
			$is_1st_char = FALSE;
		} else {
			$calc_array[count($calc_array)-1] .= $expression[$i];
		}
	}
}
if (in_array($calc_array[0], $operandS)) {
	array_unshift($calc_array, "0");
}
if (in_array($calc_array[count($calc_array)-1], $operandS)){
	array_pop($calc_array);
}
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
//array_splice($calc_array, $offset)

$position_operand = -1;
$prev_pos_oper = -1;
$oper = "+";
$result = 0;
for ($i = 0; $i < $size; $i++) {
  if (in_array($expression{$i}, $operandS) or ($i === $size - 1)) {
    $position_operand = $i;
    if ($i === $size - 1) {
    	$position_operand = $position_operand + 1;
    }
    $b = substr($expression, $prev_pos_oper + 1, $position_operand - $prev_pos_oper);
    $result = calc ($result, $oper, $b);
    $oper = $expression{$position_operand};
    $prev_pos_oper = $position_operand;
    
//     $b = substr($expression, $position_operand + 1);
//     $result = calc($a, $oper, $b);
    
  }
  
}

echo $expression."=".$result;
