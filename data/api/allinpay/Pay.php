<?php
/**
 * Pay.php
 *
 * Part of AllinpaySDK.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Fackeronline <1077341744@qq.com>
 * @link      https://github.com/Fakeronline
 */

namespace Fakeronline\AllinpaySDK;

require_once 'Services/Request.php';
require_once 'Tools/Encrypt.php';

use Fakeronline\AllinpaySDK\Services\Request;
//use InvalidArgumentException;
use Fakeronline\AllinpaySDK\Tools\Encrypt;


final class Pay extends Request{

    const PAY_TYPE_PERSONAL = 0;    //个人网银支付
    const PAY_TYPE_ENTERPRISE = 4;  //企业网银支付
    const PAY_TYPE_WAP = 10;    //WAP支付
    const PAY_TYPE_CREDIT = 11; //信用卡支付
    const PAY_TYPE_QUICK = 12;  //快捷支付
    const PAY_TYPE_AUTH = 21;   //认证支付
    const PAY_TYPE_WILDCARD = 23;   //外卡支付

    const LANGUAGE_ZH_S = 1;  //简体中文
    const LANGUAGE_ZH_T = 2;    //繁体中文
    const LANGUAGE_EN = 3;  //英文

    const CHAR_UTF8 = 1;
    const CHAR_GBK = 2;
    const CHAR_GB2312 = 3;

    const TRADE_TYPE_GOODS = 'GOODS';   //实体物品交易
    const TRADE_TYPE_SERVICES = 'SERVICES'; //服务类交易

    const CURRENCY_RMB = 0;  //人民币
    const CURRENCY_DOLLARS = 840;    //美元
    const CURRENCY_HK = 344; //港币

    protected function properties(){
        return [
            'inputCharset', 'pickupUrl', 'receiveUrl', 'version', 'language', 'signType', 'merchantId', 'payerName', 'payerEmail', 'payerTelephone', 'payerIDCard', 'pid', 'orderNo', 'orderAmount', 'orderCurrency', 'orderDatetime', 'orderExpireDatetime', 'productName', 'productPrice', 'productNum', 'productId', 'productDesc', 'ext1', 'ext2', 'extTL', 'payType', 'issuerId', 'pan', 'tradeNature', 'signMsg'
        ];
    }



    public function __construct($url, $merchantId, $key){
        parent::__construct($url, $merchantId, $key);
        $this->charSet()->setSignType()->setCurrency();
    }

    public function setLanguage($language = 1){

        if($language === self::LANGUAGE_ZH_S || $language === self::LANGUAGE_ZH_T || $language === self::LANGUAGE_EN){

            $this->value['language'] = $language;

            return $this;

        }
        showMessage('暂不支持此语言!', '', 'html', 'error');
    }


    public function setCurrency($currencyType = 0, $tradeType = null){
        if($currencyType === self::CURRENCY_DOLLARS || $currencyType === self::CURRENCY_HK || $currencyType === self::CURRENCY_RMB){

            if($currencyType !== self::CURRENCY_RMB && is_null($tradeType)){

                showMessage('设置了非人民币的货币，就必须设置贸易类型!', '', 'html', 'error');

            }

            $this->value['orderCurrency'] = $currencyType;

            return $this;

        }

        showMessage('暂不支持此货币!', '', 'html', 'error');

    }

    public function charSet($charSet = 1){

        if(in_array($charSet, [self::CHAR_UTF8, self::CHAR_GBK, self::CHAR_GB2312])){

            $this->value['inputCharset'] = $charSet;

            return $this;

        }

        showMessage('暂不支持此字符集!', '', 'html', 'error');
    }

    public function setUrl($pikUpUrl = '', $receiveUrl = ''){
        if($pikUpUrl){

            $this->value['pickupUrl'] = $pikUpUrl;

        }
        if($receiveUrl){

            $this->value['receiveUrl'] = $receiveUrl;

        }

        return $this;
    }

    final public function parameter($orderNo, $orderAmount, $payType = 0){
        //$orderNo, $orderAmount, $payType = 0
		
        if(empty($orderNo) || empty($orderAmount)){

            showMessage('缺少重要参数!', '', 'html', 'error');

        }

        if(strlen($orderNo) > 50){

            showMessage('订单号长度不能超过50!', '', 'html', 'error');

        }

        if(round($orderAmount, 2) != $orderAmount){

            showMessage('金额不正确，仅支持到分!', '', 'html', 'error');

        }

        $this->value['orderDatetime'] = date('YmdHis', time());
        $this->value['orderNo'] = $orderNo;
        $this->value['orderAmount'] = $orderAmount * 100; //转为分

        $payTypeList = [
            self::PAY_TYPE_PERSONAL,
            self::PAY_TYPE_ENTERPRISE,
            self::PAY_TYPE_WAP,
            self::PAY_TYPE_CREDIT,
            self::PAY_TYPE_QUICK,
            self::PAY_TYPE_AUTH,
            self::PAY_TYPE_WILDCARD,

        ];

        $payType = empty($payType) ? self::PAY_TYPE_PERSONAL : $payType;

        if(!in_array($payType ,$payTypeList)){

            showMessage('暂不支持此支付方式!', '', 'html', 'error');

        }

        $this->value['payType'] = $payType;

        //$this->postData = $this->sort($this->properties, $this->value);
		foreach($this->properties as $v){
            if(isset($this->value[$v])){
                $this->postData[$v] = $this->value[$v];
            }
        }
        $this->postData['signMsg'] = Encrypt::MD5_sign($this->postData, $this->config['md5key']);

        return $this;

    }

}