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


$app->get('/customers', function (Request $req, Response $res){
  $sql_query = "select id, concat(firstName, ' ', lastName) as fullName, email, phone from customers";
  try{
    $db = new mysqldb();
    $db = $db -> connectToTheDB();
    $statement = $db->query($sql_query);
    $customers = $statement->fetchAll(PDO::FETCH_OBJ);
    // $customers = $statement->fetchAll(PDO::FETCH_ASSOC);
    $url = "";
    // var_dump($customers);

    $db = null;
    $res = $this->renderer->render($res, 'index.phtml', ['customers'=>$customers, 'show_all_customers'=> true, 'url'=> $url, 'cust_created'=> false]);
    return $res;
  } catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
  }
});

$app->get('/api/customers', function(Request $req, Response $res){
  $sql_query = "select * from customers;";
  try{
    $db = new mysqldb();
    $db = $db->connectToTheDB();
    $statement = $db->query($sql_query);
    $customers = $statement->fetchAll(PDO::FETCH_OBJ);
    $db = null; 
    $customers = json_encode($customers);
    echo $customers;

  }catch( PDOException $e){
    echo "Connection failed: " . $e->getMessage();
  }
});

$app->get('/customer/{id}', function (Request $req, Response $res){
  $id = $req->getAttribute('id');
  $sql_query = "select * from customers where id=$id";
  try{
    $db = new mysqldb();
    $db = $db->connectToTheDB();
    $statement = $db->query($sql_query);
    $customer = $statement->fetchAll(PDO::FETCH_OBJ);
    $db = null ;

    // var_dump($customer);
    $res= $this->renderer->render($res, 'single_customer.phtml', ['customer'=> $customer, 'cus_id'=> $id ]);
    return $res;

  } catch(PDOException $e){
    echo "Connection Failed: " . $e.getMessage();
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

$app->get('/showaddform', function(Request $req, Response $res){
  $res= $this->renderer->render($res, 'create.phtml', []);
  return $res;
});

$app->post('/customers/c', function(Request $req, Response $res){
  $firstName = $req->getParam('first_name');
  $lastName = $req->getParam('last_name');
  $email = $req->getParam('email');
  $phone = $req->getParam('phone');

  $sql_query = "insert into customers (firstName, lastName, email, phone) values (:firstName, :lastName,:email, :phone) ";

  try{
    $db = new mysqldb();
    $db = $db->connectToTheDB();
    $statement = $db->prepare($sql_query);
    $statement->bindParam(':firstName', $firstName);
    $statement->bindParam(':lastName', $lastName);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':phone', $phone);
    $statement->execute();
    
    return $res->withRedirect("/public/customers");
   
  } catch(PDOException $e){
    echo "Connection Failed: " . $e->getMessage();
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



$app->put('/customer/{id}', function(Request $req, Response $res){
  $id = $req->getAttribute('id');
  $testMsg = "test put with php";
  // echo $testMsg;
  var_dump($req);
  $res = $testMsg;
  return $res;
  // $res = $this->renderer->render($res, 'single_customer.phtml', ['msg'=>$testMsg]);
  // return $res;
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

    $return_sql_query = "select * from customers where id=$id";
    $statement = $db->query($return_sql_query);
    $customer = $statement->fetchAll(PDO::FETCH_OBJ);
    $customer = json_encode($customer);
    $db = null;
    
    echo ($customer);
   
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