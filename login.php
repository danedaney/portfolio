<?php
//設定ファイル読み込み
require_once '../../include/starting_select/conf/const.php';

//関数ファイル読み込み
require_once '../../include/starting_select/model/common.php';
require_once '../../include/starting_select/model/login_model.php';

//変数の初期化
$user_name = '';
$password = '';
$err_msg = array();

// セッション開始
session_start();

//ユーザー名をCookieへ保存
//setcookie('user_name', $user_name, time() + 60 * 60 * 24 * 365);

try {
  $dbh = get_db_connect();
  
  $request_method = post_request_method();
  
  if($request_method === 'POST') {
    $user_name  = get_post_data('user_name');
    $password = get_post_data('password');
    
    $data = get_ec_user_userid($dbh, $user_name, $password);
    
    // 登録データを取得できたか確認
    if (isset($data[0]['user_id'])) {
      // セッション変数にuser_idを保存
      $_SESSION['user_id'] = $data[0]['user_id'];
      $_SESSION['user_name'] = $data[0]['user_name'];
      //管理ユーザのチェック
      if($data[0]['user_id'] === 4){
        header('Location: admin.php');
        exit;  
      } else {
        //ログイン済みユーザのホームページへリダイレクト
        header('Location: itemlist.php');
        exit;
      }  
    } else {
      $err_msg[] = 'ユーザー名もしくはパスワードが異なります';
    }
  }  
} catch (PDOException $e) {
  echo '失敗しました。理由：'.$e->getMessage();
}

//購入ページファイル読み込み
include_once '../../include/starting_select/view/login_view.php';
?>