<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Free_Post_Meta_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eel-post-meta';
	}

	public function get_title() {
		return __( 'Post Meta', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-meta';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
		return [ 'post', 'meta', 'author', 'date', 'category', 'comments', 'blog' ];
	}

	public function get_style_depends() {
        return [
            'eel-post-meta',
        ];
    }


	protected function register_controls() {
		// Content Tab
		$this->register_content_controls();
		
		// Style Tab
		$this->register_style_controls();
		
		
	}

	protected function register_content_controls() {
		// General Settings Section
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'page_type',
			[
				'label' => esc_html__( 'Page Type', 'easy-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'current',
				'options' => [
					'current' => esc_html__( 'Current Page', 'easy-elements' ),
					'archive' => esc_html__( 'Archive Page', 'easy-elements' ),
					'single' => esc_html__( 'Single Post/Page', 'easy-elements' ),
					'home' => esc_html__( 'Home Page', 'easy-elements' ),
				],
				'description' => esc_html__( 'Select which page type to display meta for', 'easy-elements' ),
			]
		);

		$this->add_control(
			'show_meta',
			[
				'label' => esc_html__( 'Show Meta', 'easy-elements' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => [
					'date'      => esc_html__( 'Date', 'easy-elements' ),
					'author'    => esc_html__( 'Author', 'easy-elements' ),
					'category'  => esc_html__( 'Category', 'easy-elements' ),
					'comments'  => esc_html__( 'Comments', 'easy-elements' ),
				],
				'default' => ['date', 'author'],
				'label_block' => true,
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Date Settings Section
		$this->start_controls_section(
			'section_date',
			[
				'label' => esc_html__( 'Date', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_date_icon',
			[
				'label' => esc_html__( 'Show Icon', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'date_icon',
			[
				'label' => esc_html__( 'Icon', 'easy-elements' ),
				'type' => Controls_Manager::ICONS,
				'condition' => [
					'show_date_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'date_format',
			[
				'label' => esc_html__( 'Date Format', 'easy-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'easy-elements' ),
					'F j, Y' => esc_html__( 'January 1, 2024', 'easy-elements' ),
					'j F Y' => esc_html__( '1 January 2024', 'easy-elements' ),
					'Y-m-d' => esc_html__( '2024-01-01', 'easy-elements' ),
					'm/d/Y' => esc_html__( '01/01/2024 (US)', 'easy-elements' ),
					'd/m/Y' => esc_html__( '01/01/2024 (EU)', 'easy-elements' ),
					'custom' => esc_html__( 'Custom', 'easy-elements' ),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_date_format',
			[
				'label' => esc_html__( 'Custom Format', 'easy-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'F j, Y',
				'description' => esc_html__( 'Enter a custom date format. See PHP date() function documentation.', 'easy-elements' ),
				'condition' => [
					'date_format' => 'custom',
				],
			]
		);

		$this->end_controls_section();

		// Author Settings Section
		$this->start_controls_section(
			'section_author',
			[
				'label' => esc_html__( 'Author', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_author_icon',
			[
				'label' => esc_html__( 'Show Icon', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'author_icon',
			[
				'label' => esc_html__( 'Icon', 'easy-elements' ),
				'type' => Controls_Manager::ICONS,
				'condition' => [
					'show_author_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_by_label',
			[
				'label'        => esc_html__( 'Show "By" Label', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'easy-elements' ),
				'label_off'    => esc_html__( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_link_enable',
			[
				'label'        => esc_html__( 'Author Link', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'easy-elements' ),
				'label_off'    => esc_html__( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->end_controls_section();

		// Category Settings Section
		$this->start_controls_section(
			'section_category',
			[
				'label' => esc_html__( 'Category', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_category_icon',
			[
				'label' => esc_html__( 'Show Icon', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'category_icon',
			[
				'label'   => esc_html__( 'Icon', 'easy-elements' ),
				'type'    => Controls_Manager::ICONS,
				'condition' => [
					'show_category_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_separator',
			[
				'label' => esc_html__( 'Separator', 'easy-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => ', ',
				'description' => esc_html__( 'Separator between categories', 'easy-elements' ),
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Comments Settings Section
		$this->start_controls_section(
			'section_comments',
			[
				'label' => esc_html__( 'Comments', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_comments_icon',
			[
				'label' => esc_html__( 'Show Icon', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'comments_icon',
			[
				'label' => esc_html__( 'Icon', 'easy-elements' ),
				'type' => Controls_Manager::ICONS,
				'condition' => [
					'show_comments_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'comments_text',
			[
				'label' => esc_html__( 'Comments Text', 'easy-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default (Comment/Comments)', 'easy-elements' ),
					'custom' => esc_html__( 'Custom', 'easy-elements' ),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_comments_text',
			[
				'label' => esc_html__( 'Custom Text', 'easy-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Comment',
				/* translators: %s: Number of comments */
				'description' => esc_html__( 'Use %s for the number', 'easy-elements' ),
				'condition' => [
					'comments_text' => 'custom',
				],
			]
		);

		$this->end_controls_section();

		// Separator Settings Section
		$this->start_controls_section(
			'section_separator',
			[
				'label' => esc_html__( 'Separator', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'eel_separator',
			[
				'label' => esc_html__( 'Enable Separator', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'default' => '',
			]
		);

		$this->add_control(
			'separator_type',
			[
				'label' => esc_html__( 'Separator Type', 'easy-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'line',
				'options' => [
					'dot' => esc_html__( 'Dot', 'easy-elements' ),
					'line' => esc_html__( 'Line', 'easy-elements' ),
				],
				'condition' => [
					'eel_separator' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		// Meta Container Style
		$this->start_controls_section(
			'section_meta_style',
			[
				'label' => esc_html__( 'Meta Container', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'easyel_post_meta_margin',
			[
				'label'      => esc_html__( 'Margin', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eel--blog-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_padding',
			[
				'label'      => esc_html__( 'Padding', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eel--blog-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'meta_border',
				'label' => esc_html__( 'Border', 'easy-elements' ),
				'selector' => '{{WRAPPER}} .eel--blog-meta',
			]
		);

		$this->add_control(
			'meta_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eel--blog-meta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'meta_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'easy-elements' ),
				'selector' => '{{WRAPPER}} .eel--blog-meta',
			]
		);

		$this->end_controls_section();

		// Meta Items Style
		$this->start_controls_section(
			'section_meta_items_style',
			[
				'label' => esc_html__( 'Meta Items', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'meta_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .eel--blog-meta li, {{WRAPPER}} .eel--blog-meta li *' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'meta_typography',
				'label'    => esc_html__( 'Typography', 'easy-elements' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .eel--blog-meta li',
			]
		);

		$this->add_responsive_control(
			'meta_items_spacing',
			[
				'label' => esc_html__( 'Margin Right', 'easy-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel--blog-meta li' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_items_spacing_left',
			[
				'label' => esc_html__( 'Padding Left', 'easy-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
                    '{{WRAPPER}} .eel--separator--line li + li' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Icons Style
		$this->start_controls_section(
			'section_icons_style',
			[
				'label' => esc_html__( 'Icons', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel--blog-meta i, {{WRAPPER}} .eel--blog-meta svg' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel--blog-meta svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'easy-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel--blog-meta i, {{WRAPPER}} .eel--blog-meta svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_icon_offset',
			[
				'label' => esc_html__( 'Icon Offset Vertical', 'easy-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -50,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel--blog-meta svg, {{WRAPPER}} .eel--blog-meta i' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
				],
			]
		);

		$this->add_control(
			'icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'easy-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel--blog-meta i, {{WRAPPER}} .eel--blog-meta svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Separator Style
		$this->start_controls_section(
			'section_separator_style',
			[
				'label' => esc_html__( 'Separator', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eel_separator' => 'yes',
				],
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => esc_html__( 'Separator Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel--separator--yes li + li::before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'separator_size',
			[
				'label' => esc_html__( 'Separator Size', 'easy-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel--separator--yes li + li::before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'separator_type' => 'dot',
                ],
			]
		);

		$this->add_responsive_control(
			'separator_offset',
			[
				'label' => esc_html__( 'Separator Offset', 'easy-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -50,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel--separator--yes li + li::before' => 'top: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'separator_type' => 'dot',
                ],
			]
		);

		$this->end_controls_section();

		// Links Style
		$this->start_controls_section(
			'section_links_style',
			[
				'label' => esc_html__( 'Links', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'links_style_tabs' );

		$this->start_controls_tab(
			'links_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'easy-elements' ),
			]
		);

		$this->add_control(
			'links_color',
			[
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel--blog-meta a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'links_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'easy-elements' ),
			]
		);

		$this->add_control(
			'links_hover_color',
			[
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel--blog-meta a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render_blog_meta( $settings ) {
        if ( empty( $settings['show_meta'] ) || ! is_array( $settings['show_meta'] ) ) {
            return;
        }

        $show_meta = $settings['show_meta'];
        $eel_separator = (! empty($settings['eel_separator']) && $settings['eel_separator'] == 'yes') ? 'eel--separator--yes' : '';
        $page_type = ! empty($settings['page_type']) ? $settings['page_type'] : 'current';
        $separator_type = ! empty($settings['separator_type']) ? $settings['separator_type'] : 'dot';

        // Check if we should display meta based on page type
        if (!$this->should_display_meta($page_type)) {
            return;
        }

        ?>
        <ul class="eel--blog-meta <?php echo esc_attr($eel_separator . ' eel--separator--' . $separator_type); ?>">
            <?php if ( in_array( 'date', $show_meta ) ) : ?>
                <li class="eel--blog-date">
                    <?php
                    if ( ! empty($settings['show_date_icon']) && $settings['show_date_icon'] === 'yes' ) {
                        if ( empty($settings['date_icon']) || empty($settings['date_icon']['value']) ) {
                            echo '<i class="unicon-calendar"></i>';
                        } else {
                            \Elementor\Icons_Manager::render_icon( $settings['date_icon'], [ 'aria-hidden' => 'true' ] );
                        }
                    }
                    
                    $date_format = ! empty($settings['date_format']) ? $settings['date_format'] : 'default';
                    
                    if ($date_format === 'custom' && ! empty($settings['custom_date_format'])) {
                        // Custom format
                        echo esc_html( get_the_date($settings['custom_date_format']) );
                    } elseif ($date_format !== 'default') {
                        // Predefined formats
                        echo esc_html( get_the_date($date_format) );
                    } else {
                        // Default WordPress date format
                        echo esc_html( get_the_date() );
                    }
                    ?> 
                </li>
            <?php endif; ?>

            <?php if ( in_array( 'author', $show_meta ) ) : ?>
                <li class="eel--blog-author">
                    <?php 
                    if ( ! empty($settings['show_author_icon']) && $settings['show_author_icon'] === 'yes' ) {
                        if ( empty($settings['author_icon']) || empty($settings['author_icon']['value']) ) {
                            echo '<i class="unicon-user"></i>';
                        } else {
                            \Elementor\Icons_Manager::render_icon( $settings['author_icon'], [ 'aria-hidden' => 'true' ] );
                        }
                    }

                    if ( ! empty($settings['show_by_label']) && $settings['show_by_label'] === 'yes' ) {
                        echo '<em class="eel--meta-by">' . esc_html__( 'By', 'easy-elements' ) . '</em> ';
                    }

                    // Get author information - more reliable method
                    global $post;
                    $author_id = 0;
                    $author_name = '';
                    
                    // Try multiple methods to get author data
                    if ( $post && isset($post->post_author) ) {
                        $author_id = $post->post_author;
                    } elseif ( get_the_ID() ) {
                        $author_id = get_post_field( 'post_author', get_the_ID() );
                    }
                    
                    if ( $author_id ) {
                        $author_name = get_the_author_meta( 'display_name', $author_id );
                        $author_url = get_author_posts_url( $author_id );

                        if ( ! empty($settings['author_link_enable']) && $settings['author_link_enable'] === 'yes' ) {
                            echo '<a class="eel--meta-author" href="' . esc_url( $author_url ) . '">' . esc_html( $author_name ) . '</a>';
                        } else {
                            echo '<span class="eel--meta-author">' . esc_html( $author_name ) . '</span>';
                        }
                    } else {
                        // Fallback if no author data
                        echo '<span class="eel--meta-author">' . esc_html__( 'Unknown Author', 'easy-elements' ) . '</span>';
                    }
                    ?>
                </li>
            <?php endif; ?>

            <?php if ( in_array( 'category', $show_meta ) ) : ?>
                <li class="eel--blog-cat">
                    <?php
                    if ( ! empty($settings['show_category_icon']) && $settings['show_category_icon'] === 'yes' ) {
                        if ( empty($settings['category_icon']) || empty($settings['category_icon']['value']) ) {
                            echo '<i class="unicon-folder"></i>';
                        } else {
                            \Elementor\Icons_Manager::render_icon( $settings['category_icon'], [ 'aria-hidden' => 'true' ] );
                        }
                    }
                    
                    $separator = ! empty($settings['category_separator']) ? $settings['category_separator'] : ', ';
                    $post_id = null;

                    if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                        // Try to get the preview post ID
                        $post_id = \Elementor\Plugin::$instance->preview->get_post_id();
                        // If not a real post, fallback to a sample post with categories
                        if ( ! $post_id || get_post_type($post_id) !== 'post' ) {
                            $sample = get_posts([
                                'numberposts' => 1,
                                'category__not_in' => get_option('default_category'), // avoid Uncategorized
                                'post_status' => 'publish',
                            ]);
                            if ( ! empty($sample) ) {
                                $post_id = $sample[0]->ID;
                            }
                        }
                    } else {
                        $post_id = get_the_ID();
                    }

                    $categories = get_the_category($post_id);
                    if ( ! empty( $categories ) ) {
                        $cat_links = [];
                        foreach ( $categories as $cat ) {
                            $cat_links[] = '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a>';
                        }
                        echo implode( $separator, $cat_links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                    ?>
                </li>
            <?php endif; ?>

            <?php if ( in_array( 'comments', $show_meta ) ) : ?>
                <li class="eel--blog-comments">
                    <?php
                    if ( ! empty($settings['show_comments_icon']) && $settings['show_comments_icon'] === 'yes' ) {
                        if ( empty($settings['comments_icon']) || empty($settings['comments_icon']['value']) ) {
                            echo '<i class="unicon-forum"></i>';
                        } else {
                            \Elementor\Icons_Manager::render_icon( $settings['comments_icon'], [ 'aria-hidden' => 'true' ] );
                        }
                    }
                    
                    $comments_number = get_comments_number();
                    $comments_text = ! empty($settings['comments_text']) ? $settings['comments_text'] : 'default';
                    
                    if ($comments_text === 'custom' && ! empty($settings['custom_comments_text'])) {
                        $text = str_replace('%s', $comments_number, $settings['custom_comments_text']);
                        $text = $comments_number . ' ' . $text;
                    } else {
                        $text = $comments_number . ' ' . _n( 'Comment', 'Comments', $comments_number, 'easy-elements' );
                    }
                    
                    echo '<a href="' . esc_url( get_comments_link() ) . '">' . esc_html( $text ) . '</a>';
                    ?>
                </li>
            <?php endif; ?>
        </ul>
        <?php
    }

    /**
     * Check if meta should be displayed based on page type
     */
    protected function should_display_meta($page_type) {
        switch ($page_type) {
            case 'archive':
                return is_archive() || is_category() || is_tag() || is_author() || is_date() || is_tax();
            
            case 'single':
                return is_single() || is_page();
            
            case 'home':
                return is_home() || is_front_page();
            
            case 'current':
            default:
                return true; // Always show for current page type
        }
    }

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->render_blog_meta( $settings );
	}
}
