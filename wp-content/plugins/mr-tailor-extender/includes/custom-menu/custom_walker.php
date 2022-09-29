<?php
/**
* Custom Walker
*
* @access      public
* @since       1.0
* @return      void
*/

if( !class_exists('rc_scm_walker')) {

	class rc_scm_walker extends Walker {
	    /**
	     * What the class handles.
	     *
	     * @since 3.0.0
	     * @var string
	     *
	     * @see Walker::$tree_type
	     */
	    public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	    /**
	     * Database fields to use.
	     *
	     * @since 3.0.0
	     * @todo Decouple this.
	     * @var array
	     *
	     * @see Walker::$db_fields
	     */
	    public $db_fields = array(
	        'parent' => 'menu_item_parent',
	        'id'     => 'db_id',
	    );

		/**
		 * Traverse elements to create list from elements.
		 *
		 * Display one element if the element doesn't have any children otherwise,
		 * display the element and its children. Will only traverse up to the max
		 * depth and no ignore elements under that depth. It is possible to set the
		 * max depth to include all depths, see walk() method.
		 *
		 * This method should not be called directly, use the walk() method instead.
		 *
		 * @since 2.5.0
		 *
		 * @param object $element           Data object.
		 * @param array  $children_elements List of elements to continue traversing (passed by reference).
		 * @param int    $max_depth         Max depth to traverse.
		 * @param int    $depth             Depth of current element.
		 * @param array  $args              An array of arguments.
		 * @param string $output            Used to append additional content (passed by reference).
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element ) {
				return;
			}

			$id_field = $this->db_fields['id'];
			$id       = $element->$id_field;

			// var_dump($args);die();

			/* Extra Fields */
			$args['background_url'] = ! empty( $element->background_url ) ? $element->background_url : '';
			$args['title'] = ! empty( $element->attr_title ) ? $element->attr_title : '';
			$args['description'] = ! empty( $element->description ) ? $element->description : '';
			$args['megamenu'] = ! empty( $element->megamenu ) ? $element->megamenu : '';
			$args['megamenu_title_column'] = ! empty( $element->megamenu_title_column ) ? $element->megamenu_title_column : '';
			$args['megamenu_image_column'] = ! empty( $element->megamenu_image_column ) ? $element->megamenu_image_column : '';

			// Display this element.
			$this->has_children = ! empty( $children_elements[ $id ] );
			if ( isset( $args[0] ) && is_array( $args[0] ) ) {
				$args[0]['has_children'] = $this->has_children; // Back-compat.
			}

			$this->start_el( $output, $element, $depth, ...array_values( $args ) );

			// Descend only when the depth is right and there are childrens for this element.
			if ( ( 0 == $max_depth || $max_depth > $depth + 1 ) && isset( $children_elements[ $id ] ) ) {

				foreach ( $children_elements[ $id ] as $child ) {

					if ( ! isset( $newlevel ) ) {
						$newlevel = true;
						// Start the child delimiter.
						$this->start_lvl( $output, $depth, ...array_values( $args ) );
					}
					$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
				}
				unset( $children_elements[ $id ] );
			}

			if ( isset( $newlevel ) && $newlevel ) {
				// End the child delimiter.
				$this->end_lvl( $output, $depth, $args );
			}

			// End this element.
			$this->end_el( $output, $element, $depth, ...array_values( $args ) );
		}

	    /**
	     * Starts the list before the elements are added.
	     *
	     * @since 3.0.0
	     *
	     * @see Walker::start_lvl()
	     *
	     * @param string   $output Used to append additional content (passed by reference).
	     * @param int      $depth  Depth of menu item. Used for padding.
	     * @param stdClass $args   An object of wp_nav_menu() arguments.
	     */
	    public function start_lvl( &$output, $depth = 0, $args = null ) {
	        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
	            $t = '';
	            $n = '';
	        } else {
	            $t = "\t";
	            $n = "\n";
	        }
	        $indent = str_repeat( $t, $depth );

	        // Default class.
	        $classes = array( 'sub-menu' );

	        /**
	         * Filters the CSS class(es) applied to a menu list element.
	         *
	         * @since 4.8.0
	         *
	         * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
	         * @param stdClass $args    An object of `wp_nav_menu()` arguments.
	         * @param int      $depth   Depth of menu item. Used for padding.
	         */
	        $class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );

			if( $depth === 0 && ( 'megamenu' === $args->megamenu ) && ( 'true' === $args->megamenu_title_column ) ) {
				$class_names .= ' megamenu-with-info-column';
			}

			if( $depth === 0 && ( 'megamenu' === $args->megamenu ) && ( 'true' === $args->megamenu_image_column ) ) {
				$class_names .= ' megamenu-with-image-column';
			}

	        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

	        $output .= "{$n}{$indent}<ul$class_names>{$n}";

			if( $depth === 0 && ( 'megamenu' === $args->megamenu ) && ( 'true' === $args->megamenu_title_column ) ) {
				$title = !empty($args->title) ? esc_attr($args->title) : '';
				$description = !empty($args->description) ? esc_attr($args->description) : '';
				$output .= "<li class='menu-item-info-column'><h3 class='menu-item-title'>{$title}</h3><p class='menu-item-description'>{$description}</p></li>";
			}
	    }

	    /**
	     * Ends the list of after the elements are added.
	     *
	     * @since 3.0.0
	     *
	     * @see Walker::end_lvl()
	     *
	     * @param string   $output Used to append additional content (passed by reference).
	     * @param int      $depth  Depth of menu item. Used for padding.
	     * @param stdClass $args   An object of wp_nav_menu() arguments.
	     */
	    public function end_lvl( &$output, $depth = 0, $args = null ) {
	        if ( isset( $args['item_spacing'] ) && 'discard' === $args['item_spacing'] ) {
	            $t = '';
	            $n = '';
	        } else {
	            $t = "\t";
	            $n = "\n";
	        }
	        $indent  = str_repeat( $t, $depth );

			if( $depth === 0 && ( 'megamenu' === $args['megamenu'] ) ) {
				if( 'true' === $args['megamenu_image_column'] ) {
					$output .= "<li class='menu-item-image-column'></li>";
				}
			}
	        $output .= "$indent</ul>{$n}";
	    }

	    /**
	     * Starts the element output.
	     *
	     * @since 3.0.0
	     * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	     *
	     * @see Walker::start_el()
	     *
	     * @param string   $output Used to append additional content (passed by reference).
	     * @param WP_Post  $item   Menu item data object.
	     * @param int      $depth  Depth of menu item. Used for padding.
	     * @param stdClass $args   An object of wp_nav_menu() arguments.
	     * @param int      $id     Current item ID.
	     */
	    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
	        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
	            $t = '';
	            $n = '';
	        } else {
	            $t = "\t";
	            $n = "\n";
	        }
	        $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

	        $classes   = empty( $item->classes ) ? array() : (array) $item->classes;
	        $classes[] = 'menu-item-' . $item->ID;

			if( $depth === 0 ) {
				$classes[] = 'menu-item-parent';
			}

	        /**
	         * Filters the arguments for a single nav menu item.
	         *
	         * @since 4.4.0
	         *
	         * @param stdClass $args  An object of wp_nav_menu() arguments.
	         * @param WP_Post  $item  Menu item data object.
	         * @param int      $depth Depth of menu item. Used for padding.
	         */
	        $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

			/* Extra Fields */
			$args->background_url = ! empty( $item->background_url ) ? $item->background_url : '';
			$args->title = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$args->description = ! empty( $item->description ) ? $item->description : '';
			$args->megamenu = ! empty( $item->megamenu ) ? $item->megamenu : '';
			$args->megamenu_title_column = ! empty( $item->megamenu_title_column ) ? $item->megamenu_title_column : '';
			$args->megamenu_image_column = ! empty( $item->megamenu_image_column ) ? $item->megamenu_image_column : '';

	        /**
	         * Filters the CSS classes applied to a menu item's list item element.
	         *
	         * @since 3.0.0
	         * @since 4.1.0 The `$depth` parameter was added.
	         *
	         * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
	         * @param WP_Post  $item    The current menu item.
	         * @param stdClass $args    An object of wp_nav_menu() arguments.
	         * @param int      $depth   Depth of menu item. Used for padding.
	         */
	        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );

			if( $depth === 0 && ( 'megamenu' === $args->megamenu ) ) {
				$class_names .= ' menu-item-megamenu';
			}

	        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

	        /**
	         * Filters the ID applied to a menu item's list item element.
	         *
	         * @since 3.0.1
	         * @since 4.1.0 The `$depth` parameter was added.
	         *
	         * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
	         * @param WP_Post  $item    The current menu item.
	         * @param stdClass $args    An object of wp_nav_menu() arguments.
	         * @param int      $depth   Depth of menu item. Used for padding.
	         */
	        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
	        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
			$background_image = ! empty( $item->background_url ) ? ' data-item-image="'.$item->background_url.'"' : '';

	        $output .= $indent . '<li' . $id . $class_names . $background_image . '>';

	        $atts           = array();
	        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
	        $atts['target'] = ! empty( $item->target ) ? $item->target : '';
	        if ( '_blank' === $item->target && empty( $item->xfn ) ) {
	            $atts['rel'] = 'noopener noreferrer';
	        } else {
	            $atts['rel'] = $item->xfn;
	        }
	        $atts['href']         = ! empty( $item->url ) ? $item->url : '';
	        $atts['aria-current'] = $item->current ? 'page' : '';

	        /**
	         * Filters the HTML attributes applied to a menu item's anchor element.
	         *
	         * @since 3.6.0
	         * @since 4.1.0 The `$depth` parameter was added.
	         *
	         * @param array $atts {
	         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
	         *
	         *     @type string $title        Title attribute.
	         *     @type string $target       Target attribute.
	         *     @type string $rel          The rel attribute.
	         *     @type string $href         The href attribute.
	         *     @type string $aria_current The aria-current attribute.
	         * }
	         * @param WP_Post  $item  The current menu item.
	         * @param stdClass $args  An object of wp_nav_menu() arguments.
	         * @param int      $depth Depth of menu item. Used for padding.
	         */
	        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

	        $attributes = '';
	        foreach ( $atts as $attr => $value ) {
	            if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
	                $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
	                $attributes .= ' ' . $attr . '="' . $value . '"';
	            }
	        }

	        /** This filter is documented in wp-includes/post-template.php */
	        $title = apply_filters( 'the_title', $item->title, $item->ID );

	        /**
	         * Filters a menu item's title.
	         *
	         * @since 4.4.0
	         *
	         * @param string   $title The menu item's title.
	         * @param WP_Post  $item  The current menu item.
	         * @param stdClass $args  An object of wp_nav_menu() arguments.
	         * @param int      $depth Depth of menu item. Used for padding.
	         */
	        $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

	        $item_output  = $args->before;
	        $item_output .= '<a' . $attributes . '>';
	        $item_output .= $args->link_before . $title . $args->link_after;
	        $item_output .= '</a>';
	        $item_output .= $args->after;

	        /**
	         * Filters a menu item's starting output.
	         *
	         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
	         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
	         * no filter for modifying the opening and closing `<li>` for a menu item.
	         *
	         * @since 3.0.0
	         *
	         * @param string   $item_output The menu item's starting HTML output.
	         * @param WP_Post  $item        Menu item data object.
	         * @param int      $depth       Depth of menu item. Used for padding.
	         * @param stdClass $args        An object of wp_nav_menu() arguments.
	         */
	        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	    }

	    /**
	     * Ends the element output, if needed.
	     *
	     * @since 3.0.0
	     *
	     * @see Walker::end_el()
	     *
	     * @param string   $output Used to append additional content (passed by reference).
	     * @param WP_Post  $item   Page data object. Not used.
	     * @param int      $depth  Depth of page. Not Used.
	     * @param stdClass $args   An object of wp_nav_menu() arguments.
	     */
	    public function end_el( &$output, $item, $depth = 0, $args = null ) {
	        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
	            $t = '';
	            $n = '';
	        } else {
	            $t = "\t";
	            $n = "\n";
	        }

	        $output .= "</li>{$n}";
	    }

	}
}

add_filter( 'wp_nav_menu_args', function( $args ) {
	if ( isset( $args['walker'] ) && is_string( $args['walker'] ) && class_exists( $args['walker'] ) ) {
		$args['walker'] = new $args['walker'];
	}
	return $args;
}, 1001 );

add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_script(
		'mrtailor-custom-menu-walker-scripts',
		plugins_url( 'assets/js/custom-menu.js', __FILE__ ),
		array('jquery')
	);
});
