<?php
//設定ファイル読み込み
require_once '../../include/starting_select/conf/const.php';
//関数ファイル読み込み
require_once '../../include/starting_select/model/common.php';
//関数ファイル読み込み
require_once '../../include/starting_select/model/itemlist_model.php';

//変数の初期化
$user_id = '';
$user_name = '';
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

try {
  $dbh = get_db_connect();
  
  $rows = get_item_list($dbh);
  
  $rows = entity_assoc_array($rows);
  
} catch(PDOException $e) {
  echo '失敗しました。理由:'.$e->getMessage();
}

try {
 $dbh = get_db_connect();
 
 $request_method = post_request_method();
  
  if($request_method === 'POST') {
    
    $sql_kind = get_post_data('sql_kind');
    $item_id = get_post_data('item_id');
    
    if($sql_kind ==='insert_cart'){
      
      $carts = get_ec_cart_list($dbh, $item_id, $user_id);
      if(isset($carts[0]['cart_id'])) {
        
        $normal_msg = update_cart_amount($dbh, $user_id, $item_id, $now_date);
        
      } else {
        
        $normal_msg = insert_cart($dbh, $user_id, $item_id, $now_date);
        
      }
    }
  }  
} catch(PDOException $e) {
  echo '失敗しました。理由:'.$e->getMessage();
}  

//管理ページファイル読み込み
include_once '../../include/starting_select/view/itemlist_view.php';

?>