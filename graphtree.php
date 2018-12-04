<?php
class tree_node {
  public function __construct($data = null) {
    $this->data = $data;
  }
  public $parent = null;
  public $data = null;
  public $childs = array();
  public function add_child(tree_node &$child) {
    array_push($this->childs, $child);
    $child->parent = $this;
  }
  public function has_child(){
    if (count($this->childs)){
      return TRUE;
    } else {
      return FALSE;
    }
  }
  public function add_right_child(tree_node &$child){
    if (!$this->has_child()){
      array_push($this->childs, NULL);
      array_push($this->childs, NULL);
    }
    $this->childs[1] = $child;
  }
  public function add_left_child(tree_node &$child){
    if (!$this->has_child()){
      array_push($this->childs, NULL);
      array_push($this->childs, NULL);
    }
    $this->childs[0] = $child;
  }
}

class tree {
  public $root = null;

  public function build_bin_tree(array $array) {
    for ($i = 0; $i<count($array); $i++) {
      if ($i === 0){
        $this->root = new tree_node($array[0]);
        break;
      }
      $current_node = $this->root;
      $current_value = $array[$i];
      while (TRUE) {                                //цикл перебирает узлы дерева
        if ($current_value == $current_node->data){   //дублирующиеся числа игнорируем
          break;
        }
        if ($current_value < $current_node->data){  //куда добавлеять. на лево
          if (!$current_node->has_child() || ($current_node->childs[0] === NULL)){ //если нет детей или они есть, но нет левого ребенка, создать узел и добавить слева
            $new_node = new tree_node($current_value);
            $current_node->add_left_child($new_node);
            break;
          } else {                                    //если есть левый ребенок,назначить его текущим узлом
            $current_node = $current_node->childs[0];
          }
        } else {                                          //добавлять на право
          if (!$current_node->has_child() || ($current_node->childs[1] === NULL)){ //если нет детей или они есть, но нет правого ребенка, создать узел и добавить слева
            $new_node = new tree_node($current_value);
            $current_node->add_right_child($new_node);
            break;
          } else {                                    //если есть правый ребенок,назначить его текущим узлом
            $current_node = $current_node->childs[1];
          }
        }
      }
    }
  }
}
$a = new tree_node(1);
$b = new tree_node(2);

$a->add_child($b);
$a->data = 5;
echo $b->parent->data;