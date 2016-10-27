<?php
namespace NGReview;

header('Content-type: application/json');

$method     = $_SERVER['REQUEST_METHOD'];
$controller = new ReviewsController();

if ($method == 'GET') {
    $controller->getAll();
}

if ($method == 'POST') {
    $postdata = file_get_contents("php://input");
    $data     = json_decode($postdata);

    $controller->add($data);
}


class ReviewsController {
    private $model;

    public function __construct() {
        require_once('models/review.php');

        $this->model     = new ReviewModel();
    }

    public function add($data) {

        if (!filter_var($data->usermail, FILTER_VALIDATE_EMAIL)) {
          header('HTTP/1.1 500 ERROR: Incorrect email');
          exit;
        }

        if (empty($data->username)) {
          header('HTTP/1.1 500 ERROR: Empty user name');
          exit;
        }

        $reviewID = $this->model->add($data);

        echo json_encode(array('review_id' => $reviewID));
        exit;
    }

    public function getAll() {
        $reviews = $this->model->getAll();

        echo json_encode($reviews);
        exit;
    }
}

