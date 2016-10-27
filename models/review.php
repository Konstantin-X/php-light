<?php
namespace NGReview;
use PDO;

require_once('_db.php');

class reviewModel {
    private $db;

    function __construct() {

        $this->db = Db::getInstance();
    }

    public function add($data) {
        $userID = $this->addUser($data);

        $q = $this->db->prepare('INSERT INTO reviews (user_id, text, images) VALUES (:user_id, :text, :images)');
        $q->bindParam(':user_id',    $userID,                  PDO::PARAM_INT);
        $q->bindParam(':text',       $data->text,              PDO::PARAM_STR);
        $q->bindParam(':images',     serialize($data->images), PDO::PARAM_STR);
        $q->execute();
        $reviewID = $this->db->lastInsertId();

        $this->sendAdminMail($data, $reviewID);

        return $reviewID;
    }

    public function getAll() {
        $q = $this->db->prepare('SELECT R.*, U.mail AS usermail, U.name AS username FROM reviews R, users U WHERE R.user_id=U.id ORDER BY R.created_at DESC');
        $q->execute();
        $r = $q->fetchAll(PDO::FETCH_ASSOC);
        foreach ($r as &$v) {
            $v = array('id'         => $v['id'],
                       'text'       => $v['text'],
                       'images'     => unserialize($v['images']),
                       'created_at' => $v['created_at'],
                       'created_by' => array('id' => $v['user_id'], 'username' => $v['username'], 'usermail' => $v['usermail'])
                );
        }

        return $r;
    }

    public function addUser($data) {

        $q = $this->db->prepare('INSERT INTO users (mail, name, phone) VALUES (:mail, :name, :phone)');
        $q->bindParam(':mail',  $data->usermail,    PDO::PARAM_STR);
        $q->bindParam(':name',  $data->username,    PDO::PARAM_STR);
        $q->bindParam(':phone', $data->userphone,   PDO::PARAM_STR);
        $q->execute();
        $newUserID = $this->db->lastInsertId();

        return $newUserID;
    }

    private function sendAdminMail($data, $reviewID) {
        $to      = $this->adminEmail;
        $subject = 'New review with ID #'. $reviewID;

        $headers = 'From: server@app.dev' ."\r\n";
        $headers.= 'Reply-To: '. $data->usermail ."\r\n";
        $headers.= 'X-Mailer: PHP/' . phpversion();

        $message = 'New review [#'. $reviewID.']:' ."\r\n";
        $message.= ' User:  '. $data->username  ."\r\n";
        $message.= ' Mail:  '. $data->usermail  ."\r\n";
        $message.= ' Phone: '. $data->userphone ."\r\n";
        $message.= "\r\n\r\n";
        $message.= 'Text: '. $data->text;

        return mail($to, $subject, $message, $headers);
    }
}