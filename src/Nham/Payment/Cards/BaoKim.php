<?php
/**
 * Created by Hoang Nham
 * Email: hoangnham01@gmail.com
 * Date: 28/05/2015
 */

namespace Nham\Payment\Cards;
use Config;

class BaoKim
{

    private $CORE_API_HTTP_USR;
    private $CORE_API_HTTP_PWD;
    private $MERCHANT_ID;
    private $API_USERNAME;
    private $API_PASSWORD;
    private $SECURE_CODE;

    public function __construct()
    {
        $this->CORE_API_HTTP_USR = Config::get('payment::CARDS.BaoKim.CORE_API_HTTP_USR');
        $this->CORE_API_HTTP_PWD = Config::get('payment::CARDS.BaoKim.CORE_API_HTTP_PWD');
        $this->MERCHANT_ID = Config::get('payment::CARDS.BaoKim.MERCHANT_ID');
        $this->API_USERNAME = Config::get('payment::CARDS.BaoKim.API_USERNAME');
        $this->API_PASSWORD = Config::get('payment::CARDS.BaoKim.API_PASSWORD');
        $this->SECURE_CODE = Config::get('payment::CARDS.BaoKim.SECURE_CODE');
    }


    /**
     * Thông Tin thẻ nạp
     *
     * @param      $cardSerial
     * @param      $cardCode
     * @param      $cardType
     * @param null $transactionId
     * @return array
     */
    public function cardPay($cardSerial, $cardCode, $cardType, $transactionId = null)
    {

        $transactionId = empty($transactionId) ? time() : $transactionId;
        $arrayPost = array(
            'merchant_id'    => $this->MERCHANT_ID,
            'api_username'   => $this->API_USERNAME,
            'api_password'   => $this->API_PASSWORD,
            'transaction_id' => $transactionId,
            'card_id'        => $cardType,
            'pin_field'      => $cardCode,
            'seri_field'     => $cardSerial,
            'algo_mode'      => 'hmac'
        );

        ksort($arrayPost);

        $data_sign = hash_hmac('SHA1', implode('', $arrayPost), $this->SECURE_CODE);

        $arrayPost['data_sign'] = $data_sign;

        $curl = curl_init(Config::get('payment::CARDS.BaoKim.BAOKIM_URL'));

        curl_setopt_array($curl, array(
            CURLOPT_POST           => true,
            CURLOPT_HEADER         => false,
            CURLINFO_HEADER_OUT    => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST | CURLAUTH_BASIC,
            CURLOPT_USERPWD        => $this->CORE_API_HTTP_USR . ':' . $this->CORE_API_HTTP_PWD,
            CURLOPT_POSTFIELDS     => http_build_query($arrayPost)
        ));
        $data = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $result = json_decode($data, true);
        if ($status == 200) {
            $amount = $result['amount'];
            return array(
                'success' => true,
                'card_amount'=>$result['amount'],
                'msg' => 'Bạn đã thanh toán thành công thẻ '.$cardType.' mệnh giá '.$amount
            );
        } else {
            return array(
                'success' => false,
                'msg' => $result['errorMessage'],
                'status' => $status
            );
        }
    }
}

