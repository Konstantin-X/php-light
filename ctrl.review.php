<?php
namespace NGReview;

header('Content-type: application/json');

$method = 'GET';
$id     = 0;

if (isset($_REQUEST['id']) ) {
 $method = $_SERVER['REQUEST_METHOD'];
 $id     = intval($_REQUEST['id']);
}

$controller = new ReviewsController();

if ($method == 'GET') {
    if (!$id) { header("HTTP/1.1 500 [GET] Internal Server Error [unknown product ID = $id]"); exit; }

    $controller->getById($id);
}

if ($method == 'POST' && $id) {
    if (!$id) { header("HTTP/1.1 500 [POST] Internal Server Error [unknown product ID = $id]"); exit; }
    $headers = apache_request_headers();
//var_dump($headers);
    $token    = $headers['authorization'];
    $postdata = file_get_contents("php://input");
    $request  = json_decode($postdata);

    $controller->add($id, $token, $request);
}


class ReviewsController {
    private $model;
    private $authModel;

    public function __construct() {
        require_once('models/review.php');
        require_once('models/auth.php');

        $this->model     = new ReviewModel();
        $this->authModel = new AuthModel();
    }

    public function add($productId, $token, $data) {
        $userId = $this->authModel->getUserID($token);

        if ($userId == false) { echo json_encode(array('success' => false, 'message' => 'Incorrect username or password')); exit; }

        $reviewId = $this->model->add($productId, $userId, $data);

        echo json_encode(array('review_id' => $reviewId));
        exit;
    }

    public function getById($id) {
        $reviews = $this->model->getById($id);

        echo json_encode($reviews);
        exit;
    }
}

