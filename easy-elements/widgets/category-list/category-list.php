<?php
namespace Easyel\EasyElements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || die();

class Easyel_Category_List_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eel-category-list';
	}

	public function get_title() {
		return esc_html__( 'Category List', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon eicon-folder';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
		return [ 'category', 'categories', 'taxonomy', 'term', 'list', 'grid' ];
	}

	public function get_style_depends() {
		return [ 'eel-category-list' ];
	}

	/**
	 * Public post types that have at least one taxonomy attached.
	 *
	 * @return array [ post_type => label ]
	 */
	protected function get_post_type_options() {
		$exclude    = [ 'attachment', 'revision', 'nav_menu_item' ];
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		$options    = [];

		foreach ( $post_types as $post_type => $post_type_obj ) {
			if ( in_array( $post_type, $exclude, true ) ) {
				continue;
			}
			$taxonomies = get_object_taxonomies( $post_type, 'names' );
			if ( ! empty( $taxonomies ) ) {
				$options[ $post_type ] = $post_type_obj->label;
			}
		}

		return $options;
	}

	/**
	 * Public taxonomies registered for a given post type.
	 *
	 * @param string $post_type Post type slug.
	 * @return array [ taxonomy => label ]
	 */
	protected function get_taxonomy_options( $post_type ) {
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$options    = [];

		foreach ( $taxonomies as $taxonomy => $taxonomy_obj ) {
			if ( ! empty( $taxonomy_obj->public ) ) {
				$options[ $taxonomy ] = $taxonomy_obj->label;
			}
		}

		return $options;
	}

	protected function register_controls() {

		/* ---------------------------------------------------------------
		 * CONTENT — Query
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'Query', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$post_type_options = $this->get_post_type_options();
		$default_post_type = array_key_exists( 'post', $post_type_options ) ? 'post' : ( ! empty( $post_type_options ) ? array_keys( $post_type_options )[0] : '' );

		$this->add_control(
			'post_type',
			[
				'label'   => esc_html__( 'Post Type', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $post_type_options,
				'default' => $default_post_type,
			]
		);

		// One taxonomy selector per post type, shown only when that post type
		// is selected. This gives a clean cascade without AJAX so the user can
		// show categories (or any taxonomy) of any post type.
		foreach ( $post_type_options as $post_type => $label ) {
			$tax_options = $this->get_taxonomy_options( $post_type );
			$default_tax = array_key_exists( 'category', $tax_options ) ? 'category' : ( ! empty( $tax_options ) ? array_keys( $tax_options )[0] : '' );

			$this->add_control(
				'taxonomy_' . $post_type,
				[
					'label'     => esc_html__( 'Taxonomy', 'easy-elements' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => $tax_options,
					'default'   => $default_tax,
					'condition' => [
						'post_type' => $post_type,
					],
				]
			);
		}

		$this->add_control(
			'number',
			[
				'label'       => esc_html__( 'Number of Items', 'easy-elements' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'min'         => 0,
				'description' => esc_html__( 'Set 0 to show all.', 'easy-elements' ),
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order By', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => [
					'name'  => esc_html__( 'Name', 'easy-elements' ),
					'count' => esc_html__( 'Count', 'easy-elements' ),
					'slug'  => esc_html__( 'Slug', 'easy-elements' ),
					'term_id' => esc_html__( 'ID', 'easy-elements' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Order', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => [
					'ASC'  => esc_html__( 'Ascending', 'easy-elements' ),
					'DESC' => esc_html__( 'Descending', 'easy-elements' ),
				],
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label'        => esc_html__( 'Hide Empty', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'easy-elements' ),
				'label_off'    => esc_html__( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'show_count',
			[
				'label'        => esc_html__( 'Show Count', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'easy-elements' ),
				'label_off'    => esc_html__( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_icon',
			[
				'label'        => esc_html__( 'Show Icon', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'easy-elements' ),
				'label_off'    => esc_html__( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'cat_icon',
			[
				'label'     => esc_html__( 'Icon', 'easy-elements' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'far fa-folder',
					'library' => 'fa-regular',
				],
				'condition' => [
					'show_icon' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * CONTENT — Layout
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout_category',
			[
				'label'   => esc_html__( 'Layout', 'easy-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'list' => [
						'title' => esc_html__( 'List', 'easy-elements' ),
						'icon'  => 'eicon-editor-list-ul',
					],
					'grid' => [
						'title' => esc_html__( 'Grid', 'easy-elements' ),
						'icon'  => 'eicon-gallery-grid',
					],
				],
				'default' => 'list',
				'toggle'  => false,
			]
		);

		$this->add_responsive_control(
			'cat_grid_columns',
			[
				'label'          => esc_html__( 'Columns', 'easy-elements' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'description'    => esc_html__( 'Applies when Layout is set to Grid.', 'easy-elements' ),
				'selectors'      => [
					'{{WRAPPER}} .eel-cat-layout-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
				'condition'      => [
					'layout_category' => 'grid',
				]
			]
		);

		$this->add_responsive_control(
			'items_gap',
			[
				'label'      => esc_html__( 'Items Gap', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 80,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eel-cat-list' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * STYLE — Items
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_items_style',
			[
				'label' => esc_html__( 'Items', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label'      => esc_html__( 'Padding', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eel-cat-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eel-cat-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'item_style_tabs' );

		$this->start_controls_tab(
			'item_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'easy-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'item_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-cat-item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'item_border',
				'selector' => '{{WRAPPER}} .eel-cat-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'item_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'easy-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'item_background_hover',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-cat-item:hover',
			]
		);

		$this->add_control(
			'item_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cat-item:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * STYLE — Icon
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_icon_style',
			[
				'label'     => esc_html__( 'Icon', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => esc_html__( 'Size', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 8,
						'max' => 80,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eel-cat-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cat-icon'                                     => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-cat-icon svg, {{WRAPPER}} .eel-cat-icon svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label'     => esc_html__( 'Hover Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cat-item:hover .eel-cat-icon'                                     => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-cat-item:hover .eel-cat-icon svg, {{WRAPPER}} .eel-cat-item:hover .eel-cat-icon svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eel-cat-item' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * STYLE — Title (Name)
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_name_style',
			[
				'label' => esc_html__( 'Title', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'name_color',
			[
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cat-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'name_color_hover',
			[
				'label'     => esc_html__( 'Hover Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cat-item:hover .eel-cat-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .eel-cat-name',
			]
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * STYLE — Count
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_count_style',
			[
				'label'     => esc_html__( 'Count', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_count' => 'yes',
				],
			]
		);

		$this->add_control(
			'count_color',
			[
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cat-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'count_typography',
				'selector' => '{{WRAPPER}} .eel-cat-count',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$post_type = ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'post';
		$taxonomy  = ! empty( $settings[ 'taxonomy_' . $post_type ] ) ? $settings[ 'taxonomy_' . $post_type ] : '';

		if ( empty( $taxonomy ) ) {
			return;
		}

		$terms = get_terms(
			[
				'taxonomy'   => $taxonomy,
				'hide_empty' => ( 'yes' === $settings['hide_empty'] ),
				'orderby'    => $settings['orderby'],
				'order'      => $settings['order'],
				'number'     => absint( $settings['number'] ),
			]
		);

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		$layout     = in_array( $settings['layout_category'], [ 'list', 'grid' ], true ) ? $settings['layout_category'] : 'list';
		$show_icon  = ( 'yes' === $settings['show_icon'] );
		$show_count = ( 'yes' === $settings['show_count'] );
		?>
		<div class="eel-cat-list eel-cat-layout-<?php echo esc_attr( $layout ); ?>">
			<?php foreach ( $terms as $term ) :
				$term_link = get_term_link( $term );
				if ( is_wp_error( $term_link ) ) {
					continue;
				}
				?>
				<a class="eel-cat-item" href="<?php echo esc_url( $term_link ); ?>">
					<?php if ( $show_icon && ! empty( $settings['cat_icon']['value'] ) ) : ?>
						<span class="eel-cat-icon">
							<?php Icons_Manager::render_icon( $settings['cat_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					<?php endif; ?>
					<span class="eel-cat-name"><?php echo esc_html( $term->name ); ?></span>
					<?php if ( $show_count ) : ?>
						<span class="eel-cat-count">(<?php echo esc_html( $term->count ); ?>)</span>
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
