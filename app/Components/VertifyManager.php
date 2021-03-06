<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/2
 * Time: 14:23
 */

namespace App\Components;

use App\Models\VertifyModel;
use Illuminate\Support\Facades\Mail;

class VertifyManager
{
    /*
       * 生成验证码
       *
       * By TerryQi
       */
    public static function sendVertify($phonenum)
    {
        $vertify_code = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);  //生成4位验证码
        $vertify = new VertifyModel();
        $vertify->phonenum = $phonenum;
        $vertify->code = $vertify_code;
        $vertify->save();
        /*
         * 预留，需要触发短信端口进行验证码下发
         */
        if ($vertify) {
            SMSManager::sendSMSVerification($phonenum, $vertify_code);
            return true;
        }
        return false;
    }

    /*
     * 校验验证码
     *
     * By TerryQi
     *
     * 2017-11-28
     */
    public static function judgeVertifyCode($phonenum, $vertify_code)
    {
        $vertify = VertifyModel::where('phonenum', $phonenum)
            ->where('code', $vertify_code)->where('status', '0')->first();
        if ($vertify) {
            //验证码置为失效
            $vertify->status = '1';
            $vertify->save();
            return true;
        } else {
            return false;
        }
    }


    /*
     * 生成邮箱验证码
     *
     * By zm
     *
     * 2018-02-04
     */
    public static function sendVertifyForEmail($email)
    {
        $vertify_code = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);  //生成4位验证码
        $vertify = new VertifyModel();
        $vertify->email = $email;
        $vertify->code = $vertify_code;
        $vertify->save();
        /*
         * 预留，需要触发短信端口进行验证码下发
         */
        $base=BaseManager::getBaseInfo();
        $data['vertify']=$vertify;
        $data['base']=$base;
        if ($vertify) {
            $flag=Mail::send('home.sign.email',['data'=>$vertify['code']],function($message) use ($data){
                $to = $data['vertify']['email'];
                $message ->to($to)->subject($data['base']['name'].'验证码校验');
            });
//            if($flag){
                return true;
//            }
//            else{
//                return false;
//            }
        }
        return false;
    }

    /*
     * 校验邮箱验证码
     *
     * By zm
     *
     * 2018-02-04
     */
    public static function judgeVertifyCodeByEmail($email, $vertify_code)
    {
        $vertify = VertifyModel::where('email', $email)
            ->where('code', $vertify_code)->where('status', '0')->first();
        if ($vertify) {
            //验证码置为失效
            $vertify->status = '1';
            $vertify->save();
            return true;
        } else {
            return false;
        }
    }

}