<?php
/**
 * Created by PhpStorm.
 * User: nham
 * Email: hoangnham01@gmail.com
 * Date: 02/07/2015
 * Time: 4:21 CH
 */
namespace Nham\Payment;

use Config;
use Nham\Payment\BaoKim\BaoKimPayment;
use Nham\Payment\Cards\BaoKim;
use Nham\Payment\Cards\NganLuong;
use Nham\Payment\NganLuong\MicroCheckout;

class Payment
{

    public function payBaoKim($data)
    {
        $pay = new BaoKimPayment();
        return $pay->createRequestUrl($data);
    }

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

    public function cardBaoKim($data)
    {
        $card = new BaoKim();
        return $card->cardPay($data['cardSerial'], $data['cardCode'], $data['cardType'], $data['transactionId']);
    }

    public function cardNganLuong($data)
    {
        $card = new NganLuong();
        $result = $card->CardPay($data['card_code'], $data['card_serial'], $data['type_card'], $data['transaction_id'], $data['client_full_name'], $data['client_mobile'], $data['client_email']);
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


    public function cardBaoKim1($setting = array(), $data)
    {
        $card = new BKCard($setting['BKC_CORE_API_HTTP_USR'], $setting['BKC_CORE_API_HTTP_PWD'], $setting['BKC_MERCHANT_ID'], $setting['BKC_API_USERNAME'], $setting['BKC_API_PASSWORD'], $setting['BKC_SECURE_CODE']);
        return $card->cardPay($data['card_serial'], $card['card_code'], $data['type_card'], $this->bkURL, $data['transaction_id']);

    }
}