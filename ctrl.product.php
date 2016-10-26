<?php
namespace NGReview;

header('Content-type: application/json');

$action = 'getall';
$method = 'GET';
$id     = 0;
if (isset($_REQUEST['action']) ) {
 $action = $_REQUEST['action'];
 $method = $_SERVER['REQUEST_METHOD'];
 $id     = intval($_REQUEST['id']);
}

$controller = new ProductsController($Db);

if ($action == 'getall') {
  $controller->getAll();
}

if ($action == 'getbyid') {
 $controller->getById($id);
}


class ProductsController {
  private $model;

  public function __construct() {
    require_once('models/product.php');
    $this->model = new ProductModel();
  }

  public function getAll() {
    $products = $this->model->getAll();

    echo json_encode($products);
    exit;
  }

  public function getbyid($id) {
    $product = $this->model->getbyid($id);

    echo json_encode($product);
    exit;
  }
}

