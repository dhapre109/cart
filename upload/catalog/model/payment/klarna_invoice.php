<?php

class ModelPaymentKlarnaInvoice extends Model {

    public function getMethod($address, $total) {        
        $this->language->load('payment/klarna_invoice');
        
        $klarnaCountry = $this->config->get('klarna_invoice_country');
        $settings = $klarnaCountry[$address['iso_code_3']];
        
        $klarnaInvoiceStatus = $settings['status'] == '1';
        
        if ($settings['minimum'] > 0 && $settings['minimum'] > $total) {
            $klarnaInvoiceStatus = false;
        }
        
        $zoneCount = $this->db->query("SELECT COUNT(*) AS `count` FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int) $settings['geo_zone_id'] . "' AND `country_id` = '" . (int) $address['country_id'] . "' AND (`zone_id` = '" . (int) $address['zone_id'] . "' OR `zone_id` = 0)")->row['count'];
        
        if ($settings['geo_zone_id'] != 0 && $zoneCount == 0) {
            $klarnaInvoiceStatus = false;
        }
        
        // Maps countries to currencies
        $countries = array(
            'NOR' => 'NOK',
            'SWE' => 'SEK',
            'FIN' => 'EUR',
            'DNK' => 'DKK',
            'DEU' => 'EUR',
            'NLD' => 'EUR',
        );
        
        if(!isset($countries[$address['iso_code_3']]) || $countries[$address['iso_code_3']] != $this->currency->getCode()) {
            $klarnaInvoiceStatus = false;
        }        
        
        $method = array();
        
        if ($klarnaInvoiceStatus) {
            $iso3 = $this->db->query("SELECT `iso_code_3` FROM `" . DB_PREFIX . "country` WHERE `country_id` = " . (int) $this->session->data['payment_country_id'])->row['iso_code_3'];

            $countries = $this->config->get('klarna_fee_country');
            $country = $countries[$iso3];

            if ($country['status'] == 1 && $this->cart->getSubTotal() < $country['total']) {
                $klarnaFee = $this->currency->format($this->tax->calculate($country['fee'], $country['tax_class_id']), '', '', false);
                $klarnaFeeText = $this->currency->format($this->tax->calculate($country['fee'], $country['tax_class_id']), '', '');
            } else {
                $klarnaFee = 0;
                $klarnaFeeText = '';
            }
            
            $method = array(
                'code' => 'klarna_invoice',
                'title' => sprintf($this->language->get('text_title'), $klarnaFeeText, $settings['merchant'], strtolower($address['iso_code_2']), $klarnaFee),
                'sort_order' => $settings['sort_order'],
            );
        }
        
        return $method;
    }
}
