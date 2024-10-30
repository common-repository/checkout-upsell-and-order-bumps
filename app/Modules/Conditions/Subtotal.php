<?php
/**
 * UpsellWP
 *
 * @package   checkout-upsell-woocommerce
 * @author    Anantharaj B <anantharaj@flycart.org>
 * @copyright 2024 UpsellWP
 * @license   GPL-3.0-or-later
 * @link      https://upsellwp.com
 */

namespace CUW\App\Modules\Conditions;

defined('ABSPATH') || exit;

class Subtotal extends Base
{
    /**
     * To check condition.
     *
     * @return bool
     */
    public function check($condition, $data)
    {
        if (!isset($condition['value']) || !isset($condition['operator']) || !isset($data['subtotal'])) {
            return false;
        }
        $subtotal = $data['type'] == 'cart' && isset($data['subtotal_display']) ? $data['subtotal_display'] : $data['subtotal'];
        return self::checkValues($subtotal, $condition['value'], $condition['operator']);
    }

    /**
     * To get template.
     *
     * @return string
     */
    public function template($data = [], $print = false)
    {
        return self::app()->view('Admin/Campaign/Conditions/Total', $data, $print);
    }
}