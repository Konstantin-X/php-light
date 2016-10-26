<?php
namespace NGReview;
use PDO;

require_once('_db.php');

class productModel {
    private $db;

    function __construct() {

        $this->db = Db::getInstance();
    }

    public function getAll() {
        $q = $this->db->prepare('SELECT * FROM products');
        $q->execute();
        $r = $q->fetchAll(PDO::FETCH_ASSOC);

        return $r;
    }

    public function getById($id) {
        $q = $this->db->prepare('SELECT * FROM products WHERE id=:id');
        $q->execute(array(':id' => $id));
        $r = $q->fetch(PDO::FETCH_ASSOC);

        return $r;
    }
}