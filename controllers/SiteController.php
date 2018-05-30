<?php
namespace app\controllers;

use Yii;
use app\models\User;

class SiteController extends UserAuthController{
    public $pageTitle="";
    public $pageNavId=0;

    public function actionIndex(){
        return $this->render('index');
    }
    public function actionMode(){
        return $this->render('mode');
    }

    public function actionLogin(){
        $get = Yii::$app->request->get();
        $email = $get["email"];
        $password = $get["password"];
        $remember = $get["auto_login"];

        $class_user = new User;
        $login = $class_user->login($email, $password, $remember);
        if($login===1){
            $r=1;
            $msg="success";
        }else if($login===-1){
            $r=-1;
            $msg="itensyn";
        }else{
            $r=0;
            $msg=$login;
        }

        return json_encode([
            "r"=>$r,
            "msg"=>$msg,
        ]);
    }
    public function actionItensynLogin(){
        $get=Yii::$app->request->get();
        $user_id=$get["user_id"];
        $token=$get["token"];

        $class_user=new User;
        $login=$class_user->itensyn_login($user_id,$token);
        if($login===1){
            $r=1;
            $msg="success";
        }else{
            $r=0;
            $msg=$login;
        }
        return json_encode([
            "r"=>$r,
            "msg"=>$msg
        ]);
    }
    public function actionLogout(){
        $r=0;
        $msg="";
        $class_user=new User;
        $class_user->logout();
    }
    public function actionChangePassword(){

    }
}
