<?php
$gav = function ($param) {
	return function () use ($param){
		echo $param."<br>";
	};
};
function meow($param = NULL) {
	static $i;
	if ($param) {
		$i = $param;
	}
	echo "Meow#".$i."<br>";
}
$army = array();
for ($i = 0; $i < 9; $i++) {
	array_push($army, $gav("GAV#".$i));
}
foreach ($army as $fun){
	$fun();
}
meow();
meow(1);
meow(3);
meow();