<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\login\confirm.php
*ファイル名:confirm.php
*アクセスURL:http://localhost/DT/login/confirm.php
*/
namespace login;

require_once dirname(__FILE__). '/Bootstrap.class.php';

use login\master\initMaster;
use login\lib\Database;
use login\lib\Common;

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
    'cache' => Bootstrap::CACHE_DIR
]);

$db = new Database(Bootstrap::DB_HOST,Bootstrap::DB_USER, Bootstrap::DB_PASS,
Bootstrap::DB_NAME);
$common = new Common();

//モード判定（どの画面から来たのか判断）
//登録画面からきた場合
if (isset($_POST['confirm']) === true) {
    $mode = 'confirm';
}
//戻る場合
if (isset($_POST['back']) === true) {
    $mode = 'back';
}
//登録完了
if (isset($_POST['complete']) === true) {
    $mode = 'complete';
}
//DBからid取得
$query ='';
$query1 ='';
$query= "select id from login where username = "."'".$_POST['username']."'";
$res1 = $db->select($query);
//DBからpassword取得
$query1 = "select password from login where id = ".$res1[0]['id'];
$res2 = $db->select($query1);
$db->close();

//ボタンのモードよって処理をかえる
switch ($mode) {
    case 'confirm'://新規登録
                   //データを受け継ぐ
                   // ↓この情報は入力には必要ない
        unset($_POST['confirm']);
        
        if (password_verify ($_POST['password'],$res2[0]['password'])) {
            $template = 'complete.html.twig';

     
        } else {
            echo'IDとパスワードが一致しません';
            $template = 'regist.html.twig';
           
        }

        $dataArr = $_POST;

        //エラーメッセージの配列作成
        $errArr = $common->errorCheck($dataArr);
        $err_check = $common->getErrorFlg();
        //err_check = false →エラーがあります
        //err_check = true →エラーがないです
        //エラーがなければconfirm.tpl 　あるとregist.tpl
        //$template = ($err_check === true) ? 'confirm.html.twig' : 'regist.html.twig';
        break;
    case 'back'://戻ってきたとき
                //ポストされたデータを元に戻すので、$dataArrに入れる
        $dataArr = $_POST;
        unset($dataArr['back']);
        
        //エラーも定義しておかないと、Undefinedエラーがでる
        foreach ($dataArr as $key => $value) {
            $errArr[$key] = '';
        }

        $template = 'regist.html.twig';
        break;

    case 'complete'://登録完了
        $dataArr =  $_POST;
        //↓この情報はいらないので外しておく
        unset($dataArr['complete']);
        $column = '';
        $insData = '';

        //foreach の中でSQL文を作る
        foreach ($dataArr as $key => $value) {
            $column .= $key . ', ';
            if ($key === 'password') {
                $value = password_hash ($dataArr['password'], PASSWORD_DEFAULT);
            }
            $insData .= $db->str_quote($value) . ', ';
        }

    $query = " INSERT INTO login ( "
            . $column
            . " regist_date "
            ." ) VALUES ( "
            . $insData
            ." NOW() "
            . " ) ";

    $res = $db->execute($query);
    $db->close();
    if ($res === true) {
        //登録成功時は完成時はページへ
        header('Location: ' . Bootstrap::ENTRY_URL . 'complete.php');
        exit();
     }else {
        //登録失敗時は登録画面に戻る
        $template = 'regist.html.twig';
        foreach ($dataArr as $key => $value) {
            $errArr[$key] = '';
        }
    }

    break;
}

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$template = $twig->loadTemplate($template);
$template->display($context);
