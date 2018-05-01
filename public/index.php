<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Views\PhpRenderer;

require('../vendor/autoload.php');
require('../src/config/mysqldb.php');

$app = new \Slim\App;
$container = $app->getContainer();
$container['view'] = function($container){
  return new \Slim\Views\PhpRenderer('templates/');
};

$app->get('/hello/{name}', function(Request $req, Response $res, array $args){
  $name = $args['name'];
  $res->getBody()->write("Hey $name");
  return $res;
  // return $this->view->render($res, 'index.html', []);
});

require('../src/routes/customers.php');

$app->run();
?>