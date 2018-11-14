<?php

class mysqldb{
  private $db_engine = <YOUR_DB_ENGINE>;
  private $db_host = <YOUR_DB_HOST>;
  private $db_user = <YOUR_DB_USERNAME>;
  private $db_pass = <YOUR_DB_NAME>;
  private $db_name = <YOUR_DB_NAME>;

  public function connectToTheDB(){
    $mysql_connection_str = "$this->db_engine:host=$this->db_host;dbname=$this->db_name";

    $db_connection = new PDO($mysql_connection_str, $this->db_user, $this->db_pass);

    $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db_connection;
  }
}