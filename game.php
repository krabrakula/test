<?php
class animal {
	
	public $paws;
	public $tail;
	public $full;
	protected $ass = TRUE;
	
	public function __construct($paws) {
		$this->paws = $paws;
		$this->tail = "1";
		$this->full = TRUE;
		
	}
	
	public function dodooo() {
		echo "Жрать срать рOжать";
	}
	
	public function shit() {
		if ($this->full && $this->ass) {
			echo "Дрысь-дрысь!! КАК как ? так ТАК!!<br>";
			$this->full = FALSE;
		} else {
			echo "Пердь пердь пустая кишка<br>";
		}
	}
}

class human extends animal {
	
	public $brain;
	public $hands;
	public function __construct($hands,$paws) {
		parent::__construct($paws);
		$this->hands = $hands;
	}
	public function dodoo() {
		parent::dodoo();
		echo " Говорить глупости <br>";
	}
	public function intuition() {
		if ($this->brain and $this->ass){
			echo "Мозгом знаю, Жопой чую<br>";
			return ;
		}
		if ($this->ass) {
			echo "Дурна тварина без мозку думать неаалооо(((<br>";
			return ;
		}
		if ($this->brain) {
			echo "Cognito ergo sum<br>";
			return ;
		}
	}
	public function switch_ass() {
		if ($this->ass === TRUE ) {
				$this->ass = FALSE;
		}else {
			$this->ass = TRUE;
		}
	}
}

class cityzen extends human {
	private $name;
	private $surname;
	private $age;
	public function __construct(){
		parent::__construct("2", "2");
		$this->tail = NULL;
	}
	public function __set($name, $value) {
		if ($name == "name") {
			if (preg_match("/[а-яё]+/i", $value)) {
				$this->name = $value;
			} else {
				echo "Wrong name format<br>";
			}
		}
		if ($name == "surname") {
			if (preg_match("/[а-яё]+/i", $value)) {
				$this->surname = $value;
			} else {
				echo "Wrong name format<br>";
			}
		}
		if ($name == "age") {
			if (preg_match("/[0-9]+/i", $value)) {
				$this->age = $value;
			} else {
				echo "Wrong name format<br>";
			}
		}
	}
	
	public function __get($name) {
		if ($name == "name") {
			return $this->name;
		};
		if ($name == "surname") {
			return $this->surname;
		};
		if ($name == "age") {
			return  $this->age;
		};
		if ($name == "name") {
			return $this->name;
		};
		if ($name == "full_name") {
			return $this->name." ".$this->surname;
		};
	}
}

$pig = new animal("4");
var_dump($pig);
echo "<br>";
$pig->shit();
$pig->shit();

$negra = new human(2, 2);
var_dump($negra);
$negra->shit();
$negra->intuition();
$negra->brain = "1";
$negra->intuition();
$negra->switch_ass();
$negra->intuition();
$pig->dodoo();
echo "<br>";
$negra->dodoo();

$vasia_poooopkin = new cityzen();
$vasia_poooopkin->name = "Вася";
var_dump($vasia_poooopkin);
$vasia_poooopkin->surname = "ПУПкин";
echo $vasia_poooopkin->full_name;


