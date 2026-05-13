<?php
namespace Easyel\EasyElements\Widgets;
/**
 * Easy Elements Login Register Widget
 * @package EasyElements
 * @since 1.0.0
 */
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * 
 * Handles the login register widget functionality for Elementor.
 */
class Easyel_Login_Register_Widget extends \Elementor\Widget_Base {
	
	/**
	 * Get widget style dependencies
	 * 
	 * Loads the CSS file for the widget styling
	 * 
	 * @return array Array of style handles
	*/
	/**
	 * Get widget name
	 * 
	 * @return string Widget name
	 */
	public function get_name() {
		return 'eel-login-register';
	}

	/**
	 * Get widget title
	 * 
	 * @return string Widget title
	 */
	public function get_title() {
		return __( 'Login / Register', 'easy-elements' );
	}

	/**
	 * Get widget icon
	 * 
	 * @return string Widget icon
	 */
	public function get_icon() {
		return 'easyicon easyelIcon-login';
	}

	/**
	 * Get widget categories
	 * 
	 * @return array Widget categories
	 */
	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
        return [ 'login', 'register', 'from', 'text', 'form'  ];
    }

	public function get_style_depends() {
        return [
            'eel-login-register',
        ];
    }

	public function get_script_depends() {
        return [
            'eel-login-register',
        ];
    }


	/**
	 * Register widget controls
	 * 
	 * Defines all the controls for the widget
	 */
	protected function register_controls() {
		global $wp_roles;
		$role_options = $wp_roles->get_names();
		// ========================================
		// CONTENT SECTION - Form Settings
		// ========================================
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Form', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'form_type',
			[
				'label' => esc_html__( 'Type', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'login',
				'options' => [
					'login' => esc_html__( 'Login', 'easy-elements' ),
					'register' => esc_html__( 'Register', 'easy-elements' ),
					'both' => esc_html__( 'Both', 'easy-elements' ),
				],
			]
		);
		$this->add_control(
			'redirect_link_after_login',
			[
				'label' => esc_html__( 'Redirect Link After Login', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::URL,
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'default' => [
					'url' => home_url(),
					'is_external' => true,
					'nofollow' => true,
				],
				'label_block' => true,
				'condition' => [
					'form_type' => ['login', 'both'],
				],
			]
		);


		$this->add_control(
			'role',
			[
				'label' => 'Role',
				'type'  => \Elementor\Controls_Manager::SELECT,
				'options' => $role_options,
				'multiple' => false,
				'default' => 'subscriber'
			]
		);
		
		$this->add_control(
			'auto_login_after_registration',
			[
				'label' => esc_html__( 'Auto Login After Registration', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
						'form_type' => ['register', 'both'],
					],
			]
		);
		$this->add_control(
			'redirect_after_registration',
			[
				'label' => esc_html__( 'Redirect After Logout', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
						'form_type' => ['register', 'both'],
					],
			]
		);
		$this->add_control(
			'redirect_link_after_registration',
			[
				'label' => esc_html__( 'Redirect Link After Registration', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::URL,
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'default' => [
					'url' => home_url(),
					'is_external' => true,
					'nofollow' => true,
					// 'custom_attributes' => '',
				],
				'label_block' => true,
				'condition' => [
						'form_type' => ['register', 'both'],
					],
			]
		);
		
		$this->add_control(
			'show_password_reset_link',
			[
				'label' => esc_html__( 'Show Password Reset Link', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'easy-elements' ),
				'label_off' => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
						'form_type' => ['login', 'both'],
					],
			]
		);
		$this->add_control(
			'remember_me',
			[
				'label' => esc_html__( 'Show Remember Me', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'easy-elements' ),
				'label_off' => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'form_type' => ['login', 'both'],
				],
			]
		);
		$this->add_control(
			'show_ajax_loader',
			[
				'label' => esc_html__( 'Show Ajax Loader', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'easy-elements' ),
				'label_off' => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->end_controls_section();


		// ========================================
		// CONTENT SECTION - Fields Settings
		// ========================================
		$this->start_controls_section(
			'fields_section',
			[
				'label' => esc_html__( 'Fields', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'login_fields',
			[
				'label'       => esc_html__( 'Login Fields', 'easy-elements' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => [
					// Type Field
					[
						'name'    => 'type',
						'label' => esc_html__( 'Type', 'easy-elements' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'username',
						'options' => [
							'username' => esc_html__( 'Username', 'easy-elements' ),
							'password'  => esc_html__( 'Password', 'easy-elements' ),
							'static_text' => esc_html__( 'Static Text', 'easy-elements' ),
						],
					],
					// Label Field
					[
						'name'    => 'label',
						'label'   => esc_html__( 'Label', 'easy-elements' ),
						'type'    => Controls_Manager::TEXT,
						'condition' => [
							'type!' => 'static_text',
						],
					],
					// Placeholder Field
					[
						'name'    => 'placeholder',
						'label'   => esc_html__( 'Placeholder', 'easy-elements' ),
						'type'    => Controls_Manager::TEXT,
						'condition' => [
							'type!' => 'static_text',
						],
					],
					// Icon Field
					[
						'name'    => 'icon',
						'label'   => esc_html__( 'Icon', 'easy-elements' ),
						'type'    => Controls_Manager::ICONS,
						'condition' => [
							'type!' => 'static_text',
						],
					],
					[
						'name'    => 'is_required',
						'label' => esc_html__( 'Is Required', 'easy-elements' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'easy-elements' ),
						'label_off' => esc_html__( 'No', 'easy-elements' ),
						'return_value' => 'yes',
						'default' => 'yes',
						'condition' => [
							'type!' => 'static_text',
						],
					],
					[
						'name'    => 'static_text',
						'label' => esc_html__('Static Text', 'easy-elements'),
						'type' => Controls_Manager::WYSIWYG,
						'label_block' => true,
						'default' => esc_html__("This is static field.", "easy-elements"),
						'dynamic'     => [
							'active' => true,
						],
						'condition' => [
							'type' => 'static_text',
						],
					]
				],
				
				// Default register fields
				'default'     => [
					[
						'type'              => 'username',
						'label'             => esc_html__( 'Username', 'easy-elements' ),
						'placeholder'       => esc_html__( 'Username', 'easy-elements' ),
						'is_required'       => 'yes',
					],
					[
						'type'              => 'password',
						'label'             => esc_html__( 'Password', 'easy-elements' ),
						'placeholder'       => esc_html__( 'Password', 'easy-elements' ),
						'is_required'       => 'yes',
					],
				],
				'title_field' => '{{{ type }}}',
				'condition' => [
						'form_type' => ['login', 'both'],
					],
			]
		);

		$this->add_control(
			'register_fields',
			[
				'label'       => esc_html__( 'Register Fields', 'easy-elements' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => [
					// Type Field
					[
						'name'    => 'type',
						'label' => esc_html__( 'Type', 'easy-elements' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'username',
						'options' => [
							'user_login' => esc_html__( 'Username', 'easy-elements' ),
							'user_email' => esc_html__( 'Email', 'easy-elements' ),
							'user_pass'  => esc_html__( 'Password', 'easy-elements' ),
							'confirm_password' => esc_html__( 'Confirm Password', 'easy-elements' ),
							'first_name' => esc_html__( 'First Name', 'easy-elements' ),
							'last_name' => esc_html__( 'Last Name', 'easy-elements' ),
							'display_name' => esc_html__( 'Display Name', 'easy-elements' ),
							'user_nicename' => esc_html__( 'Nice Name', 'easy-elements' ),
							'nickname ' => esc_html__( 'Nice Name', 'easy-elements' ),
							'user_url' => esc_html__( 'Website', 'easy-elements' ),
							'description' => esc_html__( 'Description', 'easy-elements' ),
							'consent' => esc_html__( 'Consent', 'easy-elements' ),
							'custom' => esc_html__( 'Custom', 'easy-elements' ),
							'static_text' => esc_html__( 'Static Text', 'easy-elements' ),
						],
					],
					// Custom User Meta Key Field
					[
						'name'    => 'meta_key',
						'label'   => esc_html__( 'Meta Key', 'easy-elements' ),
						'type'    => Controls_Manager::TEXT,
						'condition' => [
							'type' => 'custom',
						],
					],
					// Label Field
					[
						'name'    => 'label',
						'label'   => esc_html__( 'Label', 'easy-elements' ),
						'type'    => Controls_Manager::TEXT,
						'condition' => [
							'type!' => 'static_text',
						],
					],
					// Placeholder Field
					[
						'name'    => 'placeholder',
						'label'   => esc_html__( 'Placeholder', 'easy-elements' ),
						'type'    => Controls_Manager::TEXT,
						'condition' => [
							'type!' => 'static_text',
						],
					],
					// Icon Field
					[
						'name'    => 'icon',
						'label'   => esc_html__( 'Icon', 'easy-elements' ),
						'type'    => Controls_Manager::ICONS,
						'condition' => [
							'type!' => 'static_text',
						],
					],
					[
						'name'    => 'min_length',
						'label'   => esc_html__( 'Min Length', 'easy-elements' ),
						'type'    => Controls_Manager::NUMBER,
						'condition' => [
							'type!' => 'static_text',
						],
					],
					[
						'name'    => 'max_length',
						'label'   => esc_html__( 'Max Length', 'easy-elements' ),
						'type'    => Controls_Manager::NUMBER,
						'condition' => [
							'type!' => 'static_text',
						],
					],
					[
						'name'    => 'is_required',
						'label' => esc_html__( 'Is Required', 'easy-elements' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'easy-elements' ),
						'label_off' => esc_html__( 'No', 'easy-elements' ),
						'return_value' => 'yes',
						'default' => 'yes',
						'condition' => [
							'type!' => 'static_text',
						],
					],
					[	
						'name'    => 'static_text',
						'label' => esc_html__('Static Text', 'easy-elements'),
						'type' => Controls_Manager::WYSIWYG,
						'label_block' => true,
						'default' => esc_html__("Don't have account? Sign Up", "easy-elements"),
						'dynamic'     => [
							'active' => true,
						],
						'condition' => [
							'type' => 'static_text',
						],
					],
					[	
						'name'    => 'width',
						'label' => esc_html__('Width', 'easy-elements'),
						'type' => Controls_Manager::SLIDER,
						'size_units' => ['px', '%', 'em'],
						'range' => [
							'px' => [
								'min' => 10,
								'max' => 50,
							],
							'em' => [
								'min' => 0.5,
								'max' => 10,
							],
							'%' => [
								'min' => 2,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => '%',
						],
					]					
				],
				
				// Default register fields
				'default'     => [
					[
						'type'              => 'user_login',
						'label'             => esc_html__( 'Username', 'easy-elements' ),
						'placeholder'       => esc_html__( 'Username', 'easy-elements' ),
						'is_required'       => 'yes',
					],
					[
						'type'              => 'user_email',
						'label'             => esc_html__( 'Email', 'easy-elements' ),
						'placeholder'       => esc_html__( 'Email', 'easy-elements' ),
						'is_required'       => 'yes',
					],
					[
						'type'              => 'user_pass',
						'label'             => esc_html__( 'Password', 'easy-elements' ),
						'placeholder'       => esc_html__( 'Password', 'easy-elements' ),
						'is_required'       => 'yes',
						'min_length'       => 8,
					],
				],
				'title_field' => '{{{ type }}}',
				'condition' => [
					'form_type' => ['register', 'both'],
				],
			]
		);

		$this->add_control(
			'show_label',
			[
				'label' => esc_html__( 'Show Label', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'easy-elements' ),
				'label_off' => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition'    => [
					'show_icon!' => 'yes',
				],
			]
		);
		$this->add_control(
			'show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'easy-elements' ),
				'label_off' => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'description' => esc_html__( 'Enable this option to display an icon field inside the repeater items.', 'easy-elements' ),
			]
		);

		$this->add_control(
			'login_fields_labels',
			[
				'label' => esc_html__( 'Labels', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'form_type' => ['login', 'both'],
				],
			]
		);
		$this->add_control(
			'password_reset_text',
			[
				'label' => esc_html__( 'Password Reset Text', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Forgot your password?', 'easy-elements' ),
				'placeholder' => esc_html__( 'Type your text here', 'easy-elements' ),
				'condition' => [
					'form_type' => ['login', 'both'],
					'show_password_reset_link' => ['yes'],
				],
			]
		);
		$this->add_control(
			'remember_label_text',
			[
				'label' => esc_html__('Remember', 'easy-elements'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__('Remember Me', 'easy-elements'),
				'placeholder' => esc_html__('Enter text here', 'easy-elements'),
				'dynamic'     => [
					'active' => true,
				],
				'condition' => [
					'form_type' => ['login', 'both'],
				],
			]
		);
		$this->add_control(
			'cta_text',
			[
				'label' => esc_html__('Text', 'easy-elements'),
				'type' => Controls_Manager::WYSIWYG,
				'label_block' => true,
				'default' => esc_html__("Don't have account? Sign Up", "easy-elements"),
				'dynamic'     => [
					'active' => true,
				],
			]
		);
		$this->end_controls_section();


		// ========================================
		// CONTENT SECTION - Submit Button
		// ========================================
		$this->start_controls_section(
			'submit_button_section',
			[
				'label' => esc_html__( 'Submit', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'submit_button_text',
			[
				'label' => esc_html__('Text', 'easy-elements'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__('Log In', 'easy-elements'),
				'placeholder' => esc_html__('Enter text here', 'easy-elements'),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'submit_button_icon',
			[
				'label' => esc_html__('Icon', 'easy-elements'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => '',
					'library' => '',
				],
			]
		);

		$this->add_control(
			'submit_button_icon_position',
			[
				'label' => esc_html__('Icon Position', 'easy-elements'),
				'type' => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => [
					'before' => esc_html__('Before Text', 'easy-elements'),
					'after' => esc_html__('After Text', 'easy-elements'),
				],
				'condition' => [
					'submit_button_icon[value]!' => '',
				],
			]
		);
		$this->end_controls_section();


		// ========================================
		// CONTENT SECTION - Message
		// ========================================
		$this->start_controls_section(
			'message_section',
			[
				'label' => esc_html__( 'Message', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'show_success_message',
			[
				'label' => esc_html__( 'Show Success Message', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'easy-elements' ),
				'label_off' => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'login_success_message',
			[
				'label' => esc_html__( 'Login Succeess Message', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Login Success!', 'easy-elements' ),
				'placeholder' => esc_html__( 'Type your message here', 'easy-elements' ),
				'condition' => [
					'form_type' => ['login', 'both'],
					'show_success_message' => ['yes'],
				],
			]
		);
		$this->add_control(
			'register_success_message',
			[
				'label' => esc_html__( 'Register Succeess Message', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Registration Success!', 'easy-elements' ),
				'placeholder' => esc_html__( 'Type your message here', 'easy-elements' ),
				'condition' => [
						'form_type' => ['register', 'both'],
						'show_success_message' => ['yes'],
					],
			]
		);
		$this->add_control(
			'show_error_message',
			[
				'label' => esc_html__( 'Show Failed Message', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'easy-elements' ),
				'label_off' => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'login_error_message',
			[
				'label' => esc_html__( 'Login Failed Message', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Login failed! Enter correct email & password.', 'easy-elements' ),
				'placeholder' => esc_html__( 'Type your message here', 'easy-elements' ),
				'condition' => [
						'form_type' => ['login', 'both'],
						'show_error_message' => ['yes'],
					],
			]
		);
		$this->add_control(
			'register_error_message',
			[
				'label' => esc_html__( 'Register Failed Message', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Registration Failed!', 'easy-elements' ),
				'placeholder' => esc_html__( 'Type your message here', 'easy-elements' ),
				'condition' => [
						'form_type' => ['register', 'both'],
						'show_error_message' => ['yes'],
					],
			]
		);
		$this->add_control(
			'already_logged_in_message',
			[
				'label' => esc_html__( 'Already logged in Message', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'You are already logged in. Goto Homepage', 'easy-elements' ),
				'placeholder' => esc_html__( 'Type your message here', 'easy-elements' ),
			]
		);
		$this->add_control(
			'empty_error_message',
			[
				'label' => esc_html__( 'Empty Message', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'This field is required.', 'easy-elements' ),
				'placeholder' => esc_html__( 'Type your message here', 'easy-elements' ),
			]
		);
		$this->add_control(
			'confirm_pass_error_message',
			[
				'label' => esc_html__( 'Confirm Passowrd Error Message', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Confirm passowrd not matched', 'easy-elements' ),
				'placeholder' => esc_html__( 'Type your message here', 'easy-elements' ),
			]
		);
		$this->add_control(
			'min_length_error_message',
			[
				'label' => esc_html__( 'Minimum Characters Error Message', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Minimum {count} characters required.', 'easy-elements' ),
				'placeholder' => esc_html__( 'Type your message here', 'easy-elements' ),
			]
		);
		$this->add_control(
			'max_length_error_message',
			[
				'label' => esc_html__( 'Maximum Characters Error Message', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Maximum {count} characters required.', 'easy-elements' ),
				'placeholder' => esc_html__( 'Type your message here', 'easy-elements' ),
			]
		);
		$this->end_controls_section();

		// ========================================
		// STYLE SECTION - Fields Style
		// ========================================
		$this->start_controls_section(
			'form_fileds_style_section',
			[
				'label' => esc_html__( 'Fields', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'field_input_color',
			[
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-form-control' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'field_input_color_placeholder',
			[
				'label'     => esc_html__( 'Placeholder Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-form-control::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_wrapper_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-form-control, {{WRAPPER}} .eel-input-has-icon .eel-authentication-form-field:not(.eel-authentication-form-submit-field, {{WRAPPER}} .eel-authentication-form-cta-field) .eel-authentication-form-field-inner' => 'background-color: {{VALUE}};',
				],				
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'input_text_typography',
				'selector' => '{{WRAPPER}} .eel-authentication-form .eel-form-control',
			]
		);

		$this->add_control(
			'field_wrapper_padding',
			[
				'label' => esc_html__( 'Padding', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form .eel-authentication-form-field-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'input_margin',
			[
				'label' => esc_html__( 'Margin', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-input-has-icon .eel-authentication-form-field:not(.eel-authentication-form-submit-field, {{WRAPPER}} .eel-authentication-form-cta-field) .eel-authentication-form-field-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'input_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} form:not(.eel-input-has-icon) .eel-authentication-form-field:not(.eel-authentication-form-submit-field, {{WRAPPER}} .eel-authentication-form-cta-field) .eel-form-control, {{WRAPPER}} .eel-input-has-icon .eel-authentication-form-field:not(.eel-authentication-form-submit-field, {{WRAPPER}} .eel-authentication-form-cta-field) .eel-authentication-form-field-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'field_wrapper_border',
				'selector' => '{{WRAPPER}} form:not(.eel-input-has-icon) .eel-authentication-form-field:not(.eel-authentication-form-submit-field, {{WRAPPER}} .eel-authentication-form-cta-field) .eel-form-control, {{WRAPPER}} .eel-input-has-icon .eel-authentication-form-field:not(.eel-authentication-form-submit-field, {{WRAPPER}} .eel-authentication-form-cta-field) .eel-authentication-form-field-inner',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'field_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} form:not(.eel-input-has-icon) .eel-authentication-form-field:not(.eel-authentication-form-submit-field, {{WRAPPER}} .eel-authentication-form-cta-field) .eel-form-control, {{WRAPPER}} .eel-input-has-icon .eel-authentication-form-field:not(.eel-authentication-form-submit-field, {{WRAPPER}} .eel-authentication-form-cta-field) .eel-authentication-form-field-inner',
			]
		);

		$this->add_control(
			'field_icon_styles',
			[
				'label' => esc_html__( 'Icons', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_icon' => ['yes'],
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form .eel-form-field-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-authentication-form .eel-form-field-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'show_icon' => ['yes'],
				],
			]
		);
		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__('Icon Size', 'easy-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 50,
					],
					'em' => [
						'min' => 0.5,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form .eel-form-field-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-authentication-form .eel-form-field-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_icon' => ['yes'],
				],
			]
		);
		$this->end_controls_section();


		// ========================================
		// STYLE SECTION - Fields Style
		// ========================================
		$this->start_controls_section(
			'form_label_style_section',
			[
				'label' => esc_html__( 'Label', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_label' => ['yes'],
				],
			]
		);

		$this->add_control(
			'field_label_color',
			[
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form .eel-authentication-form-label' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_label' => ['yes'],
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'field_label_typography',
				'selector' => '{{WRAPPER}} .eel-authentication-form .eel-authentication-form-label',
				'separator' => 'before',
				'condition' => [
					'show_label' => ['yes'],
				],
			]
		);

		$this->add_control(
			'field_label_margin',
			[
				'label' => esc_html__( 'Margin', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form .eel-authentication-form-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_label' => ['yes'],
				],
			]
		);

		$this->end_controls_section();

		// ========================================
		// STYLE SECTION - Links Style
		// ========================================
		$this->start_controls_section(
			'links_style_section',
			[
				'label' => esc_html__( 'Links', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'links_color',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'links_color_hoevr',
			[
				'label' => esc_html__( 'Hover Color', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'links_typography',
				'selector' => '{{WRAPPER}} .eel-authentication-form a',
			]
		);

		// Border Control
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'links_border',
				'selector' => '{{WRAPPER}} .eel-authentication-form .eel-authentication-form-field-inner.eel-authentication-form-links-field',
			]
		);
		$this->end_controls_section();

		// ========================================
		// STYLE SECTION - Submit Button Style
		// ========================================
		$this->start_controls_section(
			'submit_button_style_section',
			[
				'label' => esc_html__( 'Submit', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('submit_button_style_tabs');

		$this->start_controls_tab(
			'submit_button_normal_tab',
			[
				'label' => esc_html__('Normal', 'easy-elements'),
			]
		);

		$this->add_control(
			'submit_button_color',
			[
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-submit-button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-form-btn-icon svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'submit_button_background_color',
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .eel-authentication-form .eel-submit-button',
			]
		);

		$this->add_control(
			'submit_button_wrapper_padding',
			[
				'label' => esc_html__( 'Padding', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form .eel-submit-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'submit_button_wrapper_margin',
			[
				'label' => esc_html__( 'Margin', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form .eel-submit-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Button Size Control
		$this->add_responsive_control(
			'submit_button_width',
			[
				'label' => esc_html__('Width', 'easy-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 500,
					],
					'em' => [
						'min' => 0.5,
						'max' => 10,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form-field.eel-authentication-form-submit-field' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);	

		// Border Radius Control
		$this->add_control(
			'submit_button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eel-authentication-form .eel-submit-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border Control
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'submit_button_border',
				'selector' => '{{WRAPPER}} .eel-authentication-form .eel-submit-button',
			]
		);

		// Box Shadow Control
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'submit_button_box_shadow',
				'selector' => '{{WRAPPER}} .eel-authentication-form .eel-submit-button',
			]
		);

		// Icon Size Control
		$this->add_control(
			'submit_button_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 50,
						'step' => 1,
					],
					'em' => [
						'min'  => 0.5,
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors'  => [
					'{{WRAPPER}} .eel-submit-button i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-submit-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Icon Horizontal Position
		$this->add_responsive_control(
			'submit_button_icon_position_x',
			[
				'label' => esc_html__( 'Icon Horizontal Position', 'easy-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [ 'min' => -100, 'max' => 100 ],
					'%'  => [ 'min' => -100, 'max' => 100 ],
					'em' => [ 'min' => -5, 'max' => 5 ],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-form-btn-icon' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Icon Vertical Position
		$this->add_responsive_control(
			'submit_button_icon_position_y',
			[
				'label' => esc_html__( 'Icon Vertical Position', 'easy-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [ 'min' => -100, 'max' => 100 ],
					'%'  => [ 'min' => -100, 'max' => 100 ],
					'em' => [ 'min' => -5, 'max' => 5 ],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-form-btn-icon' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_tab(); 

		$this->start_controls_tab(
			'submit_button_hover_tab',
			[
				'label' => esc_html__('Hover', 'easy-elements'),
			]
		);

		// Button Hover Color Control
		$this->add_control(
			'submit_button_hover_color',
			[
				'label'     => esc_html__( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form .eel-submit-button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-authentication-form .eel-submit-button:hover svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'submit_button_hover_bg_color',
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .eel-authentication-form .eel-submit-button:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_section();

		$this->start_controls_section(
			'message_style_section',
			[
				'label' => esc_html__( 'Message', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'message_typography',
				'selector' => '{{WRAPPER}} .eel-authentication-form .eel-form-status',
			]
		);

		$this->add_control(
			'msg_padding',
			[
				'label' => esc_html__( 'Padding', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form .eel-form-status' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'msg_margin',
			[
				'label' => esc_html__( 'Margin', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form .eel-form-status' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'success_message_styles',
			[
				'label' => esc_html__( 'Success Message', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'success_message_color',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form .eel-form-status .eel-form-success-msg' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'error_message_styles',
			[
				'label' => esc_html__( 'Failed Message', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);		
		$this->add_control(
			'error_message_color',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-authentication-form .eel-form-status .eel-form-error-msg' => 'color: {{VALUE}}',
				],
			]
		);
		

		$this->end_controls_section();
	}

	public function render_login_form($settings = []) {
		
		if ( is_user_logged_in() ) {
			$already_logged_in_message = !empty($settings['already_logged_in_message']) ? $settings['already_logged_in_message'] : 'You are already logged in. Goto Homepage';
		 	echo '<a href="'. esc_url(home_url()) .'">'. esc_html( $already_logged_in_message ) .'</a>';
			return;
		}

		$show_ajax_loader = !empty($settings['show_ajax_loader']) ? $settings['show_ajax_loader'] : 'yes';
		$show_label = !empty($settings['show_label']) ? $settings['show_label'] : '';
		$show_icon = !empty($settings['show_icon']) ? $settings['show_icon'] : '';
		$show_icons = !empty($settings['show_icon']) ? 'eel-input-has-icon' : '';
		$show_success_message = !empty($settings['show_success_message']) ? $settings['show_success_message'] : 'yes';
		$show_error_message = !empty($settings['show_error_message']) ? $settings['show_error_message'] : 'yes';
		$login_success_message = !empty($settings['login_success_message']) ? $settings['login_success_message'] : 'Login Success';
		$login_error_message = !empty($settings['login_error_message']) ? $settings['login_error_message'] : 'Login failed! Enter correct email & password.';
		$empty_error_message = !empty($settings['empty_error_message']) ? $settings['empty_error_message'] : 'This field is required.';

		$show_password_reset_link = !empty($settings['show_password_reset_link']) ? $settings['show_password_reset_link'] : 'yes';
		$password_reset_text = !empty($settings['password_reset_text']) ? $settings['password_reset_text'] : '';

		$remember_me = !empty($settings['remember_me']) ? $settings['remember_me'] : '';
		$remember_label_text = !empty($settings['remember_label_text']) ? $settings['remember_label_text'] : 'Remember Me';
		$submit_button_text = !empty($settings['submit_button_text']) ? $settings['submit_button_text'] : 'Log in';
		$submit_button_icon = !empty($settings['submit_button_icon']['value']) ? $settings['submit_button_icon'] : '';
		$submit_button_icon_position = !empty($settings['submit_button_icon_position']) ? $settings['submit_button_icon_position'] : '';

		$redirect_after_login = !empty($settings['redirect_after_login']) ? $settings['redirect_after_login'] : 'yes';
		$redirect_link_after_login = !empty($settings['redirect_link_after_login']['url']) ? $settings['redirect_link_after_login']['url'] : home_url();


		$cta_text = !empty($settings['cta_text']) ? $settings['cta_text'] : '';

		$login_fields = !empty($settings['login_fields']) ? $settings['login_fields'] : '';
	
		?>
		<form method="post" class="eel-authentication-form eel-login-form <?php echo esc_attr( $show_icons ); ?>">
			<input type="hidden" name="login_redirect_link" value="<?php echo esc_url( $redirect_link_after_login )?>">
			<input type="hidden" name="empty_error_msg" value="<?php echo esc_attr( $empty_error_message )?>">
			<?php 
				if(!empty($login_fields) && is_array($login_fields)) {
					foreach ($login_fields as $field) {
						$type = !empty($field['type']) ? $field['type'] : 'username';
						$id = 'eel_' . $type;
						$label = !empty($field['label']) ? $field['label'] : '';
						$placeholder = !empty($field['placeholder']) ? $field['placeholder'] : '';
						$icon = !empty($field['icon']['value']) ? $field['icon'] : '';
						$is_required = !empty($field['is_required']) ? 'required' : '';
						$static_text = !empty($field['static_text']) ? $field['static_text'] : '';
					

						if($type =='static_text' && !empty($static_text)) {
							?>
								<div class="eel-authentication-form-field eel-authentication-form-static-field">
									<div class="eel-authentication-form-field-inner">
										<div class="eel-form-control-wrap">
											<?php echo wp_kses_post( $static_text ); ?>
										</div>
									</div>
								</div>
							<?php
						}else{
							?>
							<div class="eel-authentication-form-field">
								<div class="eel-authentication-form-field-inner">
									<?php if ($show_icon == 'yes' && !empty($icon['value'])): ?>
										<span class="eel-form-field-icon">
											<?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?>
										</span>
									<?php endif; ?>
									<div class="eel-form-control-wrap">
										<?php 
											if($show_label == 'yes' && !empty($label)) {
												?>
													<label for="<?php echo esc_attr( $id ) ?>" class="eel-authentication-form-label"><?php echo esc_html( $label ); ?></label>
												<?php
											}
										?>
										<input type="<?php echo esc_attr( $type === 'password' ? 'password' : 'text' ); ?>" name="<?php echo esc_attr( $type ) ?>" class="eel-form-control" id="<?php echo esc_attr( $id ) ?>" placeholder="<?php echo esc_attr( $placeholder ) ?>" <?php echo esc_attr( $is_required ) ?> autocomplete />
									</div>
								</div>
								<div class="eel-error-msg" data-error-for="<?php echo esc_attr( $id )?>"></div>
							</div>
						<?php
						}


					}
				}
			?>
			<?php
				if($remember_me == 'yes' || $show_password_reset_link == 'yes') { ?>
				<div class="eel-authentication-form-field eel-authentication-form-checkbox-field">
					<div class="eel-authentication-form-field-inner">
						<div class="eel-form-control-wrap">
							<?php if($remember_me == 'yes') { ?>
								<label class="eel-authentication-form-label">
									<input type="checkbox" name="eel_rememberme" id="eel_rememberme" value="forever" />
									<?php echo esc_attr( $remember_label_text )?>
								</label>
							<?php } ?>
							<?php 
							if($show_password_reset_link == 'yes') { ?>
								<a href="<?php echo esc_url( wp_lostpassword_url() )?>" target="_blank"><?php echo esc_html( $password_reset_text ) ?></a>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>


			<div class="eel-authentication-form-field eel-authentication-form-submit-field">
				<div class="eel-authentication-form-field-inner">
					<div class="eel-form-control-wrap">
						<button type="submit" name="eel_login_submit" class="eel-submit-button">
						<?php 
							if(!empty($submit_button_icon) && $submit_button_icon_position == 'before') {
								?>
									<span class="eel-form-btn-icon">
										<?php \Elementor\Icons_Manager::render_icon( $submit_button_icon, [ 'aria-hidden' => 'true' ] ); ?>
									</span>
								<?php
							}
						?>
						<span class="eel-form-btn-text"><?php echo esc_attr( $submit_button_text )?></span>
						<?php 
							if(!empty($submit_button_icon) && $submit_button_icon_position == 'after') {
								?>
									<span class="eel-form-btn-icon">
										<?php \Elementor\Icons_Manager::render_icon( $submit_button_icon, [ 'aria-hidden' => 'true' ] ); ?>
									</span>
								<?php
							}
						?>
						<?php 
							if($show_ajax_loader == 'yes') { ?>
							<span class="eel-form-ajax-loader">
								<img src="<?php echo esc_url( EASYELEMENTS_DIR_URL . 'widgets/login-register/img/ajax-loader.gif' )?>" alt="ajax loader">
							</span>
						<?php } ?>
						</button>
					</div>
				</div>
			</div>
							
			<?php 
				if(!empty($cta_text)) { ?>
				<div class="eel-authentication-form-field eel-authentication-form-cta-field">
					<div class="eel-authentication-form-field-inner">
						<div class="eel-form-control-wrap">
							<?php echo wp_kses_post( $cta_text ); ?>
						</div>
					</div>
				</div>
			<?php } ?>
		
			<div class="eel-form-status">
				<?php 
					if($show_error_message == 'yes') {
						?>
							<p class="eel-form-error-msg"><?php echo esc_html( $login_error_message )?></p>
						<?php
					}
					if($show_success_message == 'yes') {
						?>
							<p class="eel-form-success-msg"><?php echo esc_html( $login_success_message )?></p>
						<?php
					}
				?>
			</div>
		
		</form>
		<?php
		
	}

	public function render_register_form($settings = []) {
		$show_ajax_loader = !empty($settings['show_ajax_loader']) ? $settings['show_ajax_loader'] : 'yes';
		$show_label = !empty($settings['show_label']) ? $settings['show_label'] : '';
		$show_icon = !empty($settings['show_icon']) ? $settings['show_icon'] : '';
		$show_icons = !empty($settings['show_icon']) ? 'eel-input-has-icon' : '';

		$show_success_message = !empty($settings['show_success_message']) ? $settings['show_success_message'] : 'yes';
		$show_error_message = !empty($settings['show_error_message']) ? $settings['show_error_message'] : 'yes';
		$register_success_message = !empty($settings['register_success_message']) ? $settings['register_success_message'] : 'Register Success';
		$register_error_message = !empty($settings['register_error_message']) ? $settings['register_error_message'] : 'Registration Failed';
		$empty_error_message = !empty($settings['empty_error_message']) ? $settings['empty_error_message'] : 'This field is required.';
		$confirm_pass_error_message = !empty($settings['confirm_pass_error_message']) ? $settings['confirm_pass_error_message'] : 'Confirm passowrd not matched';
		$min_length_error_message = !empty($settings['min_length_error_message']) ? $settings['min_length_error_message'] : 'Minimum {count} characters required.';
		$max_length_error_message = !empty($settings['max_length_error_message']) ? $settings['max_length_error_message'] : 'Maximum {count} characters required.';
		
		$submit_button_text = !empty($settings['submit_button_text']) ? $settings['submit_button_text'] : 'Register';
		$submit_button_icon = !empty($settings['submit_button_icon']) ? $settings['submit_button_icon'] : '';
		$submit_button_icon_position = !empty($settings['submit_button_icon_position']) ? $settings['submit_button_icon_position'] : '';

		$redirect_after_register = !empty($settings['redirect_after_registration']) ? $settings['redirect_after_registration'] : 'yes';
		$redirect_link_after_register = !empty($settings['redirect_link_after_registration']['url']) ? $settings['redirect_link_after_registration']['url'] : home_url();

		$cta_text = !empty($settings['cta_text']) ? $settings['cta_text'] : '';

		$register_fields = !empty($settings['register_fields']) ? $settings['register_fields'] : '';
		$role = !empty($settings['role']) ? $settings['role'] : 'subscriber';
		$auto_login_after_registration = !empty($settings['auto_login_after_registration']) ? $settings['auto_login_after_registration'] : 'yes';

		
	
		?>
		<form method="post" class="eel-authentication-form eel-register-form <?php echo esc_attr( $show_icons ); ?>">
			<input type="hidden" name="register_redirect_link" value="<?php echo esc_url( $redirect_link_after_register )?>">
			<input type="hidden" name="role" value="<?php echo esc_attr( $role )?>">
			<input type="hidden" name="auto_login_after_registration" value="<?php echo esc_attr( $auto_login_after_registration )?>">
			<input type="hidden" name="empty_error_msg" value="<?php echo esc_attr( $empty_error_message )?>">
			<input type="hidden" name="min_length_error_msg" value="<?php echo esc_attr( $min_length_error_message )?>">
			<input type="hidden" name="max_length_error_msg" value="<?php echo esc_attr( $max_length_error_message )?>">
			<input type="hidden" name="confirm_pass_error_msg" value="<?php echo esc_attr( $confirm_pass_error_message )?>">
			<?php 
				if(!empty($register_fields) && is_array($register_fields)) {
					foreach ($register_fields as $field) {

						$type = !empty($field['type']) ? $field['type'] : 'username';
						$input_type = $type == 'custom' && !empty($field['input_type']) ? $field['input_type'] : 'text';
						$meta_key = !empty($field['meta_key']) ? $field['meta_key'] : '';
						$id = 'eel_' . $type;
						$label = !empty($field['label']) ? $field['label'] : '';
						$placeholder = !empty($field['placeholder']) ? $field['placeholder'] : '';
						$icon = !empty($field['icon']['value']) ? $field['icon'] : '';
						$is_required = !empty($field['is_required']) ? 'required' : '';
						$static_text = !empty($field['static_text']) ? $field['static_text'] : '';
						$min_length = !empty($field['min_length']) ? $field['min_length'] : '1';
						$max_length = !empty($field['max_length']) ? $field['max_length'] : '';
						$width = !empty($field['width']['size']) ? $field['width']['size'] . $field['width']['unit'] : '';

						if($type =='custom' && !empty($meta_key)){							
							$type = 'custom_meta[' . $meta_key . ']';
							$id = 'eel_' . $meta_key;						
						}

						if($type =='consent') {
							?>
								<div class="eel-authentication-form-field eel-authentication-form-checkbox-field" <?php echo !empty($width) ? 'style="width: '.esc_attr( $width ).';"' : '' ?>>
									<div class="eel-authentication-form-field-inner">
										<div class="eel-form-control-wrap">
											<label class="eel-authentication-form-label">
												<input type="checkbox" name="<?php echo esc_attr( $type ) ?>" id="<?php echo esc_attr( $id ) ?>" <?php echo esc_attr( $is_required ) ?>/>
												<?php echo esc_attr( $label )?>
											</label>
										</div>
									</div>
									<div class="eel-error-msg" data-error-for="<?php echo esc_attr( $id )?>"></div>
								</div>
							<?php
						}elseif($type =='static_text' && !empty($static_text)) {
							?>
								<div class="eel-authentication-form-field eel-authentication-form-static-field" <?php echo !empty($width) ? 'style="width: '.esc_attr( $width ).';"' : '' ?>>
									<div class="eel-authentication-form-field-inner">
										<div class="eel-form-control-wrap">
											<?php echo wp_kses_post( $static_text ); ?>
										</div>
									</div>
								</div>
							<?php
						}else{
							?>
								<div class="eel-authentication-form-field" <?php echo !empty($width) ? 'style="width: '.esc_attr( $width ).';"' : '' ?>>
										<div class="eel-authentication-form-field-inner">
											<?php if ($show_icon == 'yes' && !empty($icon['value'])): ?>
											<span class="eel-form-field-icon eel-user-icon">
												<?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?>
											</span>
											<?php endif; ?>
											<div class="eel-form-control-wrap">
												<?php 
													if($show_label == 'yes' && !empty($label)) {
														?>
															<label for="<?php echo esc_attr( $id ) ?>" class="eel-authentication-form-label"><?php echo esc_html( $label ); ?></label>
														<?php
													}
												?>
												<input type="<?php echo esc_attr( in_array( $type, ['user_pass', 'confirm_password'] ) ? 'password' : ( $type === 'user_email' ? 'email' : 'text' ) ); ?>" name="<?php echo esc_attr( $type ) ?>" class="eel-form-control" id="<?php echo esc_attr( $id ) ?>" placeholder="<?php echo esc_attr( $placeholder ) ?>" <?php echo esc_attr( $is_required ) ?>  min="<?php echo esc_attr( $min_length )?>"  max="<?php echo esc_attr( $max_length )?>"/>
											</div>
										</div>
										<div class="eel-error-msg" data-error-for="<?php echo esc_attr( $id )?>"></div>
								</div>
							<?php
						}

						
					}
				}
			?>
			<div class="eel-authentication-form-field eel-authentication-form-submit-field">
				<div class="eel-authentication-form-field-inner">
					<div class="eel-form-control-wrap">
					<button type="submit" name="eel_login_submit" class="eel-submit-button">
					<?php
						if(!empty($submit_button_icon) && $submit_button_icon_position == 'before') {
							?>
								<span class="eel-form-btn-icon">
									<?php \Elementor\Icons_Manager::render_icon( $submit_button_icon, [ 'aria-hidden' => 'true' ] ); ?>
								</span>
							<?php
						}
					?>
					<span class="eel-form-btn-text"><?php echo esc_attr( $submit_button_text )?></span>
					<?php 
						if(!empty($submit_button_icon) && $submit_button_icon_position == 'after') {
							?>
								<span class="eel-form-btn-icon">
									<?php \Elementor\Icons_Manager::render_icon( $submit_button_icon, [ 'aria-hidden' => 'true' ] ); ?>
								</span>
							<?php
						}
					?>
					<?php 
						if($show_ajax_loader == 'yes') { ?>
						<span class="eel-form-ajax-loader">
							<img src="<?php echo esc_url( EASYELEMENTS_DIR_URL . 'widgets/login-register/img/ajax-loader.gif' )?>" alt="ajax loader">
						</span>
					<?php } ?>
					</button>
					</div>
				</div>
			</div>
			<?php 
				if(!empty($cta_text)) { ?>
				<div class="eel-authentication-form-field eel-authentication-form-cta-field">
					<div class="eel-authentication-form-field-inner">
						<div class="eel-form-control-wrap">
							<?php echo wp_kses_post( $cta_text ); ?>
						</div>
					</div>
				</div>
			<?php } ?>

			<div class="eel-form-status">
				<?php 
					if($show_error_message == 'yes') {
						?>
							<p class="eel-form-error-msg"><?php echo esc_html( $register_error_message )?></p>
						<?php
					}
					if($show_success_message == 'yes') {
						?>
							<p class="eel-form-success-msg"><?php echo esc_html( $register_success_message )?></p>
						<?php
					}
				?>
				

			</div>
		
		</form>
		<?php
		
	}

	/**
	 * Render widget output on the frontend
	 * 
	 * Generates the HTML markup for the login registers
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$form_type = !empty($settings['form_type']) ? $settings['form_type'] : 'login';

		if($form_type == 'login') {
			$this->render_login_form($settings);
		}elseif($form_type == 'register') {
			$this->render_register_form($settings);
		}else{
			$this->render_login_form($settings);
			$this->render_register_form($settings);
		}
		
	}

}