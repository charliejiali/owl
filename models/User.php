<?php
namespace app\models;

use Yii;
use yii\db\Query;
use yii\base\Model;
use yii\web\Cookie;

class User extends Model
{
    private $user_auto_login_time=7;

    public function update_password($user_id,$old,$new,$confirm){
        $old=trim($old);
        $new=trim($new);
        $confirm=trim($confirm);

        if($old==""){
            return "请填写当前密码";
        }
        if($new==""){
            return "请填写新密码";
        }
        if($confirm==""){
            return "请填写确认密码";
        }
        if($new!==$confirm){
            return "确认密码与新密码不一致";
        }
        $user=$this->getUserById($user_id);

        $old_password=$user["password"];
        $old_mask_code=$user["mask_code"];

        if($old_password!=$this->make_password($old,$old_mask_code)){
            return "当前密码错误";
        }
        $new_password=$this->make_password($new,$old_mask_code);
        if($old_password==$new_password){
            return "新密码与旧密码一致";
        }

        $r=Yii::$app->db->createCommand()->update('user',array("password"=>$new_password),array("user_id"=>$user_id))->execute();
        if(!$r){
            return "修改密码失败";
        }
        return true;
    }
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
        return (new Query)
            ->select('user_id,hash,status')
            ->from('user')
            ->where(['itensyn_uid'=>$user_id,'mask_code'=>$token])
            ->one();
    }
    public function getUserByEmail($email){
        return (new Query)
            ->select('user_id,password,hash,mask_code')
            ->from('user')
            ->where(['email'=>$email,'status'=>1])
            ->one();
    }
    public function getUserById($user_id){
        return (new Query)
            ->select('*')
            ->from('user')
            ->where(['user_id'=>$user_id,'status'=>1])
            ->one();
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

        $cookies->add(new Cookie([
            'name' => 'owl_uid',
            'value' =>$user_id,
            'expire'=>$login_time
        ]));
        $cookies->add(new Cookie([
            'name' => 'owl_token',
            'value' => $hash,
            'expire'=>$login_time
        ]));
        $cookies->add(new Cookie([
            'name' => 'owl_key',
            'value' => $mask_code,
            'expire'=>$login_time
        ]));
    }
}
