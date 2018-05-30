<?php
namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

class User extends ActiveRecord
{
    private $user_auto_login_time=7;

//    public static function change_password($user_id,$old,$new,$confirm){
//        $db=db_connect();
//        $r=0;
//        $msg="";
//
//        do{
//            if($old==""){
//                $msg="请填写当前密码";
//                break;
//            }
//            if($new==""){
//                $msg="请填写新密码";
//                break;
//            }
//            if($confirm==""){
//                $msg="请填写确认密码";
//                break;
//            }
//            if($new!=$confirm){
//                $msg="确认密码与新密码不一致";
//                break;
//            }
//
//            $user=self::get_user(array("user_id"=>$user_id));
//            if(!$user){
//                $msg="未能获取当前用户";
//                break;
//            }
//            $user=$user[0];
//
//            $old_password=$user["password"];
//            $old_mask_code=$user["mask_code"];
//
//            if($old_password!=self::make_password($old,$old_mask_code)){
//                $msg="当前密码错误";
//                break;
//            }
//
//            if($old_password==self::make_password($new,$old_mask_code)){
//                $msg="新密码与旧密码一致";
//                break;
//            }
//
//            $new_mask_code=self::make_mask_code();
//            $new_password=self::make_password($new,$new_mask_code);
//
//            $r=self::update_user(array("password"=>$new_password,"mask_code"=>$new_mask_code),array("user_id"=>$user_id));
//            if(!$r){
//                $msg="修改密码失败";
//                break;
//            }
//
//            $r=1;
//            $msg="修改密码成功";
//        }while(false);
//
//        return array(
//            "r"=>$r,
//            "msg"=>$msg
//        );
//    }
    public function login($email,$password,$remember){
        if(trim($email)===""){
            return "请输入用户名";
        }
        if(trim($password)===""){
            return "请输入密码";
        }
        $user=$this->getUserByEmail($email);
        if($user===null){
            return "当前用户不存在";
        }
        if(trim($user["password"])===""){
            return -1;
        }
        $psw=$this->make_password($password,$user["mask_code"]);
        if($psw!==$user["password"]){
            return "密码错误";
        }
        $this->_set_cookie($user["user_id"],$user["mask_code"],$user["hash"],$remember);
        return 1;
    }
    public function itensyn_login($user_id,$token){
        $user=$this->getItensynUser($user_id,$token);
        if($user===null){
            return "当前用户不存在";
        }
        $status=intval($user["status"]);
        if($status===0){
            return "该账号已被冻结";
        }
        $this->_set_cookie($user["user_id"],$token,$user["hash"],"1");
        return 1;
    }
    public function logout(){
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('owl_uid');
        $cookies->remove('owl_token');
        $cookies->remove('owl_key');
    }
    public function getItensynUser($user_id,$token){
        return User::find()
            ->select('user_id,hash,status')
            ->where(['itensyn_uid'=>$user_id,'mask_code'=>$token])
            ->asArray()->one();
    }
    public function getUserByEmail($email){
        return User::find()
            ->select('user_id,password,hash,mask_code')
            ->where(['email'=>$email,'status'=>1])
            ->asArray()->one();
    }
    public function getUserById($user_id){
        return User::find()
            ->select('email')
            ->where(['user_id'=>$user_id,'status'=>1])
            ->asArray()->one();
    }
    public function make_password($password,$mask_code){
        return substr(md5(substr(md5($password.$mask_code."+tensynpinggu+a8cfe0"),0,8)),16,8);
    }
    public function make_mask_code(){
        return substr(md5(uniqid(rand(),true)),8,8);
    }
    public function make_hash($user_id,$mask_code){
        return substr(md5(substr(md5($user_id.$mask_code."+tensynpinggu+a8cfe1"),0,8)),16,8);
    }
    public function check_login($user_id,$mask_code,$hash){
        $user=$this->getUserById($user_id);
        if($user===null){return false;}

        $check_hash=$this->make_hash($user_id,$mask_code);

        if(trim($hash)!==trim($check_hash)){return false;}

        return $user["email"];
    }

    private function _set_cookie($user_id,$mask_code,$hash,$auto_login){
        if($auto_login=="1"){
            $autoLoginExpireDays=$this->user_auto_login_time;
        }else{
            $autoLoginExpireDays=1;
        }

        $login_time=time()+$autoLoginExpireDays*24*60*60;
        $cookies = Yii::$app->response->cookies;

        $cookies->add(new \yii\web\Cookie([
            'name' => 'owl_uid',
            'value' =>$user_id,
            'expire'=>$login_time
        ]));
        $cookies->add(new \yii\web\Cookie([
            'name' => 'owl_token',
            'value' => $hash,
            'expire'=>$login_time
        ]));
        $cookies->add(new \yii\web\Cookie([
            'name' => 'owl_key',
            'value' => $mask_code,
            'expire'=>$login_time
        ]));
    }
}
