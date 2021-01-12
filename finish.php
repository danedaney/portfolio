<?php
//設定ファイル読み込み
require_once '../../include/starting_select/conf/const.php';

//関数ファイル読み込み
require_once '../../include/starting_select/model/common.php';
require_once '../../include/starting_select/model/finish_model.php';

//変数の初期化
$user_id = '';
$user_name = '';
$item_id = '';
$amount = '';
$err_msg = array();
$normal_msg = array();
$rows = array();
$now_date = date('Y-m-d H:i:s');

//ログインのチェック
session_start();
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $user_name = $_SESSION['user_name'];
} else {
// 非ログインの場合、ログインページへリダイレクト
  header('Location: login.php');
  exit;
}


try {
  $dbh = get_db_connect();
  
  $request_method = post_request_method();
  
  if($request_method === 'POST') {
    
    $dbh->beginTransaction();
    try {
      
      $rows = get_item_list($dbh, $user_id);
    
      $rows = entity_assoc_array($rows);
    
      $total_price = total_price($rows); 
      
      foreach ($rows as $value) {
        if(($value['stock'] - $value['amount']) >= 0) {
          update_item_stock($dbh, $value['item_id'], $value['amount'], $now_date);
        } else {
          throw new PDOException('在庫数が足りません。');
        }
      }
      
      delete_cart($dbh, $user_id);
      
      $dbh->commit();
      
    } catch(PDOException $e) {
      
      $dbh->rollback();
      throw $e;
    }
  }
} catch(PDOException $e) {
  //在庫数のエラーチェックとそれ以外で分けたい
  $_SESSION['stock_error_msg'] = '失敗しました。理由:'.$e->getMessage();
  header('Location: cart.php');
  exit;
}


include_once '../../include/starting_select/view/finish_view.php';
?>