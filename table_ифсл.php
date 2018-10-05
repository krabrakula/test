<?php
$row = $_POST["rowNumber"];
$col = $_POST["colNumber"];
echo "<h2>Column =>".$col."; Row=>".$row."</h2><br>";
echo "<table border = '1'>";
for ($i = 0; $i < $row; $i++) {
    echo "<tr>";
    for ($j = 0; $j < $col; $j++) {
        echo "<td>".rand(0,99)."</td>";
    }
    echo "</tr>";
}
echo "</table>";
$myArr = array();
// array_push($myArr, $);
// array_pu
echo implode(", ", $myArr);
