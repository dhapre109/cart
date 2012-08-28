<?php

class ControllerPaymentKlarna extends Controller {

    protected function index() {
        $this->load->model('checkout/order');
        $this->load->model('tool/image');
        $this->data = array_merge($this->data, $this->language->load('payment/klarna'));
        
        $orderInfo = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        
        $addressMatch = false;
        
        if ($orderInfo['payment_firstname'] == $orderInfo['shipping_firstname'] && $orderInfo['payment_lastname'] == $orderInfo['shipping_lastname'] && $orderInfo['payment_address_1'] == $orderInfo['shipping_address_1'] && $orderInfo['payment_address_2'] == $orderInfo['shipping_address_2'] && $orderInfo['payment_postcode'] == $orderInfo['shipping_postcode'] && $orderInfo['payment_city'] == $orderInfo['shipping_city'] && $orderInfo['payment_zone_id'] == $orderInfo['shipping_zone_id'] && $orderInfo['payment_zone_code'] == $orderInfo['shipping_zone_code'] && $orderInfo['payment_country_id'] == $orderInfo['shipping_country_id'] && $orderInfo['payment_country'] == $orderInfo['shipping_country'] && $orderInfo['payment_iso_code_3'] == $orderInfo['shipping_iso_code_3']) {
            $addressMatch = true;
        } else {
            $addressMatch = false;
        }
        
        if (empty($orderInfo['payment_company'])) {
            $this->data['is_company'] = false;
        } else {
            $this->data['is_company'] = true;
        }
        
        $this->data['company_id'] = $orderInfo['payment_company_id'];
        
        $this->data['address_match'] = $addressMatch;
        $this->data['country_code'] = $orderInfo['payment_iso_code_3'];
        $this->data['klarna_send'] = $this->url->link('payment/klarna/send');
        
        $this->data['klarna_nld_warning_banner'] = $this->model_tool_image->resize('data/klarna_nld_warning.jpg', 950, 118);
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/klarna.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/klarna.tpl';
        } else {
            $this->template = 'default/template/payment/klarna.tpl';
        }

