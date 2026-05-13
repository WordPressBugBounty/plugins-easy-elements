<?php
/**
 * Product Grid Lite widget.
 *
 * A lightweight WooCommerce product grid for the free Easy Elements plugin.
 *
 * @package Easyel\EasyElements\Widgets
 */

namespace Easyel\EasyElements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || die();

class Easyel_Product_Grid_Lite_Widget extends Widget_Base {

	public function get_name() {
		return 'eel-product-grid-lite';
	}

	public function get_title() {
		return esc_html__( 'Product Grid Lite', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-process-grid';
	}

	public function get_categories() {
		return array( 'easyelements_category' );
	}

	public function get_keywords() {
		return array( 'woocommerce', 'product', 'grid', 'shop', 'store', 'ecommerce' );
	}

	public function get_style_depends() {
		return array( 'eel-product-grid-lite' );
	}

	public function get_script_depends() {
		$depends = array();
		if ( function_exists( 'WC' ) ) {
			$depends[] = 'wc-add-to-cart';
		}
		return $depends;
	}

	/**
	 * Return a list of product categories for SELECT2.
	 *
	 * @return array
	 */
	private function easyel_pgl_get_product_categories() {
		$options = array();

		if ( ! taxonomy_exists( 'product_cat' ) ) {
			return $options;
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return $options;
		}

		foreach ( $terms as $term ) {
			if ( isset( $term->slug, $term->name ) ) {
				$options[ $term->slug ] = $term->name;
			}
		}

		return $options;
	}

	/**
	 * Return a list of products for SELECT2.
	 *
	 * @return array
	 */
	private function easyel_pgl_get_all_products() {
		$options = array();

		if ( ! post_type_exists( 'product' ) ) {
			return $options;
		}

		$products = get_posts(
			array(
				'post_type'      => 'product',
				'posts_per_page' => 100,
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		if ( empty( $products ) ) {
			return $options;
		}

		foreach ( $products as $product ) {
			$options[ $product->ID ] = $product->post_title;
		}

		return $options;
	}

	protected function register_controls() {

		if ( ! function_exists( 'WC' ) ) {
			$this->start_controls_section(
				'_pgl_notice_section',
				array(
					'label' => esc_html__( 'Notice', 'easy-elements' ),
				)
			);

			$this->add_control(
				'_pgl_wc_notice',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => '<strong>' . esc_html__( 'WooCommerce is required for this widget.', 'easy-elements' ) . '</strong>',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				)
			);

			$this->end_controls_section();

			return;
		}

		$this->register_section_layout();
		$this->register_section_query();
		$this->register_section_display();
		$this->register_section_addtocart();

		// Style tabs.
		$this->register_style_card();
		$this->register_style_image();
		$this->register_style_title();
		$this->register_style_price();
		$this->register_style_excerpt();
		$this->register_style_addtocart();
		$this->register_style_badge();
	}

	protected function register_section_layout() {
		$this->start_controls_section(
			'_pgl_section_layout',
			array(
				'label' => esc_html__( 'Layout', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'skin',
			array(
				'label'   => esc_html__( 'Skin', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Default', 'easy-elements' ),
					'minimal' => esc_html__( 'Minimal', 'easy-elements' ),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__( 'Columns', 'easy-elements' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => esc_html__( '1 Column', 'easy-elements' ),
					'2' => esc_html__( '2 Columns', 'easy-elements' ),
					'3' => esc_html__( '3 Columns', 'easy-elements' ),
					'4' => esc_html__( '4 Columns', 'easy-elements' ),
					'5' => esc_html__( '5 Columns', 'easy-elements' ),
				),
				'selectors'      => array(
					'{{WRAPPER}} .eel-pgl-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'      => esc_html__( 'Column Gap', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 24,
				),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-grid' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'default' => 'woocommerce_thumbnail',
			)
		);

		$this->end_controls_section();
	}

	protected function register_section_query() {
		$this->start_controls_section(
			'_pgl_section_query',
			array(
				'label' => esc_html__( 'Query', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'query_type',
			array(
				'label'   => esc_html__( 'Product Source', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'recent',
				'options' => array(
					'recent'   => esc_html__( 'Recent Products', 'easy-elements' ),
					'featured' => esc_html__( 'Featured Products', 'easy-elements' ),
					'sale'     => esc_html__( 'On-Sale Products', 'easy-elements' ),
					'custom'   => esc_html__( 'Manual Selection', 'easy-elements' ),
				),
			)
		);

		$this->add_control(
			'product_categories',
			array(
				'label'       => esc_html__( 'Include Categories', 'easy-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->easyel_pgl_get_product_categories(),
				'condition'   => array(
					'query_type!' => 'custom',
				),
			)
		);

		$this->add_control(
			'exclude_products',
			array(
				'label'       => esc_html__( 'Exclude Products', 'easy-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->easyel_pgl_get_all_products(),
				'condition'   => array(
					'query_type!' => 'custom',
				),
			)
		);

		$this->add_control(
			'manual_products',
			array(
				'label'       => esc_html__( 'Select Products', 'easy-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->easyel_pgl_get_all_products(),
				'condition'   => array(
					'query_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'products_per_page',
			array(
				'label'   => esc_html__( 'Products Count', 'easy-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 48,
				'default' => 6,
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'     => esc_html__( 'Order By', 'easy-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => array(
					'date'       => esc_html__( 'Date', 'easy-elements' ),
					'title'      => esc_html__( 'Title', 'easy-elements' ),
					'menu_order' => esc_html__( 'Menu Order', 'easy-elements' ),
					'rand'       => esc_html__( 'Random', 'easy-elements' ),
					'ID'         => esc_html__( 'ID', 'easy-elements' ),
				),
				'condition' => array(
					'query_type!' => 'custom',
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'     => esc_html__( 'Order', 'easy-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'desc',
				'options'   => array(
					'desc' => esc_html__( 'Descending', 'easy-elements' ),
					'asc'  => esc_html__( 'Ascending', 'easy-elements' ),
				),
				'condition' => array(
					'query_type!' => 'custom',
				),
			)
		);

		$this->add_control(
			'hide_out_of_stock',
			array(
				'label'        => esc_html__( 'Hide Out of Stock', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'easy-elements' ),
				'label_off'    => esc_html__( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();
	}

	protected function register_section_display() {
		$this->start_controls_section(
			'_pgl_section_display',
			array(
				'label' => esc_html__( 'Display Options', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_sale_badge',
			array(
				'label'        => esc_html__( 'Show Sale Badge', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'easy-elements' ),
				'label_off'    => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'sale_badge_text',
			array(
				'label'     => esc_html__( 'Sale Badge Text', 'easy-elements' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Sale', 'easy-elements' ),
				'condition' => array(
					'show_sale_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_price',
			array(
				'label'        => esc_html__( 'Show Price', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'easy-elements' ),
				'label_off'    => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_excerpt',
			array(
				'label'        => esc_html__( 'Show Excerpt', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'easy-elements' ),
				'label_off'    => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'label'     => esc_html__( 'Excerpt Length (words)', 'easy-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 100,
				'default'   => 15,
				'condition' => array(
					'show_excerpt' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_section_addtocart() {
		$this->start_controls_section(
			'_pgl_section_addtocart',
			array(
				'label' => esc_html__( 'Add to Cart', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_add_to_cart',
			array(
				'label'        => esc_html__( 'Show Add to Cart', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'easy-elements' ),
				'label_off'    => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'add_to_cart_text',
			array(
				'label'     => esc_html__( 'Button Text', 'easy-elements' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Add to Cart', 'easy-elements' ),
				'condition' => array(
					'show_add_to_cart' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_card() {
		$this->start_controls_section(
			'_pgl_style_card',
			array(
				'label' => esc_html__( 'Card', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'card_bottom_gap',
			array(
				'label'      => esc_html__( 'Bottom Gap', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'card_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .eel-pgl-item',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .eel-pgl-item',
			)
		);

		$this->add_responsive_control(
			'card_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => esc_html__( 'Padding', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .eel-pgl-item',
			)
		);

		$this->add_control(
			'card_hover_heading',
			array(
				'label'     => esc_html__( 'Hover State', 'easy-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'card_hover_bg_color',
			array(
				'label'     => esc_html__( 'Hover Background', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-item:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_hover_shadow',
				'selector' => '{{WRAPPER}} .eel-pgl-item:hover',
			)
		);

		$this->add_control(
			'card_hover_lift',
			array(
				'label'      => esc_html__( 'Hover Lift (Y offset)', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => -20,
						'max' => 0,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 0,
				),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-item' => 'transition: transform .35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow .4s cubic-bezier(0.4, 0, 0.2, 1), background-color .3s ease;',
					'{{WRAPPER}} .eel-pgl-item:hover' => 'transform: translateY({{SIZE}}{{UNIT}});',
				),
			)
		);

		$this->add_responsive_control(
			'card_text_align',
			array(
				'label'                => esc_html__( 'Alignment', 'easy-elements' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'easy-elements' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'easy-elements' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'easy-elements' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				// Use logical CSS values that work for BOTH text-align and align-items
				// so the button (a flex item) aligns the same as the text content.
				// `start | end | center` are valid in modern text-align and align-items.
				'selectors_dictionary' => array(
					'left'   => 'start',
					'center' => 'center',
					'right'  => 'end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .eel-pgl-item .eel-pgl-content' => 'text-align: {{VALUE}}; align-items: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_image() {
		$this->start_controls_section(
			'_pgl_style_image',
			array(
				'label' => esc_html__( 'Image', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'      => esc_html__( 'Height', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 80,
						'max' => 800,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-image img' => 'height: {{SIZE}}{{UNIT}}; width: 100%;',
				),
			)
		);

		$this->add_control(
			'image_object_fit',
			array(
				'label'     => esc_html__( 'Object Fit', 'easy-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array(
					'cover'   => esc_html__( 'Cover', 'easy-elements' ),
					'contain' => esc_html__( 'Contain', 'easy-elements' ),
					'fill'    => esc_html__( 'Fill', 'easy-elements' ),
					'none'    => esc_html__( 'None', 'easy-elements' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-image img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-image, {{WRAPPER}} .eel-pgl-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'image_aspect_ratio',
			array(
				'label'     => esc_html__( 'Aspect Ratio', 'easy-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4/3',
				'options'   => array(
					''     => esc_html__( 'Auto', 'easy-elements' ),
					'1/1'  => esc_html__( 'Square (1:1)', 'easy-elements' ),
					'4/3'  => esc_html__( 'Landscape (4:3)', 'easy-elements' ),
					'3/2'  => esc_html__( 'Standard (3:2)', 'easy-elements' ),
					'16/9' => esc_html__( 'Wide (16:9)', 'easy-elements' ),
					'3/4'  => esc_html__( 'Portrait (3:4)', 'easy-elements' ),
					'4/5'  => esc_html__( 'Portrait (4:5)', 'easy-elements' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-image' => 'aspect-ratio: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'image_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'easy-elements' ),
				'description' => esc_html__( 'Visible if image is transparent or missing.', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-image' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'image_hover_zoom_heading',
			array(
				'label'     => esc_html__( 'Hover Effect', 'easy-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'image_hover_zoom',
			array(
				'label'        => esc_html__( 'Enable Image Zoom', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'easy-elements' ),
				'label_off'    => esc_html__( 'Off', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'image_hover_zoom_scale',
			array(
				'label'      => esc_html__( 'Zoom Scale', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1.3,
						'step' => 0.01,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 1.03,
				),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-item:hover .eel-pgl-image img' => 'transform: scale({{SIZE}});',
				),
				'condition'  => array(
					'image_hover_zoom' => 'yes',
				),
			)
		);

		$this->add_control(
			'image_hover_overlay',
			array(
				'label'     => esc_html__( 'Hover Overlay Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-image::after' => 'content: ""; position: absolute; inset: 0; background-color: {{VALUE}}; opacity: 0; transition: opacity .3s ease; pointer-events: none;',
					'{{WRAPPER}} .eel-pgl-item:hover .eel-pgl-image::after' => 'opacity: 1;',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_title() {
		$this->start_controls_section(
			'_pgl_style_title',
			array(
				'label' => esc_html__( 'Title', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .eel-pgl-title, {{WRAPPER}} .eel-pgl-title a',
			)
		);

		$this->start_controls_tabs( '_pgl_title_color_tabs' );

		$this->start_controls_tab(
			'_pgl_title_color_normal',
			array(
				'label' => esc_html__( 'Normal', 'easy-elements' ),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-title, {{WRAPPER}} .eel-pgl-title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_pgl_title_color_hover',
			array(
				'label' => esc_html__( 'Hover', 'easy-elements' ),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-title a:hover, {{WRAPPER}} .eel-pgl-item:hover .eel-pgl-title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_price() {
		$this->start_controls_section(
			'_pgl_style_price',
			array(
				'label'     => esc_html__( 'Price', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_price' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .eel-pgl-price, {{WRAPPER}} .eel-pgl-price .amount',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-price, {{WRAPPER}} .eel-pgl-price ins .amount, {{WRAPPER}} .eel-pgl-price .amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'price_del_color',
			array(
				'label'     => esc_html__( 'Del (Old Price) Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-price del, {{WRAPPER}} .eel-pgl-price del .amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'price_margin',
			array(
				'label'      => esc_html__( 'Margin', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_excerpt() {
		$this->start_controls_section(
			'_pgl_style_excerpt',
			array(
				'label'     => esc_html__( 'Excerpt', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_excerpt' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .eel-pgl-excerpt',
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-excerpt' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'excerpt_margin',
			array(
				'label'      => esc_html__( 'Margin', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_addtocart() {
		$this->start_controls_section(
			'_pgl_style_addtocart',
			array(
				'label'     => esc_html__( 'Add to Cart Button', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_add_to_cart' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'btn_typography',
				'selector' => '{{WRAPPER}} .eel-pgl-addtocart, {{WRAPPER}} .eel-pgl-addtocart .added_to_cart',
			)
		);

		$this->start_controls_tabs( '_pgl_btn_tabs' );

		$this->start_controls_tab(
			'_pgl_btn_normal',
			array(
				'label' => esc_html__( 'Normal', 'easy-elements' ),
			)
		);

		$this->add_control(
			'btn_color',
			array(
				'label'     => esc_html__( 'Text Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-addtocart, {{WRAPPER}} .eel-pgl-addtocart .added_to_cart' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-addtocart' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_pgl_btn_hover',
			array(
				'label' => esc_html__( 'Hover', 'easy-elements' ),
			)
		);

		$this->add_control(
			'btn_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-addtocart:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn_bg_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-addtocart:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'btn_border',
				'selector'  => '{{WRAPPER}} .eel-pgl-addtocart',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'btn_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-addtocart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-addtocart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'btn_icon_heading',
			array(
				'label'     => esc_html__( 'Hover Icon', 'easy-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'btn_show_hover_icon',
			array(
				'label'        => esc_html__( 'Show Icon on Hover', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'easy-elements' ),
				'label_off'    => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors_dictionary' => array(
					'yes' => 'inline-block',
					''    => 'none',
				),
				'selectors'    => array(
					'{{WRAPPER}} .eel-pgl-addtocart::after' => 'display: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 32,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-addtocart::after' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-pgl-addtocart:hover::after, {{WRAPPER}} .eel-pgl-addtocart:focus::after' => 'width: calc({{SIZE}}{{UNIT}} + 2px);',
				),
				'condition'  => array(
					'btn_show_hover_icon' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_badge() {
		$this->start_controls_section(
			'_pgl_style_badge',
			array(
				'label'     => esc_html__( 'Sale Badge', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_sale_badge' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .eel-pgl-badge',
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => esc_html__( 'Text Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-badge' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-pgl-badge' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_padding',
			array(
				'label'      => esc_html__( 'Padding', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'badge_position_heading',
			array(
				'label'     => esc_html__( 'Position', 'easy-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'badge_position_top',
			array(
				'label'      => esc_html__( 'Top Offset', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-badge' => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_position_left',
			array(
				'label'      => esc_html__( 'Left Offset', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .eel-pgl-badge' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Build WP_Query args from settings.
	 *
	 * @param array $settings Widget settings.
	 * @return array
	 */
	protected function build_query_args( $settings ) {
		$query_type        = isset( $settings['query_type'] ) ? $settings['query_type'] : 'recent';
		$per_page          = isset( $settings['products_per_page'] ) ? absint( $settings['products_per_page'] ) : 6;
		$orderby           = isset( $settings['orderby'] ) ? sanitize_key( $settings['orderby'] ) : 'date';
		$order             = isset( $settings['order'] ) && 'asc' === $settings['order'] ? 'ASC' : 'DESC';
		$hide_out_of_stock = ! empty( $settings['hide_out_of_stock'] ) && 'yes' === $settings['hide_out_of_stock'];

		if ( $per_page < 1 ) {
			$per_page = 6;
		}

		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => $per_page,
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => true,
		);

		if ( 'custom' === $query_type ) {
			$ids = array();
			if ( ! empty( $settings['manual_products'] ) && is_array( $settings['manual_products'] ) ) {
				$ids = array_map( 'absint', $settings['manual_products'] );
			}

			if ( empty( $ids ) ) {
				$args['post__in'] = array( 0 );
			} else {
				$args['post__in']       = $ids;
				$args['posts_per_page'] = count( $ids );
				$args['orderby']        = 'post__in';
			}

			return $args;
		}

		$args['orderby'] = $orderby;
		$args['order']   = $order;

		// Always exclude products hidden from catalog/search.
		// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Required to filter by product visibility/category like WooCommerce core does.
		$args['tax_query'] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => array( 'exclude-from-catalog' ),
				'operator' => 'NOT IN',
			),
		);

		if ( ! empty( $settings['product_categories'] ) && is_array( $settings['product_categories'] ) ) {
			$cats = array_map( 'sanitize_text_field', $settings['product_categories'] );
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $cats,
			);
		}

		if ( ! empty( $settings['exclude_products'] ) && is_array( $settings['exclude_products'] ) ) {
			// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in -- User-configured exclude list, expected to be small.
			$args['post__not_in'] = array_map( 'absint', $settings['exclude_products'] );
		}

		$meta_query = array();

		if ( $hide_out_of_stock ) {
			$meta_query[] = array(
				'key'     => '_stock_status',
				'value'   => 'instock',
				'compare' => '=',
			);
		}

		if ( 'featured' === $query_type ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN',
			);
		}

		if ( 'sale' === $query_type ) {
			if ( function_exists( 'wc_get_product_ids_on_sale' ) ) {
				$sale_ids = wc_get_product_ids_on_sale();
				if ( empty( $sale_ids ) ) {
					$args['post__in'] = array( 0 );
				} else {
					$args['post__in'] = $sale_ids;
				}
			}
		}

		if ( ! empty( $meta_query ) ) {
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Required to honour the "hide out of stock" widget setting like WooCommerce core does.
			$args['meta_query'] = $meta_query;
		}

		return $args;
	}

	protected function render() {
		if ( ! function_exists( 'WC' ) ) {
			if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				echo '<div class="eel-pgl-notice">' . esc_html__( 'WooCommerce is required for this widget.', 'easy-elements' ) . '</div>';
			}
			return;
		}

		$settings = $this->get_settings_for_display();
		$skin     = ! empty( $settings['skin'] ) ? sanitize_key( $settings['skin'] ) : 'default';

		$args  = $this->build_query_args( $settings );
		$query = new \WP_Query( $args );

		if ( ! $query->have_posts() ) {
			echo '<div class="eel-pgl-empty">' . esc_html__( 'No products found.', 'easy-elements' ) . '</div>';
			return;
		}

		$title_tag       = isset( $settings['title_tag'] ) ? sanitize_key( $settings['title_tag'] ) : 'h3';
		$allowed_tags    = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span' );
		if ( ! in_array( $title_tag, $allowed_tags, true ) ) {
			$title_tag = 'h3';
		}

		$image_size = 'woocommerce_thumbnail';
		if ( ! empty( $settings['image_size'] ) && is_string( $settings['image_size'] ) ) {
			$image_size = $settings['image_size'];
		}

		$show_sale_badge  = 'yes' === ( isset( $settings['show_sale_badge'] ) ? $settings['show_sale_badge'] : 'yes' );
		$sale_badge_text  = isset( $settings['sale_badge_text'] ) ? $settings['sale_badge_text'] : esc_html__( 'Sale', 'easy-elements' );
		$show_price       = 'yes' === ( isset( $settings['show_price'] ) ? $settings['show_price'] : 'yes' );
		$show_excerpt     = 'yes' === ( isset( $settings['show_excerpt'] ) ? $settings['show_excerpt'] : '' );
		$excerpt_length   = isset( $settings['excerpt_length'] ) ? absint( $settings['excerpt_length'] ) : 15;
		$show_add_to_cart = 'yes' === ( isset( $settings['show_add_to_cart'] ) ? $settings['show_add_to_cart'] : 'yes' );
		$button_text      = isset( $settings['add_to_cart_text'] ) ? $settings['add_to_cart_text'] : esc_html__( 'Add to Cart', 'easy-elements' );

		$wrapper_classes = array(
			'eel-pgl',
			'eel-pgl-skin-' . $skin,
		);

		echo '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '">';
		echo '<div class="eel-pgl-grid">';

		while ( $query->have_posts() ) {
			$query->the_post();

			global $product; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- WooCommerce core global, expected by template hooks.
			$product = wc_get_product( get_the_ID() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- WooCommerce core global, expected by template hooks.

			if ( ! $product instanceof \WC_Product ) {
				continue;
			}

			$permalink = get_permalink();

			echo '<div class="eel-pgl-item product' . ( $product->is_on_sale() ? ' eel-pgl-on-sale' : '' ) . '">';

			echo '<div class="eel-pgl-image">';

			if ( $show_sale_badge && $product->is_on_sale() ) {
				echo '<span class="eel-pgl-badge">' . esc_html( $sale_badge_text ) . '</span>';
			}

			echo '<a href="' . esc_url( $permalink ) . '" aria-label="' . esc_attr( get_the_title() ) . '">';
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( $image_size, array( 'loading' => 'lazy' ) );
			} else {
				echo wp_kses_post( wc_placeholder_img( $image_size ) );
			}
			echo '</a>';

			echo '</div>';

			echo '<div class="eel-pgl-content">';

			printf(
				'<%1$s class="eel-pgl-title"><a href="%2$s">%3$s</a></%1$s>',
				esc_attr( $title_tag ),
				esc_url( $permalink ),
				esc_html( get_the_title() )
			);

			if ( $show_price ) {
				$price_html = $product->get_price_html();
				if ( ! empty( $price_html ) ) {
					echo '<div class="eel-pgl-price">' . wp_kses_post( $price_html ) . '</div>';
				}
			}

			if ( $show_excerpt ) {
				$excerpt = $product->get_short_description();
				if ( empty( $excerpt ) ) {
					$excerpt = get_the_excerpt();
				}

				if ( ! empty( $excerpt ) ) {
					$excerpt_text = wp_trim_words( wp_strip_all_tags( $excerpt ), $excerpt_length, '…' );
					echo '<div class="eel-pgl-excerpt">' . esc_html( $excerpt_text ) . '</div>';
				}
			}

			if ( $show_add_to_cart ) {
				$this->render_add_to_cart_button( $product, $button_text );
			}

			echo '</div>';
			echo '</div>';
		}

		echo '</div>';
		echo '</div>';

		wp_reset_postdata();
	}

	/**
	 * Render an Add-to-Cart button matching WooCommerce AJAX behaviour.
	 *
	 * @param \WC_Product $product     Product object.
	 * @param string      $button_text Fallback button text.
	 */
	protected function render_add_to_cart_button( $product, $button_text ) {
		$classes = array(
			'eel-pgl-addtocart',
			'button',
			'product_type_' . $product->get_type(),
		);

		if ( $product->is_purchasable() && $product->is_in_stock() ) {
			$classes[] = 'add_to_cart_button';
			if ( $product->supports( 'ajax_add_to_cart' ) ) {
				$classes[] = 'ajax_add_to_cart';
			}
		}

		if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
			$label = $button_text;
		} else {
			$label = $product->add_to_cart_text();
		}

		$attributes = array(
			'href'             => $product->add_to_cart_url(),
			'data-quantity'    => '1',
			'data-product_id'  => $product->get_id(),
			'data-product_sku' => $product->get_sku(),
			'aria-label'       => $product->add_to_cart_description(),
			'rel'              => 'nofollow',
			'class'            => implode( ' ', array_unique( $classes ) ),
		);

		if ( function_exists( 'wc_implode_html_attributes' ) ) {
			$attr_html = wc_implode_html_attributes( $attributes );
		} else {
			$parts = array();
			foreach ( $attributes as $attr => $value ) {
				$parts[] = sanitize_key( $attr ) . '="' . esc_attr( $value ) . '"';
			}
			$attr_html = implode( ' ', $parts );
		}

		echo '<a ' . $attr_html . '>' . esc_html( $label ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
