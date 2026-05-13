<?php
/**
 * Copyright Widget for Easy Elements.
 *
 * Displays a copyright notice with an auto-updating year, optional year range,
 * site name placeholder support, and an optional link.
 *
 * @package Easyel\EasyElements\Widgets
 * @since   1.0.0
 */

namespace Easyel\EasyElements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Easyel_Copyright_Widget
 *
 * Renders a footer-friendly copyright string with placeholders.
 */
class Easyel_Copyright_Widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'eel-copyright';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Copyright', 'easy-elements' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'easyicon easyelIcon-copyright';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'easyelements_category' );
	}

	/**
	 * Get widget keywords (for search inside Elementor panel).
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'copyright', 'footer', 'year', 'company', 'rights', 'reserved' );
	}

	/**
	 * Style assets the widget depends on.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'eel-copyright' );
	}

	/**
	 * Register Elementor controls.
	 *
	 * @return void
	 */
	protected function register_controls() {

		/* ---------- Content Tab ---------- */
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Copyright Settings', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'copyright_text',
			array(
				'label'       => esc_html__( 'Copyright Text', 'easy-elements' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'label_block' => true,
				'default'     => __( '&copy; {year} {site_title}. All Rights Reserved.', 'easy-elements' ),
				'description' => esc_html__( 'Available placeholders: {year} {site_title} {site_url}', 'easy-elements' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'year_format',
			array(
				'label'       => esc_html__( 'Year Format', 'easy-elements' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'current',
				'options'     => array(
					'current' => esc_html__( 'Current Year (e.g. 2026)', 'easy-elements' ),
					'range'   => esc_html__( 'Year Range (e.g. 2020 - 2026)', 'easy-elements' ),
				),
				'description' => esc_html__( 'Choose how the {year} placeholder is replaced.', 'easy-elements' ),
			)
		);

		$this->add_control(
			'start_year',
			array(
				'label'       => esc_html__( 'Start Year', 'easy-elements' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1900,
				'max'         => (int) gmdate( 'Y' ),
				'step'        => 1,
				'default'     => (int) gmdate( 'Y' ),
				'placeholder' => '2020',
				'condition'   => array(
					'year_format' => 'range',
				),
			)
		);

		$this->add_control(
			'year_separator',
			array(
				'label'       => esc_html__( 'Year Separator', 'easy-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => ' - ',
				'placeholder' => ' - ',
				'condition'   => array(
					'year_format' => 'range',
				),
			)
		);

		$this->add_control(
			'enable_link',
			array(
				'label'        => esc_html__( 'Link Site Name', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'easy-elements' ),
				'label_off'    => esc_html__( 'No', 'easy-elements' ),
				'default'      => '',
				'description'  => esc_html__( 'Wrap the {site_title} placeholder with a link.', 'easy-elements' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'link_url',
			array(
				'label'       => esc_html__( 'Custom Link', 'easy-elements' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'Leave empty to use Site URL', 'easy-elements' ),
				'default'     => array(
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				),
				'condition'   => array(
					'enable_link' => 'yes',
				),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_responsive_control(
			'text_align',
			array(
				'label'     => esc_html__( 'Alignment', 'easy-elements' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .eel-copyright' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'html_tag',
			array(
				'label'   => esc_html__( 'HTML Tag', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'p',
				'options' => array(
					'p'    => 'P',
					'div'  => 'DIV',
					'span' => 'SPAN',
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
				),
			)
		);

		$this->end_controls_section();

		/* ---------- Style Tab ---------- */
		$this->start_controls_section(
			'style_section',
			array(
				'label' => esc_html__( 'Copyright Style', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .eel-copyright' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .eel-copyright',
			)
		);

		$this->add_control(
			'link_heading',
			array(
				'label'     => esc_html__( 'Link', 'easy-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'enable_link' => 'yes',
				),
			)
		);

		$this->start_controls_tabs(
			'link_color_tabs',
			array(
				'condition' => array(
					'enable_link' => 'yes',
				),
			)
		);

		$this->start_controls_tab(
			'link_normal_tab',
			array(
				'label'     => esc_html__( 'Normal', 'easy-elements' ),
				'condition' => array(
					'enable_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-copyright a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_hover_tab',
			array(
				'label'     => esc_html__( 'Hover', 'easy-elements' ),
				'condition' => array(
					'enable_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => esc_html__( 'Link Hover Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eel-copyright a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'      => 'text_shadow',
				'label'     => esc_html__( 'Text Shadow', 'easy-elements' ),
				'selector'  => '{{WRAPPER}} .eel-copyright',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => esc_html__( 'Padding', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eel-copyright' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'margin',
			array(
				'label'      => esc_html__( 'Margin', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eel-copyright' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Build the year string based on settings.
	 *
	 * Uses date_i18n() so the year flips at midnight in the site's
	 * configured timezone, not UTC.
	 *
	 * @param array $settings Widget settings.
	 * @return string
	 */
	private function get_year_string( $settings ) {
		$current_year = (int) date_i18n( 'Y' );
		$format       = isset( $settings['year_format'] ) ? $settings['year_format'] : 'current';

		if ( 'range' === $format ) {
			$start_year = isset( $settings['start_year'] ) ? absint( $settings['start_year'] ) : $current_year;
			$separator  = isset( $settings['year_separator'] ) && '' !== $settings['year_separator']
				? sanitize_text_field( $settings['year_separator'] )
				: ' - ';

			if ( $start_year > 0 && $start_year < $current_year ) {
				return $start_year . $separator . $current_year;
			}
		}

		return (string) $current_year;
	}

	/**
	 * Resolve the link URL — falls back to home_url() when none provided.
	 *
	 * @param array $settings Widget settings.
	 * @return string
	 */
	private function get_link_url( $settings ) {
		$url = isset( $settings['link_url']['url'] ) ? trim( (string) $settings['link_url']['url'] ) : '';
		if ( '' === $url ) {
			$url = home_url( '/' );
		}
		return $url;
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$raw_text   = isset( $settings['copyright_text'] ) ? $settings['copyright_text'] : '';
		$year_str   = $this->get_year_string( $settings );
		$site_title = get_bloginfo( 'name' );
		$site_url   = home_url( '/' );

		// Build site title — optionally wrapped in a link.
		$site_title_html = esc_html( $site_title );

		if ( ! empty( $settings['enable_link'] ) && 'yes' === $settings['enable_link'] ) {
			$href        = esc_url( $this->get_link_url( $settings ) );
			$is_external = ! empty( $settings['link_url']['is_external'] );
			$nofollow    = ! empty( $settings['link_url']['nofollow'] );

			$rel_parts = array();
			if ( $is_external ) {
				$rel_parts[] = 'noopener';
				$rel_parts[] = 'noreferrer';
			}
			if ( $nofollow ) {
				$rel_parts[] = 'nofollow';
			}

			$target  = $is_external ? '_blank' : '_self';
			$rel_str = ! empty( $rel_parts ) ? ' rel="' . esc_attr( implode( ' ', $rel_parts ) ) . '"' : '';

			$site_title_html = sprintf(
				'<a href="%1$s" target="%2$s"%3$s>%4$s</a>',
				$href,
				esc_attr( $target ),
				$rel_str,
				esc_html( $site_title )
			);
		}

		// Allow a small subset of safe inline HTML inside the user-supplied text.
		$allowed_html = array(
			'a'      => array(
				'href'   => true,
				'target' => true,
				'rel'    => true,
				'title'  => true,
			),
			'br'     => array(),
			'span'   => array( 'class' => true ),
			'strong' => array(),
			'em'     => array(),
			'b'      => array(),
			'i'      => array(),
		);

		$safe_text = wp_kses( (string) $raw_text, $allowed_html );

		// Replace placeholders AFTER kses so the generated link tag is preserved.
		$replacements = array(
			'{year}'       => esc_html( $year_str ),
			'{site_title}' => $site_title_html,
			'{site_url}'   => esc_url( $site_url ),
		);

		$output = strtr( $safe_text, $replacements );

		// Whitelist HTML tag.
		$allowed_tags = array( 'p', 'div', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
		$tag          = isset( $settings['html_tag'] ) && in_array( $settings['html_tag'], $allowed_tags, true )
			? $settings['html_tag']
			: 'p';

		printf(
			'<%1$s class="eel-copyright">%2$s</%1$s>',
			esc_attr( $tag ),
			wp_kses( $output, $allowed_html ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
}
