<?php
class sql_in_out_edit{
  private $link;
  public function __construct(){
    $host = 'localhost'; // адрес сервера
    $database = 'test_db'; // имя базы данных
    $user = 'root'; // имя пользователя
    $password = 'root'; // пароль
    $this->link = mysqli_connect($host, $user, $password, $database)
      or die("Ошибка " . mysqli_error($link));
  }
  public function __destruct() {
    mysqli_close($this->link);
  }
  public function sql_output_all() {
    $query ="SELECT * FROM people";
    $result = mysqli_query($this->link, $query) or die("Ошибка " . mysqli_error($this->link));
    if ($result) {
      $output_arr = mysqli_fetch_fields($result);
      $head = array();
      foreach ($output_arr as $value) {
        array_push($head, $value->name);
      }
      $body = mysqli_fetch_all($result);
      array_unshift($body, $head);

      echo json_encode($body);
    }
  }
}
