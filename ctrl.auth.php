<?php
namespace NGReview;

header('Content-type: application/json');

if (isset($_REQUEST['action']) ) {
    $action = $_REQUEST['action'];

    $postdata = file_get_contents("php://input");
    $authData  = json_decode($postdata);
}

$controller = new AuthController();

if ($action == 'login') {
    $controller->login($authData);
}

if ($action == 'register') {
    $controller->register($authData);
}

class AuthController {
    private $model;

    public function __construct() {
        require_once('models/auth.php');
        $this->model = new AuthModel();
    }

    public function login($authData) {
        $result = $this->model->login($authData);

        echo json_encode($result);
        exit;
    }

    public function register($authData) {
        $result = $this->model->register($authData);

        echo json_encode($result);
        exit;
    }
}


