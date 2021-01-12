<?php
/**
*バリデーション
*@param user_name 
*@return $err_msg
*/
function validation_user_name($str) {
  $err_msg = [];
  $pattern = '/^[a-zA-Z0-9]*$/';
  
  if(mb_strlen($str) < 5 ){
    $err_msg[] = 'ユーザー名は６文字以上で入力してください';
  }elseif(preg_match($pattern, $str) !== 1) {
    $err_msg[] = 'ユーザー名は半角英数字で入力してください';
  }  
  return $err_msg;
}

/**
*バリデーション
*@param user_name 
*@return $err_msg
*/
function validation_password($str) {
  $err_msg = [];
  $pattern = '/^[a-zA-Z0-9]*$/';
  
  if(mb_strlen($str) < 5 ){
    $err_msg[] = 'パスワードは６文字以上で入力してください';
  }elseif(preg_match ($pattern, $str) !== 1) {
    $err_msg[] = 'パスワードは半角英数字で入力してください';
  }
  return $err_msg;
}

/**
*drink_masterにinsertする
*@param $dbh, $user_name, $password, $now_date
*@param normal_msg 
*/
function insert_user($dbh,$user_name,$password,$now_date) {
  try {
    $sql = 'insert into ec_user(user_name, password, create_datetime, update_datetime) values(?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_name, PDO::PARAM_STR); 
    $stmt->bindValue(2, $password, PDO::PARAM_STR);
    $stmt->bindValue(3, $now_date, PDO::PARAM_STR);
    $stmt->bindValue(4, $now_date, PDO::PARAM_STR); 
    $stmt->execute();
    $normal_msg[] = 'アカウントを作成しました';
  } catch (PDOException $e) {
    throw $e;
  }
  return $normal_msg;
}


/**
* ec_user一覧を取得する
* @param obj $dbh DBハンドル
* @return array ユーザ一覧配列データ
*/
function get_user_list($dbh, $user_name) {
  $members = array();
  
  try { 
    $sql = 'SELECT *
            FROM ec_user
            WHERE user_name = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
    $stmt->execute();
    $members = $stmt->fetchAll();
  } catch (PDOException $e) {
    throw $e;
  } 
  return $members;
}

?>