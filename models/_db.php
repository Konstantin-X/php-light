<?php
namespace NGReview;
use PDO;

class Db {
  private static $instance = NULL;

  public static function getInstance() {
    if (!isset(self::$instance)) {
      $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

      $DB_host = getenv('OPENSHIFT_MYSQL_DB_HOST');
      $DB_port = getenv('OPENSHIFT_MYSQL_DB_PORT');
      $DB_user = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
      $DB_pass = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
      $DB_name = 'php';

      self::$instance = new PDO('mysql:host='. $DB_host .':'. $DB_port. ';dbname='. $DB_name, $DB_user, $DB_pass, $pdo_options);
    }

    return self::$instance;
  }
}
