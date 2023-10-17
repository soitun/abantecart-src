<?php
/*------------------------------------------------------------------------------
  $Id$

  AbanteCart, Ideal OpenSource Ecommerce Solution
  http://www.AbanteCart.com

  Copyright © 2011-2020 Belavier Commerce LLC

  This source file is subject to Open Software License (OSL 3.0)
  License details is bundled with this package in the file LICENSE.txt.
  It is also available at this URL:
  <http://www.opensource.org/licenses/OSL-3.0>

 UPGRADE NOTE:
   Do not edit or add to this file if you wish to upgrade AbanteCart to newer
   versions in the future. If you wish to customize AbanteCart for your
   needs please refer to http://www.AbanteCart.com for more information.
------------------------------------------------------------------------------*/
if (!defined('DIR_CORE')) {
    header('Location: static_pages/');
}

class ModelTotalSubTotal extends Model
{
    public function getTotal(&$total_data, &$total, &$taxes, &$cust_data)
    {
        if ($this->config->get('sub_total_status')) {
            //create new instance of language for case when model called from admin-side
            $language = new ALanguage($this->registry, $this->language->getLanguageCode(), 0);
            $language->load($language->language_details['directory']);
            $language->load('total/sub_total');

            //currency based recalculation for all products to avoid fractional loss
            $converted_sum = 0;
            $subtotal = $taxAmount = $subTotalWithTax = 0;
            $products = $this->cart->getProducts() + $this->cart->getVirtualProducts();

            foreach ($products as $product) {
                $price = $product['price'] ?: $product['amount'];
                $subtotal += ($price * $product['quantity']);
                $subTotalWithTax += $product['quantity']
                    * round(
                        $this->tax->calculate(
                            $price,
                            $product['tax_class_id']),
                        $this->currency->getCurrency()['decimal_place']
                    );
                $taxAmount += $product['quantity']
                    *
                        $this->tax->calcTotalTaxAmount(
                            $price,
                            $product['tax_class_id']
                    );
            }
            if($this->config->get('config_tax')) {
                $subtotal = max(($subTotalWithTax - $taxAmount), $subtotal);
            }
            $converted_sum = $this->currency->format_number($subtotal);

            //currency display value
            $converted_sum_txt = $this->currency->format(max(0, $converted_sum), '', 1);
            $total_data[] = [
                'id'         => 'subtotal',
                'title'      => $language->get('text_sub_total'),
                'text'       => $converted_sum_txt,
                'converted'  => $converted_sum,
                'value'      => $subtotal,
                'sort_order' => $this->config->get('sub_total_sort_order'),
                'total_type' => $this->config->get('sub_total_total_type'),
            ];
            $total += $this->cart->getSubTotal();
        }
    }
}
