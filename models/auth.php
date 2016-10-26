<?php
namespace NGReview;
use PDO;

require_once('_db.php');

class AuthModel {
    private $db;
    private $adminEmail = 'admin@app.dev';

    function __construct() {

        $this->db = Db::getInstance();
    }

    public function getUserID($token) {
        $q = $this->db->prepare('SELECT id FROM users WHERE token = :token');
        $q->execute(array(':token' => $token));
        $r = $q->fetch(PDO::FETCH_ASSOC);

        if ($r == false) { return false; }

        return $r['id'];
    }

    public function login($data) {
        $q = $this->db->prepare('SELECT id FROM users WHERE mail = :mail AND pass = :pass');
        $q->execute(array(':mail' => $data->usermail, ':pass' => md5($data->password)));
        $r = $q->fetch(PDO::FETCH_ASSOC);

        if ($r == false) { return array('success' => false, 'message' => 'Incorrect username or password'); }

        $token = $this->makeToken();
        $q = $this->db->prepare('UPDATE users SET token = :token WHERE id = :id');
        $q->execute(array(':id' => $r['id'], ':token' => $token));

        $r = array('success' => true, 'token' => $token);

        return $r;
    }

    public function register($data) {
        $r = array( 'success' => false, 'message' => 'During registration, the error occurred, please try again later or contact administrator.');

        // Check user exist
        $q = $this->db->prepare('SELECT id FROM users WHERE mail = :mail');
        $q->bindParam(':mail', $data->usermail, PDO::PARAM_STR);
        $q->execute();
        $a = $q->fetch(PDO::FETCH_ASSOC);

        if ($a !== false) {
            $r['message'] = 'User with same email already exist!';
            return $r;
        }

        // OK = email is unique
        $token = $this->makeToken();

        $q = $this->db->prepare('INSERT INTO users (mail, pass, name, phone, token) VALUES (:mail, :pass, :name, :phone, :token)');
        $q->bindParam(':mail',  $data->usermail,      PDO::PARAM_STR);
        $q->bindParam(':pass',  md5($data->password), PDO::PARAM_STR);
        $q->bindParam(':name',  $data->username,      PDO::PARAM_STR);
        $q->bindParam(':phone', $data->userphone,     PDO::PARAM_STR);
        $q->bindParam(':token', $token,               PDO::PARAM_STR);
        $q->execute();
        $newUserId = $this->db->lastInsertId();

        if ($newUserId) {
            $r = array( 'success' => true, 'token' => $token );
            $this->sendAdminMail($newUserId, $data);
        }

        return $r;
    }

    private function makeToken() {

        return md5(uniqid(mt_rand(), true));
    }

    private function sendAdminMail($newUserId, $newUser) {
      // TODO:
        $to      = $this->adminEmail;
        $subject = 'New User registration with ID #'. $newUserId;

        $headers = 'From: server@app.dev' ."\r\n";
        $headers.= 'Reply-To: '. $newUser->usermail ."\r\n";
        $headers.= 'X-Mailer: PHP/' . phpversion();

        $message = 'New User [#'. $newUserId.'] registration:' ."\r\n";
        $message.= ' User:  '. $newUser->username  ."\r\n";
        $message.= ' Mail:  '. $newUser->usermail  ."\r\n";
        $message.= ' Phone: '. $newUser->userphone ."\r\n";

        return mail($to, $subject, $message, $headers);
    }
}
