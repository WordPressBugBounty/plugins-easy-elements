<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Free_Mini_Cart_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eel-mini-cart';
	}

	public function get_title() {
		return __( 'Mini Cart', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon eicon-cart';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
		return [
			'woo',
			'woocommerce',
			'mini cart',
			'cart',
			'shopping cart',
			'header cart',
		];
	}

	public function get_style_depends() {
		return [
			'eel-mini-cart',
		];
	}

	public function get_script_depends() {
		return [];
	}

	protected function register_controls() {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$this->register_content_controls();
		$this->register_style_controls();
	}

	protected function register_content_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'cart_icon',
			[
				'label'   => __( 'Cart Icon', 'easy-elements' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'show_cart_count',
			[
				'label'        => __( 'Show Cart Count', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'easy-elements' ),
				'label_off'    => __( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		/**
		 * Fires after the cart count control so add-on plugins can register
		 * extra content controls (cart panel toggle behaviour, subtotal display,
		 * view-cart / checkout buttons, empty-cart message, etc.). Add-ons call
		 * $widget->add_control( ... ) inside their callback.
		 *
		 * @param \Elementor\Widget_Base $widget The mini cart widget instance.
		 */
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- "eel_" is the Easy Elements plugin prefix.
		do_action( 'eel_mini_cart_after_count_controls', $this );

		$this->end_controls_section();
	}

	protected function register_style_controls() {

		// -------------------------------------------------------
		// Wrapper
		// -------------------------------------------------------
		$this->start_controls_section(
			'wrapper_style_section',
			[
				'label' => __( 'Wrapper', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'wrapper_padding',
			[
				'label'      => __( 'Padding', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eel-mini-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'wrapper_border_radius',
			[
				'label'      => __( 'Border Radius', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eel-mini-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'wrapper_transition',
			[
				'label'      => __( 'Transition Duration (s)', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 's' ],
				'range'      => [ 's' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ] ],
				'default'    => [ 'size' => 0.3, 'unit' => 's' ],
				'selectors'  => [
					'{{WRAPPER}} .eel-mini-cart' => 'transition: all {{SIZE}}{{UNIT}} ease;',
				],
			]
		);

		$this->start_controls_tabs( 'wrapper_tabs' );

		$this->start_controls_tab(
			'wrapper_normal_tab',
			[ 'label' => __( 'Normal', 'easy-elements' ) ]
		);

		$this->add_control(
			'wrapper_bg_color',
			[
				'label'     => __( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .eel-mini-cart' => 'background-color: {{VALUE}};' ],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wrapper_border',
				'selector' => '{{WRAPPER}} .eel-mini-cart',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .eel-mini-cart',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'wrapper_hover_tab',
			[ 'label' => __( 'Hover', 'easy-elements' ) ]
		);

		$this->add_control(
			'wrapper_hover_bg_color',
			[
				'label'     => __( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .eel-mini-cart:hover' => 'background-color: {{VALUE}};' ],
			]
		);

		$this->add_control(
			'wrapper_hover_border_color',
			[
				'label'     => __( 'Border Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .eel-mini-cart:hover' => 'border-color: {{VALUE}};' ],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'wrapper_hover_box_shadow',
				'selector' => '{{WRAPPER}} .eel-mini-cart:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// -------------------------------------------------------
		// Icon
		// -------------------------------------------------------
		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => __( 'Icon', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .eel-mini-cart-icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-mini-cart-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label'      => __( 'Size', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [ 'px' => [ 'min' => 10, 'max' => 100 ] ],
				'default'    => [ 'size' => 20, 'unit' => 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eel-mini-cart-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-mini-cart-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label'      => __( 'Spacing (right)', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
				'selectors'  => [
					'{{WRAPPER}} .eel-mini-cart-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// -------------------------------------------------------
		// Cart Count Badge
		// -------------------------------------------------------
		$this->start_controls_section(
			'count_style_section',
			[
				'label'     => __( 'Cart Count Badge', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_cart_count' => 'yes' ],
			]
		);

		$this->add_control(
			'count_color',
			[
				'label'     => __( 'Text Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [ '{{WRAPPER}} .eel-cart-count' => 'color: {{VALUE}};' ],
			]
		);

		$this->add_control(
			'count_background_color',
			[
				'label'     => __( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'selectors' => [ '{{WRAPPER}} .eel-cart-count' => 'background-color: {{VALUE}};' ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'count_typography',
				'selector' => '{{WRAPPER}} .eel-cart-count',
			]
		);

		$this->add_responsive_control(
			'count_size',
			[
				'label'      => __( 'Badge Size', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 10, 'max' => 50 ] ],
				'selectors'  => [
					'{{WRAPPER}} .eel-cart-count' => 'min-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'count_position_top',
			[
				'label'      => __( 'Position Top', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => -30, 'max' => 30 ] ],
				'selectors'  => [ '{{WRAPPER}} .eel-cart-count' => 'top: {{SIZE}}{{UNIT}};' ],
			]
		);

		$this->add_responsive_control(
			'count_position_left',
			[
				'label'      => __( 'Position Left', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => -30, 'max' => 50 ] ],
				'selectors'  => [ '{{WRAPPER}} .eel-cart-count' => 'left: {{SIZE}}{{UNIT}};' ],
			]
		);

		$this->end_controls_section();

		/**
		 * Fires after the Free style sections (Wrapper, Icon, Cart Count Badge)
		 * are registered. Add-on plugins use this to add Pro style sections
		 * for the cart panel, items, footer, buttons, empty-cart message, etc.
		 *
		 * @param \Elementor\Widget_Base $widget The mini cart widget instance.
		 */
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- "eel_" is the Easy Elements plugin prefix.
		do_action( 'eel_mini_cart_after_style_sections', $this );
	}

	protected function render() {
		if ( ! function_exists( 'WC' ) ) {
			echo '<p>' . esc_html__( 'WooCommerce is not active.', 'easy-elements' ) . '</p>';
			return;
		}

		$settings   = $this->get_settings_for_display();
		$cart_count = ( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;

		$this->add_render_attribute( 'mini-cart', 'class', 'eel-mini-cart' );
		$this->add_render_attribute( 'mini-cart', 'data-show-count', $settings['show_cart_count'] );

		/**
		 * Allow add-on plugins to add wrapper classes (cart-type variants, etc.)
		 * and data-* attributes (subtotal mode, empty-cart settings, etc.).
		 *
		 * @param \Elementor\Widget_Base $widget   The widget instance — call
		 *                                         $widget->add_render_attribute().
		 * @param array                  $settings Widget settings.
		 */
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- "eel_" is the Easy Elements plugin prefix.
		do_action( 'eel_mini_cart_render_attributes', $this, $settings );
		?>
		<div <?php echo $this->get_render_attribute_string( 'mini-cart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<a class="eel-mini-cart-toggle" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'View cart', 'easy-elements' ); ?>">
				<span class="eel-mini-cart-icon">
					<?php \Elementor\Icons_Manager::render_icon( $settings['cart_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
				<?php if ( $settings['show_cart_count'] === 'yes' ) : ?>
					<span class="eel-cart-count"><?php echo esc_html( $cart_count ); ?></span>
				<?php endif; ?>
				<?php
				/**
				 * Fires inside the toggle, after the count badge.
				 * Add-on plugins use this to render the subtotal display.
				 *
				 * @param array $settings Widget settings.
				 */
				// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- "eel_" is the Easy Elements plugin prefix.
				do_action( 'eel_mini_cart_after_toggle_count', $settings );
				?>
			</a>
			<?php
			/**
			 * Fires after the toggle. Add-on plugins use this to render the
			 * dropdown / off-canvas / modal cart panel that appears on click.
			 *
			 * @param array $settings Widget settings.
			 */
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- "eel_" is the Easy Elements plugin prefix.
			do_action( 'eel_mini_cart_after_toggle', $settings );
			?>
		</div>
		<?php
	}
}