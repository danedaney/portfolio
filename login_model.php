<?php
function get_ec_user_userid($dbh, $user_name, $password) {
  $data = array();
  
  try { 
    $sql = 'SELECT *
            FROM ec_user
            WHERE user_name = ? AND password = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
    $stmt->bindValue(2, $password, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetchAll();
  } catch (PDOException $e) {
    throw $e;
  } 
  return $data;
}


?>