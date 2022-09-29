import combination_products_template from "./templates/combination_products";
import product_categories_template from "./templates/product_categories";
import specific_products_template from "./templates/specific_products";

declare var jQuery: any;
declare var acfw_edit_coupon: any;

const $: any = jQuery;
const module_block: HTMLElement = document.querySelector("#acfw_bogo_deals");

/**
 * Toggle editing mode.
 *
 * @since 1.0.0
 *
 * @param {toggle} bool True to toggle editing mode, false otherwise.
 */
export function toggle_editing_mode(toggle: boolean) {
  $(module_block).data("editing", toggle);
  $(module_block).find("#save-bogo-deals").prop("disabled", !toggle);
}

/**
 * Toggle condition type.
 *
 * @since 1.1.0
 */
export function toggle_block_data_type() {
  const $select: JQuery = $(this),
    block_type: string = $select.data("block"),
    condition_block: HTMLElement = module_block.querySelector(
      ".bogo-conditions-block"
    ),
    deals_block: HTMLElement = module_block.querySelector(
      ".bogo-product-deals-block"
    ),
    bogo_deals: any = $(module_block).data("bogo_deals"),
    conditions: any = bogo_deals.conditions ? bogo_deals.conditions : [],
    deals: any = bogo_deals.deals ? bogo_deals.deals : [],
    block_data: any = block_type == "conditions" ? conditions : deals,
    block_data_type: string =
      block_type == "conditions"
        ? bogo_deals.conditions_type
        : bogo_deals.deals_type;

  let isPremium = false,
    markup,
    tempBlock;

  switch ($select.val()) {
    case "combination-products":
      markup = combination_products_template(block_data, block_type == "deals");
      isPremium = true;
      break;

    case "product-categories":
      markup = product_categories_template(
        block_data,
        block_data_type,
        block_type == "deals"
      );
      isPremium = true;
      break;

    case "specific-products":
    default:
      markup = specific_products_template(
        block_data,
        block_data_type,
        block_type == "deals"
      );
      isPremium = false;
      break;
  }

  if (block_type == "conditions") {
    $(condition_block).html(markup);
    tempBlock = condition_block;
  } else {
    const $multipleDesc = $(module_block).find(".multiple-items-desc");

    if ("specific-products" !== $select.val()) $multipleDesc.show();
    else $multipleDesc.hide();

    $(deals_block).html(markup);
    tempBlock = deals_block;
  }

  if (isPremium && undefined !== acfw_edit_coupon.upsell)
    $(tempBlock).addClass("premium-only");
  else $(tempBlock).removeClass("premium-only");

  $("body").trigger("wc-enhanced-select-init");
}
