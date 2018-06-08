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

        return json_encode(array(
            "r"=>$r,
            "msg"=>$msg,
        ));
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
        return json_encode(array(
            "r"=>$r,
            "msg"=>$msg
        ));
    }
    public function actionLogout(){
        $class_user=new User;
        $class_user->logout();
    }
    public function actionChangePassword(){
        return $this->render('pwd_change');
    }
    public function actionUpdatePassword(){
        $post=Yii::$app->request->post();

        $old_password=$post["old"];
        $new_password=$post["new"];
        $confirm_password=$post["confirm"];

        $class_user=new User;
        $user_id=$this->user_id;
        $result=$class_user->update_password($user_id,$old_password,$new_password,$confirm_password);
        if($result===true){
            $r=1;
            $msg="密码修改成功";
        }else{
            $r=0;
            $msg=$result;
        }
        return json_encode(array(
            "r"=>$r,
            "msg"=>$msg
        ));
    }
}
