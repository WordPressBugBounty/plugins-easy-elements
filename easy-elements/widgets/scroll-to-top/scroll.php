<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Scroll_To_Top_Widget extends \Elementor\Widget_Base {


	public function get_name() {
		return 'eel-scroll-to-top';
	}

	public function get_title() {
		return __( 'Scroll Top', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-scroll-top';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
        return [ 'scroll', 'top', 'back', 'to', 'text' ];
    }

	public function get_style_depends() {
        return [
            'eel-scroll-to-top',
        ];
    }

	public function get_script_depends() {
        return [
            'eel-scroll-to-top',
        ];
    }

	protected function register_controls() {
		// Example: Style section for button
		$this->start_controls_section(
			'section_scroll_setting',
			[
				'label' => __( 'Scroll Button', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'scroll_btn_icon',
			[
				'label'   => __( 'Icon', 'easy-elements' ),
				'type'    => Controls_Manager::ICONS,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_scroll_style',
			[
				'label' => __( 'Scroll Button', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'scroll_btn_color',
			[
				'label'     => __( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #easyel-top-to-bottom i' => 'color: {{VALUE}};',
					'{{WRAPPER}} #easyel-top-to-bottom svg, {{WRAPPER}} #easyel-top-to-bottom svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'scroll_btn_bg',
			[
				'label'     => __( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #easyel-top-to-bottom' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div id="easyel-top-to-bottom">
			<?php
			if ( ! empty( $settings['scroll_btn_icon']['value'] ) ) {
				\Elementor\Icons_Manager::render_icon(
					$settings['scroll_btn_icon'],
					[ 'aria-hidden' => 'true' ]
				);
			} else {
				?>
				<i class="unicon-arrow-up"></i>
				<?php
			}
			?>
		</div>
		<?php
	}
}