<?php

namespace Nham\Payment\NganLuong;

include_once 'nusoap.php';
use nusoap_client;
use DOMDocument;
/**
 * Class NL_MicroCheckout
 *
 * Su dung khi tich hop thanh toan voi NganLuong.vn
 *
 * @version NL 2.0
 * @author <phuonglh@peacesoft.net>
 * @since 07/2011
 */
class MicroCheckout
{
    public $urlWS = '';
    public $merchantSiteCode = '';
    public $merchantPassword = '';
    private $tokenCode = '';

    function __construct($merchant_site_code, $merchant_password, $url_ws)
    {
        $this->merchantSiteCode = $merchant_site_code;
        $this->merchantPassword = $merchant_password;
        $this->urlWS = $url_ws;
    }

    public function setExpressCheckoutPayment($inputs)
    {
        $result = $this->_call('SetExpressCheckoutPayment', $inputs);
        if (trim($result) != '') {
            return $this->_convertXMLToArray($result, 'result');
        }
        return false;
    }

    public function setExpressCheckoutDeposit($inputs)
    {
        $result = $this->_call('SetExpressCheckoutDeposit', $inputs);
        if (trim($result) != '') {
            return $this->_convertXMLToArray($result, 'result');
        }
        return false;
    }

    public function getExpressCheckout($inputs)
    {
        $result = $this->_call('GetExpressCheckout', $inputs);
        //var_dump($result);
        if (trim($result) != '') {
            return $this->_convertXMLToArray($result, 'result');
        }
        return false;
    }

    public function checkReturnUrlAuto($receiver, $order_code, $amount, $currency_code, $token_code, $checksum)
    {
        return $this->checkReturnUrl($receiver, $order_code, $amount, $currency_code, $token_code, $checksum);
    }

    public function getTokenCode()
    {
        return $this->tokenCode;
    }

    public function checkReturnUrl($receiver, $order_code, $amount, $currency_code, $token_code, $checksum)
    {
        $this->tokenCode = $token_code;
        $md5 = $this->merchantSiteCode . $receiver . $order_code . $amount . $currency_code . $token_code . $this->merchantPassword;
        return (md5($md5) == $checksum);
    }

    private function _call($function_name, $inputs)
    {
        $params = array(
            'merchant_site_code' => $this->merchantSiteCode,
            'checksum'           => $this->_makeChecksum($inputs),
            'params'             => '<params>' . $this->_convertArrayToXML($inputs) . '</params>'
        );

        $client = new nusoap_client($this->urlWS, true);
        $result = $client->call($function_name, $params);
        //	echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
        //echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
        //echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

        return $result;
    }

    private function _getMapKeys()
    {
        return array(
            'receiver',
            'order_code',
            'amount',
            'currency_code',
            'tax_amount',
            'discount_amount',
            'fee_shipping',
            'request_confirm_shipping',
            'no_shipping',
            'return_url',
            'cancel_url',
            'language',
            'token'
        );
    }

    private function _makeChecksum($params)
    {
        $md5 = '';
        $keys = $this->_getMapKeys();
        foreach ($keys as $key) {
            $md5 .= strval(@$params[$key]);
        }
        $md5 .= $this->merchantPassword;
        return md5($md5);
    }

    private function _convertXMLToArray($xml, $tag = 'params')
    {
        $doc = new DOMDocument();
        $doc->loadXML($xml);
        $params = $doc->getElementsByTagName($tag);
        return $this->_getNodes($params->item(0));
    }

    private function _getNodes($parent_node)
    {
        $result = array();
        $nodes = $parent_node->childNodes;
        for ($i = 0; $i < $nodes->length; $i++) {
            $node = $nodes->item($i);
            if ($node->nodeType == 1) {
                if ($parent_node->getElementsByTagName($node->nodeName)->length == 1) {
                    if ($this->_hasChildNodes($node)) {
                        $result[$node->nodeName] = $this->_getNodes($node);
                    } else {
                        $result[$node->nodeName] = trim(html_entity_decode($node->nodeValue));
                    }
                } else {
                    if ($this->_hasChildNodes($node)) {
                        $result[] = $this->_getNodes($node);
                    } else {
                        $result[] = trim(html_entity_decode($node->nodeValue));
                    }
                }
            }
        }
        return $result;
    }

    private function _hasChildNodes($parent_node)
    {
        if ($parent_node->hasChildNodes()) {
            $nodes = $parent_node->childNodes;
            for ($i = 0; $i < $nodes->length; $i++) {
                if ($nodes->item($i)->nodeType == 1) {
                    return true;
                }
            }
        }
        return false;
    }

    private function _convertArrayToXML($array)
    {
        $result = "";
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                $result .= is_numeric($key) ? "<item>" : "<$key>";
                if (is_array($value)) {
                    $result .= $this->_convertArrayToXML($value);
                } else {
                    $result .= htmlspecialchars($value);
                }
                $result .= is_numeric($key) ? "</item>" : "</$key>";
            }
        }
        return $result;
    }
}

?>