        $this->render();
    }

    public function send() {
        $this->load->model('checkout/order');
        $this->load->model('checkout/coupon');
        
        $json = array();

        $orderInfo = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        
        if (!$orderInfo) {
            $this->response->setOutput(json_encode($json));
            return;
        }
        
        $discount = 0;
        
        if (isset($this->session->data['coupon'])) {
            $couponInfo = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);;
        } else {
            $couponInfo = false;
        }
        
        if ($couponInfo['type'] == 'F') {
            $couponInfo['discount'] = min($couponInfo['discount'], $subTotal);
        }
        
        if ($this->config->get('klarna_server') == 'live') {
            //$server = 'https://payment.klarna.com/';
            $server = 'https://payment-beta.klarna.com/';
        } elseif ($this->config->get('klarna_server') == 'beta') {
            $server = 'https://payment-beta.klarna.com/';
        }
        
        switch ($orderInfo['payment_iso_code_3']) {
            // Sweden
            case 'SWE':
                $country = 209;
                $language = 138;
                $encoding = 2;
                $currency = 0;
                break;
            // Finland
            case 'FIN':
                $country = 73;
                $language = 37;
                $encoding = 4;
                $currency = 2;
                break;
            // Denmark
            case 'DNK':
                $country = 59;
                $language = 27;
                $encoding = 5;
                $currency = 3;
                break;
            // Norway	
            case 'NOR':
                $country = 164;
                $language = 97;
                $encoding = 3;
                $currency = 1;
                break;
            // Germany	
            case 'DEU':
                $country = 81;
                $language = 28;
                $encoding = 6;
                $currency = 2;
                break;
            // Netherlands															
            case 'NLD':
                $country = 154;
                $language = 101;
                $encoding = 7;
                $currency = 2;
                break;
        }
        
        $address = array(
            'email' => $orderInfo['email'],
            'telno' => $orderInfo['telephone'],
            'cellno' => '',
            'fname' => $orderInfo['payment_firstname'],
            'lname' => $orderInfo['payment_lastname'],
            'company' => $orderInfo['payment_company'],
            'careof' => '',
            'street' => trim($orderInfo['payment_address_1'] . ' ' . $orderInfo['payment_address_2']),
            'house_number' => '',
            'house_extension' => '',
            'zip' => $orderInfo['payment_postcode'],
            'city' => $orderInfo['payment_city'],
            'country' => $country,
        );
        
        if ($orderInfo['payment_iso_code_3'] == 'DEU' || $orderInfo['payment_iso_code_3'] == 'NLD') {
            $address['house_number'] = $this->request->post['house_no'];
        }
        
        if ($orderInfo['payment_iso_code_3'] == 'NLD') {
            $address['house_extension'] = $this->request->post['house_ext'];
        }
        
        // Discounts
        $result = $this->db->query("SELECT (SELECT ABS(`value`) FROM `" . DB_PREFIX . "order_total` WHERE `code` = 'credit' AND `order_id` = " . (int) $orderInfo['order_id'] . ") AS `credit`, (SELECT ABS(SUM(`value`)) FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = " . (int) $orderInfo['order_id'] . " AND `value` < 0 AND `code` != 'credit') AS `discount`")->row;
        
        $totalDiscount = (int) $result['discount'];
        $totalCredit = (int) $result['credit'];
        
        $goodsList = array();
        
        foreach ($this->cart->getProducts() as $product) {
            $price = 0;
            
            $discount = min($product['total'], $totalDiscount);
            $totalDiscount -= $discount;
            $discount = $discount / $product['quantity'];
            
            $credit = min($product['total'], $totalCredit);
            $totalCredit -= $credit;
            $credit = $credit / $product['quantity'];
            
            $productTax = $this->tax->getTax($product['price'] - $discount, $product['tax_class_id']);
            
            $price = $product['price'] - $credit - $discount + $productTax;
            
            $goodsList[] = array(
                'qty' => (int) $product['quantity'],
                'goods' => array(
                    'artno' => $product['model'],
                    'title' => $product['name'],
                    'price' => (int) str_replace('.', '', $this->currency->format($price, '', '', false)),
                    'vat' => 0.0,
                    'discount' => 0.0,
                    'flags' => 32,
                )
            );
        }
        
        // Shipping
        if (!$couponInfo || $couponInfo['shipping'] != '1') {
            $price = $this->tax->calculate($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

            $goodsList[] = array(
                'qty' => 1,
                'goods' => array(
                    'artno' => $orderInfo['shipping_code'],
                    'title' => $orderInfo['shipping_method'],
                    'price' => (int) str_replace('.', '', $this->currency->format($price, '', '', false)),
                    'vat' => (double) (($price / $this->session->data['shipping_method']['cost'] - 1) * 100),
                    'discount' => 0,
                    'flags' => 8 + 32,
                )
            );
        }
        
        // Klarna Fee
        
        $result = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = " . (int) $orderInfo['order_id'] . " AND `code` = 'klarna_fee'")->row;
        
        if (isset($result['value'])) {
            
            $feeWithTax  = $this->tax->calculate($result['value'], $this->config->get('klarna_fee_tax_class_id'));
            
            $goodsList[] = array(
                'qty' => 1,
                'goods' => array(
                    'artNo' => '',
                    'title' => 'Klarna Invoice fee',
                    'price' => (int) str_replace('.', '', $this->currency->format($feeWithTax, '', '', false)),
                    'vat' => (double) (($feeWithTax / $result['value'] - 1) * 100),
                    'discount' => 0,
                    'flags' => 16 + 32,
                )
            );
        }

        $digest = '';
        
        foreach ($goodsList as $goods) {
            $digest .= $goods['goods']['title'] . ':';
        }
        
        $digest = base64_encode(pack('H*', hash('sha256', $digest . $this->config->get('klarna_secret'))));
        
        if (isset($this->request->post['pno'])) {
            $pno = $this->request->post['pno'];
        } elseif (!empty($orderInfo['payment_company_id'])) {
            $pno = $orderInfo['payment_company_id'];
        } else {
            $pno = '';
        }
        
        $transaction = array(
            '4.1',
            'API:OPENCART:' . VERSION,
            $pno,
            (int) $this->request->post['gender'],
            '',
            '', 
            (string) $this->session->data['order_id'], 
            '',
            $address, 
            $address, 
            //$orderInfo['ip'], 
            '109.239.111.4',
            0, 
            $currency, 
            $country,
            $language, 
            (int) $this->config->get('klarna_merchant'),
            $digest, 
            $encoding,
            -1, 
            $goodsList,
            $orderInfo['comment'],
            array('delay_adjust' => 1),
            array(),
            array(), // yearly_salary for customers in Denmark when Part Payment is used
            array(),
            array(),
            array(),
        );
        
        $xml  = "<methodCall>";
        $xml .= "  <methodName>add_invoice</methodName>";
        $xml .= '  <params>';
        
        foreach ($transaction as $parameter)  {
            $xml .= '    <param><value>' . $this->constructXmlrpc($parameter) . '</value></param>';
        }
        
        $xml .= "  </params>";
        $xml .= "</methodCall>";        

        $ch = curl_init($server);

        $headers = array(
            'Content-Type: text/xml',
            'Content-Length: ' . strlen($xml),
        );

        curl_setopt($ch, CURLOPT_URL, $server);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            $log = new Log('klarna.log');
            
            $log->write('HTTP Error. Code: ' . curl_errno($ch) . ' message: ' . curl_error($ch));
            $json['error'] = $this->language->get('error_network');
        } else {
            preg_match('/<member><name>faultString<\/name><value><string>(.+)<\/string><\/value><\/member>/', $response, $match);

            if (isset($match[1])) {
                die(htmlspecialchars($response));
                $json['error'] = utf8_encode($match[1]); 
            } else {
                $xml = simplexml_load_string($response);
                
                $invoiceNumber = (string) $xml->params->param->value->array->data->value[0]->string;
                $klarnaOrderStatus = (int) $xml->params->param->value->array->data->value[1]->int;

                if ($klarnaOrderStatus == 1) {
                    $orderStatus = $this->config->get('klarna_accepted_order_status_id');
                } elseif ($klarnaOrderStatus == 2) {
                    $orderStatus = $this->config->get('klarna_pending_order_status_id');
                } else {
                    $orderStatus = $this->config->get('config_order_status_id');
                }
                
                $this->model_checkout_order->confirm($this->session->data['order_id'], $orderStatus, "Klarna's Invoice ID: " . $invoiceNumber, 1);
                
                $json['redirect'] = $this->url->link('checkout/success');
            }
        }
        
        curl_close($ch);
        
        $this->response->setOutput(json_encode($json));
    }
    
    private function constructXmlrpc($data) {
        $type = gettype($data);

        switch ($type) {
            case 'boolean':
                if ($data == true) {
                    $value = 1;
                } else {
                    $value = false;
                }
                
                $xml = '<boolean>' . $value . '</boolean>';
                break;

                
            case 'integer':
                $xml = '<int>' . (int) $data . '</int>';
                break;
            
            case 'double':
                $xml = '<double>' . (double) $data . '</double>';
                break;
            
            case 'string':
                $xml = '<string>' . htmlspecialchars($data) . '</string>';
                break;
                
            case 'array':
                // is numeric ?
                if ($data === array_values($data)) {
                    $xml = '<array><data>';
                    
                    foreach ($data as $value) {
                        $xml .= '<value>' . $this->constructXmlrpc($value) . '</value>';
                    }
                    
                    $xml .= '</data></array>';
                    
                } else {
                    // array is associative
                    $xml = '<struct>';
                    
                    foreach ($data as $key => $value) {
                        $xml .= '<member>';
                        $xml .= '  <name>' . htmlspecialchars($key) . '</name>';
                        $xml .= '  <value>' . $this->constructXmlrpc($value) . '</value>';
                        $xml .= '</member>';
                    }
                    
                    $xml .= '</struct>';
                }
                
                break;
            
            default:
                $xml = '<nil/>';
                break;
        }
        
        return $xml;
    }

}