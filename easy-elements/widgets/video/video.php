<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use \Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Video_Popup_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eel-video-popup';
	}

	public function get_title() {
		return __( 'Video', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-video';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
        return [ 'video', 'popup', 'link', 'click' ];
    }

	public function get_style_depends() {
        return [
            'eel-video-popup',
        ];
    }

	protected function register_controls() {
		$this->start_controls_section(
			'section_video',
			[
				'label' => __( 'Video Settings', 'easy-elements' ),
			]
		);

		$this->add_control(
		    'display_type',
		    [
		        'label' => __( 'Display Type', 'easy-elements' ),
		        'type' => Controls_Manager::SELECT,
		        'default' => 'normal',
		        'options' => [
		        	'normal' => __( 'Normal Only', 'easy-elements' ),
		            'popup'  => __( 'Popup Only', 'easy-elements' ),		            
		        ],
		    ]
		);

		$this->add_control(
			'video_type',
			[
				'label'   => __( 'Video Type', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'youtube',
				'options' => [
					'youtube'    => __( 'YouTube', 'easy-elements' ),
					'vimeo'      => __( 'Vimeo', 'easy-elements' ),
					'self_hosted'=> __( 'Self Hosted', 'easy-elements' ),
				],
			]
		);

		$this->add_control(
			'video_url',
			[
				'label'       => __( 'Video URL', 'easy-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'https://www.youtube.com/watch?v=hDABYoas7SY',
				'placeholder' => __( 'https://www.youtube.com/watch?v=hDABYoas7SY', 'easy-elements' ),
				'condition'   => [
					'video_type' => 'youtube',
				],
			]
		);

		$this->add_control(
			'video_url_vimeo',
			[
				'label'       => __( 'Video URL', 'easy-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'https://vimeo.com/347119375',
				'placeholder' => __( 'https://vimeo.com/347119375', 'easy-elements' ),
				'condition'   => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'self_hosted_video',
			[
				'label'     => __( 'Self Hosted Video', 'easy-elements' ),
				'type'      => Controls_Manager::MEDIA,
				'media_type' => 'video',
				'condition' => [
					'video_type' => 'self_hosted',
				],
			]
		);

		$this->add_responsive_control(
		    'video_height',
		    [
		        'label' => __( 'Video Height', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => [ 'px', 'vh' ],
		        'range' => [
		            'px' => [
		                'min' => 100,
		                'max' => 1000,
		            ],
		            'vh' => [
		                'min' => 10,
		                'max' => 100,
		            ],
		        ],
		        'default' => [
		            'size' => 700,
		            'unit' => 'px',
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-wrapper iframe' => 'height: {{SIZE}}{{UNIT}};',
		            '{{WRAPPER}} .eel-video-popup-wrapper video' => 'height: {{SIZE}}{{UNIT}};',
		        ],
		        'condition' => [
		        	'display_type' => 'normal',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'video_max_width',
		    [
		        'label' => __( 'Video Max Width', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => [ 'px', '%' ],
		        'range' => [
		            'px' => [
		                'min' => 100,
		                'max' => 1920,
		            ],
		            '%' => [
		                'min' => 10,
		                'max' => 100,
		            ],
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-wrapper iframe.eel-normal-video' => 'max-width: {{SIZE}}{{UNIT}};',
		            '{{WRAPPER}} .eel-video-popup-wrapper video.eel-normal-video' => 'max-width: {{SIZE}}{{UNIT}};',
		        ],
		        'condition' => [
		        	'display_type' => 'normal',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'video_border_radius_normal',
		    [
		        'label' => __( 'Video Border Radius', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-wrapper iframe.eel-normal-video' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		            '{{WRAPPER}} .eel-video-popup-wrapper video.eel-normal-video' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		        'condition' => [
		        	'display_type' => 'normal',
		        ],
		    ]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'video_filters',
				'label' => __( 'CSS Filters', 'easy-elements' ),
				'selector' => '{{WRAPPER}} .eel-video-popup-wrapper iframe, {{WRAPPER}} .eel-video-popup-wrapper video',
			]
		);



		$this->add_control(
			'popup_trigger_text',
			[
				'label'       => __( 'Trigger Text', 'easy-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Play Video', 'easy-elements' ),
				'condition' => [
					'display_type' => 'popup',
				],
			]
		);

		$this->add_responsive_control(
			'popup_icon_spacing',
			[
				'label' => __( 'Text Spacing', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'size' => 8,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-video-popup-btn .eel-icon-wrap + .eel-trigger-text' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-video-popup-btn .eel-trigger-text + .eel-icon-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'display_type' => 'popup',
				],
			]
		);

		$this->add_control(
		    'popup_text_color',
		    [
		        'label' => __( 'Text Color', 'easy-elements' ),
		        'type' => Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn .eel-trigger-text' => 'color: {{VALUE}};',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		        ],
		    ]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Typography::get_type(),
		    [
		        'name' => 'popup_text_typography',
		        'label' => __( 'Typography', 'easy-elements' ),
		        'selector' => '{{WRAPPER}} .eel-video-popup-btn .eel-trigger-text',
		        'condition' => [
		            'display_type' => 'popup',
		        ],
		    ]
		);

		$this->add_control(
		    'popup_icon_show',
		    [
		        'label' => __( 'Show Icon', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SWITCHER,
		        'label_on' => __( 'Yes', 'easy-elements' ),
		        'label_off' => __( 'No', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default' => 'yes',
		        'condition' => [
		            'display_type' => 'popup',
		        ],
		    ]
		);

		$this->add_control(
		    'popup_icon_hover_show',
		    [
		        'label' => __( 'Default Hide & Hover Show', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SWITCHER,
		        'label_on' => __( 'Yes', 'easy-elements' ),
		        'label_off' => __( 'No', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default' => 'no',
		        'condition' => [
		            'display_type' => 'popup',
						'popup_icon_show' => 'yes'
		        ],
		    ]
		);

		$this->add_control(
			'popup_trigger_icon',
			[
				'label' => __( 'Icon', 'easy-elements' ),
				'type'  => Controls_Manager::ICONS,
				'default' => [
					'value' => '',
					'library' => 'fa-solid',
				],
				'condition' => [
					'display_type' => 'popup',
					'popup_icon_show' => 'yes',
				],
			]
		);		

		$this->add_control(
		    'popup_icon_position',
		    [
		        'label' => __( 'Icon Position', 'easy-elements' ),
		        'type' => Controls_Manager::SELECT,
		        'default' => 'before',
		        'options' => [
		            'before' => __( 'Before Text', 'easy-elements' ),
		            'after'  => __( 'After Text', 'easy-elements' ),
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);


		$this->add_control(
		    'popup_icon_color',
		    [
		        'label' => __( 'Icon Color', 'easy-elements' ),
		        'type'  => Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn i'   => 'color: {{VALUE}};',
		            '{{WRAPPER}} .eel-video-popup-btn svg' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-video-popup-btn svg path' => 'fill: {{VALUE}};',

		        ],
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);


		$this->add_responsive_control(
		    'icon_size',
		    [
		        'label' => __( 'Icon Size', 'easy-elements' ),
		        'type' => Controls_Manager::SLIDER,
		        'range' => [
		            'px' => [
		                'min' => 8,
		                'max' => 100,
		            ],
		        ],
		        'default' => [
		            'size' => 20,
		            'unit' => 'px',
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
		            '{{WRAPPER}} .eel-video-popup-btn svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-video-popup-btn svg path' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);

		$this->add_control(
		    'icon_bg_color',
		    [
		        'label' => __( 'Circle Background Color', 'easy-elements' ),
		        'type' => Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn .eel-icon-wrap' => 'background-color: {{VALUE}};',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'circle_size',
		    [
		        'label' => __( 'Circle Size', 'easy-elements' ),
		        'type' => Controls_Manager::SLIDER,
		        'range' => [
		            'px' => [ 'min' => 10, 'max' => 200 ],
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn .eel-icon-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; place-content: center;',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'icon_border_radius',
		    [
		        'label' => __( 'Circle Border Radius', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn .eel-icon-wrap, {{WRAPPER}} .eel-icon-wrap .eel-overlay-play' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Box_Shadow::get_type(),
		    [
		        'name'     => 'circle_shadow',
		        'label'    => __( 'Circle Shadow', 'easy-elements' ),
		        'selector' => '{{WRAPPER}} .eel-video-popup-btn .eel-icon-wrap',
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);


		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'     => 'button_border',
		        'label'    => __( 'Button Border', 'easy-elements' ),
		        'selector' => '{{WRAPPER}} .eel-video-popup-btn .eel-icon-wrap',
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);

		$this->add_control(
		    'circle_glow',
		    [
		        'label'        => __( 'Active Ripple', 'easy-elements' ),
		        'type'         => \Elementor\Controls_Manager::SWITCHER,
		        'label_on'     => __( 'On', 'easy-elements' ),
		        'label_off'    => __( 'Off', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default'      => '',
		        'condition'    => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);

		$this->add_control(
		    'glow_color',
		    [
		        'label'     => __( 'Ripple Color', 'easy-elements' ),
		        'type'      => Controls_Manager::COLOR,
		        'default'   => 'rgba(102, 102, 102, 0.1)',
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn.eel-glow-active .eel-icon-wrap' => '--glow-color: {{VALUE}};',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		            'circle_glow' => 'yes',		            
		        ],
		    ]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wrap_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-video-popup-wrapper',
				'condition' => [
					'display_type' => 'popup',	            
				],
			]
		);

		$this->add_responsive_control(
		    'wrap_radius',
		    [
		        'label' => __( 'Border Radius', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'wrap_padding',
		    [
		        'label' => __( 'Padding', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		        ],
		    ]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_video_self_options',
			[
				'label' => __( 'Video Settings', 'easy-elements' ),
				'condition' => [
					'display_type' => 'normal',
					'video_type'  => 'self_hosted',
				],
			]
		);

		$this->add_control(
		    'self_hosted_autoplay',
		    [
		        'label' => __( 'Autoplay', 'easy-elements' ),
		        'type' => Controls_Manager::SWITCHER,
		        'label_on' => __( 'Yes', 'easy-elements' ),
		        'label_off' => __( 'No', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default' => '',
		        'condition' => [
		            'display_type' => 'normal',
		            'video_type' => 'self_hosted',
		        ],
		    ]
		);

		$this->add_control(
		    'self_hosted_loop',
		    [
		        'label' => __( 'Loop', 'easy-elements' ),
		        'type' => Controls_Manager::SWITCHER,
		        'label_on' => __( 'Yes', 'easy-elements' ),
		        'label_off' => __( 'No', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default' => '',
		        'condition' => [
		            'display_type' => 'normal',
		            'video_type' => 'self_hosted',
		        ],
		    ]
		);

		$this->add_control(
		    'self_hosted_muted',
		    [
		        'label' => __( 'Muted', 'easy-elements' ),
		        'type' => Controls_Manager::SWITCHER,
		        'label_on' => __( 'Yes', 'easy-elements' ),
		        'label_off' => __( 'No', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default' => '',
		        'condition' => [
		            'display_type' => 'normal',
		            'video_type' => 'self_hosted',
		        ],
		    ]
		);

		$this->add_control(
		    'self_hosted_controls',
		    [
		        'label' => __( 'Show Controls', 'easy-elements' ),
		        'type' => Controls_Manager::SWITCHER,
		        'label_on' => __( 'Yes', 'easy-elements' ),
		        'label_off' => __( 'No', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default' => 'yes',
		        'condition' => [
		            'display_type' => 'normal',
		            'video_type' => 'self_hosted',
		        ],
		    ]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_video_options',
			[
				'label' => __( 'Video Settings', 'easy-elements' ),
				'condition' => [
					'display_type' => 'normal',
					'video_type!'  => 'self_hosted',
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'easy-elements' ),
				'label_off'=> __( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'mute',
			[
				'label' => __( 'Mute', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'easy-elements' ),
				'label_off'=> __( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => __( 'Loop', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'easy-elements' ),
				'label_off'=> __( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'controls',
			[
				'label' => __( 'Player Controls', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'easy-elements' ),
				'label_off'=> __( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'privacy_mode',
			[
				'label' => __( 'Privacy Mode', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'easy-elements' ),
				'label_off'=> __( 'Off', 'easy-elements' ),
				'return_value' => 'yes',
				'description' => __( 'YouTube nocookie / Vimeo DNT', 'easy-elements' ),
			]
		);

		$this->add_control(
			'yt_suggested',
			[
				'label' => __( 'Suggested Videos', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'easy-elements' ),
				'label_off'=> __( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => '',
				'description' => __( 'Show suggested videos at end', 'easy-elements' ),
				'condition' => [
					'video_type' => 'youtube',
				],
			]
		);

		$this->add_control(
			'yt_start_time',
			[
				'label' => __( 'Start Time (seconds)', 'easy-elements' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'default' => '',
				'description' => __( 'Start video from specific second', 'easy-elements' ),
				'condition' => [
					'video_type' => 'youtube',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_image_overlay',
			[
				'label' => __( 'Video Overlay', 'easy-elements' ),
				'condition' => [
					'display_type' => 'normal',
				],
			]
		);

		$this->add_control(
			'show_image_overlay',
			[
				'label' => __( 'Image Overlay', 'easy-elements' ),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'easy-elements' ),
				'label_off'=> __( 'Off', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'overlay_image',
			[
				'label' => __( 'Choose Image', 'easy-elements' ),
				'type'  => \Elementor\Controls_Manager::MEDIA,
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'overlay_image_size',
			[
				'label' => __( 'Image Resolution', 'easy-elements' ),
				'type'  => \Elementor\Controls_Manager::SELECT,
				'default' => 'full',
				'options' => [
					'thumbnail' => __( 'Thumbnail', 'easy-elements' ),
					'medium'    => __( 'Medium', 'easy-elements' ),
					'large'     => __( 'Large', 'easy-elements' ),
					'full'      => __( 'Full', 'easy-elements' ),
				],
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'overlay_play_icon',
			[
				'label' => __( 'Play Icon', 'easy-elements' ),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'overlay_icon',
			[
				'label' => __( 'Icon', 'easy-elements' ),
				'type'  => \Elementor\Controls_Manager::ICONS,
				'condition' => [
					'show_image_overlay' => 'yes',
					'overlay_play_icon'  => 'yes',
				],
			]
		);

		$this->add_control(
			'overlay_lightbox',
			[
				'label' => __( 'Lightbox', 'easy-elements' ),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'lightbox_animation',
			[
				'label' => __( 'Animation', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::ANIMATION,
				'default' => '',
				'condition' => [
					'overlay_lightbox' => 'yes',
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_video_popup_overlay_settings',
			[
				'label' => __( 'Play Button Overlay Settings', 'easy-elements' ),
				'condition' => [
					'display_type' => 'popup',
				],
			]
		);

		$this->add_control(
			'popup_play_overlay',
			[
				'label'       => __( 'Play Overlay', 'easy-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Play', 'easy-elements' ),
			]
		);

		$this->add_control(
            'popup_play_color',
            [
                'label' => esc_html__('Text Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-icon-wrap .eel-overlay-play' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'popup_play__typography',
                'selector' => '{{WRAPPER}} .eel-icon-wrap .eel-overlay-play',
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background_overly_play',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-icon-wrap .eel-overlay-play',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'video_icon_settings_style',
			[
				'label' => __( 'Video Overlay Style', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'display_type' => 'normal',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__('Icon Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-overlay-play-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-overlay-play-icon svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'video_icon_size',
			[
				'label' => esc_html__('Icon Size', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', '%'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
					'em' => [
						'min' => 0.5,
						'max' => 10,
					],
					'%' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-overlay-play-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-overlay-play-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'video_border',
				'selector' => '{{WRAPPER}} .eel-overlay-play-icon',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'video_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-overlay-play-icon',
			]
		);
		$this->add_responsive_control(
			'video_border_radius',
			[
				'label' => esc_html__('Border Radius', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eel-overlay-play-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'custom_circle_normal_size',
			[
				'label' => esc_html__('Circle Size', 'easy-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
					'em' => [
						'min' => 0.5,
						'max' => 20,
					],
					'%' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-overlay-play-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'popup_style_section',
			[
				'label' => __( 'Popup Style', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'display_type' => 'popup',
				],
			]
		);

		$this->add_control(
			'popup_overlay_bg',
			[
				'label' => __( 'Overlay Background', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eel-video-popup-overlay' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'popup_content_max_width',
			[
				'label' => __( 'Popup Max Width', 'easy-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 300,
						'max' => 1920,
					],
					'%' => [
						'min' => 30,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-video-popup-content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'popup_content_height',
			[
				'label' => __( 'Popup Height', 'easy-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 1080,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-video-popup-iframe-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'popup_content_border_radius',
			[
				'label' => __( 'Popup Border Radius', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eel-video-popup-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_control(
			'popup_close_color',
			[
				'label' => __( 'Close Button Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-video-popup-close' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'popup_close_bg',
			[
				'label' => __( 'Close Button Background', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-video-popup-close' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function get_youtube_id( $url ) {
        preg_match('/(?:v=|\/)([0-9A-Za-z_-]{11}).*/', $url, $matches);
        return $matches[1] ?? '';
    }

    private function get_vimeo_id( $url ) {
        preg_match('/(\d+)/', $url, $matches);
        return $matches[1] ?? '';
    }

	protected function render() {
		$settings     = $this->get_settings_for_display();
		$embed_type   = $settings['video_type'];
		$display_type = $settings['display_type'];
		$video_url    = '';

		/* -----------------------------------------
		* Get Video URL
		* ----------------------------------------- */
		if ( $embed_type === 'self_hosted' && ! empty( $settings['self_hosted_video']['url'] ) ) {
			$video_url = $settings['self_hosted_video']['url'];
		} elseif ( $embed_type === 'youtube' && ! empty( $settings['video_url'] ) ) {
			$video_url = $settings['video_url'];
		} elseif ( $embed_type === 'vimeo' && ! empty( $settings['video_url_vimeo'] ) ) {
			$video_url = $settings['video_url_vimeo'];
		}

		if ( empty( $video_url ) ) {
			return;
		}

		$video_id   = uniqid( 'eel_video_' );
		$circle_glow = ( ! empty( $settings['circle_glow'] ) && $settings['circle_glow'] === 'yes' ) ? 'eel-glow-active' : '';
		$icon_hide_h_show = ( ! empty( $settings['popup_icon_hover_show'] ) && $settings['popup_icon_hover_show'] === 'yes' ) ? 'eel-vicon-hide-show' : '';

		echo '<div class="eel-video-popup-wrapper ' . esc_attr( $icon_hide_h_show ) . '">';

		/* =====================================================================
		* NORMAL VIDEO
		* ===================================================================== */
		if ( $display_type === 'normal' ) {

			// Image Overlay
			if ( ! empty( $settings['show_image_overlay'] ) && $settings['show_image_overlay'] === 'yes' && ! empty( $settings['overlay_image']['url'] ) ) {
				$overlay_size = ! empty( $settings['overlay_image_size'] ) ? $settings['overlay_image_size'] : 'full';
				$overlay_id   = ! empty( $settings['overlay_image']['id'] ) ? $settings['overlay_image']['id'] : 0;
				$overlay_url  = $overlay_id ? wp_get_attachment_image_url( $overlay_id, $overlay_size ) : $settings['overlay_image']['url'];
				$overlay_url  = esc_url( $overlay_url );
				$bg_style = "background-image: url('{$overlay_url}'); background-size: cover; background-position: center;";
				$overlay_lightbox = ! empty( $settings['overlay_lightbox'] ) && $settings['overlay_lightbox'] === 'yes' ? 'yes' : 'no';
				$lightbox_animation = ! empty( $settings['lightbox_animation'] ) ? $settings['lightbox_animation'] : '';
				echo '<div class="eel-video-overlay" 
					style="' . esc_attr($bg_style) . '"
					data-lightbox="' . esc_attr( $overlay_lightbox ) . '"
					data-video-type="' . esc_attr( $embed_type ) . '"
					data-video-url="' . esc_url( $video_url ) . '"
					data-popup-id="' . esc_attr( $video_id ) . '"
					data-animation="' . esc_attr( $lightbox_animation ) . '">';					
				if ( ! empty( $settings['overlay_play_icon'] ) && $settings['overlay_play_icon'] === 'yes' ) {
					$icon_html    = '';
					$icon_setting = $settings['overlay_icon'];
					if ( ! empty( $icon_setting['value'] ) ) {
						ob_start();
						\Elementor\Icons_Manager::render_icon( $icon_setting, [ 'aria-hidden' => 'true' ] );
						$icon_html = ob_get_clean();
					}
					if ( empty( $icon_html ) ) {
						$icon_html = '<i class="unicon-play"></i>';
					}
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted output from Elementor Icons_Manager or hardcoded HTML.
					echo '<span class="eel-overlay-play-icon">' . $icon_html . '</span>';
				}

				echo '</div>';
				
				// Create lightbox overlay structure if lightbox is enabled
				if ( $overlay_lightbox === 'yes' ) {
					$animation_class = ! empty( $lightbox_animation ) ? ' animated ' . $lightbox_animation : '';
					echo '<div id="' . esc_attr( $video_id ) . '" class="eel-video-popup-overlay" data-animation="' . esc_attr( $lightbox_animation ) . '">';
					echo '<div class="eel-video-popup-content ' . esc_attr( $animation_class ) . '">';
					echo '<span class="eel-video-popup-close">&times;</span>';
					echo '<div class="eel-video-popup-iframe-wrapper"></div>';
					echo '</div>';
					echo '</div>';
				}
			}


			// Self Hosted Video
			if ( $embed_type === 'self_hosted' ) {
				$bool_attrs = [];
				if ( ! empty( $settings['self_hosted_autoplay'] ) ) {
					$bool_attrs[] = 'autoplay';
					$bool_attrs[] = 'muted';
				}
				if ( ! empty( $settings['self_hosted_loop'] ) ) $bool_attrs[] = 'loop';
				if ( ! empty( $settings['self_hosted_muted'] ) ) $bool_attrs[] = 'muted';
				if ( ! empty( $settings['self_hosted_controls'] ) ) $bool_attrs[] = 'controls';
				$bool_attrs[] = 'playsinline';
				$bool_attrs   = array_unique( $bool_attrs );
				$attr_string  = implode( ' ', array_map( 'esc_attr', $bool_attrs ) ) . ' preload="none"';
				// All attributes are hardcoded whitelisted boolean values — safe to output.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $attr_string contains only whitelisted boolean attrs and hardcoded preload="none".
			echo '<video class="eel-normal-video" src="' . esc_url( $video_url ) . '" ' . $attr_string . '></video>';
			} else {
				// YouTube/Vimeo
				$params = [
					'autoplay' => ! empty( $settings['autoplay'] ) ? '1' : '0',
					'loop'     => ! empty( $settings['loop'] ) ? '1' : '0',
					'controls' => ! empty( $settings['controls'] ) ? '1' : '0',
				];
				if ( ! empty( $settings['mute'] ) ) $params['mute'] = '1';

				if ( $embed_type === 'youtube' ) {
					$yt_id = $this->get_youtube_id( $video_url );
					if ( ! $yt_id ) { echo '</div>'; return; }
					if ( empty( $settings['yt_suggested'] ) ) {
						$params['rel'] = '0';
					}
					if ( ! empty( $settings['yt_start_time'] ) ) {
						$params['start'] = absint( $settings['yt_start_time'] );
					}
					$yt_domain = ! empty( $settings['privacy_mode'] ) ? 'www.youtube-nocookie.com' : 'www.youtube.com';
					$embed_url = 'https://' . $yt_domain . '/embed/' . $yt_id . '?' . http_build_query( $params );
				} elseif ( $embed_type === 'vimeo' ) {
					$vimeo_id = $this->get_vimeo_id( $video_url );
					if ( ! $vimeo_id ) { echo '</div>'; return; }
					if ( ! empty( $settings['privacy_mode'] ) ) {
						$params['dnt'] = '1';
					}
					$embed_url = 'https://player.vimeo.com/video/' . $vimeo_id . '?' . http_build_query( $params );
				}
				echo '<iframe class="eel-normal-video" src="' . esc_url( $embed_url ) . '" loading="lazy" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
			}
		}	

		if ( $display_type === 'popup' ) {

			$icon_html    = '';
			$icon_setting = $settings['popup_trigger_icon'];

			if ( ! empty( $icon_setting['value'] ) ) {
				ob_start();
				\Elementor\Icons_Manager::render_icon( $icon_setting, [ 'aria-hidden' => 'true' ] );
				$icon_html = ob_get_clean();
			}
			if ( empty( $icon_html ) ) {
				$icon_html = '<i class="unicon-play"></i>';
			}

			$label = ! empty( $settings['popup_trigger_text'] ) ? wp_strip_all_tags( $settings['popup_trigger_text'] ) : 'Play Video';
			?>

			<div class="eel-video-popup-btn entro-all-video-popup <?php echo esc_attr($circle_glow); ?>"
				data-video-type="<?php echo esc_attr( $embed_type ); ?>"
				data-video-src="<?php echo esc_url( $video_url ); ?>"
				data-popup-id="<?php echo esc_attr( $video_id ); ?>"
				aria-label="<?php echo esc_attr( $label ); ?>">
				<?php if ( $settings['popup_icon_show'] === 'yes' && $settings['popup_icon_position'] === 'before' ) : ?>
					<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted output from Elementor Icons_Manager. ?>
					<span class="eel-icon-wrap"><?php echo $icon_html; ?>
						<?php if(!empty($settings['popup_play_overlay'])): ?>
							<span class="eel-overlay-play"><?php echo esc_html( $settings['popup_play_overlay'] ); ?> </span>
						<?php endif; ?>
					</span>
				<?php endif; ?>
				<?php if ( ! empty( $settings['popup_trigger_text'] ) ) : ?>
					<span class="eel-trigger-text"><?php echo esc_html( $settings['popup_trigger_text'] ); ?></span>
				<?php endif; ?>
				<?php if ( $settings['popup_icon_show'] === 'yes' && $settings['popup_icon_position'] === 'after' ) : ?>
					<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted output from Elementor Icons_Manager. ?>
					<span class="eel-icon-wrap"><?php echo $icon_html; ?>
					<?php if(!empty($settings['popup_play_overlay'])): ?>
						<span class="eel-overlay-play"><?php echo esc_html( $settings['popup_play_overlay'] ); ?> </span>
					<?php endif; ?>
					</span>
				<?php endif; ?>
			</div>			
			<div id="<?php echo esc_attr( $video_id ); ?>" class="eel-video-popup-overlay">
				<div class="eel-video-popup-content">
					<span class="eel-video-popup-close">&times;</span>
					<div class="eel-video-popup-iframe-wrapper"></div>
				</div>
			</div>
			<?php			
		}
		echo '</div>';
	}
} 