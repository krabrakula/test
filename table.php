<?php
//Функция меняющая местами два элемента. проверить работает ли с массивами
function swap(&$x, &$y) {
    $tmp = $x;
    $x = $y;
    $y = $tmp;
}
function arrToStr($separator, $array) {
    $size = count($array);
    $string = "";
    for ($i = 0; $i < $size; $i++) {
        $string = $string.$array[$i];
        if ($i < $size - 1) {
            $string = $string.$separator;
        }
    }
    return $string;
}

$size = filter_input(INPUT_POST, "arraySize");
$arr = array();
//создание массива случайных чисел от 0 до 99
for ($i = 0; $i < $size; $i++) {
    array_push($arr, rand(0,99));
}

// алгоритм прямой замены
// for ($i = $size - 1; $i > 0; $i--) {
//     $max_index = $i;
//     $max_value = $arr[$i];
//     for ($j = 0; $j < $i; $j++) {
//         if ($max_value < $arr[$j]) {
//             $max_value = $arr[$j];
//             $max_index = $j;
//         }
//     }
//     if ($max_index != $i) {
//         swap($arr[$max_index], $arr[$i]);
//     }
// }
    
// }

// asort($arr);
// //Пузырек
// for ($i = 0; $i < $size-1; $i++) {
//     $over = TRUE;
//     for ($j = 0; $j < ($size-$i-1); $j++) {
//         if ($arr[$j] > $arr[$j+1]) {
// //           $tmp = $arr[$j];
// //             $arr[$j] = $arr[$j+1];
// //             $arr[$j+1] = $tmp;
//                swap($arr[j],$arr[j+1]);
//             $over = FALSE;
//         }
//     }
//     if ($over === TRUE) {
//         break;
//     }
// }
$cunt = 0;
for ($i = 0; $i < $size - 1; $i++) {
    $break = TRUE;
    for ($j = $size-1; $j > $i; $j--) {
        if ($arr[$j-1] > $arr[$j]) {
            swap($arr[$j-1], $arr[$j]);
            $break = FALSE;
            $cunt++;
        }
    }
    if ($break === TRUE) {
        break;
    } 
}








$arrEven = array();
$arrOdd = array();
for ($i = 0; $i < $size; $i++){
    if ( ($arr[$i]%2) != 0){
        array_push($arrOdd, $arr[$i]);       
    } else {
        array_push($arrEven, $arr[$i]);        
    }
}
//вывод
echo "Sorted array => ".arrToStr( ", ", $arr)."<br>";
echo "Even numbers => ".implode( ", ", $arrEven)."<br>";
echo "Odd numbers => ".implode( ", ", $arrOdd)."<br>";
echo "Number of iterations => ".$cunt."<br>";

