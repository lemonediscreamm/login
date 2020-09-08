<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\login\lib\Common.class.php
*ファイル名:Common..class.php
*アクセスURL:http://localhost/DT/login/lib/Common.class.php
*/

namespace login\lib;

class Common
{
    private $dataArr = [];
    private $errArr = [];
    
    //初期化
    public function __construct()
    {
    }
    public function errorCheck($dataArr)
    { 
        $this->dataArr = $dataArr;
        //クラス内のメソッドを読み込む
        $this->createErrorMessage();

        $this->userNamecheck();
        $this->passwordcheck();
        
        return $this->errArr;
    }

    private function createErrorMessage()
    {
        foreach ($this->dataArr as $key => $val){
            $this->errArr[$key] = '';
        }
    }
    
    private function usernameCheck()
    {
        if($this->dataArr['username'] === '') {
            $this->errArr['username'] = 'ユーザIDを入力してください';
        }
    }

    private function passwordCheck()
    {
        if($this->dataArr['password'] === '') {
            $this->errArr['password'] = 'パスワードを入力してください';
        }
    }
        public function getErrorFlg()
        {
            $err_check = true;
            foreach ($this->errArr as $key => $value) {
                if ($value !== '') {
                    $err_check = false;
                }
            }
            return $err_check;
        }
    } 