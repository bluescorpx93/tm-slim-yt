<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Views\PhpRenderer as Slimview;

require('../vendor/autoload.php');
require('../src/config/mysqldb.php');

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(['settings' => $config]);
$container = $app->getContainer();
$container['renderer'] = new Slimview('../src/templates');



// ROUTES FOR BOTH API AND FRONTEND




$app->get('/api/customers', function (Request $req, Response $res){
  $sql_query = "select * from customers";
  try{
    $db = new mysqldb();
    $db = $db -> connectToTheDB();
    $statement = $db->query($sql_query);
    $customers = $statement->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    // print_r($customers);
    // $customers = json_encode($customers);

    $res = $this->renderer->render($res, 'index.phtml', ['customers'=>$customers, 'show_all_customers'=> true]);
    return $res;
  } catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
  }
});

$app->get('/api/customer/{id}', function(Request $req, Response $res){
  $id= $req->getAttribute('id');
  $sql_query = "select * from customers where id=$id";
  try{
    $db = new mysqldb();
    $db = $db->connectToTheDB();
    $statement = $db->query($sql_query);
    $customer = $statement->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    echo json_encode($customer);

  } catch (PDOException $e){
    echo "Connection Failed: ". $e.getMessage();
  }

});

$app->post('/api/customers', function(Request $req, Response $res){
  $firstName = $req->getParam('first_name');
  $lastName = $req->getParam('last_name');
  $email = $req->getParam('email');
  $phone = $req->getParam('phone');

  $sql_query = "insert into customers (firstName, lastName, email, phone) values (:firstName, :lastName, :email, :phone);";
  try{
    $db = new mysqldb();
    $db = $db->connectToTheDB();
    $statement = $db->prepare($sql_query);
    $statement->bindParam(':firstName', $firstName);
    $statement->bindParam(':lastName', $lastName);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':phone', $phone);
    $statement->execute();
    echo "Customer Added";

  } catch(PDOEception $e){
    echo "Connection Failed: ". $e.getMessage();
  }
});

$app->put('/api/customer/{id}', function(Request $req, Response $res){
  $id = $req->getAttribute("id");
  $firstName = $req->getParam('first_name');
  $lastName = $req->getParam('last_name');
  $email = $req->getParam('email');
  $phone = $req->getParam('phone');

  $sql_query = "update customers set firstName=:firstName, lastName=:lastName, email=:email, phone=:phone where id=$id;";
  try{
    $db = new mysqldb();
    $db = $db->connectToTheDB();
    $statement = $db->prepare($sql_query);
    $statement->bindParam(':firstName', $firstName);
    $statement->bindParam(':lastName', $lastName);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':phone', $phone);
    $statement->execute();
    echo "Customer Edited";

  } catch(PDOEception $e){
    echo "Connection Failed: ". $e.getMessage();
  }
});

$app->delete('/api/customer/{id}', function(Request $req, Response $res){
  $id = $req->getAttribute("id");
  $sql_query = "delete from customers where id=$id";
  try{
    $db = new mysqldb();
    $db = $db->connectToTheDB();
    $statement = $db->query($sql_query);
    echo "Deleted";
  } catch (PDOException $e){
    echo "Connection Failed: ". $e.getMessage();
  }
});

$app->get('/hello/{name}', function(Request $req, Response $res, array $args){
    $name = $args['name'];
    // $res->getBody()->write("Hey $name");
    $res = $this->renderer->render($res, 'test.phtml', ['theName'=>$name]);
    return $res;
  });

$app->run();
?>