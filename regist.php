<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\login\regist.php
*ファイル名:regist.php
*アクセスURL:http://localhost/DT/login/regist.php
*/
namespace login;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use login\master\initMaster;
use login\Bootstrap;

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

//初期データを設定
$dataArr = [
    'username' => '',
    'password' => ''
];

//エラーメッセージの定義、初期
$errArr = [];
foreach ($dataArr as $key => $value) {
    $errArr[$key] = '';
}

$context = [];
$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;

$template = $twig->loadTemplate('regist.html.twig');
$template->display($context);