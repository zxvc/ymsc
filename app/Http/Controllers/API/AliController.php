<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3
 * Time: 13:51
 */

namespace app\Http\Controllers\API;

use App\Components\BannerManager;
use App\Components\DateTool;
use App\Components\MemberManager;
use App\Components\OrderManager;
use App\Components\ServiceManager;
use App\Components\Utils;
use App\Http\Controllers\Controller;
use App\Models\OrderModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yansongda\Pay\Log;
use Yansongda\Pay\Pay;

class AliController extends Controller
{
    //获取支付宝支付的相关信息
    private function getConfigForAli()
    {
        $config = [
            'app_id' => Utils::ALIPAY_APPID, // APP APPID
            'notify_url' => 'http://'.$_SERVER['SERVER_NAME'].Utils::ALIPAY_NOTIFY_URL,
            'return_url' => 'http://'.$_SERVER['SERVER_NAME'].Utils::ALIPAY_RETRUN_URL,
            'ali_public_key' => Utils::ALIPAY_PUBLIC_KEY,     // 支付宝公钥，1行填写
            'private_key' => Utils::ALIPAY_PRIVATE_KEY,        // 自己的私钥，1行填写
            'log' => [ // optional
                'file' => app_path() . Utils::ALIPAY_LOG_FILE,
                'level' => Utils::ALIPAY_LOG_LEVEL
            ]
        ];
        return $config;
    }
    /*
     * 支付宝支付成功回调（根据文档写的）
     *
     * By zm
     *
     * 2018-04-08
     */
    public function aliNotify(Request $request)
    {
        $config = self::getConfigForAli();
        Log::info('Ali config : ', $config);
        $ali = new Pay($config);
        $user=$request->cookie('user');
//        Log::info('Ali user : ', $user);
        try {
            $data = Pay::alipay($config)->verify(); // 是的，验签就这么简单！
            Log::info('Ali notify', $data->all());
            //支付成功
            if ($data) {
                //总订单out_trade_no
                $out_trade_no = $data->out_trade_no;
                Log::info('order out_trade_no:'.$data->out_trade_no);
                //针对总订单进行处理
//                $order = OrderManager::getOrderByUserIdAndTradeNoWithoutSuborder($user['id'],$out_trade_no);
                $order=OrderModel::where('trade_no',$out_trade_no)->first();
                $order_data['pay_at']=date("Y-m-d H:i:s");
                $order_data['status']=2;
                $order=OrderManager::setOrder($order,$order_data);
                $reuslt=$order->save();     //总订单设定支付时间和订单状态
                if($reuslt){
                    Log::info('order trade_no:'.$order->trade_no);
                    //会员积分变更
                    $member=UserModel::find($order['user_id']);
                    $member_data['score']=$member['score']+floor($order['total_amount']);       //积分按元取整来算
//                    $member_data['score']=$member['score']+$order['total_amount'];       //积分按分来算
                    $member=MemberManager::setUser($member,$member_data);
                    $member->save();
                }
            }
            else{
                file_put_contents(storage_path('notify.txt'), "收到异步通知\r\n", FILE_APPEND);
            }
        } catch (Exception $e) {
            Log::info('ali err : '.$e->getMessage());
        }
    }


    public function aliReturn(Request $request)
    {
        $config = self::getConfigForAli();

        return Pay::alipay($config)->verify(); // 是的，验签就这么简单！;
    }
}