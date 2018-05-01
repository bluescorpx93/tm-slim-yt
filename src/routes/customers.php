<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;


$app->get('/api/customers', function (Request $req, Response $res){
  $sql_query = "select * from customers";
  try{
    $db = new db();
    $db = $db -> connect();
    $statement = $db->query($sql_query);
    $customers = $statement->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    echo json_encode($customers);
    // echo $res;
  } catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
  }
});

$app->get('/api/customer/{id}', function(Request $req, Response $res){
  $id= $req->getAttribute('id');
  $sql_query = "select * from customers where id=$id";
  try{
    $db = new db();
    $db = $db->connect();
    $statement = $db->query($sql_query);
    $customer = $statement->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    echo json_encode($customer);

  } catch (PDOException $e){
    echo "Connection Failed: ". $e.getMessage();
  }

});