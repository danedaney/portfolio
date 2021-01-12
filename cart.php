<?php
//設定ファイル読み込み
require_once '../../include/starting_select/conf/const.php';

//関数ファイル読み込み
require_once '../../include/starting_select/model/common.php';
require_once '../../include/starting_select/model/cart_model.php';

//変数の初期化
$user_id = '';
$user_name = '';
$sql_kind = '';
$item_id = '';
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

if (isset($_SESSION['stock_error_msg'])) {
  $err_msg[] = $_SESSION['stock_error_msg'];
  unset($_SESSION["stock_error_msg"]);
}

try {
  $dbh = get_db_connect();
  
  $request_method = post_request_method();
  
  if($request_method === 'POST') {
    
    $sql_kind = get_post_data('sql_kind');
    $item_id = get_post_data('item_id');
    $amount = get_post_data('amount');
    
    //削除機能
    if($sql_kind === 'delete_cart') {
      
      $normal_msg = delete_cart($dbh, $user_id, $item_id);
      
      //数量変更  
    } elseif($sql_kind === 'change_cart') {
      
      $err_msg = validation_amount($amount);
      
      if(count($err_msg) === 0) {
        $normal_msg = update_cart($dbh, $user_id, $item_id, $amount, $now_date);
      }
    }
  }
  
  //カートの中の商品一覧取得
  $rows = get_item_list($dbh, $user_id);
  
  $rows = entity_assoc_array($rows);
  
  $total_price = total_price($rows);
  
  
} catch(PDOException $e) {
  echo '失敗しました。理由:'.$e->getMessage();
}


//管理ページファイル読み込み
include_once '../../include/starting_select/view/cart_view.php';

?>