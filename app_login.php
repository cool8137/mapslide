<?php
  $secrete = $_POST['secrete'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $enc_password = md5($password);

  if ($secrete == "LatteCafe"){

  
    require 'config.php';
    //Database Class
    require 'classes/database.php';

    $database = new Database;
  

    //Query
    $database->query("SELECT * FROM users WHERE username = :username AND password = :password");
    $database->bind(':username',$username);
    $database->bind(':password',$enc_password);
    $rows = $database->resultset();
    $count = count($rows);
    if($count > 0){
	  echo $enc_password.' '.$username.' ';
      echo 'youMayPass';
    } else {
      echo 'youMayNotPass';
    }
  }
?>