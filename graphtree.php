<?php
class Node {

    public function __construct(integer $value) {
        $this->value = $value;
        $this->children = array();
    }
    public $value = null;
    public $parent = null;
    public $children = null;
    
    //добавляет в текущий узел ссылку на дочерний, а в дочерний - ссылку на текущий, как на родителя
    public function add_child(int $value){
        $child = new Node($value);
        array_push($this->children, $child);
        $child->parent = $this;
    }
    
    //проверяет наличие левого ребенка, добавляет если нет; обновляет ссылки, если уже есть
    public function add_child_left(int $value){
        if ($this->is_end_node()){
            $this->add_child($value);
        } else {
            $child = new Node($value);
            $this->children[0] = $child;
            $child->parent = $this;
        }
    }
    
    //проверяет наличие детей, если нет добавляет пустого левого, затем правого; обновляет ссылки, если уже есть
    public function add_child_right(int $value){
        $len = count($this->children);
        if ($len == 0){
            array_push($this->children, NULL);
            $this->add_child($value);
        }
        if ($len == 1){
            $this->add_child($value);
        }
        if ($len > 1){
            $child = new Node($value);
            $this->children[1] = $child;
            $child->parent = $this;
        }
    }
    
    //alias для подсчета детей
    public function count_children() {
        return count($this->children);
    }
    
    //является ли узел конечным, проверяя количество потомков
    public function is_end_node(){
        if ($this->count_children() == 0){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    //возвращает ссылку на левого ребенка или на заглушку типа NULL, или возвращает NULL если нет 
    public function get_left_child() {
        if ($this->is_end_node()){
            return NULL;
        } else {
            return $this->children[0];
        }
    }
    
    //возвращает ссылку на правого ребенка...
    public function get_right_child() {
        if ($this->count_children() <= 1){
            return NULL;
        } else {
            return $this->children[1];
        }
    }
}

class Parsed_node {
    public $value = NULL;
    public $children = array();
}


class Tree {
    private $root = NULL;
    
    //проветка соответствия формату входящих данных
    private function check_data_type(array &$values) {
        if (!is_array($values)){
            throw new Exception('Wrong Data Type. Not Array!');
        }
        if (count($values) == 0){
            throw new Exception('Empty array!');
        }
        foreach ($values as $value) {
            if (!is_integer($value)){
                throw new Exception('Wrong dattype. Not integer!');
            }
        }
    }
    
    //строит бинарное дерево на основе полученного массива чисел, сравнивая их между собой. меньшие - налево, большие - направо.
    public function build_comp_tree(array &$values){
        $this->check_data_type($values);
        $this->root = new Node($values[0]);
        for ($i = 1; $i < count($values); $i++) {
            $current_node = $this->root;
            while (true) {
                //условие игнорирования повторного значения
                if ($values[$i] === $current_node->value){
                    break;
                }
                //добавление меньшего узла влево или большего вправо
                if ($values[$i] < $current_node->value){
                    $left_child = $current_node->get_left_child();
                    if (!$left_child){
                        $current_node->add_child_left($values[$i]);
                        break;
                    } else {
                        $current_node = $left_child;
                    }
                }
                if ($values[$i] > $current_node->value){
                    $right_child = $current_node->get_right_child();
                    if (!$right_child){
                        $current_node->add_child_right($values[$i]);
                        break;
                    } else {
                        $current_node = $right_child;
                    }
                }
            }
        }
       
    }
    
    public function tree_to_JSON() {
        $current_node = $this->root;
        $current_parsed_node = new Parsed_node();
        $parsed_tree = $current_parsed_node;
        $current_parsed_node->value = $current_node->value;
                                                                $current_node = new Node(1);//remove after all
        $deep = 0;
        $indexes_stack = array();
        $parents_stack = array();
        
        while (true) {
            //проверка на первое вхождение в узел. условием является отсутствие индекса для узла данной глубины. Задание начального индекса
            if ($deep == count($indexes_stack)){
                array_push($indexes_stack, 0);// 0 это порядковый номер ребенка слева направо
                array_push($parents_stack, &$current_parsed_node);
            }
            //проверка. если единственный - выход из цикла.
            if ($current_node->is_end_node() and !$current_node->parent){
                break;
            }
            //проверка, не вышли ли мы за границы индексов детей. если вышли идем в родителя. если нет - идем в ребенка
            if ($indexes_stack[$deep] == count($current_node->children)){
                if (!$current_node->parent){
                    break;
                } else {
                    $current_node = $current_node->parent;
                    $deep--;
                    array_pop($indexes_stack);
                }
            } else {
                $index = &$indexes_stack[$deep];
                $child = $current_node->children[$index];
                if ($child){
                    $current_node = $child;
                    $current_parsed_node = new Parsed_node();
                    $current_parsed_node->value = $child->value;
                    array_push($parents_stack[$deep]->$children, $current_parsed_node);
                    $index++;
                    $deep++;
                } else {
                    $index++;
                    array_push($parents_stack[$deep]->$children, NULL);
                }
            }
            
        }
    }
}