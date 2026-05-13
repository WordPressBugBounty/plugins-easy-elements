<?php
namespace Easyel\EasyElements\Widgets;
use \Elementor\Controls_Manager;
use \Elementor\Repeater;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Elementor Table Widget.
 *
 * @since 1.0.0
 */

class Easyel_Table_Elementor_Widget extends \Elementor\Widget_Base {


	public function get_name() {
		return 'eel-table';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Table', 'easy-elements' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'easyicon easyelIcon-clients-logo-grid';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */

	public function get_keywords() {
		return [ 'table', 'simple', 'data', 'click', 'text' ];
	}

	public function get_style_depends() {
		return [
			'eel-table',
		];
	}

	public function get_script_depends() {
		return [
			'eel-table',
		];
	}
	
	protected function register_controls() {

		$this->start_controls_section(
			'content_table_header',
			[
				'label' => esc_html__( 'Table Header', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'table_header',
			[
				'label' => esc_html__( 'Table Header Cell', 'easy-elements' ),
				'type' => Controls_Manager::REPEATER,
				'prevent_empty' => false,
				'fields' => [
					[
						'name' => 'text',
						'label' => esc_html__( 'Text', 'easy-elements' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => esc_html__( 'Table Header', 'easy-elements' ),
						'default' => esc_html__( 'Table Header', 'easy-elements' ),
						'dynamic' => [
							'active' => true,
						]
					],
					[
						'name'	=> 'header_icon',
						'label' => esc_html__( 'Icon', 'easy-elements' ),
						'type' => Controls_Manager::SWITCHER,
						'label_off' => esc_html__( 'No', 'easy-elements' ),
						'label_on' => esc_html__( 'Yes', 'easy-elements' ),
					],
					[
						'name'	=> 'table_head_icon',
						'label' => esc_html__( 'Icon', 'easy-elements' ),
						'type' => \Elementor\Controls_Manager::ICONS,
						'default' => [
							'value' => 'far fa-user-circle',
							'library' => 'fa-solid',
						],
						'condition' => [
							'header_icon' => 'yes',
						],
					],
					[
						'name'	=> 'advance',
						'label' => esc_html__( 'Advance Settings', 'easy-elements' ),
						'type' => Controls_Manager::SWITCHER,
						'label_off' => esc_html__( 'No', 'easy-elements' ),
						'label_on' => esc_html__( 'Yes', 'easy-elements' ),
					],
					[
						'name'	=> 'colspan',
						'label' => esc_html__( 'colSpan', 'easy-elements' ),
						'type' => Controls_Manager::SWITCHER,
						'condition' => [
							'advance' => 'yes',
						],
						'label_off' => esc_html__( 'No', 'easy-elements' ),
						'label_on' => esc_html__( 'Yes', 'easy-elements' ),
					],
					[
						'name'	=> 'colspannumber',
						'label' => esc_html__( 'colSpan Number', 'easy-elements' ),
						'type' => Controls_Manager::TEXT,
						'condition' => [
							'advance' => 'yes',
							'colspan' => 'yes',
						],
						'placeholder' => esc_html__( '1', 'easy-elements' ),
						'default' => esc_html__( '1', 'easy-elements' ),
					],
					[
						'name'	=> 'customwidth',
						'label' => esc_html__( 'Custom Width', 'easy-elements' ),
						'type' => Controls_Manager::SWITCHER,
						'condition' => [
							'advance' => 'yes',
						],
						'label_off' => esc_html__( 'No', 'easy-elements' ),
						'label_on' => esc_html__( 'Yes', 'easy-elements' ),
					],
					[
						'name'	=> 'width',
						'label' => esc_html__( 'Width', 'easy-elements' ),
						'type' => Controls_Manager::SLIDER,
						'condition' => [
							'advance' => 'yes',
							'customwidth' => 'yes',
						],
						'range' => [
							'%' => [
								'min' => 0,
								'max' => 100,
							],
							'px' => [
								'min' => 1,
								'max' => 1000,
							],
						],
						'default' => [
							'size' => 30,
							'unit' => '%',
						],
						'size_units' => [ '%', 'px' ],
						'selectors' => [ '{{WRAPPER}} table.easyel-table .easyel-table-header {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
						]
					],
					[
						'name' => 'align', 
						'label' => esc_html__( 'Alignment', 'easy-elements' ),
						'type' => Controls_Manager::CHOOSE,
						'condition' => [
							'advance' => 'yes',
						],
						'options' => [
							'left' => [
								'title' => esc_html__( 'Left', 'easy-elements' ),
								'icon' => 'eicon-text-align-left',
							],
							'center' => [
								'title' => esc_html__( 'Center', 'easy-elements' ),
								'icon' => 'eicon-text-align-center',
							],
							'right' => [
								'title' => esc_html__( 'Right', 'easy-elements' ),
								'icon' => 'eicon-text-align-right',
							],
							'justify' => [
								'title' => esc_html__( 'Justified', 'easy-elements' ),
								'icon' => 'eicon-text-align-justify',
							],
						],
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} table.easyel-table .easyel-table-header {{CURRENT_ITEM}}' => 'text-align: {{VALUE}};',
						]
					],
					[
						'name' => 'vertical_align', 
						'label' => esc_html__( 'Vertical Alignment', 'easy-elements' ),
						'type' => Controls_Manager::CHOOSE,
						'condition' => [
							'advance' => 'yes',
						],
						'options' => [
							'top' => [
								'title' => esc_html__( 'Top', 'easy-elements' ),
								'icon' => 'fa fa-arrow-up',
							],
							'middle' => [
								'title' => esc_html__( 'Middle', 'easy-elements' ),
								'icon' => 'fa fa-arrows-v',
							],
							'bottom' => [
								'title' => esc_html__( 'Bottom', 'easy-elements' ),
								'icon' => 'fa fa-arrow-down',
							],
						],
						'default' => 'top',
						'toggle' => true,
						'selectors' => [
							'{{WRAPPER}} table.easyel-table .easyel-table-header {{CURRENT_ITEM}}' => 'vertical-align: {{VALUE}};',
						]
					],
					
					[
						'name'	=> 'decoration',
						'label' => esc_html__( 'Decoration', 'easy-elements' ),
						'type' => Controls_Manager::SELECT,
						'condition' => [
							'advance' => 'yes',
						],
						'options' => [
							''  => esc_html__( 'Default', 'easy-elements' ),
							'underline' => esc_html__( 'Underline', 'easy-elements' ),
							'overline' => esc_html__( 'Overline', 'easy-elements' ),
							'line-through' => esc_html__( 'Line Through', 'easy-elements' ),
							'none' => esc_html__( 'None', 'easy-elements' ),
						],
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} table.easyel-table .easyel-table-header {{CURRENT_ITEM}}' => 'text-decoration: {{VALUE}};',
						],
					],
					[
						'name'	=> 'table_head_tooltip',
						'label' => esc_html__( 'Tooltip Settings', 'easy-elements' ),
						'type' => Controls_Manager::SWITCHER,
						'label_off' => esc_html__( 'No', 'easy-elements' ),
						'label_on' => esc_html__( 'Yes', 'easy-elements' ),
					],
					[
						'name'	=> 'table_head_tooltip_icon',
						'label' => esc_html__( 'Tooltip Icon', 'easy-elements' ),
						'type' => \Elementor\Controls_Manager::ICONS,
						'default' => [
							'value' => 'fas fa-question-circle',
							'library' => 'fa-solid',
						],
						'condition' => [
							'table_head_tooltip' => 'yes',
						],
					],
					[
						'name'	=> 'table_head_tooltip_description',
						'label' => esc_html__('Tooltip Description', 'easy-elements' ),
						'type' => \Elementor\Controls_Manager::TEXTAREA,
						'rows' => 10,
						'placeholder' => esc_html__( 'Type your tooltip description here', 'easy-elements' ),
						'condition' => [
							'table_head_tooltip' => 'yes',
						],
					],
				],
				'default' => [
					[ 'text' => esc_html__( 'Table Header', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table Header', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table Header', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table Header', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table Header', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table Header', 'easy-elements' ) ],						
				],
				'title_field' => '{{{ text }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_table_body',
			[
				'label' => esc_html__( 'Table Body', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		
		$repeater->add_control(
			'row', [
				'label' => esc_html__( 'New Row', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			]
		);

		$repeater->add_control(
		'table_body_choose_img_icon',
			[
				'label' => esc_html__( 'Select Type', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon' => esc_html__( 'Icon', 'easy-elements' ),
					'image' => esc_html__( 'Image', 'easy-elements' ),
				],
			]
		);

		$repeater->add_control(
			'table_icon',
			[
				'label' => esc_html__( 'Icon', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'condition' => [
					'table_body_choose_img_icon' => ['icon']
				]
			]
		);
		
		$repeater->add_control(
			'table_image',
			[
				'label' => esc_html__( 'Image', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'table_body_choose_img_icon' => ['image']
				]
			]
		);

		$repeater->add_control(
			'text', [
				'label' => esc_html__( 'Text', 'easy-elements' ),
				'type' => Controls_Manager::WYSIWYG,
				'label_block' => true,
				'placeholder' => esc_html__( 'Table Data', 'easy-elements' ),
				'default' => esc_html__( 'Table Data', 'easy-elements' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'advance', [
				'label' => esc_html__( 'Advance Settings', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			]
		);

		$repeater->add_control(
			'colspan', [
				'label' => esc_html__( 'colSpan', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'advance' => 'yes',
				],
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			]
		);

		$repeater->add_control(
			'colspannumber', [
				'label' => esc_html__( 'colSpan Number', 'easy-elements' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'advance' => 'yes',
					'colspan' => 'yes',
				],
				'placeholder' => esc_html__( '1', 'easy-elements' ),
				'default' => esc_html__( '1', 'easy-elements' ),
			]
		);

		$repeater->add_control(
			'rowspan', [
				'label' => esc_html__( 'rowSpan', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'advance' => 'yes',
				],
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			]
		);	

		$repeater->add_control(
			'rowspannumber', [
				'label' => esc_html__( 'rowSpan Number', 'easy-elements' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'advance' => 'yes',
					'rowspan' => 'yes',
				],
				'placeholder' => esc_html__( '1', 'easy-elements' ),
				'default' => esc_html__( '1', 'easy-elements' ),
			]
		);		

		$repeater->add_control(
			'table_body_data_flex',
			[
				'label' => esc_html__( 'Data Flex', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
				'condition' => [
					'advance' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'data-flex-align', [
				'label' => esc_html__( 'Alignment', 'easy-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'condition' => [
					'advance' => 'yes',
					'table_body_data_flex' => 'yes',
				],
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'easy-elements' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'easy-elements' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'easy-elements' ),
						'icon' => 'eicon-text-align-right',
					],
					'space-between' => [
						'title' => esc_html__( 'Justified', 'easy-elements' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body .data-flex' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'flex-data-mid-gap',
				[
				'label' => esc_html__( 'Middle Gap', 'easy-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'condition' => [
					'advance' => 'yes',
					'table_body_data_flex' => 'yes',
				],				
				'selectors' => [ '{{WRAPPER}} table.easyel-table .easyel-table-body .data-flex' => 'gap: {{SIZE}}{{UNIT}};',
				]
			],
		);

		$repeater->add_control(
			'align', [
				'label' => esc_html__( 'Alignment', 'easy-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'condition' => [
					'advance' => 'yes',						
					'table_body_data_flex!' => 'yes',
				],
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'easy-elements' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'easy-elements' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'easy-elements' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'easy-elements' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body {{CURRENT_ITEM}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'vertical_align', [
				'label' => esc_html__( 'Vetical Alignment', 'easy-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'condition' => [
					'advance' => 'yes',
				],
				'options' => [
				'top' => [
					'title' => esc_html__( 'Top', 'easy-elements' ),
					'icon' => 'eicon-v-align-top',
				],
				'middle' => [
					'title' => esc_html__( 'Middle', 'easy-elements' ),
					'icon' => 'eicon-v-align-middle',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'easy-elements' ),
					'icon' => 'eicon-v-align-bottom',
				],
			],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body {{CURRENT_ITEM}}' => 'vertical-align: {{VALUE}};',
				],
			]
		);
		$repeater->add_control(
			'advanced_item_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
				]
			]
		);
		$repeater->add_control(
			'advanced_item_text_color',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
				]
			]
		);
		$repeater->add_control(
			'advanced_item_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body {{CURRENT_ITEM}} i' => 'color: {{VALUE}};',
					'{{WRAPPER}} table.easyel-table .easyel-table-body {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}};',
				]
			]
		);
		$repeater->add_control(
			'tb_cswidthS',
			[
				'label' => esc_html__( 'Custom Width', 'easy-elements' ),
				'type' => Controls_Manager::SELECT,
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'advance' => 'yes',
				],
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			]
		);	
		$repeater->add_responsive_control(
			'tb_cswidth',
			[
			'label' => esc_html__( 'Width', 'easy-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'condition' => [
				'advance' => 'yes',
				'tb_cswidthS' => 'yes',
			],
			'range' => [
				'%' => [
					'min' => 0,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
			],
			
			'selectors' => [ '{{WRAPPER}} table.easyel-table .easyel-table-body {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
			]
			],
		);

		$repeater->add_control(
			'decoration',
			[
				'label' => esc_html__( 'Decoration', 'easy-elements' ),
				'type' => Controls_Manager::SELECT,
				'condition' => [
					'advance' => 'yes',
				],
				'options' => [
					''  => esc_html__( 'Default', 'easy-elements' ),
					'underline' => esc_html__( 'Underline', 'easy-elements' ),
					'overline' => esc_html__( 'Overline', 'easy-elements' ),
					'line-through' => esc_html__( 'Line Through', 'easy-elements' ),
					'none' => esc_html__( 'None', 'easy-elements' ),
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} table.easyel-table {{CURRENT_ITEM}}' => 'text-decoration:{{VALUE}};',
				],
			]
		);	
		$repeater->add_control(
			'table_body_tooltip',
			[
			'label' => esc_html__( 'Tooltip Settings', 'easy-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'label_off' => esc_html__( 'No', 'easy-elements' ),
			'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			]
		);
		$repeater->add_control(
			'table_body_tooltip_icon',
			[
				'label' => esc_html__( 'Tooltip Icon', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-question-circle',
					'library' => 'fa-solid',
				],
				'condition' => [
					'table_body_tooltip' => 'yes',
				],
			]
		);
		$repeater->add_control(
			'table_body_tooltip_description',
			[
				'label' => esc_html__('Tooltip Description', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'placeholder' => esc_html__( 'Type your tooltip description here', 'easy-elements' ),
				'condition' => [
					'table_body_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_body',
			[
				'label' => esc_html__( 'Table Body Cell', 'easy-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),

				'default' => [
					[ 'text' => esc_html__( 'Table Data', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table Data', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table Data', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table Data', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table Data', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table Data', 'easy-elements' ) ],
				],

				'title_field' => '{{{ text }}}',
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'content_table_footer',
			[
				'label' => esc_html__( 'Table footer', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'table_footer',
			[
				'label' => esc_html__( 'Table footer Cell', 'easy-elements' ),
				'type' => Controls_Manager::REPEATER,
				'prevent_empty' => false,
				'default' => [
					[ 'text' => esc_html__( 'Table footer', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table footer', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table footer', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table footer', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table footer', 'easy-elements' ) ],
					[ 'text' => esc_html__( 'Table footer', 'easy-elements' ) ],						
				],

				'fields' => [
					[
						'name' => 'text',
						'label' => esc_html__( 'Text', 'easy-elements' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => esc_html__( 'Table footer', 'easy-elements' ),
						'default' => esc_html__( 'Table footer', 'easy-elements' ),
						'dynamic' => [
							'active' => true,
						]
					],
					[
						'name'	=> 'advance',
						'label' => esc_html__( 'Advance Settings', 'easy-elements' ),
						'type' => Controls_Manager::SWITCHER,
						'label_off' => esc_html__( 'No', 'easy-elements' ),
						'label_on' => esc_html__( 'Yes', 'easy-elements' ),
					],
					[
						'name'	=> 'colspan',
						'label' => esc_html__( 'colSpan', 'easy-elements' ),
						'type' => Controls_Manager::SWITCHER,
						'condition' => [
							'advance' => 'yes',
						],
						'label_off' => esc_html__( 'No', 'easy-elements' ),
						'label_on' => esc_html__( 'Yes', 'easy-elements' ),
					],
					[
						'name'	=> 'colspannumber',
						'label' => esc_html__( 'colSpan Number', 'easy-elements' ),
						'type' => Controls_Manager::TEXT,
						'condition' => [
							'advance' => 'yes',
							'colspan' => 'yes',
						],
						'placeholder' => esc_html__( '1', 'easy-elements' ),
						'default' => esc_html__( '1', 'easy-elements' ),
					],
					[
						'name'	=> 'customwidth',
						'label' => esc_html__( 'Custom Width', 'easy-elements' ),
						'type' => Controls_Manager::SWITCHER,
						'condition' => [
							'advance' => 'yes',
						],
						'label_off' => esc_html__( 'No', 'easy-elements' ),
						'label_on' => esc_html__( 'Yes', 'easy-elements' ),
					],
					[
						'name'	=> 'width',
						'label' => esc_html__( 'Width', 'easy-elements' ),
						'type' => Controls_Manager::SLIDER,
						'condition' => [
							'advance' => 'yes',
							'customwidth' => 'yes',
						],
						'range' => [
							'%' => [
								'min' => 0,
								'max' => 100,
							],
							'px' => [
								'min' => 1,
								'max' => 1000,
							],
						],
						'default' => [
							'size' => 30,
							'unit' => '%',
						],
						'size_units' => [ '%', 'px' ],
						'selectors' => [ '{{WRAPPER}} table.easyel-table .easyel-table-footer {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
						]
					],
					[
						'name' => 'align', 
						'label' => esc_html__( 'Alignment', 'easy-elements' ),
						'type' => Controls_Manager::CHOOSE,
						'condition' => [
							'advance' => 'yes',
						],
						'options' => [
							'left' => [
								'title' => esc_html__( 'Left', 'easy-elements' ),
								'icon' => 'eicon-text-align-left',
							],
							'center' => [
								'title' => esc_html__( 'Center', 'easy-elements' ),
								'icon' => 'eicon-text-align-center',
							],
							'right' => [
								'title' => esc_html__( 'Right', 'easy-elements' ),
								'icon' => 'eicon-text-align-right',
							],
							'justify' => [
								'title' => esc_html__( 'Justified', 'easy-elements' ),
								'icon' => 'eicon-text-align-justify',
							],
						],
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} table.easyel-table .easyel-table-footer {{CURRENT_ITEM}}' => 'text-align: {{VALUE}};',
						]
					],
					[
						'name' => 'vertical_align', 
						'label' => esc_html__( 'Vertical Alignment', 'easy-elements' ),
						'type' => Controls_Manager::CHOOSE,
						'condition' => [
							'advance' => 'yes',
						],
						'options' => [
							'top' => [
								'title' => esc_html__( 'Top', 'easy-elements' ),
								'icon' => 'eicon-v-align-up',
							],
							'middle' => [
								'title' => esc_html__( 'Middle', 'easy-elements' ),
								'icon' => 'eicon-v-align-middle',
							],
							'bottom' => [
								'title' => esc_html__( 'Bottom', 'easy-elements' ),
								'icon' => 'eicon-v-align-bottom',
							],
						],
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} table.easyel-table .easyel-table-footer {{CURRENT_ITEM}}' => 'text-align: {{VALUE}};',
						]
					],
					
					[
						'name'	=> 'decoration',
						'label' => esc_html__( 'Decoration', 'easy-elements' ),
						'type' => Controls_Manager::SELECT,
						'condition' => [
							'advance' => 'yes',
						],
						'options' => [
							''  => esc_html__( 'Default', 'easy-elements' ),
							'underline' => esc_html__( 'Underline', 'easy-elements' ),
							'overline' => esc_html__( 'Overline', 'easy-elements' ),
							'line-through' => esc_html__( 'Line Through', 'easy-elements' ),
							'none' => esc_html__( 'None', 'easy-elements' ),
						],
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} table.easyel-table .easyel-table-footer {{CURRENT_ITEM}}' => 'text-decoration: {{VALUE}};',
						],
					],
					[
						'name'	=> 'table_foot_tooltip',
						'label' => esc_html__( 'Tooltip Settings', 'easy-elements' ),
						'type' => Controls_Manager::SWITCHER,
						'label_off' => esc_html__( 'No', 'easy-elements' ),
						'label_on' => esc_html__( 'Yes', 'easy-elements' ),
					],
					[
						'name'	=> 'table_foot_tooltip_icon',
						'label' => esc_html__( 'Tooltip Icon', 'easy-elements' ),
						'type' => \Elementor\Controls_Manager::ICONS,
						'default' => [
							'value' => 'fas fa-question-circle',
							'library' => 'fa-solid',
						],
						'condition' => [
							'table_foot_tooltip' => 'yes',
						],
					],
					[
						'name'	=> 'table_foot_tooltip_description',
						'label' => esc_html__('Tooltip Description', 'easy-elements' ),
						'type' => \Elementor\Controls_Manager::TEXTAREA,
						'rows' => 10,
						'placeholder' => esc_html__( 'Type your tooltip description here', 'easy-elements' ),
						'condition' => [
							'table_foot_tooltip' => 'yes',
						],
					],
				],
				'title_field' => '{{{ text }}}',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'General Style', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'vertical_align_table', [
				'label' => esc_html__( 'Vetical Alignment', 'easy-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
				'top' => [
					'title' => esc_html__( 'Top', 'easy-elements' ),
					'icon' => 'eicon-v-align-top',
				],
				'middle' => [
					'title' => esc_html__( 'Middle', 'easy-elements' ),
					'icon' => 'eicon-v-align-middle',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'easy-elements' ),
					'icon' => 'eicon-v-align-bottom',
				],
			],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body td' => 'vertical-align: {{VALUE}};',
					'{{WRAPPER}} table.easyel-table .easyel-table-body th' => 'vertical-align: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'table_margin',
			[
				'label' => esc_html__( 'Table Margin', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'table_padding',
			[
				'label' => esc_html__( 'Table Padding', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.easyel-table tbody td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'table_border',
				'label' => esc_html__( 'Border', 'easy-elements' ),
				'selector' => '{{WRAPPER}} table.easyel-table tbody',
			]
		);
		$this->add_responsive_control(
			'table_radius',
			[
				'label' => esc_html__( 'Table Border Radius', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'table_header_style',
			[
				'label' => esc_html__( 'Table Header Style', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'header_align',
			[
				'label' => esc_html__( 'Alignment', 'easy-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'easy-elements' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'easy-elements' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'easy-elements' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'easy-elements' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-header' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'header_text_color',
			[
				'label' => esc_html__( 'Text Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-header' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_typography',
				'selector' => '{{WRAPPER}} table.easyel-table .easyel-table-header',
				
			]
		);

		$this->add_control(
			'header_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-header' => 'background-color: {{VALUE}};',
				]
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'body_head_border',
				'selector' => '{{WRAPPER}} .table.easyel-table .easyel-table-header th',
			]
		);
		$this->add_responsive_control(
			'table_thead_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-header th' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'table_thead_padding',
			[
				'label' => esc_html__( 'Padding', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-header th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// header icon settings 
		$this->add_control(
			'header_icon_styles',
			[
				'label' => esc_html__( 'Icon Style', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'header_icon_color',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-header-icon i' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} table.easyel-table .easyel-header-icon svg path' => 'fill: {{VALUE}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'header_icon_size',
			[
				'label' => esc_html__( 'Size', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-header-icon i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} table.easyel-table .easyel-header-icon svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'header_icon_y_position',
			[
				'label' => esc_html__( 'Pisition ( Vertical )', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-header-icon' => 'top: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'header_icon_margin',
			[
				'label' => esc_html__( 'Margin', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-header-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.easyel-table .easyel-header-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();


		$this->start_controls_section(
			'table_body_style',
			[
				'label' => esc_html__( 'Table Body Style', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'body_align',
			[
				'label' => esc_html__( 'Alignment', 'easy-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'easy-elements' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'easy-elements' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'easy-elements' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'easy-elements' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'body_text_color',
			[
				'label' => esc_html__( 'Text Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body' => 'color: {{VALUE}};',
				]
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'body_typography',
				'selector' => '{{WRAPPER}} table.easyel-table .easyel-table-body',
				
			]
		);

		$this->add_control(
			'body_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'striped_bg', 
			[
				'label' => esc_html__( 'Striped Background', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			]
		);
		$this->add_control(
			'striped_bg_color', 
			[
				'label' => esc_html__( 'Secondary Background Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'striped_bg' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body tr:nth-of-type(2n)' => 'background-color: {{VALUE}};',
				]
			]
		);
		$this->add_control(
			'body_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body td i' => 'color: {{VALUE}};',
					'{{WRAPPER}} table.easyel-table .easyel-table-body td svg path' => 'fill: {{VALUE}};',
				]
			]
		);
		$this->add_control(
			'body_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .table.easyel-table .easyel-table-body td i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .table.easyel-table .easyel-table-body td svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'body_icon_gap',
			[
				'label' => esc_html__( 'Icon Margin', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body td i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.easyel-table .easyel-table-body td svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'table_tbody_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body td' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'table_tbody_padding',
			[
				'label' => esc_html__( 'Padding', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'table_tbody_margin',
			[
				'label' => esc_html__( 'Margin', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-body td' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.easyel-table .easyel-table-body td p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'body_border',
				'selector' => '{{WRAPPER}} .table.easyel-table .easyel-table-body td',
			]
		);
		$this->add_control(
			'tooltip_heading',
			[
				'label' => esc_html__( 'Tooltip Options', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'tooltip_icon_color',
			[
				'label' => esc_html__( 'Tooltip Icon Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-tooltip i' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} table.easyel-table .easyel-tooltip svg path' => 'fill: {{VALUE}} !important;',
				]
			]
		);
		$this->add_control(
			'tooltip_icon_size',
			[
				'label' => esc_html__( 'Tooltip Icon Size', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .table.easyel-table .easyel-tooltip i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .table.easyel-table .easyel-tooltip svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);
		$this->add_responsive_control(
			'tooltip_icon_margin',
			[
				'label' => esc_html__( 'Tooltip Icon Margin', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .table.easyel-table .easyel-tooltip i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .table.easyel-table .easyel-tooltip svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'tooltip_align',
			[
				'label' => esc_html__( 'Tooltip Align', 'easy-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'easy-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' => esc_html__( 'Top', 'easy-elements' ),
						'icon' => 'eicon-v-align-top',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'easy-elements' ),
						'icon' => 'eicon-h-align-right',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'easy-elements' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'left',
				'toggle' => true,
			]
		);
		
		// image style 
		$this->add_control(
			'table_img_heading',
			[
				'label' => esc_html__( 'Image Options', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'table_img_size',
			[
				'label' => esc_html__( 'Size', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .easyel-table-body .easyel-table-image' => 'max-width: {{SIZE}}{{UNIT}} !important;height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'table_img_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .easyel-table-body .easyel-table-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'table_footer_style',
			[
				'label' => esc_html__( 'Table footer Style', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'footer_align',
			[
				'label' => esc_html__( 'Alignment', 'easy-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'easy-elements' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'easy-elements' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'easy-elements' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'easy-elements' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-footer' => 'text-align: {{VALUE}};',
				],
			]
		);
		

		$this->add_control(
			'footer_text_color',
			[
				'label' => esc_html__( 'Text Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-footer' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'footer_typography',
				'selector' => '{{WRAPPER}} table.easyel-table .easyel-table-footer',
				
			]
		);

		$this->add_control(
			'footer_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-footer' => 'background-color: {{VALUE}};',
				]
			]
		);
		$this->add_responsive_control(
			'table_tfoot_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-footer th' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'table_tfoot_padding',
			[
				'label' => esc_html__( 'Padding', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} table.easyel-table .easyel-table-footer th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'body_foot_border',
				'selector' => '{{WRAPPER}} .table.easyel-table .easyel-table-footer th',
			]
		);

		$this->end_controls_section();

	}
	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$unique = wp_rand(10,6554120);	
		$tooltip_align = $settings['tooltip_align'];
		?>
		<!-- Tooltip element -->
		<table class="easyel-table table single-plan" id="table-<?php echo esc_attr($unique);?>">
			<?php if ($settings['table_header']):?>
				<thead  class="easyel-table-header">
					<tr class="single-plan__header">
						<?php
						foreach ( $settings['table_header'] as $index => $headeritem ) {

							$repeater_setting_key = $this->get_repeater_setting_key( 'text', 'table_header', $index );
							$this->add_inline_editing_attributes( $repeater_setting_key );
				
							$colspan = ( isset( $headeritem['colspan'], $headeritem['advance'] ) && $headeritem['colspan'] === 'yes' && $headeritem['advance'] === 'yes' ) ? 'colspan="' . esc_attr( $headeritem['colspannumber'] ) . '"' : '';

							echo '<th class="header-title elementor-inline-editing elementor-repeater-item-' . esc_attr( $headeritem['_id'] ) . '" '
								. esc_attr( $colspan ) . ' '
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								. $this->get_render_attribute_string( $repeater_setting_key ) . '>';

							// Header Icon
							if ( isset( $headeritem['header_icon'] ) && $headeritem['header_icon'] === 'yes' && ! empty( $headeritem['table_head_icon']['value'] ) ) {
								echo '<span class="easyel-header-icon">';
								
								// Render icon safely
								\Elementor\Icons_Manager::render_icon(
									$headeritem['table_head_icon'],
									[ 'aria-hidden' => 'true' ]
								);

								echo '</span>';
							}

							echo wp_kses_post( $headeritem['text'] );

							if ( isset( $headeritem['table_head_tooltip'] ) && $headeritem['table_head_tooltip'] === 'yes' ) {
								echo '<span class="easyel-tooltip" '
									. 'data-bs-custom-class="tooltip-table-title" '
									. 'data-bs-toggle="tooltip" '
									. 'data-bs-placement="' . esc_attr( $tooltip_align ) . '" '
									. 'title="' . esc_attr( $headeritem['table_head_tooltip_description'] ) . '">';

								\Elementor\Icons_Manager::render_icon(
									$headeritem['table_head_tooltip_icon'],
									[ 'aria-hidden' => 'true' ]
								);

								echo '</span>';
							}

							echo '</th>';
						}
						?>
					</tr>
				</thead>
			<?php endif;?>
			<?php if ($settings['table_body']):?>
				<tbody class="easyel-table-body">
					<tr class="single-plan__content">
						<?php
							foreach ( $settings['table_body'] as $index => $item ) {

								// Flex data condition
								$flex_data = ( !empty( $item['table_body_data_flex'] ) && $item['table_body_data_flex'] === 'yes' ) ? 'data-flex' : '';

								$table_icon = ! empty( $item['table_icon'] ) ? $item['table_icon'] : '';


								$table_body_key = $this->get_repeater_setting_key( 'text', 'table_body', $index );
								
								$this->add_render_attribute( $table_body_key, 'class', 'plan-title ' . $flex_data . ' elementor-repeater-item-' . esc_attr( $item['_id'] ) );

								$this->add_inline_editing_attributes( $table_body_key );

								if ( isset( $item['row'] ) && $item['row'] === 'yes' ) {
									echo '</tr><tr class="single-plan__content">';
								}

								$colspan = ( isset( $item['colspan'], $item['advance'] ) && $item['colspan'] === 'yes' && $item['advance'] === 'yes' )
									? 'colspan="' . esc_attr( $item['colspannumber'] ) . '"'
									: '';

								$rowspan = ( isset( $item['rowspan'], $item['advance'] ) && $item['rowspan'] === 'yes' && $item['advance'] === 'yes' )
									? 'rowspan="' . esc_attr( $item['rowspannumber'] ) . '"'
									: '';
									
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo '<td ' . $colspan . ' ' . $rowspan . ' ' . $this->get_render_attribute_string( $table_body_key ) . '>';

								// table icon 
								if ( ! empty( $table_icon ) ) {
									\Elementor\Icons_Manager::render_icon( $table_icon, [ 'aria-hidden' => 'true' ] );
								}

								// table image 
								$image_id  = ! empty( $item['table_image']['id'] ) ? absint( $item['table_image']['id'] ) : '';
								$image_url = ! empty( $item['table_image']['url'] ) ? esc_url( $item['table_image']['url'] ) : '';
								// Case 1: Attachment ID exists and is an image
								if ( $image_id && wp_attachment_is_image( $image_id ) ) {
									echo wp_get_attachment_image(
										$image_id,
										'thumbnail',
										false,
										[
											'class' => 'easyel-table-image',
											'alt'   => esc_attr( get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ),
										]
									);
								}
								
								// Case 2: Fallback to URL
								elseif ( $image_url ) {
									echo '<img src="' . esc_url( $image_url ) . '" class="easyel-table-image" alt="">';
								}		

								echo wp_kses_post( $item['text'] );

								if ( isset( $item['table_body_tooltip'] ) && $item['table_body_tooltip'] === 'yes' ) {
									echo '<span class="easyel-tooltip" '
										. 'data-bs-custom-class="tooltip-table-title" '
										. 'data-bs-toggle="tooltip" '
										. 'data-bs-placement="' . esc_attr( $tooltip_align ) . '" '
										. 'title="' . esc_attr( $item['table_body_tooltip_description'] ) . '">';
									
									\Elementor\Icons_Manager::render_icon(
										$item['table_body_tooltip_icon'], 
										[ 'aria-hidden' => 'true' ]
									);
									echo '</span>';
								}

								echo '</td>';
							}
						?>
					</tr>
				</tbody>
			<?php endif;?>
			<?php if ($settings['table_footer']):?>
				<tfoot class="easyel-table-footer">
					<tr class="easyel-single-plane-footer">
						<?php
						foreach ( $settings['table_footer'] as $index => $footeritem ) {

							$repeater_setting_key = $this->get_repeater_setting_key( 'text', 'table_footer', $index );
							$this->add_inline_editing_attributes( $repeater_setting_key );

							$colspan = ( $footeritem['colspan'] === 'yes' && $footeritem['advance'] === 'yes' ) 
								? 'colspan="' . esc_attr( $footeritem['colspannumber'] ) . '"' 
								: '';

							echo '<th class="footer-title elementor-inline-editing elementor-repeater-item-' . esc_attr( $footeritem['_id'] ) . '" ' 
								.  esc_attr( $colspan ) . ' ' 
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								. $this->get_render_attribute_string( $repeater_setting_key ) 
								. '>' 
								. wp_kses_post( $footeritem['text'] );

							if ( isset( $footeritem['table_foot_tooltip'] ) && $footeritem['table_foot_tooltip'] === 'yes' ) {
								echo '<span class="easyel-tooltip" '
									. 'data-bs-custom-class="tooltip-table-title" '
									. 'data-bs-toggle="tooltip" '
									. 'data-bs-placement="' . esc_attr( $tooltip_align ) . '" '
									. 'title="' . esc_attr( $footeritem['table_foot_tooltip_description'] ) . '">';

								\Elementor\Icons_Manager::render_icon(
									$footeritem['table_foot_tooltip_icon'], 
									[ 'aria-hidden' => 'true' ]
								);

								echo '</span>';
							}

							echo '</th>';
						}
						?>
					</tr>
				</tfoot>
			<?php endif;?>
		</table>
		
		<?php
	}
}
