<?php
echo "<h1>MySendbox <br>";

$a = $_GET[a];
$b = $_GET[b];
$c = $_GET[c];

echo "Our variablez are ".($a + $b) ;
if ($a>$b) {
    echo "<h1>$a bolshe $b</h1>";
} else if ($a<$b) {
    echo "<h1>$a menshe $b</h1>";
    
} else {
    echo "<h1>chisla ravny</h1>";
}

for ($i=0;$i<$c;$i++){
    echo  ($i + 1)." ";
}
// $i=0;
// while ($i<$c){
    
//     echo  ($i + 1)." ";
//     $i++;
// }
echo "<table border = '1'>";

for ($i = 0; $i < $a; $i++) {
    echo "<tr>";
    for ($j = 0; $j < $b; $j++) {
        echo "<td bgcolor ='#".dechex(random_int(0, 9999999))."'>".random_int(1, 100)."</td>"; 
    }
    echo "</tr>";
}
echo "</table>";
?>
