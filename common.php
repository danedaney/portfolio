<?php
/**
* DBハンドルを取得(データベースに接続)
* @return obj $dbh DBハンドル
*/
function get_db_connect() {
   
  try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => DB_CHARSET));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  } catch (PDOException $e) {
    throw $e;
  }
  return $dbh;
}

/**
*リクエストメソッドを取得
*@return str POST 
*/
function post_request_method() {
  return $_SERVER['REQUEST_METHOD'];
}

/**
*POSTデータを取得
*@param str $key 配列キー
*@return str POST値 
*/
function get_post_data($key) {
  $str = '';
  
  if (isset($_POST[$key]) === TRUE) {
    $str = preg_replace('/\A[　\s]*|[　\s]*\z/u', '', $_POST[$key]);
  }
  return $str;
}

/**
* 特殊文字をHTMLエンティティに変換する(2次元配列の値)
* @param array  $assoc_array 変換前配列
* @return array 変換後配列
*/
function entity_assoc_array($assoc_array) {
    
    foreach ($assoc_array as $key => $value) {
        foreach ($value as $keys => $values) {
            //特殊文字をHTMLエンティティに変換
            $assoc_array[$key][$keys] = entity_str($values);
        }
    }
    
  return $assoc_array;
}

/**
* 特殊文字をHTMLエンティティに変換する
* @param str  $str 変換前文字
* @return str 変換後文字
*/
function entity_str($str) {

  return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}

?>