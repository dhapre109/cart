<?php

class ModelPaymentKlarnaInvoice extends Model {

    public function getMethod($address, $total) {        
        $this->language->load('payment/klarna_invoice');
        
        $klarnaCountry = $this->config->get('klarna_invoice_country');
        $settings = $klarnaCountry[$address['iso_code_3']];
        
        $klarnaInvoiceStatus = $settings['status'] == '1';
        
        $zoneCount = $this->db->query("SELECT COUNT(*) AS `count` FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int) $settings['geo_zone_id'] . "' AND `country_id` = '" . (int) $address['country_id'] . "' AND (`zone_id` = '" . (int) $address['zone_id'] . "' OR `zone_id` = 0)")->row['count'];
        
        $minimumTotal = (double) $this->config->get('klarna_invoice_minimum_amount');
        
        if ($minimumTotal > 0 && $minimumTotal > $total) {
            $klarnaInvoiceStatus = false;
        }
        
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

            $method = array(
                'code' => 'klarna_invoice',
                'title' => $this->language->get('text_title'),
                'sort_order' => $settings['sort_order'],
            );
        }
        
        return $method;
    }
}
