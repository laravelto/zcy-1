<?php
/**
 * Refund.php
 *
 * Part of allinpay.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Fackeronline <1077341744@qq.com>
 * @link      https://github.com/Fakeronline
 */

namespace Fakeronline\AllinpaySDK;

use Fakeronline\AllinpaySDK\Services\Response;
use Fakeronline\AllinpaySDK\Utils\Arr;

final class Refund extends Response{

    protected function properties(){
        return [
            'merchantId', 'version', 'signType', 'orderNo', 'orderAmount', 'orderDatetime', 'refundAmount', 'refundDatetime', 'refundResult', 'errorCode', 'returnDatetime', 'signMsg'
        ];
    }

}
 

