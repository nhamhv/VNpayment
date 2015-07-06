<?php
namespace Nham\Payment\BaoKim;

/**
 *
 *        Phiên bản: 1.1
 *        Tên lớp: BaoKimPayment
 *        Chức năng: Tích hợp thanh toán qua baokim.vn cho các merchant site có đăng ký API
 *                        - Xây dựng URL chuyển thông tin tới baokim.vn để xử lý việc thanh toán cho merchant site.
 *                        - Xác thực tính chính xác của thông tin đơn hàng được gửi về từ baokim.vn.
 *
 */
use Config;

class BaoKimPayment
{
    /**
     * Cấu hình phương thức thanh toán với các tham số
     * E-mail Bảo Kim - E-mail tài khoản bạn đăng ký với BaoKim.vn.
     * Merchant ID - là “mã website” được Baokim cấp khi bạn đăng ký tích hợp.
     * Mã bảo mật - là “mật khẩu” được Baokim cấp khi bạn đăng ký tích hợp
     * Vd : 12f31c74fgd002b1
     * Server Bảo Kim
     * Trang ​Kiểm thử - server để test thử phương thức thanh. .toán
     * Trang thực tế - Server thực tế thực hiện thanh toán.
     * https://www.baokim.vn/payment/order/version11' => ('Trang thực tế'),
     * http://kiemthu.baokim.vn/payment/order/version11' => ('Trang kiểm thử')
     * Chọn Save configuration để áp dụng thay đổi
     * Hàm xây dựng url chuyển đến BaoKim.vn thực hiện thanh toán, trong đó có tham số mã hóa (còn gọi là public key)
     * @param array $data
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
    public function createRequestUrl($data)
    {

        $total_amount = str_replace('.', '', $data['total_amount']);
        $base_url = url();
        $currency = 'VND'; // USD
        // Mảng các tham số chuyển tới baokim.vn
        $params = array(
            'merchant_id'       => strval(Config::get('payment::BaoKim.MERCHANT_ID')),
            'order_id'          => strval($data['order_id']),
            'business'          => strval(Config::get('payment::BaoKim.EMAIL_BUSINESS')),
            'total_amount'      => strval($total_amount),
            'shipping_fee'      => strval('0'),
            'tax_fee'           => strval('0'),
            'order_description' => strval('Thanh toán đơn hàng từ Website ' . $base_url . ' với mã đơn hàng ' . $data['order_id']),
            'url_success'       => strtolower($data['url_success']),
            'url_cancel'        => strtolower($data['url_cancel']),
            'url_detail'        => strtolower(''),
            'payer_name'        => strval($data['payer_name']),
            'payer_email'       => strval($data['payer_email']),
            'payer_phone_no'    => strval($data['payer_phone_no']),
            'shipping_address'  => strval($data['address']),
            'currency'          => strval($currency),

        );
        ksort($params);

        $params['checksum'] = hash_hmac('SHA1', implode('', $params), Config::get('payment::BaoKim.SECURE_PASS'));

        //Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào
        $bkUrl = Config::get('payment::SANDBOX') ? Config::get('payment::BaoKim.BAOKIM_URL_SANDBOX') : Config::get('payment::BaoKim.BAOKIM_URL');
        $redirect_url = $bkUrl . Config::get('payment::BaoKim.BAOKIM_API_PAYMENT');
        if (strpos($redirect_url, '?') === false) {
            $redirect_url .= '?';
        } else if (substr($redirect_url, strlen($redirect_url) - 1, 1) != '?' && strpos($redirect_url, '&') === false) {
            // Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
            $redirect_url .= '&';
        }

        // Tạo đoạn url chứa tham số
        $url_params = '';
        foreach ($params as $key => $value) {
            if ($url_params == '')
                $url_params .= $key . '=' . urlencode($value);
            else
                $url_params .= '&' . $key . '=' . urlencode($value);
        }
        return $redirect_url . $url_params;
    }

    /**
     * Hàm thực hiện xác minh tính chính xác thông tin trả về từ BaoKim.vn
     * @param array $url_params chứa tham số trả về trên url
     * @return true nếu thông tin là chính xác, false nếu thông tin không chính xác
     */
    public function verifyResponseUrl($url_params = array())
    {
        if (empty($url_params['checksum'])) {
            echo "invalid parameters: checksum is missing";
            return false;
        }

        $checksum = $url_params['checksum'];
        unset($url_params['checksum']);

        ksort($url_params);

        if (strcasecmp($checksum, hash_hmac('SHA1', implode('', $url_params), Config::get('payment::BaoKim.SECURE_PASS'))) === 0)
            return true;
        else
            return false;
    }
}