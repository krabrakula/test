<?php
$data = array();

$data["name"] = array("string" => trim(filter_input(INPUT_POST, "name")),
		"pattern" => "/(^[a-z]{1,40}$|^[а-яё]{1,40}$)/i");
$data["surname"] = array("string" => trim(filter_input(INPUT_POST, "surname")),
		"pattern" => "/(^[a-z]{1,40}$|^[а-яё]{1,40}$)/i");
$data["patronimic"] = array("string" => trim(filter_input(INPUT_POST, "patronimic")),
		"pattern" => "/(^[a-z]{1,40}$|^[а-яё]{1,40}$)/i");
$data["date"] = array("string" => trim(filter_input(INPUT_POST, "date")),
		"pattern" => "/^(([12]?\d|30)\.(4|6|9|11)|([12]?\d|30|31)\.(1|3|5|7|8|10|12)|([12]?\d\.2))\.(19|20)\d\d$/");
$data["phone"] = array("string" => trim(filter_input(INPUT_POST, "phone")),
		"pattern" => "/^(\+38)?\(?0\d\d\)?\d\d\d-?\d\d-?\d\d$/");
$data["email"] = array("string" => trim(filter_input(INPUT_POST, "email")),
		"pattern" => "/^[\w\.]+@[\w\.]+\.[a-z]{2,6}$/i");
$data["time"] = array("string" => trim(filter_input(INPUT_POST, "time")),
		"pattern" => "/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/");

foreach ($data as $key => $value){
	try {
		if ($value["string"] === "") {
			continue;
		}
		if (!preg_match($value["pattern"], $value["string"])) {
			throw new Exception($key." ".$value["string"]." is wrong<br>");
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
};



