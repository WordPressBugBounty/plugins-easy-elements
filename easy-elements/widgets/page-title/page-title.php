<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Page_Title_Widget extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'eel-page-title';
	}

	public function get_title() {
		return __( 'Page Title', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-page-title';
	}

	public function get_categories() {
		return [ 'easyelements_header_footer_category' ];
	}

	public function get_keywords() {
        return [ 'page', 'link', 'title', 'text' ];
    }

	public function get_style_depends() {
        return [
            'eel-page-title',
        ];
    }



	protected function register_controls() {

		// =====================================================================
		// CONTENT TAB
		// =====================================================================

		// --- Main Title -----------------------------------------------------
		$this->start_controls_section(
			'section_main_title',
			[
				'label' => esc_html__( 'Main Title', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'custom_title_show_hide',
			[
				'label'        => esc_html__( 'Show Main Title', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Show', 'easy-elements' ),
				'label_off'    => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'custom_title',
			[
				'label'       => esc_html__( 'Custom Title Text', 'easy-elements' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default'     => '',
				'placeholder' => esc_html__( 'Enter custom title (leave blank for page title)', 'easy-elements' ),
				'description' => esc_html__( 'Leave blank to use the current page title automatically.', 'easy-elements' ),
				'condition'   => [
					'custom_title_show_hide' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'HTML Tag', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'div'  => 'Div',
					'span' => 'Span',
					'p'    => 'Paragraph',
				],
				'default' => 'h1',
				'condition' => [
					'custom_title_show_hide' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_size',
			[
				'label'       => esc_html__( 'Title Size Preset', 'easy-elements' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'large',
				'options'     => [
					'small' => esc_html__( 'Small', 'easy-elements' ),
					'large' => esc_html__( 'Large', 'easy-elements' ),
				],
				'condition' => [
					'custom_title_show_hide' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// --- Sub Heading ----------------------------------------------------
		$this->start_controls_section(
			'section_sub_heading',
			[
				'label' => esc_html__( 'Sub Heading', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label'       => esc_html__( 'Sub Heading Text', 'easy-elements' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'description' => esc_html__( 'Shown under the main title. Wrap any words in {{ }} to highlight them — e.g. An {{experienced team}} for your business.', 'easy-elements' ),
			]
		);

		$this->add_control(
			'heading_title_tag',
			[
				'label'   => esc_html__( 'HTML Tag', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'div'  => 'Div',
					'span' => 'Span',
					'p'    => 'Paragraph',
				],
			]
		);

		$this->end_controls_section();

		// --- Description ----------------------------------------------------
		$this->start_controls_section(
			'section_description',
			[
				'label' => esc_html__( 'Description', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'custom_description',
			[
				'label'       => esc_html__( 'Description Text', 'easy-elements' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'label_block' => true,
				'placeholder' => esc_html__( 'Enter description', 'easy-elements' ),
				'description' => esc_html__( 'Optional short text shown below the headings.', 'easy-elements' ),
			]
		);

		$this->end_controls_section();

		// --- Layout (alignment + decorative border) -------------------------
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label'   => esc_html__( 'Alignment', 'easy-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left'   => [
						'title' => __( 'Left', 'easy-elements' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'easy-elements' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'easy-elements' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .eel-page-title-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_show_hide',
			[
				'label'        => esc_html__( 'Decorative Border', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Show', 'easy-elements' ),
				'label_off'    => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'description'  => esc_html__( 'Adds a small accent bar beside the main title.', 'easy-elements' ),
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'border_position',
			[
				'label'     => esc_html__( 'Border Position', 'easy-elements' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'left'  => esc_html__( 'Left', 'easy-elements' ),
					'right' => esc_html__( 'Right', 'easy-elements' ),
				],
				'default'   => 'left',
				'condition' => [
					'border_show_hide' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// =====================================================================
		// STYLE TAB
		// =====================================================================

		// --- General Style (decorative border color) ------------------------
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => esc_html__( 'General', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Original misspelled ID preserved on purpose — renaming would orphan
		// every saved value across existing pages.
		$this->add_control(
			'boder_color',
			[
				'label'     => esc_html__( 'Border Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'border_show_hide' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .eel-border-left' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// --- Main Title Style ----------------------------------------------
		$this->start_controls_section(
			'section_main_title_style',
			[
				'label'     => esc_html__( 'Main Title', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'custom_title_show_hide' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-page-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .eel-page-title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => esc_html__( 'Margin', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default'    => [
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .eel-page-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// --- Sub Heading Style ---------------------------------------------
		$this->start_controls_section(
			'section_sub_heading_style',
			[
				'label' => esc_html__( 'Sub Heading', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_title_color',
			[
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-heading-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'highlighted_span_color',
			[
				'label'       => esc_html__( 'Highlighted Word Color', 'easy-elements' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Color applied to words wrapped in {{ }} inside the sub heading.', 'easy-elements' ),
				'selectors'   => [
					'{{WRAPPER}} .eel-highlighted' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_title_typography',
				'selector' => '{{WRAPPER}} .eel-heading-title',
			]
		);

		$this->add_responsive_control(
			'heading_title_margin',
			[
				'label'     => esc_html__( 'Margin', 'easy-elements' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eel-heading-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// --- Description Style ---------------------------------------------
		$this->start_controls_section(
			'section_description_style',
			[
				'label' => esc_html__( 'Description', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-page-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .eel-page-description',
			]
		);

		$this->add_responsive_control(
			'description_margin',
			[
				'label'     => esc_html__( 'Margin', 'easy-elements' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eel-page-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get the dynamic page title based on the current page context.
	 *
	 * @return string
	 */
	private function get_dynamic_page_title() {

		// Elementor editor mode — use the current document title
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || wp_doing_ajax() ) {
			$document = \Elementor\Plugin::$instance->documents->get_current();
			if ( $document ) {
				$post_id = $document->get_main_id();
				if ( $post_id ) {
					return get_the_title( $post_id );
				}
			}
		}

		if ( is_home() ) {
			return get_option( 'page_for_posts' ) ? get_the_title( get_option( 'page_for_posts' ) ) : __( 'Blog', 'easy-elements' );
		} elseif ( is_category() ) {
			return single_cat_title( '', false );
		} elseif ( is_archive() ) {
			return get_the_archive_title();
		} elseif ( is_search() ) {
			// translators: %s is the search query entered by the user.
			return sprintf( __( 'Search Results for "%s"', 'easy-elements' ), get_search_query() );
		} elseif ( is_404() ) {
			return __( 'Page Not Found', 'easy-elements' );
		} elseif ( is_tag() ) {
			return single_tag_title( '', false );
		} elseif ( is_author() ) {
			return get_the_author();
		} elseif ( is_date() ) {
			return get_the_date();
		} elseif ( is_post_type_archive() ) {
			return post_type_archive_title( '', false );
		} elseif ( is_singular() ) {
			return get_the_title();
		} else {
			return get_the_title();
		}
	}
	

	protected function render() {
		$settings = $this->get_settings_for_display();
		$title = (!empty($settings['custom_title']) && (!isset($settings['custom_title_show_hide']) || $settings['custom_title_show_hide'] === 'yes'))
			? $settings['custom_title']
			: $this->get_dynamic_page_title();
		$description = !empty($settings['custom_description']) ? $settings['custom_description'] : '';
		$tag = !empty($settings['title_tag']) ? $settings['title_tag'] : 'h1';
		$title_size = !empty($settings['title_size']) ? $settings['title_size'] : 'large';
		$heading_title = !empty($settings['heading_title']) ? $settings['heading_title'] : '';
		$heading_title_tag = !empty($settings['heading_title_tag']) ? $settings['heading_title_tag'] : 'h2';
		$border_position = !empty($settings['border_position']) ? $settings['border_position'] : 'left';
		$border_show_hide = !empty($settings['border_show_hide']) ? $settings['border_show_hide'] : '';
		
		$processed_heading_title = '';
		if (!empty($heading_title)) {
			$processed_heading_title = preg_replace('/\{\{([^}]+)\}\}/', '<span class="eel-highlighted">$1</span>', $heading_title);
		}
		?>
		<div class="eel-page-title-wrapper eel-title-size-<?php echo esc_attr($title_size); ?> eel-border-position-<?php echo esc_attr($border_position); ?>">
			
		<?php if ( isset( $settings['custom_title_show_hide']) && !empty( $settings['custom_title_show_hide'] ) && $settings['custom_title_show_hide'] === 'yes' ){ ?>
			<<?php echo esc_attr($tag); ?> class="eel-page-title">
				<?php if ($border_show_hide == 'yes') { ?>
					<span class="eel-border-left"></span>
				<?php } ?>
				<?php echo wp_kses_post($title); ?>
			</<?php echo esc_attr($tag); ?>>
		<?php } ?>
			<?php if ($heading_title): ?>
				<<?php echo esc_attr($heading_title_tag); ?> class="eel-heading-title"><?php echo wp_kses_post($processed_heading_title); ?></<?php echo esc_attr($heading_title_tag); ?>>
			<?php endif; ?>
			
			<?php if ($description): ?>
				<div class="eel-page-description"><?php echo wp_kses_post($description); ?></div>
			<?php endif; ?>
		</div>
		<?php
	}
	
}
