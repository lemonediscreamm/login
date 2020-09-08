<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\login\list.php
*ファイル名:list.php
*アクセスURL:http://localhost/DT/login/list.php
*/
namespace login;

require_once dirname(__FILE__). '/Bootstrap.class.php';

use login\Bootstrap;
use login\master\initMaster;
use login\lib\Database;
use login\lib\Common;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);
$db = new Database(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS,
Bootstrap::DB_NAME);

$query = " SELECT "
        . " username, "
        . " password, "
        . " regist_date "
        . " FROM "
        . "     login ";
$dataArr = $db->select($query);
$db->close();

$context = [];
$context['dataArr'] = $dataArr;
$template = $twig->loadTemplate('list.html.twig');
$template->display($context);