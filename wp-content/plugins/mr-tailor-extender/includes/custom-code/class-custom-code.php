<?php

if ( ! class_exists( 'MTCustomCode' ) ) :

	/**
	 * MTCustomCode class.
	 *
	 * @since 1.4.2
	*/
	class MTCustomCode {

		/**
		 * The single instance of the class.
		 *
		 * @since 1.4.2
		 * @var MTCustomCode
		*/
		protected static $_instance = null;

		/**
		 * MTCustomCode constructor.
		 *
		 * @since 1.4.2
		*/
		public function __construct() {

			$this->customizer_options();

			add_action('mrtailor_header_start', array($this, 'mrtailor_header_code'));
			add_action('mrtailor_footer_action', array($this, 'mrtailor_footer_code'));
		}

		/**
		 * Ensures only one instance of MTCustomCode is loaded or can be loaded.
		 *
		 * @since 1.4.2
		 *
		 * @return MTCustomCode
		*/
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Output header custom code
		 *
		 * @return void
		 */
		public function mrtailor_header_code() {
			echo get_option( 'mt_custom_code_header_js', '' );
		}

		/**
		 * Output footer custom code
		 *
		 * @return void
		 */
		public function mrtailor_footer_code() {
			echo get_option( 'mt_custom_code_footer_js', '' );
		}

		/**
		 * Registers customizer options.
		 *
		 * @since 1.4.2
		 * @return void
		 */
		protected function customizer_options() {
			add_action( 'customize_register', array( $this, 'mt_custom_code_customizer' ) );
		}

		/**
		 * Creates customizer options.
		 *
		 * @since 1.4.2
		 * @return void
		 */
		public function mt_custom_code_customizer( $wp_customize ) {

			// Section
			$wp_customize->add_section( 'custom_code_panel', array(
		 		'title'       => esc_attr__( 'Additional JS', 'mrtailor-extender' ),
		 		'priority'    => 201,
		 	) );

		 	$wp_customize->add_setting( 'mt_custom_code_header_js', array(
				'type'		 => 'option',
				'capability' => 'manage_options',
				'transport'  => 'refresh',
				'default' 	 => '',
			) );

			$wp_customize->add_control(
				new WP_Customize_Code_Editor_Control(
					$wp_customize,
					'mt_custom_code_header_js',
					array(
						'code_type' 	=> 'javascript',
						'label'       	=> esc_attr__( 'Header JavaScript Code', 'mrtailor-extender' ),
						'section'     	=> 'custom_code_panel',
						'priority'    	=> 10,
					)
				)
			);

			$wp_customize->add_setting( 'mt_custom_code_footer_js', array(
				'type'		 => 'option',
				'capability' => 'manage_options',
				'transport'  => 'refresh',
				'default' 	 => '',
			) );

			$wp_customize->add_control(
				new WP_Customize_Code_Editor_Control(
					$wp_customize,
					'mt_custom_code_footer_js',
					array(
						'code_type' 	=> 'javascript',
						'label'       	=> esc_attr__( 'Footer JavaScript Code', 'mrtailor-extender' ),
						'section'     	=> 'custom_code_panel',
						'priority'    	=> 10,
					)
				)
			);
		}
	}

endif;

$mt_custom_code = new MTCustomCode;
