<?php
namespace NGReview;
use PDO;

require_once('_db.php');

class reviewModel {
    private $db;

    function __construct() {

        $this->db = Db::getInstance();
    }

    public function add($productId, $userId, $data) {

        $q = $this->db->prepare('INSERT INTO reviews (product_id, user_id, rate, text, images) VALUES (:product_id, :user_id, :rate, :text, :images)');

        $q->bindParam(':product_id', $productId,               PDO::PARAM_INT);
        $q->bindParam(':user_id',    $userId,                  PDO::PARAM_INT);
        $q->bindParam(':rate',       $data->rate,              PDO::PARAM_INT);
        $q->bindParam(':text',       $data->text,              PDO::PARAM_STR);
        $q->bindParam(':images',     serialize($data->images), PDO::PARAM_STR);

        $q->execute();

        return $this->db->lastInsertId();
    }

    public function getById($id) {
        $q = $this->db->prepare('SELECT R.*, U.mail AS usermail, U.name AS username FROM reviews R, users U WHERE R.user_id=U.id AND product_id=:id ORDER BY R.created_at DESC');
        $q->execute(array(':id' => $id));
        $r = $q->fetchAll(PDO::FETCH_ASSOC);
        foreach ($r as &$v) {
            $v = array('id'         => $v['id'],
                       'product'    => $v['product_id'],
                       'rate'       => $v['rate'],
                       'text'       => $v['text'],
                       'images'     => unserialize($v['images']),
                       'created_at' => $v['created_at'],
                       'created_by' => array('id' => $v['user_id'], 'username' => $v['username'], 'usermail' => $v['usermail'])
                );
        }
        return $r;
    }
}