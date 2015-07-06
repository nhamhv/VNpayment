<?php
return array(

    'SANDBOX'   => false,
    'NganLuong' => array(
        'MERCHANT_ID'    => '32925',
        'MERCHANT_PASS'  => 'hoangnham92',
        'RECEIVER'       => 'hoangnham01@gmai.com',
        'URL_WS_SANDBOX' => 'http://beta.nganluong.vn/micro_checkout_api.php?wsdl',
        'URL_WS'         => 'https://www.nganluong.vn/micro_checkout_api.php?wsdl'
    ),
    'BaoKim'    => array(
        //CẤU HÌNH TÀI KHOẢN (Configure account)
        'EMAIL_BUSINESS'     => 'hoangnham01@gmail.com',//Email Bảo kim
        'MERCHANT_ID'        => '18248',// Mã website tích hợp
        'SECURE_PASS'        => '62890a315059da89',
        //Mật khẩu website của khách hàng là : 62890a315059da89
        //Để đảm bảo cho tính bảo mật thông tin khách hàng, mật khẩu này chỉ hiển thị duy nhất một lần. Mong quý khách hay lưu mật khẩu này để sử dụng.
        // Cấu hình tài khoản tích hợp
        'API_USER'           => 'hoangnham01', //API USER,
        'API_PWD'            => 'o581JWR49U06rgXc5efw8L6q91hmT',//API PASSWORD
        'PRIVATE_KEY_BAOKIM' => '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCjlEG+vW0lz8CQ
uVt7GQfx7jWoCTF5V+hbscnTG8ntPRNd9GyDyB6suNLmxSW4/F8ZUkjIw95EEdnF
tZizGPp3G4v4geNtUV0oCrbBSJHCAbuzSOHdU9zeCFhbkpIlRhHoghYA2gR16Sk+
dCJxs6qICwcRJBV2PPQV3NstdgWAJGF3COlwJIJ6IYpm0/wpe8Z3f0g2kvFA8pY/
q8wBlwMP8LFRJkhEUYUcs7cgRWyWEfbx99fgxj+V717qQBrNuEzi9CLCEhcEb3xD
O95MKfd4XuAW/vuN7MD+NRqeYc/mICqz39OBe0wVhoxq7giqikadcI/O2IBxHssz
9klN8kwFAgMBAAECggEAdhZeC+NVuxkqS4/0EX51dNphU8gnNhEXBbLoHwWNlT0l
EQuVluDN/CSE4+FopQ1TXcVAE+cKOpukWY0KCii7D2cMeb7SvqUhGfn6CrhnuOVl
ajzwCMY0yPi/SIQcCtp1rSLT3ZOF6tUCWDqgbvKj5ggH9wDUmYHRN0Mz2rK7r8/r
ymZkKdS8bPzV+OMcAVT5QNjqd83efVGHJA0Fvl53zcMNFMCWYQKVoIn8Tl/6Gm5a
5MCTjNTxOTtfP/ZiI3ukFovPITrnnhTswpq7A57CCg8tBnkonGZMdkAe2m8yYbwp
dILqm7dx/9Font52ULgYQLfJ4A60MCk/Ing+t5AYIQKBgQDXsWJONq73tbgSuQNl
SCX+0T+GlXB6haFJfJ1iGmA2r9wP06qgjB9Wle7KCiFLPhSkCpKiJm5Ha9MyvIuo
VViXWV3RLOwTDfk9on6altWTFGPiHoVLmYLe01yfqeoZYDwCFcK2pWgSmHjZB5LA
MpzvaWfZAZ4oUs0ZAmExukVKSQKBgQDCJcgeelv5Quzc9U32ganJt6ZhN7n4ZmWu
PPAzIuQQKyVBGB11rkRb+QkW4Q7CdaUQLiLitGTvEVGrJr4uVegusFX/9RiiiEOM
v8TN2AxCiO5XN1hEvPOI5MZ/vDsVa+u/0Ex8RLqhuYIqMMuzexyqWYmYdoeYhVvH
1BKhvK/T3QKBgFEj68VckOmitMJmUz6wq4p2kR1B7nRI/Om2NUaIgZVRBsn1aITI
8akS8ieTM/8oNX+Ycp0JNAcuKt81SpxirtcQyVg9O/nXEeH71QC2qVWRIBoaPS65
ayAEBx4RP32YVDq8kOlAxCvqq9uJG82tvJfb4TMjhqnIrFsyJ/obBqHxAoGAIiXR
EANHgA0Uajy/LLdbrL4fhoPtstIX2lCfku71HB7qm4tpSxSOs3qW7a5CEVPt522l
9yfxhNcP/UGAO9giUWv0hGOQpK3A69WjAO2aIm3BNhfm75goYZCTlU3/OmJUAdXa
ThUsmlttmIwil/v0a8X53JYkfWAfZ4NWj5oHdwUCgYEAjx9RC73lj2bjJJecd7rs
+KJKSG1zY8HHVLeAqn4uXNH2+a8gBCf5d8VMbqSwT6OSE5WSZXm5B8De5/HBmLQA
V1TQ92qRD2FLQBtje0auCq+fZB2wTNjiinWWwrcxuQqtu1m+B+MmjbjdyayIXvEe
Ib9A/5wnM6/kR6QnBKF7BQ0=
-----END PRIVATE KEY-----
',
        'BAOKIM_URL_SANDBOX' => 'http://kiemthu.baokim.vn',
        'BAOKIM_URL'         => 'https://www.baokim.vn',
        'BAOKIM_API_PAYMENT' => '/payment/order/version11',
    ),
    'CARDS'     => array(
        'NganLuong' => array(
            'MERCHANT_ID'         => '32925',
            'MERCHANT_PASSWORD'   => 'hoangnham92',
            'EMAIL_RECEIVE_MONEY' => 'hoangnham01@gmail.com'
        ),
        'BaoKim'    => array(
            'BAOKIM_URL'        => 'https://www.baokim.vn/the-cao/restFul/send',
            'CORE_API_HTTP_USR' => 'merchant_18248',
            'CORE_API_HTTP_PWD' => '18248v6Wx32COKylkwz1GBYFq1N38Z19tRT',
            'MERCHANT_ID'       => '18248',
            'API_USERNAME'      => 'hoangnhamnamevn',
            'API_PASSWORD'      => 'hoangnhamnamevnaha234sabg',
            'SECURE_CODE'       => '62890a315059da89'
        )
    )
);



