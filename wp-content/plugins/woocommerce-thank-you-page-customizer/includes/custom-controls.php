<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	require_once ABSPATH . 'wp-includes/class-wp-customize-control.php';
}
if ( class_exists( 'WP_Customize_Control' ) ):
	if ( ! class_exists( 'WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control' ) ) {
		class WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control extends WP_Customize_Control {
			public $type = 'wtyp_radio_icons';

			public function render_content() {
				?>
                <div class="customize-control-content">
					<?php
					if ( ! empty( $this->label ) ) {
						?>
                        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<?php
					}

					if ( ! empty( $this->description ) ) {
						?>
                        <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
						<?php
					}
					$class = $this->id;
					$class = str_replace( '[', '-', $class );
					$class = str_replace( ']', '', $class );
					?>
                    <div class="wtyp-radio-icons-wrap <?php esc_attr_e( $class ); ?>">
						<?php
						foreach ( $this->choices as $key => $value ) {
							?>
                            <label class="wtyp-radio-icons-label <?php if ( $key == $this->value() )
								esc_attr_e( 'wtyp-radio-icons-active' ) ?>">
                                <input type="radio" style="display: none;" name="<?php esc_attr_e( $this->id ); ?>"
                                       value="<?php esc_attr_e( $key ); ?>" <?php $this->link(); ?> <?php checked( esc_attr( $key ), $this->value() ); ?>/>
                                <span class="<?php esc_attr_e( $key ); ?>"></span>
                            </label>
							<?php
						}
						?>
                    </div>
                </div>
				<?php
			}

			public function enqueue() {
				wp_enqueue_script( 'woocommerce-thank-you-page-custom-controls-social-icons', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'custom-control-social-icons.js', array(
					'jquery',
//					'customize-preview',
				), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION, true );
				wp_enqueue_style( 'woocommerce-thank-you-page-custom-controls-social-icons-css', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'custom-control-social-icons.css' );
			}
		}
	}
	if ( ! class_exists( 'WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Blocks_Control' ) ) {
		class WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Blocks_Control extends WP_Customize_Control {
			public $type = 'wtyp_block';

			public function render_content() {
				?>
                <div class="customize-control-content">
					<?php
					if ( ! empty( $this->label ) ) {
						?>
                        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<?php
					}
					$class = $this->id;
					$class = str_replace( '[', '-', $class );
					$class = str_replace( ']', '', $class );
					?>
                    <input type="hidden" name="<?php esc_attr_e( $this->id ); ?>" id="<?php esc_attr_e( $class ); ?>"
                           value="<?php echo htmlentities( $this->value() ); ?>"
                           class="<?php echo $class; ?>" <?php $this->link(); ?>/>
                </div>
				<?php
				$rows           = json_decode( $this->value(), true );
				$block_titles   = array(
					'thank_you_message'    => __( 'Thank you message', 'woocommerce-thank-you-page-customizer' ),
					'order_confirmation'   => __( 'Order confirmation', 'woocommerce-thank-you-page-customizer' ),
					'order_details'        => __( 'Order details', 'woocommerce-thank-you-page-customizer' ),
					'customer_information' => __( 'Customer information', 'woocommerce-thank-you-page-customizer' ),
					'coupon'               => __( 'Coupon', 'woocommerce-thank-you-page-customizer' ),
					'social_icons'         => __( 'Social icons', 'woocommerce-thank-you-page-customizer' ),
					'google_map'           => __( 'Google map', 'woocommerce-thank-you-page-customizer' ),
					'bing_map'             => __( 'Bing map', 'woocommerce-thank-you-page-customizer' ),
					'order_again'          => __( 'Order again', 'woocommerce-thank-you-page-customizer' ),
					'text_editor'          => __( 'Text editor', 'woocommerce-thank-you-page-customizer' ),
					'products'             => __( 'Products', 'woocommerce-thank-you-page-customizer' ),
					'payment_method'       => __( 'Payment method', 'woocommerce-thank-you-page-customizer' ),
				);
				$contents       = array(
					'text_editor',
					'products',
				);
				$components     = array(
					'thank_you_message',
					'order_confirmation',
					'order_details',
					'customer_information',
					'coupon',
					'social_icons',
					'google_map',
					'bing_map',
					'order_again',
					'payment_method',
				);
				$components_old = array(
					'sale_products',
					'best_selling_products',
					'recent_products',
					'recently_viewed_products',
					'featured_products',
					'up_sells_products',
					'cross_sells_products',
					'related_products',
					'top_rated_products',
				);
				?>
                <div class="<?php echo $this->set( 'container' ) ?>">
					<?php
					if ( is_array( $rows ) && count( $rows ) ) {
						foreach ( $rows as $row_key => $row_value ) {
							if ( is_array( $row_value ) ) {
								?>
                                <div class="<?php echo $this->set( array(
									'container__row',
									'container__row_' . $row_key,
									count( $row_value ) . '-column',
								) ) ?>">
									<?php

									if ( count( $row_value ) ) {
										foreach ( $row_value as $block_key => $block_value ) {

											if ( is_array( $block_value ) && count( $block_value ) ) {
												?>
                                                <div class="<?php echo $this->set( array(
													'container__block',
													'container__block_' . $block_key,
													'droppable',
													'draggable',
												) ) ?>">
													<?php
													foreach ( $block_value as $block_value_k => $block_value_v ) {
														if ( in_array( $block_value_v, $components_old ) ) {
															continue;
														}
														$component_key = array_search( $block_value_v, $components );
														if ( $component_key !== false ) {
															unset( $components[ $component_key ] );
														}
														?>
                                                        <div class="<?php echo $this->set( array(
															'item',
															$block_value_v
														) ) ?>"
                                                             data-block_item="<?php echo $block_value_v ?>">
															<?php echo isset( $block_titles[ $block_value_v ] ) ? $block_titles[ $block_value_v ] : $block_value_v ?>
                                                            <span class="<?php echo $this->set( 'edit' ) ?> wtyp_icons-edit"
                                                                  title="<?php _e( 'Edit this item', 'woocommerce-thank-you-page-customizer' ) ?>"></span>
                                                            <span class="<?php echo $this->set( 'remove' ) ?> wtyp_icons-cancel"
                                                                  title="<?php _e( 'Remove this item', 'woocommerce-thank-you-page-customizer' ) ?>"></span>
                                                        </div>
														<?php
													}
													?>
                                                    <div class="<?php echo $this->set( array(
														'edit-block-container',
													) ) ?>">
                                                        <span class="<?php echo $this->set( array(
	                                                        'edit-block-add-item',
                                                        ) ) ?>"
                                                              title="<?php _e( 'Add items', 'woocommerce-thank-you-page-customizer' ) ?>">+</span>
                                                    </div>
                                                </div>
												<?php
											} else {
												?>
                                                <div class="<?php echo $this->set( array(
													'container__block',
													'droppable',
													'draggable',
												) ) ?>">
                                                    <div class="<?php echo $this->set( array(
														'edit-block-container',
													) ) ?>">
                                                        <span class="<?php echo $this->set( array(
	                                                        'edit-block-add-item',
                                                        ) ) ?>"
                                                              title="<?php _e( 'Add items', 'woocommerce-thank-you-page-customizer' ) ?>">+</span>
                                                    </div>
                                                </div>

												<?php
											}
										}

									} else {
										?>
                                        <div class="<?php echo $this->set( array(
											'container__block',
											'droppable',
											'draggable',
										) ) ?>">
                                            <div class="<?php echo $this->set( array(
												'edit-block-container',
											) ) ?>">
                                                        <span class="<?php echo $this->set( array(
	                                                        'edit-block-add-item',
                                                        ) ) ?>">Add item</span>
                                            </div>
                                        </div>
										<?php
									}
									?>
                                    <span class="<?php echo $this->set( array(
										'remove-row',
									) ) ?> dashicons dashicons-trash"
                                          title="<?php _e( 'Remove', 'woocommerce-thank-you-page-customizer' ) ?>"></span>
                                </div>
								<?php
							}
						}
					}
					?>
                </div>
                <h3 class="<?php echo $this->set( 'add-row-title' ) ?>"><?php echo __( 'Click on items below to add row', 'woocommerce-thank-you-page-customizer' ) ?></h3>
                <div class="<?php echo $this->set( 'add-row-container' ) ?>">
					<?php
					for ( $i = 1; $i <= 4; $i ++ ) {
						?>
                        <span class="<?php echo $this->set( array( 'add-row', 'row-' . $i . '-column' ) ) ?>"
                              data-column_nums="<?php echo $i ?>"
                              title="<?php printf( __( 'Add an %s-columns row', 'woocommerce-thank-you-page-customizer' ), $i ) ?>">
                            <?php
                            for ( $j = 1; $j <= $i; $j ++ ) {
	                            ?>
                                <span class="<?php echo $this->set( 'add-row-item' ) ?>"></span>
	                            <?php
                            }
                            ?>
                        </span>
						<?php
					}
					?>

                </div>
				<?php

				?>
                <div class="<?php echo $this->set( 'components-container' ) ?>">
                    <div class="<?php echo $this->set( 'components-overlay' ) ?>"></div>
                    <div class="<?php echo $this->set( 'components' ) ?>">
                        <div class="<?php echo $this->set( 'components-close-container' ) ?>"><span
                                    class="<?php echo $this->set( 'components-close' ) ?> wtyp_icons-cancel"></span>
                        </div>
                        <h3 class="<?php echo $this->set( 'available-components' ) ?>"><?php echo __( 'Available components', 'woocommerce-thank-you-page-customizer' ) ?></h3>
                        <div class="<?php echo $this->set( array( 'components__block' ) ) ?>">
							<?php
							if ( is_array( $components ) && count( $components ) ) {

								foreach ( $components as $components_k => $components_v ) {
									?>
                                    <div class="<?php echo $this->set( array( 'item', $components_v ) ) ?>"
                                         data-block_item="<?php echo $components_v ?>">
										<?php echo isset( $block_titles[ $components_v ] ) ? $block_titles[ $components_v ] : $components_v ?>
                                        <span class="<?php echo $this->set( 'edit' ) ?> wtyp_icons-edit"
                                              title="<?php _e( 'Edit this item', 'woocommerce-thank-you-page-customizer' ) ?>"></span>
                                        <span class="<?php echo $this->set( 'remove' ) ?> wtyp_icons-cancel"
                                              title="<?php _e( 'Remove this item', 'woocommerce-thank-you-page-customizer' ) ?>"></span>
                                    </div>
									<?php
								}
							}

							?>
                        </div>
                        <div class="<?php echo $this->set( array( 'contents__block' ) ) ?>">
							<?php
							foreach ( $contents as $content ) {
								?>
                                <div class="<?php echo $this->set( array( 'item', $content ) ) ?>"
                                     data-block_item="<?php echo $content ?>">
									<?php echo isset( $block_titles[ $content ] ) ? $block_titles[ $content ] : $content ?>
                                    <span class="<?php echo $this->set( 'edit' ) ?> wtyp_icons-edit"
                                          title="<?php _e( 'Edit this item', 'woocommerce-thank-you-page-customizer' ) ?>"></span>
                                    <span class="<?php echo $this->set( 'remove' ) ?> wtyp_icons-cancel"
                                          title="<?php _e( 'Remove this item', 'woocommerce-thank-you-page-customizer' ) ?>"></span>
                                </div>
								<?php
							}
							?>
                        </div>
                    </div>
                </div>
				<?php
			}

			private function set( $name ) {
				if ( is_array( $name ) ) {
					return implode( ' ', array_map( array( $this, 'set' ), $name ) );

				} else {
					return esc_attr__( 'woocommerce-thank-you-page-' . $name );

				}
			}

			public function enqueue() {
				wp_enqueue_script( 'woocommerce-thank-you-page-custom-controls-blocks-js', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'custom-control-blocks.js', array(
					'jquery',
					'jquery-ui-sortable',
					'jquery-ui-draggable',
					'jquery-ui-droppable',
				), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION, true );
				$rows = array(
					1 => '',
					2 => '',
					3 => '',
					4 => '',
				);
				foreach ( $rows as $key => $val ) {
					ob_start();
					?>
                    <div class="<?php echo $this->set(
						array(
							'container__row',
							$key . '-column',
						) ) ?>">
						<?php
						for ( $i = 0; $i < $key; $i ++ ) {
							?>
                            <div class="<?php echo $this->set(
								array(
									'container__block',
									'droppable',
									'draggable',
								) ) ?>">
                                <div class="<?php echo $this->set( array(
									'edit-block-container',
								) ) ?>">
                                                        <span class="<?php echo $this->set( array(
	                                                        'edit-block-add-item',
                                                        ) ) ?>">+</span>
                                </div>
                            </div>
							<?php
						}
						?>
                        <span class="<?php echo $this->set( array(
							'remove-row',
						) ) ?> dashicons dashicons-trash"
                              title="<?php _e( 'Remove', 'woocommerce-thank-you-page-customizer' ) ?>"></span>
                    </div>
					<?php
					$rows[ $key ] = ob_get_clean();
				}

				wp_localize_script( 'woocommerce-thank-you-page-custom-controls-blocks-js', 'woocommerce_thank_you_page_custom_control_blocks_params', array(
						'rows' => $rows
					)
				);
				wp_enqueue_style( 'woocommerce-thank-you-page-icons-css', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'woocommerce-thank-you-page-icons.css' );
				wp_enqueue_style( 'woocommerce-thank-you-page-custom-controls-blocks-css', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'custom-control-blocks.css' );
			}
		}
	}
	if ( ! class_exists( 'WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Text_Editor_Control' ) ) {
		class WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Text_Editor_Control extends WP_Customize_Control {
			public $type = 'wtyp_text_editor';

			public function render_content() {

				?>
                <div class="customize-control-content">
					<?php
					$class  = $this->id;
					$class  = str_replace( '[', '-', $class );
					$class  = str_replace( ']', '', $class );
					$value  = json_decode( $this->value() , true);
					$value1 = json_encode( wtypc_base64_encode( $value ) );
					?>
                    <input type="hidden" name="<?php esc_attr_e( $this->id ); ?>" id="<?php esc_attr_e( $class ); ?>"
                           value="<?php echo esc_attr( $value1 ); ?>"
                           class="<?php echo $class; ?>" <?php $this->link(); ?>/>
                </div>
				<?php
			}
		}
	}
	if ( ! class_exists( 'WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Products_Control' ) ) {
		class WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Products_Control extends WP_Customize_Control {
			public $type = 'wtyp_products';

			public function render_content() {
				?>
                <div class="customize-control-content">
					<?php
					$class = $this->id;
					$class = str_replace( '[', '-', $class );
					$class = str_replace( ']', '', $class );
					$value = $this->value() ;
					?>
                    <input type="hidden" name="<?php esc_attr_e( $this->id ); ?>" id="<?php esc_attr_e( $class ); ?>"
                           value="<?php echo $value ? htmlentities( $value ) : ''; ?>"
                           class="<?php echo $class; ?>" <?php $this->link(); ?>/>
                </div>
				<?php
			}
		}
	}
	if ( ! class_exists( 'WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Image_Radio_Button_Custom_Control' ) ) {
		/**
		 * Image Radio Button Custom Control
		 *
		 * @author Anthony Hortin <http://maddisondesigns.com>
		 * @license http://www.gnu.org/licenses/gpl-2.0.html
		 * @link https://github.com/maddisondesigns
		 */
		class WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Image_Radio_Button_Custom_Control extends WP_Customize_Control {
			/**
			 * The type of control being rendered
			 */
			public $type = 'wtyp_image_radio_button';

			/**
			 * Enqueue our scripts and styles
			 */
			public function enqueue() {
				wp_enqueue_style( 'woocommerce-thank-you-page-customizer-custom-controls-radio-image-css', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'customizer-radio-image.css', array(), '1.0', 'all' );
			}

			/**
			 * Render the control in the customizer
			 */
			public function render_content() {
				?>
                <div class="wtyp_image_radio_button_control">
					<?php if ( ! empty( $this->label ) ) { ?>
                        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php } ?>
					<?php if ( ! empty( $this->description ) ) { ?>
                        <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
					<?php } ?>

					<?php foreach ( $this->choices as $key => $value ) { ?>
                        <label class="wtyp-radio-button-label">
                            <input type="radio" name="<?php echo esc_attr( $this->id ); ?>"
                                   value="<?php echo esc_attr( $key ); ?>" <?php $this->link(); ?> <?php checked( esc_attr( $key ), $this->value() ); ?>/>
                            <img src="<?php echo esc_attr( $value['image'] ); ?>" alt="<?php echo esc_attr( $value['name'] ); ?>" title="<?php echo esc_attr( $value['name'] ); ?>"/>
                        </label>
					<?php } ?>
                </div>
				<?php
			}
		}

	}
endif;
