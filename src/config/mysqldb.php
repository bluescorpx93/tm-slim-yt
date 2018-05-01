<?php

class mysqldb{
  private $db_engine = "mysql";
  private $db_host = 'localhost';
  private $db_user = 'root';
  private $db_pass = 'mariadb';
  private $db_name = 'slim_php_app';

  public function connectToTheDB(){
    $mysql_connection_str = "$this->db_engine:host=$this->db_host;dbname=$this->db_name";

    $db_connection = new PDO($mysql_connection_str, $this->db_user, $this->db_pass);

    $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db_connection;
  }
}