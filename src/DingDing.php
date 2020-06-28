<?php
/**
 * Created by PhpStorm.
 * User: volicano
 * Date: 2020/6/28
 * Time: 10:54
 */

namespace SendMessage\DingDing;


class DingDing
{
    const DingDing_URL='https://oapi.dingtalk.com/robot/send?access_token=';

    protected $access_token;
    protected $secret;
    /**
     * Create a new confide instance.
     *
     * DingDing constructor.
     *
     * @return void
     */
    public function __construct($access_token = null)
    {
        if (is_null($access_token)) {
            $access_token = env('DINGDING_TOKEN', '');
            $secret = env('DINGDING_SECRET', '');
        }
        $this->token = $access_token;
        if($secret){
            $this->secret = $secret;
        }
    }

    /**
     * 对加签安全设置的机器人发消息签名，规则：https://ding-doc.dingtalk.com/doc#/serverapi2/qf2nxq
     * @param $secret
     * @return string
     */
    public function sign($secret){
        if($secret == null || $secret == '') {
            return '';
        }
        list($msec, $sec) = explode(' ', microtime());
        $timestamp = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $string_to_sign = $timestamp . "\n"  . $secret;
        $signature=hash_hmac('sha256',$string_to_sign,$secret,true);
        $urlencode_signature = urlencode(base64_encode($signature));
        return "timestamp=" . $timestamp . "&sign=" . $urlencode_signature;
    }

    /**
     * 推送文本消息到钉钉
     *
     * @param $text
     * @return array
     */
    public function pushText($text,$someone='',$isAtAll=false)
    {
        $curl = curl_init();
        $token = $this->token;
        $secret = $this->secret;
        $url = self::DingDing_URL.$token;
        if($secret){
            $sign=$this->sign($secret);
            if($sign != null && $sign != '') {
                $url = $url . "&" . $sign;
            }
        }
        curl_setopt($curl, CURLOPT_URL,$url);
        $header = ['Content-Type:application/json'];
        curl_setopt ( $curl, CURLOPT_HTTPHEADER, $header );
        curl_setopt($curl, CURLOPT_HEADER, 0);  //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->TextData($text,$someone,$isAtAll)));
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $data = curl_exec($curl);
        if(curl_errno($curl)){
            $error_info = 'Request Error:' . curl_error($curl);
            $result = ['status'=>$http_status,'msg'=>$error_info];
        }else{
            $return_data = json_decode($data,true);
            if($return_data['errcode']==0){
                $result = ['status'=>'200','msg'=>'ok'];
            }else{
                $result = ['status'=>'-1','msg'=>$return_data];
            }
        }
        curl_close($curl);
        return $result;
    }

    /**
     * 指定某个人发送
     * $someone  被@人列表 是一个字符串
     * $isAtAll  是否@所有人
     */
    private function textData($text,$someone='',$isAtAll=false)
    {
        if($someone||$isAtAll){
            if($someone){
                return [
                    'msgtype'=>"text",
                    "text"=>[
                        "content"=>$text,
                    ],
                    "at"=>[
                        "atMobiles"=>[$someone],
                        "isAtAll"=>$isAtAll
                    ],
                ];
            }else{
                return [
                    'msgtype'=>"text",
                    "text"=>[
                        "content"=>$text." "."@".$someone,
                    ],
                    "at"=>[
                        "atMobiles"=>[$someone],
                        "isAtAll"=>$isAtAll
                    ],
                ];
            }
        }else{
            return [
                'msgtype'=>"text",
                "text"=>[
                    "content"=>$text,
                ]
            ];
        }
    }
}