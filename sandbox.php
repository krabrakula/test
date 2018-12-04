<?php
function correction(&$multi){
  if ($multi === ""){
    $multi = "1";
  }
  if ($multi === "-"){
    $multi = "-1";
  }
}
//$a = filter_input(INPUT_POST, 'a');
//$b = filter_input(INPUT_POST, 'b');
//$c = filter_input(INPUT_POST, 'c');

$str = filter_input(INPUT_POST, 'eq');

//preg_match('/(\-?\d+)x\^2/', $eq, $matches);
//$a = $matches[1];
//preg_match('/([\+\-]\d+)x[^^]/', $eq, $matches);
//$b = $matches[1];
//preg_match('/([\+\-]\d+)[^x]/', $eq, $matches);
//$c = $matches[1];
$eq = array("x2"=>array(), "x1"=>array(), "x0"=>array());
$div_str = preg_split("/=/", $str);
for ($i = 0; $i < count($div_str); $i++) {
  $k = 1;
  if ($i > 0){
    $k = -1;
  }

  $matches = null;
  $skobki = preg_match_all("/\((.+?)\)/", $div_str[$i], $matches, PREG_SET_ORDER);
  if ($skobki > 0){
    $sk_str = array($matches[0][1], $matches[1][1]);
    $sk_arr = array(array(), array());
    $match = null;

    for ($j = 0; $j < count($sk_arr); $j++){
      if (preg_match("/(-?\d*)x\^2/i", $sk_str[$j], $match)){
        correction($match[1]);
        array_push($sk_arr[$j], array("val"=>(int)$match[1], "pow"=>2));
      }
      if (preg_match("/(-?\d*)x(?:[^^]|$)/i", $sk_str[$j], $match)){
        correction($match[1]);
        array_push($sk_arr[$j], array("val"=>(int)$match[1], "pow"=>1));
      }
      if (preg_match("/(-?\d+)(?:[^x]|$)/i", $sk_str[$j], $match)){
        array_push($sk_arr[$j], array("val"=>(int)$match[1], "pow"=>0));
      }
    }
    for ($i1 = 0; $i1 < count($sk_arr[0]); $i1++){
      for($j1 = 0; $j1 < count($sk_arr[1]); $j1++){
        $val = $k * $sk_arr[0][$i1]["val"] * $sk_arr[1][$j1]["val"];
        $pow = $sk_arr[0][$i1]["pow"] + $sk_arr[1][$j1]["pow"];
        switch ($pow) {
          case 2:
            array_push($eq["x2"], $val);
          break;
          case 1:
            array_push($eq["x1"], $val);
          break;
          case 0:
            array_push($eq["x0"], $val);
          break;
        }
      }
    }
  }
  $div_str[$i] = preg_replace("/\(.*?\)/", "", $div_str[$i]);
  if(preg_match("/(-?\d+)x\^2/", $div_str[$i], $match)){
    array_push($eq["x2"], $match[1]*$k);
  }
  if(preg_match("/(-?\d*)x(?:[^^]|$)/i", $div_str[$i], $match)){
    array_push($eq["x1"], $match[1]*$k);
  }
  if(preg_match("/(-?\d+)(?:[^x]|$)/i", $div_str[$i], $match)){
    array_push($eq["x0"], $match[1]*$k);
  }
}

$a = array_sum($eq["x2"]);
$b = array_sum($eq["x1"]);
$c = array_sum($eq["x0"]);

echo "$a $b $c = ".($a+$b+$c).";<br>";

$d = pow($b, 2) - 4 * $a * $c;
if ($d < 0){
  echo 'уравнение имеет комплексное';
  return;
}
$x1 = 0 - $b + pow($d, 0.5);
$x2 = 0 - $b - pow($d, 0.5);
echo 'x1 = '.$x1." x2 = $x2";