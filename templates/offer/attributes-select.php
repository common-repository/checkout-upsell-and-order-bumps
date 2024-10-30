<?php
/**
 * Offer variant (variation product) attributes select
 *
 * This template can be overridden by copying it to yourtheme/checkout-upsell-woocommerce/offer/attributes-select.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer) will need to copy the new files
 * to your theme to maintain compatibility. We try to do this as little as possible, but it does happen.
 */

defined('ABSPATH') || exit;

if (empty($offer['product']['available_attributes']) || empty($offer['product']['variants'])) return;

$product_object = wc_get_product($offer['product']['id']);
$variations_json = function_exists('wc_esc_json') ? wc_esc_json(wp_json_encode($offer['product']['variants'])) : '[]';
?>

<div class="cuw-attributes-section">
    <select class="variant-select" name="variation_id" data-chosen_attributes="" style="display: none;">
        <option value=""
                data-regular_price="0"
                data-price="0"
                data-price_html="<?php echo esc_attr($offer['product']['price_html']); ?>"
                data-stock_qty="<?php echo esc_attr($offer['product']['stock_qty']); ?>"
                data-image="<?php echo empty($offer['product']['fixed_image']) ? esc_attr($offer['product']['image']) : ''; ?>">
        </option>
        <?php foreach ($offer['product']['variants'] as $variant) { ?>
            <option value="<?php echo esc_attr($variant['id']); ?>"
                    data-regular_price="<?php echo esc_attr($variant['regular_price']); ?>"
                    data-price="<?php echo esc_attr($variant['price']); ?>"
                    data-price_html="<?php echo esc_attr($variant['price_html']); ?>"
                    data-stock_qty="<?php echo esc_attr($variant['stock_qty']); ?>"
                    data-tax="<?php echo isset($variant['tax']) ? esc_attr($variant['tax']) : 0; ?>"
                    data-image="<?php echo empty($offer['product']['fixed_image']) ? esc_attr($variant['image']) : ''; ?>">
                <?php echo esc_html($variant['info']); ?>
            </option>
        <?php } ?>
    </select>

    <div class="cuw-attributes-select"
         data-product_variations="<?php echo $variations_json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
        <?php foreach ($offer['product']['available_attributes'] as $attribute => $options) {
            $name = !empty($args['attribute_select_name']) ? $args['attribute_select_name'] . '[attribute_' . sanitize_title($attribute) . ']' : 'attribute_' . sanitize_title($attribute);
            ?>
            <div class="attribute-select-wrapper">
                <label><?php echo wp_kses_post(wc_attribute_label($attribute)); ?></label>
                <select class="attribute-select" id="<?php echo esc_attr($attribute); ?>"
                        name="<?php echo esc_attr($name); ?>"
                        data-attribute_name="<?php echo 'attribute_' . esc_attr(sanitize_title($attribute)); ?>">
                    <option value=""><?php esc_html_e('Choose an option', 'woocommerce'); ?></option>
                    <?php if (taxonomy_exists($attribute) && function_exists('wc_get_product_terms')) {
                        $terms = wc_get_product_terms($product_object->get_id(), $attribute, ['fields' => 'all']);
                        foreach ($terms as $term) {
                            if (in_array($term->slug, $options, true)) {
                                echo '<option value="' . esc_attr($term->slug) . '">' . esc_html(apply_filters('woocommerce_variation_option_name', $term->name, $term, $attribute, $product_object)) . '</option>';
                            }
                        }
                    } else {
                        foreach ($options as $option) {
                            echo '<option value="' . esc_attr($option) . '">' . esc_html(apply_filters('woocommerce_variation_option_name', $option, null, $attribute, $product_object)) . '</option>';
                        }
                    } ?>
                </select>
            </div>
        <?php } ?>
        <a href="#" class="cuw-reset-attributes" style="display: none;">
            <?php esc_html_e('Clear', 'woocommerce'); ?>
        </a>
    </div>
</div>
