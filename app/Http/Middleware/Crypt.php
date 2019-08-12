<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\FrontendApi\FrontendApiMainController;

class Crypt
{
    //数据串间隔标志 前后统一
    private CONST LIMIT='aesrsastart';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     */
    public function handle($request, Closure $next)
    {
        $requestNum = count($request->request);
        //空参放行
        if(!$requestNum){
            return $next($request);
        }
        //检验参数是否符合规范 系统只允许接入一个名为DATA的参数
        if($requestNum!=1 || !isset($request['data'])){
            $con = new FrontendApiMainController();
            return $con->msgOut(false,[],100507);die;
        }
        $inData = $request->input('data');
        //带DATA数据却为null
        if(is_null($inData)){
            $con = new FrontendApiMainController();
            return $con->msgOut(false,[],100506);die;
        }
        //错误返回
        if(!is_string($inData)){
            $con = new FrontendApiMainController();
            return $con->msgOut(false,[],100500);die;
        }
        $requestCryptData = explode(self::LIMIT,$inData);
        if(count($requestCryptData)!=3){
            $con = new FrontendApiMainController();
            return  $con->msgOut(false,[],100501);die;
        }
        $data = $requestCryptData[0];//固定位 数组 自生成
        $iv =self::rsaDeCrypt( $requestCryptData[1] );
        if(!$iv){
            $con = new FrontendApiMainController();
            return $con->msgOut(false,[],100502);die;
        }
        $key =self::rsaDeCrypt( $requestCryptData[2] );
        if(!$key){
            $con = new FrontendApiMainController();
            return $con->msgOut(false,[],100503);die;
        }
        $deAesData = self::deAesCrypt($data,$key,$iv) ;
        if(!$deAesData){
            $con = new FrontendApiMainController();
            return $con->msgOut(false,[],100505);die;
        }
        $deData = json_decode( $deAesData );
        if(is_null($deData)){
            $con = new FrontendApiMainController();
            return $con->msgOut(false,[],100504);die;
        }
        foreach ($deData as $k=>$v){
            $request[$k]=$v;
        }
        unset($request['data']);
        return $next($request);
    }
    /**
     * RSA解密 自带私钥
     * @param  $rsaData rsa加密后的数据串
     * @return Sting/Bool 解密后的字符串或false
     */
    private function rsaDeCrypt($rsaData){
        //中间件还未生成缓存 所以将私钥配置在此 以减少系统开销
        $pkcs8_private="-----BEGIN PRIVATE KEY-----
MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAK3m6BabZZ2qQwjm
IOBOZ1q9g9OnqGapuinLs3182ew2LAQT62iLReBCNB64TRh/tU4iIIjx5bNRpNZ8
IrcP92YVNuxMrdSCqXpC5gpGFKf1CfG0SrO+TPmO/d1zexJq/yArc7HbYMFZRfks
7BjnaQGJ5rCVEVyS/y+0I5hU+t37AgMBAAECgYBHuq6QZJiNZ+Uujn2RB8Xpz7HI
Y6c6cVewVa07XXwlylJtfCnlfUzdK3GEGlDygfqut7Tjx6MPS+XJ8nn/OD661nRX
bHnEzgPEFPJmJ926NkZpz13Ox9n8I7o1LWwduWsxOebkSKi6w8fWXLCaEZx7stV8
xHvqZUAVlOkdJwcsAQJBANj6wviGzy/nvyOeFREtLlvC+KHjBVtqM+w3uiKzASyM
JjKYM7wjLAviX0+S9/F3h3d2HoGGxh8HZbzYi0FpZ0ECQQDNLPQPbMkYL/HkM3kC
3U2ebFKkx+qjp7cv28wd7tPmZ6mxHA8ihtANCt0F0J4F3fSpWLWXDs2Yw8oR+UjG
jZI7AkBui0s77QqvgGU8EzTufFNLAslDSPMYwMHVTgrx1Lr7ZCetzSdGabDuGRWv
59OUXO5SaYZfPTfA5TbrAHPqDnZBAkBJRUBzMboutRCBGhChAT7y0GRDDFGy1/YH
VUrzdZKeuW5UHV0aS2KJBdQge3uzRKxWvaM7qsGpSGIlQQzIO055AkBvkOcvyrkV
s+RmDzYuKUoG0zIjmIZidcaTP1p2ngqCl/RXl1evVAmXet26uDPkFtmOGvFTngZM
Web+LMihoBTa
-----END PRIVATE KEY-----";
          $flag = openssl_private_decrypt(base64_decode($rsaData), $deRsaCryptData,$pkcs8_private);
          if(!$flag)
            return false;
          return $deRsaCryptData;
    }
    /**
     * AES解密 加密方式 AES-128-CBC
     * @param  $enAes AES加密后的字符串
     * @param  $key AES加密时使用的key
     * @param  $iv AES加密时使用的偏移量
     * @return Sting/Bool 解密后的字符串或false
     */
    private function deAesCrypt($enAes,$key,$iv){
        $str =  openssl_decrypt(base64_decode($enAes),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv);
        return $str;
    }


}
