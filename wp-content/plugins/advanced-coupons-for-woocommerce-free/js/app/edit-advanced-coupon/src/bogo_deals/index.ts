import { remove_leading_zeros } from "../helper";
import { toggle_editing_mode, toggle_block_data_type } from "./toggles";
import {
  save_bogo_deals,
  clear_bogo_deals,
  update_combined_products_list,
  save_on_coupon_publish,
} from "./save";
import {
  trigger_add_table_row_form,
  trigger_edit_table_row_form,
  cancel_add_edit_table_row,
  add_edit_table_row,
  product_table_remove_product,
} from "./table";

declare var jQuery: any;
declare var acfw_edit_coupon: any;

const $: any = jQuery;
const module_block: HTMLElement = document.querySelector("#acfw_bogo_deals");

/**
 * Export functions to be accessed by ACFWP.
 */
acfw_edit_coupon = {
  ...acfw_edit_coupon,
  bogo_toggle_editing_mode: toggle_editing_mode,
};

/**
 * Add free products module events script.
 *
 * @since 1.0.0
 */
export default function bogo_deals_module_events(): void {
  $(module_block).on(
    "click",
    ".acfw-styled-table tfoot .add-table-row",
    trigger_add_table_row_form
  );
  $(module_block).on(
    "click",
    ".acfw-styled-table td.actions a.edit",
    trigger_edit_table_row_form
  );
  $(module_block).on(
    "click",
    "tr.add-edit-form .actions .cancel",
    cancel_add_edit_table_row
  );
  $(module_block).on(
    "click",
    "tr.add-edit-form .actions .condition-product-add",
    add_edit_table_row
  );
  $(module_block).on(
    "click",
    ".acfw-styled-table td.actions a.remove",
    product_table_remove_product
  );
  $(module_block).on("click", "button#save-bogo-deals", save_bogo_deals);
  $(module_block).on("click", "button#clear-bogo-deals", clear_bogo_deals);
  $(module_block).on(
    "change",
    "select#bogo-condition-type,select#bogo-deals-type",
    toggle_block_data_type
  );
  $(module_block).on(
    "change",
    ".combined-products-form select,.combined-products-form input",
    update_combined_products_list
  );
  $(module_block).on("change", ".condition-quantity", restrict_quantity_value);
  $(module_block).on("blur", ".wc_input_price", remove_leading_zeros);
  $(module_block).css("min-height", $("#coupon_options").height());
  $(module_block).on("change", "input[name='bogo_type']", trigger_toggle);
  $(module_block).on(
    "click",
    ".acfw-styled-table .actions .remove",
    trigger_toggle
  );
  $(module_block)
    .find(".notice-option")
    .on("change", "input,textarea,select", trigger_toggle);
  $(module_block)
    .find("select#bogo-condition-type,select#bogo-deals-type")
    .trigger("change");
  $("form#post").on("submit", save_on_coupon_publish);
}

/**
 * Trigger toggle editing mode.
 *
 * @since 1.0.0
 */
function trigger_toggle(): void {
  toggle_editing_mode(true);
}

/**
 * Restrict condition quantity value.
 *
 * @since 1.0.0
 */
function restrict_quantity_value() {
  const $field: JQuery = jQuery(this);

  if ($field.val() < 1) $field.val(1);
}
