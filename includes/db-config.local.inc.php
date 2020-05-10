<?php

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  
  $host = '127.0.0.1';
  $user = 'root';
  $password = 'S@mur@11961';
  $db = 'db_iste_240';
  $port = 3306;

  function retrieveConnectionToDB() {
    global $user, $host, $password, $db, $port;

    $mysqli = new mysqli($host, $user, $password, $db, $port);

    if(!$mysqli) {
      exit("ERROR {$mysqli->connect_error}");
    }

    return $mysqli;
  }

?>