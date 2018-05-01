<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;
$app->get('/hello/{name}', function(Request $req, Response $res, array $args){
  $name = $args['name'];
  $res->getBody()->write("Hey $name");
  return $res;

});

require('../src/routes/customers.php');
$app->run();
?>