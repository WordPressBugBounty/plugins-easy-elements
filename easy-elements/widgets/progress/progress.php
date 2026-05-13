<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

defined( 'ABSPATH' ) || die();

class Easyel_Progress_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'easyel-progress-bar';
	}

	public function get_title() {
		return esc_html__( 'Progress Bar', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-progress-bar';
	}

	public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'progress', 'text', 'link', 'circle', 'text' ];
    }

	public function get_style_depends() {
        return [
            'easyel-progress-bar',
        ];
    }


	protected function register_controls() {
		$this->start_controls_section(
			'section_progress',
			[
				'label' => esc_html__( 'Progress Bar', 'easy-elements' ),
			]
		);

		$this->add_control(
			'select_style',
			[
				'label' => esc_html__( 'Select Style', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' => esc_html__( 'Style 1', 'easy-elements' ),
					'style2' => esc_html__( 'Style 2', 'easy-elements' ),
				],
			]
		);		
		
		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'easy-elements' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Web Designer', 'easy-elements' ),
				'default' => esc_html__( 'Web Designer', 'easy-elements' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'easy_percent',
			[
				 'label' => __( 'Percent', 'easy-elements' ),
				 'type' => \Elementor\Controls_Manager::NUMBER,
				 'min' => 0,
				 'max' => 100,
				 'default' => 50,
			]
	  	);
		$this->end_controls_section();

		$this->start_controls_section(
			'rt_progress_style',
			[
				'label' => esc_html__( 'Progress', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'progress_color',
			[
				'label' => esc_html__( 'Progress Color', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .easyel-progress-bar .progress' => 'background: {{VALUE}}',
				],
				'condition' => [
					'select_style' => 'style1'
				]
			]
		);
		
		$this->add_control(
			'progress_bar_color',
			[
				'label' => esc_html__( 'Progress Bar Color', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .easyel-progress-bar .progress-bar' => 'background: {{VALUE}}',
				],
				'condition' => [
					'select_style' => 'style1'
				]
			]
		);

		// progress style 2 bg settings
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'progress_color',
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .easyel-progress-bar .progress',
				'condition' => [
					'select_style' => 'style2'
				]
			]
		);


		$this->add_control(
			'progress_height',
			[
				'label' => esc_html__( 'Height', 'easy-elements' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .easyel-progress-bar .progress' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'progress_radius',[
				'label' => esc_html__( 'Border Radius', 'easy-elements' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .easyel-progress-bar .progress' => 'border-radius: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_styles',
			[
				'label' => esc_html__( 'Title', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .easyel-progress-bar .title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .easyel-progress-bar .title',
			]
		);	
		$this->end_controls_section();

		$this->start_controls_section(
			'easy_percent_styles',
			[
				'label' => esc_html__( 'Percent', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'easy_percent_color',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .easyel-progress-bar .easy_percent' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'easy_percent_typography',
				'selector' => '{{WRAPPER}} .easyel-progress-bar .easy_percent',
			]
		);	
		$this->end_controls_section();
	}

	protected function render() {
	$settings = $this->get_settings_for_display();
	$easy_percent = !empty($settings['easy_percent']) ? rtrim($settings['easy_percent']) : 0;
	$title = !empty($settings['title']) ? $settings['title'] : '';
	?>		
	<div class="easyel-progress-bar">
		<?php 
		if( $settings['select_style'] == 'style2' ) : ?>
			<div class="progress-style2">
				<?php if ( $title ) : ?>
					<p class="title"><?php echo wp_kses_post( $title ); ?></p>
				<?php endif; ?>
				<div class="progress">
					<div class="progress-bar wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay=".3s" role="progressbar" style="width: <?php echo esc_html( $easy_percent ); ?>%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
					</div>
					<span class="easy_percent"><?php echo esc_html( $easy_percent ); ?>%</span>
				</div>
			</div>
			<?php 
		else : ?>
			<div class="progress-top">
				<?php if ( $title ) : ?>
					<p class="title"><?php echo wp_kses_post( $title ); ?></p>
				<?php endif; ?>
				<?php if ( $easy_percent ) : ?>
					<span class="easy_percent"><?php echo esc_html( $easy_percent ); ?>%</span>
				<?php endif; ?>
			</div>
			<div class="progress">
				<div class="progress-bar"
					style="width: <?php echo esc_html( $easy_percent ); ?>%;" 
					aria-valuenow="<?php echo esc_attr( $easy_percent ); ?>"
					aria-valuemin="0"
					aria-valuemax="100">
				</div>
			</div>
			<?php 
		endif; ?>
	</div>
	<?php
	}
}
