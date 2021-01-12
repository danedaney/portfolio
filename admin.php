<?php
//設定ファイル読み込み
require_once '../../include/starting_select/conf/const.php';
//関数ファイル読み込み
require_once '../../include/starting_select/model/common.php';
//関数ファイル読み込み
require_once '../../include/starting_select/model/admin_model.php';

//変数の初期化
$item_id = '';
$name = '';
$price = '';
$stock = '';
$comment = '';
$status = '';
$err_msg = array();
$normal_msg = array();
$rows = array();
$now_date = date('Y-m-d H:i:s');

//ログインのチェック
session_start();
if (isset($_SESSION['user_id'])) {
  if($_SESSION['user_name'] !== 'admin') {
    header('Location: itemlist.php');
    exit;
  }
  $user_id = $_SESSION['user_id'];
  $user_name = $_SESSION['user_name'];
} else {
// 非ログインの場合、ログインページへリダイレクト
  header('Location: login.php');
  exit;
}

try {
  //DB接続
  $dbh = get_db_connect();
  //リクエストメソッド取得
  $request_method = post_request_method();
  
  if($request_method === 'POST') {
    //POST値取得
    $sql_kind = get_post_data('sql_kind');
    $name = get_post_data('name');
    $price = get_post_data('price');
    $stock = get_post_data('stock');
    $comment = get_post_data('comment');
    $status = get_post_data('private_public');
    
    //sql_kindがinsert
    if($sql_kind === 'insert'){
      //ファイルアップロード
      $upload_result = new_img_filename();
      $new_img_filename = $upload_result['filename'];
      
      //バリデーション
      $err_msg = array_merge(validation_status($status), $err_msg);
      $err_msg = array_merge($upload_result['err_msg'], $err_msg);
      $err_msg = array_merge(validation_comment($comment), $err_msg);
      $err_msg = array_merge(validation_stock($stock), $err_msg);
      $err_msg = array_merge(validation_price($price), $err_msg);
      $err_msg = array_merge(validation_name($name), $err_msg);
      
      //エラーなければ、item_masterにinsertする
      if (count($err_msg) === 0) {
        $dbh->beginTransaction();
        try {
          insert_item_master($dbh, $name, $price, $comment, $new_img_filename, $status, $now_date);
          
          $item_id = $dbh->lastInsertId();
          
          $normal_msg = insert_item_stock($dbh, $item_id, $stock, $now_date);
          
          $dbh->commit();
        } catch (PDOException $e) {
          $dbh->rollback();
          throw $e;
        }   
      }
      //sql_kindがupdate_stock    
    } elseif($sql_kind === 'update_stock') {
        $item_id = get_post_data('item_id');     
        $stock = get_post_data('stock');
        
        $err_msg = validation_stock($stock);
        
        //drink_stockテーブルの書き換え
        if (count($err_msg) === 0) { 
          $normal_msg = update_item_stock($dbh, $item_id, $stock, $now_date);
        }
      //sql_kindがupdate_public_private  
    } elseif($sql_kind === 'update_private_public') {
        $item_id = get_post_data('item_id');
        $status = get_post_data('private_public');
        
        $err_msg = validation_status($status);
        
        //drink_masterテーブルの書き換え
        if (count($err_msg) === 0) {
          $normal_msg = update_item_master($dbh, $item_id, $status, $now_date);
        }
        
    } elseif($sql_kind === 'delete') {
        $item_id = get_post_data('item_id');
        
        if (count($err_msg) === 0) {
          
          $dbh->beginTransaction();
          try{
            
            delete_item_master($dbh, $item_id);
            
            $normal_msg=delete_item_stock($dbh, $item_id);
            
            $dbh->commit();
            
          } catch (Exception $e) {
            
            $dbh->rollback();
            throw $e;
          }
        }
      }  
    }  
    //投稿一覧を取得
    $rows = get_post_table_list($dbh);
    
    //特殊文字をHTMLエンティティに変換
    $rows = entity_assoc_array($rows);

} catch (Exception $e) {
  echo '失敗しました。理由:'.$e->getMessage();
}
//管理ページの読み込み
include_once '../../include/starting_select/view/admin_view.php';
