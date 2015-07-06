<?php

namespace Nham\Payment;

use Config;
use Nham\Payment\BaoKim\BaoKimPayment;
use Nham\Payment\Cards\BaoKim;
use Nham\Payment\Cards\NganLuong;
use Nham\Payment\NganLuong\MicroCheckout;

class Payment
{

    /**
     * * Hàm xây dựng url chuyển đến BaoKim.vn thực hiện thanh toán, trong đó có tham số mã hóa (còn gọi là public key)
     * @param string $orderCode Mã đơn hàng
     * @param string $totalAmount Giá trị đơn hàng
     * @param string $orderDescription Mô tả đơn hàng
     * @param string $urlSuccess Url trả về khi thanh toán thành công
     * @param string $urlCancel Url trả về khi hủy thanh toán
     * @param string $payerName
     * @param string $payerEmail
     * @param string $payerPhoneNo
     * @param string $address
     *       $order_id                Mã đơn hàng
     *       $business            Email tài khoản người bán
     *       $total_amount            Giá trị đơn hàng
     *       $shipping_fee            Phí vận chuyển
     *       $tax_fee                Thuế
     *       $order_description    Mô tả đơn hàng
     *       $url_success            Url trả về khi thanh toán thành công
     *       $url_cancel            Url trả về khi hủy thanh toán
     *       $url_detail            Url chi tiết đơn hàng
     *       null $payer_name
     *       null $payer_email
     *       null $payer_phone_no
     *       null $shipping_address
     * @return string url cần tạo
     */
    public function payBaoKim($orderCode, $totalAmount, $orderDescription, $urlSuccess, $urlCancel, $payerName, $payerEmail, $payerPhoneNo, $address)
    {
        $pay = new BaoKimPayment();
        return $pay->createRequestUrl($orderCode, $totalAmount, $orderDescription, $urlSuccess, $urlCancel, $payerName, $payerEmail, $payerPhoneNo, $address);
    }

    /**
     * Hàm thực hiện xác minh tính chính xác thông tin trả về từ BaoKim.vn
     * @param array $urlParams chứa tham số trả về trên url
     * @return true nếu thông tin là chính xác, false nếu thông tin không chính xác
     */
    public function verifyBaoKimResponseUrl($urlParams)
    {
        $pay = new BaoKimPayment();
        return $pay->verifyResponseUrl($urlParams);
    }

    /**
     * @param     $orderCode
     * @param     $amount
     * @param     $items
     * @param     $returnUrl
     * @param     $cancelUrl
     * @param int $taxAmount
     * @param int $feeShipping
     * @param int $discountAmount
     * @return array
     */
    public function payNganLuong($orderCode, $amount, $items, $returnUrl, $cancelUrl, $taxAmount = 0, $feeShipping = 0, $discountAmount = 0)
    {
        $inputs = array(
            'receiver'                 => Config::get('payment::NganLuong.RECEIVER'),
            'order_code'               => $orderCode,
            'amount'                   => $amount,
            'currency_code'            => 'vnd',
            'tax_amount'               => $taxAmount,
            'discount_amount'          => $discountAmount,
            'fee_shipping'             => $feeShipping,
            'request_confirm_shipping' => '0',
            'no_shipping'              => $feeShipping > 0 ? 0 : 1,
            'return_url'               => $returnUrl,
            'cancel_url'               => $cancelUrl,
            'language'                 => 'vi',
            'items'                    => $items
        );
        $URL_WS = Config::get('payment::SANDBOX') ? Config::get('payment::NganLuong.URL_WS_SANDBOX') : Config::get('payment::NganLuong.URL_WS');
        $obj = new MicroCheckout(Config::get('payment::NganLuong.MERCHANT_ID'), Config::get('payment::NganLuong.MERCHANT_PASS'), $URL_WS);
        $result = $obj->setExpressCheckoutPayment($inputs);
        if ($result != false) {
            if ($result['result_code'] == '00') {
                return array(
                    'success' => true,
                    'url'     => $result['link_checkout']
                );
            } else {
                return array(
                    'success' => false,
                    'msg'     => 'Mã lỗi ' . $result['result_code'] . ' (' . $result['result_description'] . ') '
                );
            }
        } else {
            return array(
                'success' => false,
                'msg'     => 'Lỗi kết nối tới cổng thanh toán Ngân Lượng'
            );
        }

    }

    /**
     * @param $urlParams
     * @return bool
     */
    public function verifyNganLuongResponseUrl($urlParams)
    {
        $URL_WS = Config::get('payment::SANDBOX') ? Config::get('payment::NganLuong.URL_WS_SANDBOX') : Config::get('payment::NganLuong.URL_WS');
        $obj = new MicroCheckout(Config::get('payment::NganLuong.MERCHANT_ID'), Config::get('payment::NganLuong.MERCHANT_PASS'), $URL_WS);
        $urlParams['receiver'] = empty($urlParams['receiver']) ? null : $urlParams['receiver'];
        $urlParams['order_code'] = empty($urlParams['order_code']) ? null : $urlParams['order_code'];
        $urlParams['amount'] = empty($urlParams['amount']) ? null : $urlParams['amount'];
        $urlParams['currency_code'] = empty($urlParams['currency_code']) ? null : $urlParams['currency_code'];
        $urlParams['token_code'] = empty($urlParams['token_code']) ? $urlParams['token_code'] : $urlParams['token_code'];
        $urlParams['checksum'] = empty($urlParams['checksum']) ? null : $urlParams['checksum'];

        return $obj->checkReturnUrlAuto($urlParams['receiver'], $urlParams['order_code'], $urlParams['amount'], $urlParams['currency_code'], $urlParams['token_code'], $urlParams['checksum']);
    }

    /**
     * @param $cardSerial
     * @param $cardCode
     * @param $cardType
     * @param $transactionId
     * @return array
     */
    public function cardBaoKim($cardSerial, $cardCode, $cardType, $transactionId)
    {
        $card = new BaoKim();
        return $card->cardPay($cardSerial, $cardCode, $cardType, $transactionId);
    }

    /**
     * @param $cardCode
     * @param $cardSerial
     * @param $typeCard
     * @param $transactionId
     * @param $clientFullName
     * @param $clientMobile
     * @param $clientEmail
     * @return array
     */
    public function cardNganLuong($cardCode, $cardSerial, $typeCard, $transactionId, $clientFullName, $clientMobile, $clientEmail)
    {
        $card = new NganLuong();
        $result = $card->CardPay($cardCode, $cardSerial, $typeCard, $transactionId, $clientFullName, $clientMobile, $clientEmail);
        if ($result->error_code == '00') {
            return array(
                'success'     => true,
                'card_amount' => $result->card_amount,
                'msg'         => $result->error_message,
            );
        }
        return array(
            'success' => false,
            'msg'     => $result->error_message,
        );
    }
}