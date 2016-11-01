<?php
    /*
     *      El software de Intermoneda.Com es una aplicación web para la compra 
     *               y venta de Bitcoins y otras criptomonedas
     *
     *                        Copyright (C) 2016 INTERMONEDA
     *
     *       This program is Copyright software: you can't redistribute it and/or
     *     modify it under the terms of the Copyright (C) License
     *     as published by the Federal Daily from México, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      
     * 
     */
// class mpago
//    {
//
//        public function __construct()
//        {
////        }
////
//        function button($amount = '0.00', $description = '', $itemnumber = '101', $extra_array = null) {
//			if(osc_get_preference('mpago_standard', 'payment')==1) {
//                MPago::standardButton($amount, $description, $itemnumber, $extra_array);
//            } else {
//                MPago::dgButton($amount, $description, $itemnumber, $extra_array);
//           }
//        }
//		function standardButton($amount = '0.00', $description = '', $itemnumber = '101', $extra_array = null) {	
//            $extra = payment_prepare_custom($extra_array);
//            $r = rand(0,1000);
//            $extra .= 'random,'.$r;


            //$apcs = self::customToAPC($extra); dio problemas
        define('EMAIL_ADD', $acc['a_field_1']); // For system notification.
        define('PAYPAL_EMAIL_ADD', $acc['a_field_1']);
                                                
            $m_shop = $acc['a_field_1'];
            $orderid = $get['id'];
            //$m_amount = number_format($amount, 2, '.', '');
            //$m_curr = $currency;
            $desc = 'Compra de divisas '.$amount.' '.$currency;
            //$m_desc = base64_encode($desc);
            $m_key = $acc['a_field_2'];
            $RETURNURL = $settings[url].'payment.php?b=check&c=MercadoPago&d=results';

                    $data = array(
                // Required
                "item_title" => $desc,
                "item_quantity" => "1",
                "item_unit_price" => $amount,
                "item_currency_id" => $currency, 
                // Optional
                "item_id" => $orderid,
                "item_description" => $desc,
                "item_picture_url" => "",
                "external_reference" => "",
                "payer_name" => "",
                "payer_surname" => "",
                "payer_email" => $m_shop,
                "back_url_success" => $RETURNURL,
                "back_url_pending" => ""

            );

             $md5String = '8800231436497281'.
                   'NpF8Z3N3HLsfpdDzc0Ykxg6svYRPUVJG'.
                    $data["item_quantity"]. // item_quantity
                    $data["item_currency_id"].// osc_get_preference('currency', 'payment'). // item_currency_id
                    $data["item_unit_price"].//$amount. // item_unit_price
                    $data["item_id"]. // item_id
                    $data["external_reference"]; // external_reference
            // Get md5 hash
            $md5 = md5($md5String);
            //md5('client_id'.'client_secret'.'item_quantity'.'item_currency_id'.'item_unit_price'.'item_id'.'external_reference');


//}

       function processPayment() {
            return MPago::processStandardPayment();
        }


        function processStandardPayment() {
            if (Params::getParam('payment_status') == 'Completed' || Params::getParam('st') == 'Completed') {
                // Have we processed the payment already?
                $tx = Params::getParam('tx')==''?Params::getParam('tx'):Params::getParam('txn_id');
                $payment = ModelPayment::newInstance()->getPayment($tx);
                if (!$payment) {
                    if(Params::getParam('custom')!='') {
                        $custom = Params::getParam('custom');
                    } else if(Params::getParam('cm')!='') {
                        $custom = Params::getParam('cm');
                    } else if(Params::getParam('extra')!='') {
                        $custom = Params::getParam('extra');
                    }
                    $data = payment_get_custom($custom);
                    $product_type = explode('x', Params::getParam('item_number'));
                    // SAVE TRANSACTION LOG
                    $payment_id = ModelPayment::newInstance()->saveLog(
                                                                Params::getParam('item_name'), //concept
                                                                $tx,
                                                                Params::getParam('mc_gross')!=''?Params::getParam('mc_gross'):Params::getParam('payment_gross'), //amount
                                                                Params::getParam('mc_currency'), //currency
                                                                Params::getParam('payer_email')!=''?Params::getParam('payer_email'):'', // payer's email
                                                                $data['user'], //user
                                                                $data['itemid'], //item
                                                                $product_type[0], //product type
                                                                'MPAGO'); //source
                    if ($product_type[0] == '101') {
                        ModelPayment::newInstance()->payPublishFee($product_type[2], $payment_id);
                    } else if ($product_type[0] == '201') {
                        ModelPayment::newInstance()->payPremiumFee($product_type[2], $payment_id);
                    } else {
                        ModelPayment::newInstance()->addWallet($data['user'], Params::getParam('mc_gross')!=''?Params::getParam('mc_gross'):Params::getParam('payment_gross'));
                    }
                    return PAYMENT_COMPLETED;
                }
                return PAYMENT_ALREADY_PAID;
            }
            return PAYMENT_PENDING;
        }

         //Makes an API call using an NVP String and an Endpoint
        function httpPost($my_endpoint, $my_api_str) {
            // setting the curl parameters.
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $my_endpoint);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            // turning off the server and peer verification(TrustManager Concept).
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            // setting the NVP $my_api_str as POST FIELD to curl
            curl_setopt($ch, CURLOPT_POSTFIELDS, $my_api_str);
            // getting response from server
            $httpResponse = curl_exec($ch);
            if (!$httpResponse) {
                $response = "$API_method failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')';
                return $response;
            }
            $httpResponseAr = explode("&", $httpResponse);
            $httpParsedResponseAr = array();
            foreach ($httpResponseAr as $i => $value) {
                $tmpAr = explode("=", $value);
                if (sizeof($tmpAr) > 1) {
                    $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
                }
            }

            if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
                $response = "Invalid HTTP Response for POST request($my_api_str) to $API_Endpoint.";
                return $response;
            }

            return $httpParsedResponseAr;
        }
//}}
?>