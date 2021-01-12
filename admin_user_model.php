<?php
/**
* ec_user一覧を取得する
* @param obj $dbh DBハンドル
* @return array ユーザ一覧配列データ
*/
function get_user_list($dbh) {
  try { 
    $sql = 'SELECT user_name, create_datetime
            FROM ec_user';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
  } catch (PDOException $e) {
    throw $e;
  } 
  return $rows;
}

?>