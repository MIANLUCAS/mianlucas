<?php if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly ?>

<div id="<?php echo esc_attr($panel_id); ?>" class="panel woocommerce_options_panel acfw-generic-panel <?php echo isset($additional_classes) ? esc_attr($additional_classes) : ''; ?>">
    <h3><?php echo $title; ?></h3>
    <div class="options_group">

<?php foreach ($fields as $field):

    if (is_array($field['cb'])) {
        call_user_func_array($field['cb'], array($field['args']));
    } else {
        $field['cb']($field['args']);
    }

endforeach;?>

    </div>

</div><!--#url_coupon_data-->
