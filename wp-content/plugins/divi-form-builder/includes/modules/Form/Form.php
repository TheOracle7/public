<?php

class DE_FB_Form extends ET_Builder_Module {

	
	public $vb_support = 'on';
	public $is_bloom_enabled = false;
	public $bloom_email_list = array();

	public $fields_defaults = array();
	public $folder_name = '';
	public $child_item_text = '';
	public $de_domain_name = '';
	public $text_shadow = '';
	public $margin_padding= '';
	public $_additional_fields_options = array();
	public $_original_content = '';


	private $email_error_message = '';

	protected $module_credits = array(
		'module_uri' => 'https://diviengine.com',
		'author'     => 'Divi Engine',
		'author_uri' => 'https://diviengine.com',
	);

	public function init() {
		$this->slug       = 'de_fb_form';
		$this->_use_unique_id  = true;
		$this->name = esc_html__( 'Form - Divi Form Builder', 'divi-form-builder' );
        $this->folder_name = 'divi_form_builder';
		$this->child_slug      = 'de_fb_form_field';
		$this->child_item_text = esc_html__( 'Form Field', 'divi-form-builder' );
		$this->de_domain_name  = 'divi-form-builder';
		add_action( 'wp_mail_failed', array( $this, 'get_mail_error_message' ), 10, 1 );

		if ( class_exists('ET_Bloom') ) {
			$bloom_obj = ET_Bloom::get_this();
			if ( $bloom_obj ) {
				$all_accounts = $bloom_obj->providers->accounts();
				if ( !empty( $all_accounts ) ) {
					$this->is_bloom_enabled = true;
					$this->bloom_email_list = $this->get_email_lists( $all_accounts );
				}
			}
		}

		$this->settings_modal_toggles = array(
			'general' => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Main Options', 'divi-form-builder' ),
					'multistep_options'        => esc_html__( 'Multistep Options', 'divi-form-builder' ),
					'email'        => esc_html__( 'Email Notification', 'divi-form-builder' ),
					'email_confirmation'        => esc_html__( 'Email Confirmation', 'divi-form-builder' ),
					'notices'        => esc_html__( 'Notices', 'divi-form-builder' ),
					'redirect'     => esc_html__( 'Redirects', 'divi-form-builder' ),
					'extra_options'     => esc_html__( 'Extra', 'divi-form-builder' ),
					'spam'         => esc_html__( 'Spam Protection', 'divi-form-builder' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'upload_text'		=> array(
						'title' => esc_html__( 'Upload Text', 'divi-form-builder'),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'upload_description'     => array(
								'name' => esc_html__( 'Upload Description', 'divi-form-builder')
							),
							'upload_preview_name'     => array(
								'name' => esc_html__( 'Upload Preview Name', 'divi-form-builder')
							),
							'upload_preview_size'     => array(
								'name' => esc_html__( 'Upload Preview Size', 'divi-form-builder')
								)
						)
					),							
					'notices_text'		=> array(
						'title' => esc_html__( 'Notice Text', 'divi-form-builder'),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'success_notice_text'     => array(
								'name' => esc_html__( 'Success Notification', 'divi-form-builder')
							),
							'failed_notice_text'     => array(
								'name' => esc_html__( 'Failed Notification', 'divi-form-builder')
							),
						)
					),
					'progress_bar_colors'		=> array(
						'title' => esc_html__( 'Progress Bar Colors', 'divi-form-builder'),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'progress_bar_active_color'     => array(
								'name' => esc_html__( 'Active color', 'divi-form-builder')
							),
							'progress_bar_inactive_color'     => array(
								'name' => esc_html__( 'Inactive color', 'divi-form-builder')
							),
						)
					),
					'date_time_app'			=> array(
						'title'		=> esc_html__( 'Date/Time Picker', 'divi-form-builder' ),
					),
					'text' => array(
					  'title'    => esc_html__( 'Content Text', 'divi-form-builder' ),
					  'priority' => 45,
					  'tabbed_subtoggles' => true,
					  'bb_icons_support' => true,
					  'sub_toggles' => array(
						'p'     => array(
						  'name' => 'P',
						  'icon' => 'text-left',
						),
						'h1' => array(
							'name' => 'H1',
							'icon' => 'text-h1',
						),
						'h2' => array(
							'name' => 'H2',
							'icon' => 'text-h2',
						),
						'h3' => array(
							'name' => 'H3',
							'icon' => 'text-h3',
						),
						'h4' => array(
							'name' => 'H4',
							'icon' => 'text-h4',
						),
						'h5' => array(
							'name' => 'H5',
							'icon' => 'text-h5',
						),
						'h6' => array(
							'name' => 'H6',
							'icon' => 'text-h6',
						),
						'a'     => array(
						  'name' => 'A',
						  'icon' => 'text-link',
						),
						'ul'    => array(
						  'name' => 'UL',
						  'icon' => 'list',
						),
						'ol'    => array(
						  'name' => 'OL',
						  'icon' => 'numbered-list',
						),
						'quote' => array(
						  'name' => 'QUOTE',
						  'icon' => 'text-quote',
						),
					  ),
					),							
					'captcha'		=> array(
						'title' => esc_html__( 'Captcha', 'divi-form-builder'),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'basic_captcha'     => array(
								'name' => esc_html__( 'Basic Captcha Question', 'divi-form-builder')
							),
							'basic_captcha_answer'     => array(
								'name' => esc_html__( 'Basic Captcha Answer', 'divi-form-builder')
							)
						)
					),
				)
			)
		);

		$this->main_css_element = '%%order_class%%';

		$this->advanced_fields = array(
			'borders'        => array(
				'default' => array(
					'css'          => array(
						'main'      => array(
							'border_radii'  => sprintf( '.select2-container--default .select2-search--dropdown .select2-search__field, .select2-dropdown, %1$s .select2-container--default .select2-selection--single, %1$s .et_pb_contact_field[data-type=file],%1$s .et_pb_contact_field[data-type=image],%1$s .dropzone, %1$s p.et_pb_contact_field textarea, %1$s p.et_pb_contact_field select, %1$s p.et_pb_contact_field input, %1$s p.et_pb_contact_field .input, %1$s .input[type="checkbox"] + label i, %1$s .input[type="radio"] + label i', $this->main_css_element ),
							'border_styles' => sprintf( '.select2-container--default .select2-search--dropdown .select2-search__field, .select2-dropdown, %1$s .select2-container--default .select2-selection--single, %1$s .et_pb_contact_field[data-type=file],%1$s .et_pb_contact_field[data-type=image],%1$s .dropzone, %1$s p.et_pb_contact_field textarea, %1$s p.et_pb_contact_field select, %1$s p.et_pb_contact_field input, %1$s p.et_pb_contact_field .input, %1$s .input[type="checkbox"] + label i, %1$s .input[type="radio"] + label i', $this->main_css_element ),
						),
						'important' => 'plugin_only',
					),
					'label_prefix' => esc_html__( 'Inputs', 'et_builder' ),
				),
				'form_whole' => array(
					'css'          => array(
						'main'      => array(
							'border_radii'  => "%%order_class%% form",
							'border_styles' => "%%order_class%% form",
						),
						'important' => 'plugin_only',
					),
					'label_prefix' => esc_html__( 'Whole Form', 'et_builder' ),
				),
				'form' => array(
					'css'          => array(
						'main'      => array(
							'border_radii'  => "%%order_class%% .divi-form-wrapper",
							'border_styles' => "%%order_class%% .divi-form-wrapper",
						),
						'important' => 'plugin_only',
					),
					'label_prefix' => esc_html__( 'Form Field Wrapper', 'et_builder' ),
				),
				'captcha' => array(
					'css'          => array(
						'main'      => array(
							'border_radii'  => "%%order_class%% input[type=text].maths_answer",
							'border_styles' => "%%order_class%% input[type=text].maths_answer",
						),
						'important' => 'plugin_only',
					),
					'label_prefix' => esc_html__( 'Basic Captcha Input', 'et_builder' ),
				),
			),
			'fonts'          => array(
				'form_title' => array(
					'label'    => esc_html__( 'Form Title', 'divi-form-builder' ),
					'css'      => array(
							'main' => "%%order_class%% .form-title",
							'important' => 'plugin_only',
					),
					'font_size' => array(
							'default' => '26px',
					),
					'line_height' => array(
							'default' => '1em',
					),
				),
				'label_text' => array(
					'label'    => esc_html__( 'Label', 'divi-form-builder' ),
					'css'      => array(
							'main' => "%%order_class%% .field_label",
							'important' => 'plugin_only',
					),
					'font_size' => array(
							'default' => '14px',
					),
					'line_height' => array(
							'default' => '1em',
					),
				),
				'placeholder_text' => array(
					'label'    => esc_html__( 'Placeholder', 'divi-form-builder' ),
					'css'      => array(
						'main' => "%%order_class%% input::placeholder, %%order_class%% input:-ms-input-placeholder, %%order_class%% input::-webkit-input-placeholder,%%order_class%% textarea::placeholder, %%order_class%% textarea:-ms-input-placeholder, %%order_class%% textarea::-webkit-input-placeholder",
						'important' => 'plugin_only',
					),
					'font_size' => array(
							'default' => '14px',
					),
					'line_height' => array(
							'default' => '1em',
					),
				),
				'field_description' => array(
					'label'    => esc_html__( 'Field Description', 'divi-form-builder' ),
					'css'      => array(
							'main' => "%%order_class%% .df_field_description_text",
							'important' => 'plugin_only',
					),
					'font_size' => array(
							'default' => '14px',
					),
					'line_height' => array(
							'default' => '1em',
					),
				),
				'success_notice_text' => array(
					'label'    => esc_html__( 'Success Notice Text', 'divi-form-builder' ),
					'css'      => array(
							'main' => "%%order_class%% .message_success",
							'important' => 'plugin_only',
					),
					'font_size' => array(
							'default' => '14px',
					),
					'line_height' => array(
							'default' => '1em',
					),
				),
				'failed_notice_text' => array(
					'label'    => esc_html__( 'Failed Notice Text', 'divi-form-builder' ),
					'css'      => array(
							'main' => "%%order_class%% .message_failed",
							'important' => 'plugin_only',
					),
					'font_size' => array(
							'default' => '14px',
					),
					'line_height' => array(
							'default' => '1em',
					),
				),
				'upload_description' => array(
					'label'    => esc_html__( 'Upload Description', 'divi-form-builder' ),
					'css'      => array(
							'main' => "%%order_class%% .drop-description",
							'important' => 'plugin_only',
					),
					'font_size' => array(
							'default' => '14px',
					),
					'line_height' => array(
							'default' => '1em',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'     => 'upload_text',
					'sub_toggle'		=> 'upload_description'
				),
				'upload_preview_name' => array(
					'label'    => esc_html__( 'Upload Preview Name', 'divi-form-builder' ),
					'css'      => array(
							'main' => "%%order_class%% .file_upload_item .preview_name",
							'important' => 'plugin_only',
					),
					'font_size' => array(
							'default' => '14px',
					),
					'line_height' => array(
							'default' => '1em',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'     => 'upload_text',
					'sub_toggle'		=> 'upload_preview_name'
				),
				'upload_preview_size' => array(
					'label'    => esc_html__( 'Upload Description', 'divi-form-builder' ),
					'css'      => array(
							'main' => "%%order_class%% .file_upload_item .upload_size",
							'important' => 'plugin_only',
					),
					'font_size' => array(
							'default' => '14px',
					),
					'line_height' => array(
							'default' => '1em',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'     => 'upload_text',
					'sub_toggle'		=> 'upload_preview_size'
				),
				'success_notice_text' => array(
					'label'    => esc_html__( 'Success Notification', 'divi-form-builder' ),
					'css'      => array(
						'main' => "%%order_class%% .message_success",
						'important' => 'plugin_only',
					),
					'font_size' => array(
							'default' => '14px',
					),
					'line_height' => array(
							'default' => '1em',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'     => 'notices_text',
					'sub_toggle'		=> 'success_notice_text'
				),
				'failed_notice_text' => array(
					'label'    => esc_html__( 'Failed Notification', 'divi-form-builder' ),
					'css'      => array(
						'main' => "%%order_class%% .message_failed",
						'important' => 'plugin_only',
					),
					'font_size' => array(
							'default' => '14px',
					),
					'line_height' => array(
							'default' => '1em',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'     => 'notices_text',
					'sub_toggle'		=> 'failed_notice_text'
				),
				'bloom_description' => array(
					'label'    => esc_html__( 'Bloom Sign Up Description', 'divi-form-builder' ),
					'css'      => array(
						'main' => "%%order_class%% .et_pb_contact p #bloom_subscribe+label",
						'important' => 'plugin_only',
					),
					'font_size' => array(
							'default' => '14px',
					),
					'line_height' => array(
							'default' => '1em',
					),
				),
				'progress_bar_step_title' => array(
					'label'    => esc_html__( 'Progress bar Step title', 'divi-form-builder' ),
					'css'      => array(
						'main' => "%%order_class%% .df_step_title_text",
						'important' => 'plugin_only',
					),
					'font_size' => array(
						'default' => '14px',
					),
					'line_height' => array(
						'default' => '1em',
					),
					'depends_show_if'	=> 'on'
				),
				'progress_bar_step_number' => array(
					'label'    => esc_html__( 'Progress bar Step Number/Icon', 'divi-form-builder' ),
					'css'      => array(
						'main' => "%%order_class%% .df_progressbar_number, %%order_class%% .df_progressbar_icon",
						'important' => 'plugin_only',
					),
					'font_size' => array(
						'default' => '14px',
					),
					'line_height' => array(
						'default' => '20px',
					),
				),
				'progress_bar_percentage' => array(
					'label'    => esc_html__( 'Progress bar Percentage ', 'divi-form-builder' ),
					'css'      => array(
						'main' => "%%order_class%% .df_progressbar_percentage",
						'important' => 'plugin_only',
					),
					'font_size' => array(
						'default' => '14px',
					),
					'line_height' => array(
						'default' => '1em',
					),
				),
				'required_error_notice' => array(
					'label'    => esc_html__( 'Required Error Notice', 'divi-form-builder' ),
					'css'      => array(
						'main' => "%%order_class%% label.error",
						'important' => 'plugin_only',
					),
				),
				'required_mark_text' => array(
					'label'    => esc_html__( 'Required Mark', 'divi-form-builder' ),
					'css'      => array(
						'main' => "%%order_class%% .de_fb_required",
						'important' => 'plugin_only',
					),
					'font_size' => array(
						'default' => '14px',
					),
					'line_height' => array(
						'default' => '1em',
					),
				),
				'datepicker_time' => array(
					'label'    => esc_html__( 'Date Picker', 'divi-form-builder' ),
					'css'      => array(
						'main' => "#ui-datepicker-div, #ui-datepicker-div button, #ui-datepicker-div input, #ui-datepicker-div select",
						'important' => 'plugin_only',
					),
					'font_size' => array(
						'default' => '14px',
					),
					'line_height' => array(
						'default' => '1em',
					),
				),
				'content_text'   => array(
					'label'    => esc_html__( 'Text', 'divi-form-builder' ),
					'css'      => array(
					  'line_height' => "{$this->main_css_element} .dfb_content_text_field p",
					  'color' => "{$this->main_css_element} .dfb_content_text_field p",
					),
					'line_height' => array(
					  'default' => floatval( et_get_option( 'body_font_height', '1.7' ) ) . 'em',
					),
					'font_size' => array(
					  'default' => absint( et_get_option( 'body_font_size', '14' ) ) . 'px',
					),
					'toggle_slug' => 'text',
					'sub_toggle'  => 'p',
				  ),
				  'content_link'   => array(
					'label'    => esc_html__( 'Link', 'divi-form-builder' ),
					'css'      => array(
					  'main' => "{$this->main_css_element} .dfb_content_text_field a",
					  'color' => "{$this->main_css_element} .dfb_content_text_field a",
					),
					'line_height' => array(
					  'default' => '1em',
					),
					'font_size' => array(
					  'default' => absint( et_get_option( 'body_font_size', '14' ) ) . 'px',
					),
					'toggle_slug' => 'text',
					'sub_toggle'  => 'a',
				  ),
				  'content_ul'   => array(
					'label'    => esc_html__( 'Unordered List', 'divi-form-builder' ),
					'css'      => array(
					  'main'        => "{$this->main_css_element} .dfb_content_text_field ul",
					  'color'       => "{$this->main_css_element} .dfb_content_text_field ul",
					  'line_height' => "{$this->main_css_element} .dfb_content_text_field ul li",
					),
					'line_height' => array(
					  'default' => '1em',
					),
					'font_size' => array(
					  'default' => '14px',
					),
					'toggle_slug' => 'text',
					'sub_toggle'  => 'ul',
				  ),
				  'content_ol'   => array(
					'label'    => esc_html__( 'Ordered List', 'divi-form-builder' ),
					'css'      => array(
					  'main'        => "{$this->main_css_element} .dfb_content_text_field ol",
					  'color'       => "{$this->main_css_element} .dfb_content_text_field ol",
					  'line_height' => "{$this->main_css_element} .dfb_content_text_field ol li",
					),
					'line_height' => array(
					  'default' => '1em',
					),
					'font_size' => array(
					  'default' => '14px',
					),
					'toggle_slug' => 'text',
					'sub_toggle'  => 'ol',
				  ),
				  'content_quote'   => array(
					'label'    => esc_html__( 'Blockquote', 'divi-form-builder' ),
					'css'      => array(
					  'main' => "{$this->main_css_element} .dfb_content_text_field blockquote, {$this->main_css_element} .dfb_content_text_field blockquote p",
					  'color' => "{$this->main_css_element} .dfb_content_text_field blockquote, {$this->main_css_element} .dfb_content_text_field blockquote p",
					),
					'line_height' => array(
					  'default' => '1em',
					),
					'font_size' => array(
					  'default' => '14px',
					),
					'toggle_slug' => 'text',
					'sub_toggle'  => 'quote',
				  ),
				  'header_1'   => array(
					'label'    => esc_html__( 'Heading', 'divi-form-builder' ),
					'css'      => array(
					  'main' => "{$this->main_css_element} .dfb_content_text_field h1",
					),
					'font_size' => array(
					  'default' => absint( et_get_option( 'body_header_size', '30' ) ) . 'px',
					),
					'line_height' => array(
					  'default' => '1em',
					),
					'toggle_slug' => 'text',
					'sub_toggle'  => 'h1',
				  ),
				  'header_2'   => array(
					'label'    => esc_html__( 'Heading 2', 'divi-form-builder' ),
					'css'      => array(
					  'main' => "{$this->main_css_element} .dfb_content_text_field h2",
					),
					'font_size' => array(
					  'default' => '26px',
					),
					'line_height' => array(
					  'default' => '1em',
					),
					'toggle_slug' => 'text',
					'sub_toggle'  => 'h2',
				  ),
				  'header_3'   => array(
					'label'    => esc_html__( 'Heading 3', 'divi-form-builder' ),
					'css'      => array(
					  'main' => "{$this->main_css_element} .dfb_content_text_field h3",
					),
					'font_size' => array(
					  'default' => '22px',
					),
					'line_height' => array(
					  'default' => '1em',
					),
					'toggle_slug' => 'text',
					'sub_toggle'  => 'h3',
				  ),
				  'header_4'   => array(
					'label'    => esc_html__( 'Heading 4', 'divi-form-builder' ),
					'css'      => array(
					  'main' => "{$this->main_css_element} .dfb_content_text_field h4",
					),
					'font_size' => array(
					  'default' => '18px',
					),
					'line_height' => array(
					  'default' => '1em',
					),
					'toggle_slug' => 'text',
					'sub_toggle'  => 'h4',
				  ),
				  'header_5'   => array(
					'label'    => esc_html__( 'Heading 5', 'divi-form-builder' ),
					'css'      => array(
					  'main' => "{$this->main_css_element} .dfb_content_text_field h5",
					),
					'font_size' => array(
					  'default' => '16px',
					),
					'line_height' => array(
					  'default' => '1em',
					),
					'toggle_slug' => 'text',
					'sub_toggle'  => 'h5',
				  ),
				  'header_6'   => array(
					'label'    => esc_html__( 'Heading 6', 'divi-form-builder' ),
					'css'      => array(
					  'main' => "{$this->main_css_element} .dfb_content_text_field h6",
					),
					'font_size' => array(
					  'default' => '14px',
					),
					'line_height' => array(
					  'default' => '1em',
					),
					'toggle_slug' => 'text',
					'sub_toggle'  => 'h6',
				  ),
				  'basic_captcha' => array(
					  'label'    => esc_html__( 'Captcha Math', 'divi-form-builder' ),
					  'css'      => array(
							  'main' => "%%order_class%% .maths_captcha label",
							  'important' => 'plugin_only',
					  ),
					  'font_size' => array(
							  'default' => '14px',
					  ),
					  'line_height' => array(
							  'default' => '1em',
					  ),
					  'toggle_slug' => 'captcha',
					  'sub_toggle'  => 'basic_captcha',
				  ),
				  'basic_captcha_answer' => array(
					  'label'    => esc_html__( 'Captcha Answer', 'divi-form-builder' ),
					  'css'      => array(
							  'main' => "%%order_class%% input[type=text].maths_answer",
							  'important' => 'plugin_only',
					  ),
					  'font_size' => array(
							  'default' => '14px',
					  ),
					  'line_height' => array(
							  'default' => '1em',
					  ),
					  'toggle_slug' => 'captcha',
					  'sub_toggle'  => 'basic_captcha_answer',
				  ),

			),
			'box_shadow'     => array(
				'default' => array(
					'css' => array(
						'main' => implode(
							', ',
							array(
								'%%order_class%% .et_pb_contact_field input',
								'%%order_class%% .et_pb_contact_field select',
								'%%order_class%% .et_pb_contact_field textarea',
								'%%order_class%% .et_pb_contact_field .et_pb_contact_field_options_list label > i',
								'%%order_class%% input.et_pb_contact_captcha',
								'%%order_class%% .et_pb_contact_field[data-type=file]', 
								'%%order_class%% .et_pb_contact_field[data-type=image]', 
								'%%order_class%% .dropzone',
								'.select2-container--default .select2-search--dropdown .select2-search__field', 
								'.select2-dropdown', 
								'%%order_class%% .select2-container--default .select2-selection--single',
							)
						),
					),
				),
			),
			'button' => array(
				'button' => array(
					'label' => esc_html__( 'Submit Button', 'divi-form-builder' ),
					'css' => array(
						'main' => "{$this->main_css_element} .divi-form-submit.et_pb_button",
						'important' => 'all',
					),
					'box_shadow'  => array(
						'css' => array(
							'main' => "{$this->main_css_element} .divi-form-submit.et_pb_button",
									'important' => 'all',
						),
					),
					'margin_padding' => array(
						'css'           => array(
							'main' => "{$this->main_css_element} .divi-form-submit.et_pb_button",
							'important' => 'all',
						),
					),
					'use_alignment' => false,
				),
				'radio_checkbox_button' => array(
					'label' => esc_html__( 'Radio/Checkbox Button', 'divi-form-builder' ),
					'css' => array(
						'main' => "{$this->main_css_element} .dfb_radio_button .et_pb_contact_field_checkbox",
						'important' => 'all',
					),
					'box_shadow'  => array(
						'css' => array(
							'main' => "{$this->main_css_element} .dfb_radio_button .et_pb_contact_field_checkbox",
									'important' => 'all',
						),
					),
					'margin_padding' => array(
						'css'           => array(
							'main' => "{$this->main_css_element} .dfb_radio_button .et_pb_contact_field_checkbox",
							'important' => 'all',
						),
					),
					'use_alignment' => false,
				),
				'radio_checkbox_button_active' => array(
					'label' => esc_html__( 'Active Radio/Checkbox Button', 'divi-form-builder' ),
					'css' => array(
						'main' => "{$this->main_css_element} .dfb_radio_button .et_pb_contact_field_checkbox input:checked + label",
						'important' => 'all',
					),
					'box_shadow'  => array(
						'css' => array(
							'main' => "{$this->main_css_element} .dfb_radio_button .et_pb_contact_field_checkbox input:checked + label",
									'important' => 'all',
						),
					),
					'margin_padding' => array(
						'css'           => array(
							'main' => "{$this->main_css_element} .dfb_radio_button .et_pb_contact_field_checkbox input:checked + label",
							'important' => 'all',
						),
					),
					'use_alignment' => false,
				),
				'previous_step_button' => array(
					'label' => esc_html__( 'Previous Step Button', 'divi-form-builder' ),
					'css' => array(
						'main' => "{$this->main_css_element} .df_step_prev",
						'important' => 'all',
					),
					'box_shadow'  => array(
						'css' => array(
							'main' => "{$this->main_css_element} .df_step_prev",
							'important' => 'all',
						),
					),
					'margin_padding' => array(
						'css'           => array(
							'main' => "{$this->main_css_element} .df_step_prev",
							'important' => 'all',
						),
					),
					'use_alignment' => false,
				),
				'next_step_button' => array(
					'label' => esc_html__( 'Next Step Button', 'divi-form-builder' ),
					'css' => array(
						'main' => "{$this->main_css_element} .df_step_next",
						'important' => 'all',
					),
					'box_shadow'  => array(
						'css' => array(
							'main' => "{$this->main_css_element} .df_step_next",
							'important' => 'all',
						),
					),
					'margin_padding' => array(
						'css'           => array(
							'main' => "{$this->main_css_element} .df_step_next",
							'important' => 'all',
						),
					),
					'use_alignment' => false,
				),
			),
			'margin_padding' => array(
				'css' => array(
                    'main'=>'%%order_class%% form',
				),
				'label'          => esc_html__( 'Whole Form', 'et_builder' ),
			),
			'max_width'      => array(
				'css' => array(
					'module_alignment' => '%%order_class%%.et_pb_contact_form_container.et_pb_module',
				),
			),
			'text'           => array(
				'css' => array(
					'text_orientation' => '%%order_class%% input, %%order_class%% textarea, %%order_class%% label',
					'text_shadow'      => '%%order_class%%, %%order_class%% input, %%order_class%% textarea, %%order_class%% label, %%order_class%% select',
				),
			),
			'form_field'     => array(
				'form_field' => array(
					'label'          => esc_html__( 'Fields', 'et_builder' ),
					'css'            => array(
						'main'                         => '%%order_class%% .select2-container, %%order_class%% input, %%order_class%% .divi-form-builder-field',
						'background_color'             => '%%order_class%% .select2-selection, %%order_class%% .divi-form-builder-field, %%order_class%% p input, .et_pb_contact %%order_class%%  p textarea, .js .et_pb_contact %%order_class%%  .tmce-active textarea.wp-editor-area,  %%order_class%% p input[type="checkbox"] + label i, %%order_class%% p input[type="radio"] + label i',
						'background_color_hover'       => '%%order_class%% .select2-selection:hover, %%order_class%% .divi-form-builder-field:hover, %%order_class%% p input:hover, .et_pb_contact %%order_class%%  p textarea:hover, .js .et_pb_contact %%order_class%%  .tmce-active textarea.wp-editor-area:hover, %%order_class%% p input[type="checkbox"]:hover + label i, %%order_class%% p input[type="radio"]:hover + label i',
						'focus_background_color'       => '%%order_class%% .select2-selection:focus, %%order_class%% .divi-form-builder-field:focus, %%order_class%% p input:focus, .et_pb_contact %%order_class%%  p textarea:focus,.js .et_pb_contact %%order_class%%  .tmce-active textarea.wp-editor-area:focus, %%order_class%% p input[type="checkbox"]:active + label i, %%order_class%% p input[type="radio"]:active + label i',
						'focus_background_color_hover' => '%%order_class%% .select2-selection:focus:hover, %%order_class%% .divi-form-builder-field:focus:hover, %%order_class%% p input:focus:hover, .et_pb_contact %%order_class%%  p textarea:focus:hover,.js .et_pb_contact %%order_class%%  .tmce-active textarea.wp-editor-area:focus:hover, %%order_class%% p input[type="checkbox"]:active:hover + label i, %%order_class%% p input[type="radio"]:active:hover + label i',
						'form_text_color'              => '%%order_class%% .select2-selection__rendered, %%order_class%% .divi-form-builder-field, %%order_class%% p input, .et_pb_contact %%order_class%%  p textarea,.js .et_pb_contact %%order_class%%  .tmce-active textarea.wp-editor-area, %%order_class%% p input[type="checkbox"] + label, %%order_class%% p input[type="radio"] + label, %%order_class%% p input[type="checkbox"]:checked + label i:before',
						'form_text_color_hover'        => '%%order_class%% .select2-selection__rendered:hover, %%order_class%% .divi-form-builder-field:hover, %%order_class%% p input:hover, .et_pb_contact %%order_class%%  p textarea:hover,.js .et_pb_contact %%order_class%%  .tmce-active textarea.wp-editor-area:hover, %%order_class%% p input[type="checkbox"]:hover + label, %%order_class%% p input[type="radio"]:hover + label, %%order_class%% p input[type="checkbox"]:checked:hover + label i:before',
						'focus_text_color'             => '%%order_class%% .select2-selection__rendered:focus, %%order_class%% p input:focus, .et_pb_contact %%order_class%%  p textarea:focus,.js .et_pb_contact %%order_class%%  .tmce-active textarea.wp-editor-area:focus',
						'focus_text_color_hover'       => '%%order_class%% .select2-selection__rendered:focus:hover, %%order_class%% p input:focus:hover, .et_pb_contact %%order_class%%  p textarea:focus:hover,.js .et_pb_contact %%order_class%%  .tmce-active textarea.wp-editor-area:focus:hover',
						'padding'                      => '%%order_class%% .select2-container, %%order_class%% .divi-form-builder-field, %%order_class%% .et_pb_contact_field input, .et_pb_contact %%order_class%%  p textarea, .js .et_pb_contact %%order_class%%  .tmce-active textarea.wp-editor-area',
						'margin'                       => '%%order_class%% .select2-container, %%order_class%% .divi-form-builder-field, %%order_class%% .et_pb_contact_field',
					),
					'box_shadow'     => false,
					'border_styles'  => false,
					'font_field'     => array(
						'css' => array(
							'main'  => implode(
								', ',
								array(
									"{$this->main_css_element} .divi-form-builder-field",
									"{$this->main_css_element} .divi-form-builder-field::placeholder",
									"{$this->main_css_element} .divi-form-builder-field::-webkit-input-placeholder",
									"{$this->main_css_element} .divi-form-builder-field::-moz-placeholder",
									"{$this->main_css_element} .divi-form-builder-field:-ms-input-placeholder",
									"{$this->main_css_element} .divi-form-builder-field[type=checkbox] + label",
									"{$this->main_css_element} .divi-form-builder-field[type=radio] + label",
								)
							),
							'hover' => array(
								"{$this->main_css_element} .divi-form-builder-field:hover",
								"{$this->main_css_element} .divi-form-builder-field:hover::placeholder",
								"{$this->main_css_element} .divi-form-builder-field:hover::-webkit-input-placeholder",
								"{$this->main_css_element} .divi-form-builder-field:hover::-moz-placeholder",
								"{$this->main_css_element} .divi-form-builder-field:hover:-ms-input-placeholder",
								"{$this->main_css_element} .divi-form-builder-field[type=checkbox]:hover + label",
								"{$this->main_css_element} .divi-form-builder-field[type=radio]:hover + label",
							),
						),
					),
					'margin_padding' => array(
						'css' => array(
							'main'    => '%%order_class%% .input',
							'padding' => '%%order_class%% .et_pb_contact_field .input',
							'margin'  => '%%order_class%% .et_pb_contact_field',
						),
					),
				),
			),
		);

		$this->help_videos=array(
			array(
				'id'   => 'bnefjeBymqc',
				'name' => esc_html__( 'Feature Update: Multistep Forms in Divi', $this->de_domain_name ),
			),
			array(
				'id'   => 'iE0jCAflGTE',
				'name' => esc_html__( 'Building a Custom Post Creation Form', $this->de_domain_name ),
			),
			array(
				'id'   => 'DYKWtzfDj2U',
				'name' => esc_html__( 'User Field Mapping', $this->de_domain_name ),
			),
			array(
				'id'   => 'S0dOAG0',
				'name' => esc_html__( 'Building a Post Creation Form', $this->de_domain_name ),
			),
			array(
				'id'   => '23xdrSxK3UE',
				'name' => esc_html__( 'How to Add a Filter Field', $this->de_domain_name ),
			),
			array(
				'id'   => '6dhNwkljzUk',
				'name' => esc_html__( 'Cómo añadir iconos a los campos de un formulario en Divi', $this->de_domain_name ),
			),
			array(
				'id'   => 'SfdHlr9Jres',
				'name' => esc_html__( 'Build a Contact Form', $this->de_domain_name ),
			),
			array(
				'id'   => 'leoojbKrOGQ',
				'name' => esc_html__( 'Spam Protection', $this->de_domain_name ),
			),
			array(
				'id'   => 'KFmKdbX5lnw',
				'name' => esc_html__( 'Redirects', $this->de_domain_name ),
			),
			array(
				'id'   => 'kNSZqXYFJWM',
				'name' => esc_html__( 'Form Notices', $this->de_domain_name ),
			),
			array(
				'id'   => 'Hmtd3_I_lR4',
				'name' => esc_html__( 'Ajax Form Submission', $this->de_domain_name ),
			),
			array(
				'id'   => 'CLdbtPs0nT8',
				'name' => esc_html__( 'Email Options', $this->de_domain_name ),
			),
			array(
				'id'   => 'CnUTacRQbx4',
				'name' => esc_html__( 'Form Module Overview', $this->de_domain_name ),
			),
			array(
				'id'   => 'UMnqCkqTkIQ',
				'name' => esc_html__( 'Form Fields Overview', $this->de_domain_name ),
			),
			array(
				'id'   => 'CLdbtPs0nT8',
				'name' => esc_html__( 'Email Options', $this->de_domain_name ),
			),
			array(
				'id'   => '0mCsH2LXtEE',
				'name' => esc_html__( 'Building a Basic Contact Form', $this->de_domain_name ),
			),
			array(
				'id'   => 'DYKWtzfDj2U',
				'name' => esc_html__( 'User Field Mapping', $this->de_domain_name ),
			),
			array(
				'id'   => 'EB9TRiZBWaY',
				'name' => esc_html__( 'Build a User Registration Form', $this->de_domain_name ),
			),
			array(
				'id'   => 'Z9F1i61EMmo',
				'name' => esc_html__( ' Post Field Mapping', $this->de_domain_name ),
			),
			array(
				'id'   => 's0XlWR5agVw',
				'name' => esc_html__( 'Using the Edit/Delete Post Module', $this->de_domain_name ),
			),
			array(
				'id'   => '5XZmCZPQzAY',
				'name' => esc_html__( 'Introducing Divi Form Builder', $this->de_domain_name ),
			),
		);

		//$this->init_hooks();
	}

	public function get_email_lists( $all_accounts ) {
		$email_list = array( 'none' => esc_html__( 'Select', 'divi-form-builder' ) );
		foreach( $all_accounts as $service => $accounts ) {
			foreach ( $accounts as $name => $details ) {
				if ( ! empty( $details['lists'] ) ) {
					foreach ( $details['lists'] as $id => $list_data ) {
						$email_list[ $service . '_' . $id ] = $list_data['name'] . ' ( ' . strtoupper($service) . ' )';
					}
				}
			}
		}

		return $email_list;
	}

	public function get_acf_fields(  ){

        $acf_fields = array();

        if ( function_exists( 'acf_get_field_groups' ) ) {
            $fields_all = get_posts(array(
                'posts_per_page'   => -1,
                'post_type'        => 'acf-field',
                'orderby'          => 'name',
                'order'            => 'ASC',
                'post_status'       => 'publish',
            ));

            $acf_fields['none'] = 'Please select an ACF field';

            foreach ( $fields_all as $field ) {

                $post_parent = $field->post_parent;
                $post_parent_name = get_the_title($post_parent);
                $grandparent = wp_get_post_parent_id($post_parent);
                $grandparent_name = get_the_title($grandparent);

                $acf_fields[$field->post_name] = $post_parent_name . " > " . $field->post_title . " - " . $grandparent_name;
            }


            $field_groups = acf_get_field_groups();
            foreach ( $field_groups as $group ) {
                // DO NOT USE here: $fields = acf_get_fields($group['key']);
                // because it causes repeater field bugs and returns "trashed" fields
                $fields = get_posts(array(
                    'posts_per_page'   => -1,
                    'post_type'        => 'acf-field',
                    'orderby'          => 'name',
                    'order'            => 'ASC',
                    'suppress_filters' => true, // DO NOT allow WPML to modify the query
                    'post_parent'      => $group['ID'],
                    'post_status'       => 'publish',
                    'update_post_meta_cache' => false
                ));

                $acf_fields['none'] = 'Please select an ACF field';

                foreach ( $fields as $field ) {
                    $acf_fields[$field->post_name] = $field->post_title . " - " . $group['title'];
                }
            }
        }

        return $acf_fields;
    }

	public function get_fields() {

		$acf_fields = $this->get_acf_fields();

		$registered_post_types = et_get_registered_post_type_options( false, false );

		unset($registered_post_types['attachment']);
		// unset($registered_post_types['project']);

		$field_mapping_types = array();
		$form_type = array();

		$divi_layouts = DE_FB::get_divi_layouts();

		$form_type['contact'] = 'Contact Form';
		foreach ( $registered_post_types as $key => $post_type ) {
			$post_obj = get_post_type_object($key);
			$field_mapping_types[] = $key . '_default_field';
			$field_mapping_types[] = $key . '_taxonomy_field';
			if ($post_obj->labels->singular_name == "Post Type" || $post_obj->labels->singular_name == "Taxonomy") {

			}  else {
			$form_type[$key] = 'Create ' . $post_obj->labels->singular_name . ' Form';
			}
		}
		$form_type['register'] = 'User Registration Form';
		$form_type['login']	= 'Login Form';
		$form_type['custom'] = 'Custom Form (advanced users)';

		$post_status_objects = get_post_stati(array(), 'objects');
		$post_statuses = array();

		foreach ( $post_status_objects as $name => $obj ) {
			$post_statuses[$name] = $obj->label;
		}

		global $wp_roles;

	    $all_roles = $wp_roles->roles;
	    $editable_roles = apply_filters('editable_roles', $all_roles);

	    $user_roles = array();

	    foreach ( $editable_roles as $key => $role ) {
	    	$user_roles[$key] = $role['name'];
	    }

		$fields = array(
			'title'					=> array(
				'label'				=> esc_html__( 'Form Title', 'divi-form-builder' ),
				'type'				=> 'text',
				'description'		=> esc_html__( 'Define your Form Title', 'divi-form-builder' ),
				'toggle_slug'		=> 'main_content',
				'default_on_front'	=> '',
				'option_category'	=> 'configuration',
			),
			'form_id'				=> array(
				'label'				=> esc_html__( 'Form ID', 'divi-form-builder' ),
				'type'				=> 'text',
				'description'		=> esc_html__( 'Define Unique Form ID.', 'divi-form-builder' ),
				'toggle_slug'		=> 'main_content',
				'default'			=> '',
				'option_category'	=> 'configuration',
			),
			'form_type'				=> array(
				'label'				=> esc_html__( 'Form Type', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'configuration',
				'options'			=> $form_type,
				'description'		=> esc_html__( 'Choose the type of form', 'divi-form-builder' ),
				'toggle_slug'		=> 'main_content',
				'affects'			=> array(
					'save_to_database',
					'default_post_status',
					'is_user_edit',
					'enable_submission_notification',
					'is_ajax_submit',
					'form_action_url',
					'ignore_field_prefix'
				),
				'default'			=> 'contact',
			),
			// LOGIN TEXT SETTINGS
			'login_already_text'             => array(
				'label'				=> esc_html__( 'Already logged in text', 'divi-form-builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Add text/html for when the user is already logged in.', 'et_builder' ),
				'toggle_slug'		=> 'main_content',
				'default'			=>	"You're already logged in.",
				'show_if' => array('form_type' => 'login'),
			),

			// REGISTER TEXT SETTINGS			
			'register_wrong_password_text'             => array(
				'label'				=> esc_html__( 'Confirm password error message', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Add the error text when the passwords do not match.', 'et_builder' ),
				'toggle_slug'		=> 'main_content',
				'default'			=>	"Sorry, your passwords do not match.",
				'show_if' => array('form_type' => 'register'),
			),

			'is_user_edit' 			=> array(
				'label'           	=> esc_html__( 'Is User Edit Form?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'If it is user edit form, please enable this option. Otherwise it will be register user form.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'main_content',
				'depends_show_if'	=> 'register',
				'affects'			=> array(
					'default_user_role',
					'auto_login'
				)
			),
			'default_user_role'		=> array(
				'label'				=> esc_html__( 'Default User Role', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'configuration',
				'options'			=> $user_roles,
				'description'		=> esc_html__( 'Choose the default role for new user.', 'divi-form-builder' ),
				'toggle_slug'		=> 'main_content',
				'depends_show_if'	=> 'off',
				'default'			=> 'editor',
			),
			'auto_login' 			=> array(
				'label'           	=> esc_html__( 'Auto Login after register?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'Enable this option to login automatically after user registered.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'main_content',
				'depends_show_if'	=> 'off',
			),
			'ignore_field_prefix'	=> array(
				'label'				=> esc_html__( 'Ignore Form Field Prefix', 'divi-form-builder' ),
				'type'				=> 'yes_no_button',
				'option_category'	=> 'configuration',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'		=> esc_html__( 'We are adding our own prefix to form field to avoid wordpress reserved keywords. If you want to use original field name that you defined, please enable this option, but make sure you don\'t use reserved keywords.', 'divi-form-builder' ),
				'toggle_slug'		=> 'main_content',
				'depends_show_if'	=> 'custom',
				'default'			=> 'off',
			),
			'submit_button_text' 	=> array(
				'label'           	=> esc_html__( 'Submit Button', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Define the text of the form submit button.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'main_content',
				'default'			=> 'Submit',
			),
			'form_action_url' 	=> array(
				'label'           	=> esc_html__( 'Action URL', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Define the action url for custom form submit.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'main_content',
				'default'			=> '',
				'depends_show_if'	=> 'custom'
			),
			'disable_submit_for_required' => array(
				'label'           	=> esc_html__( 'Disable Submit Button until required field is filled', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'If you want disable submit button until all required fields are filled or selected, please enable this option.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'main_content',
				'depends_show_if'	=> 'contact'
			),
			'is_ajax_submit' => array(
				'label'           	=> esc_html__( 'Ajax Submission?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'If you submit the form via ajax, please enable this.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'main_content',
				'depends_show_if'	=> 'contact'
			),
			'ajax_submit_button_text' 	=> array(
				'label'           	=> esc_html__( 'Ajax Processing Submit Button Text', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'When you click "submit" we will change the button text to inform the person that the ajax form is processing - change the text here.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'main_content',
				'default'			=> 'Processing',
				'show_if'			=> array('is_ajax_submit' => 'on')
			),
			'reset_form_on_submit' => array(
				'label'           	=> esc_html__( 'Clear the form after a failed ajax submission?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'on',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'show_if'			=> array('is_ajax_submit' => 'on'),
				'description'     	=> esc_html__( 'If you want to clear the form after a failed ajax submission, enable this', 'divi-form-builder' ),
				'toggle_slug'     	=> 'main_content',
			),

			'hide_until_loaded' => array(
				'label'           	=> esc_html__( 'Hide Form Until Loaded?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'on',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'If you want hide the form until it is fully loaded - enable this', 'divi-form-builder' ),
				'toggle_slug'     	=> 'main_content',
			),

			'use_preload_animation' => array(
				'label'           	=> esc_html__( 'Use Preloading Animation?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'If you want show preloading animation until form is loaded - enable this', 'divi-form-builder' ),
				'toggle_slug'     	=> 'main_content',
			),

			'preload_anim_style'	=> array(
				'label'           	=> esc_html__( 'Preloading Animation Style', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'default'         	=> 'before_title',
				'options'         	=> array(
					'divi'			=> esc_html__( 'Divi VB Style', 'divi-form-builder' ),
					'load-1' 		=> esc_html__( 'Three Lines Vertical', 'divi-form-builder' ),
                    'load-2' 		=> esc_html__( 'Three Lines Horizontal', 'divi-form-builder' ),
                    'load-3' 		=> esc_html__( 'Three Dots Bouncing', 'divi-form-builder' ),
                    'load-4' 		=> esc_html__( 'Donut', 'divi-form-builder' ),
                    'load-5' 		=> esc_html__( 'Donut Multiple', 'divi-form-builder' ),
                    'load-6' 		=> esc_html__( 'Ripple', 'divi-form-builder' ),
				),
				'description'     	=> esc_html__( 'Select the preloading animation to show.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'main_content',
				'show_if_not'		=> array( 'use_preload_animation' => array( 'off' ) ),
			),

			'scrollto_form_after_submit' => array(
				'label'           	=> esc_html__( 'Scroll to Form after Submission?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'on',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'Please enable this option when you want to scroll down to form after submission.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'main_content',
			),

			'scrollto_form_offset' => array(
				'label'       => esc_html__( 'Top Offset when scrolled', 'divi-form-builder' ),
				'type'        => 'range',
				'option_category'   => 'configuration',
				'default'			=> '0px',
				'fixed_unit'       => 'px',
				'range_settings'  => array(
					'min'  => '-500',
					'max'  => '500',
					'step' => '1',
					''
				),
				'description' => esc_html__( 'Choose the offset with px for scrolling after submission', 'divi-form-builder' ),
				'toggle_slug'       => 'main_content',
				'show_if'     => array('scrollto_form_after_submit' => 'on'),
			),

			'success_hide_form' => array(
				'label'           	=> esc_html__( 'Hide Form After Successful Submission?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'If you want the form fields to be hidden and only show the success message - enable this', 'divi-form-builder' ),
				'toggle_slug'     	=> 'main_content',
			),
			'default_post_status' 	=> array(
				'label'				=> esc_html__( 'Default Post Status', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'configuration',
				'options'			=> $post_statuses,
				'description'		=> esc_html__( 'Choose the default status when post is created.', 'divi-form-builder' ),
				'toggle_slug'		=> 'main_content',
				'show_if_not'		=> array( 'form_type' => array( 'register', 'login', 'contact', 'custom' ) ),
				'default'			=> 'draft',
			),
			// edit permission setting - select field with options: Logged in Author or Administrator, All users, Users with specific role
			'edit_permission' 	=> array(
				'label'				=> esc_html__( 'Edit Permission', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'configuration',
				'options'			=> array(
					'author'	=> esc_html__( 'Logged in Author or Administrator', 'divi-form-builder' ),
					'all'		=> esc_html__( 'All users', 'divi-form-builder' ),
					'role'		=> esc_html__( 'Users with specific role', 'divi-form-builder' )
				),
				'description'		=> esc_html__( 'Choose the permission of the user who can edit the post.', 'divi-form-builder' ),
				'toggle_slug'		=> 'main_content',
				'show_if_not'		=> array( 'form_type' => array( 'register', 'login', 'contact', 'custom' ) ),
				'default'			=> 'author',
			),
			// setting to define the user role if they choose 'role'
			'edit_permission_role' 	=> array(
				'label'				=> esc_html__( 'User role', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'configuration',
				'options'			=> $user_roles,
				'description'		=> esc_html__( 'Choose the role of the user who can edit the post.', 'divi-form-builder' ),
				'toggle_slug'		=> 'main_content',
				'show_if'			=> array( 'edit_permission' => array( 'role' ) ),
				'default'			=> 'author',
			),



			// display notice that appears if the user does not have the ability to edit the post - text field with default value "You do not have permission to edit this post."
			'no_permission_notice' 	=> array(
				'label'				=> esc_html__( 'No Permission Notice', 'divi-form-builder' ),
				'type'				=> 'text',
				'option_category'	=> 'configuration',
				'description'		=> esc_html__( 'Enter the notice that appears if the user does not have the ability to edit the post.', 'divi-form-builder' ),
				'toggle_slug'		=> 'main_content',
				'show_if_not'		=> array( 'form_type' => array( 'register', 'login', 'contact', 'custom' ) ),
				'default'			=> 'You do not have permission to edit this post.',
			),

			'enable_assign_terms' 	=> array(
				'label'				=> esc_html__( 'Enable assign taxonomy terms for Non Logged In Users', 'divi-form-builder' ),
				'type'				=> 'yes_no_button',
				'option_category'	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'		=> esc_html__( 'If you want non logged in users can assign taxonomy terms, enable this.', 'divi-form-builder' ),
				'toggle_slug'		=> 'main_content',
				'show_if_not'		=> array( 'form_type' => array( 'register', 'login', 'contact', 'custom' ) ),
			),
			'message_position'		=> array(
				'label'           	=> esc_html__( 'Notice Message Display Position', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'default'         	=> 'before_title',
				'options'         	=> array(
					'before_title'	=> esc_html__( 'Before Form Title', 'divi-form-builder' ),
					'after_title'	=> esc_html__( 'After Form Title', 'divi-form-builder' ),
					'before_button'	=> esc_html__( 'Before Submit Button', 'divi-form-builder' ),
					'after_button'	=> esc_html__( 'After Submit Button', 'divi-form-builder' ),
				),
				'description'     	=> esc_html__( 'Select Position to display success or failed message/layout. It will not effect when redirect url is set.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'notices',
			),
			'redirect_after_success' => array(
				'label'           	=> esc_html__( 'Redirect to another url after successful submission?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'Set the full url of the page to go after login. Leave blank to go to homepage for default', 'divi-form-builder' ),
				'toggle_slug'     	=> 'redirect',
				'depends_show_if'	=> 'contact',
				'affects'			=> array(
					'success_message_type',
					'redirect_url_after_submission'
				),
			),
			'success_message_type'  => array(
				'label'           	=> esc_html__( 'Success Message Type', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'default'         	=> 'text',
				'options'         	=> array(
					'text'           	=> esc_html__( 'Text', 'divi-form-builder' ),
					'layout'           	=> esc_html__( 'Divi Layout', 'divi-form-builder' )
				),
				'description'     	=> esc_html__( 'Select Message Type to simple text or custom divi layout.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'notices',
				'depends_show_if'	=> 'off',
				'affects'			=> array(
					'success_message',
					'success_layout'
				),
			),
			'success_message'   	=> array(
				'label'           	=> esc_html__( 'Success Message Text', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'						=> 'Thank you for your message, we will be in touch shortly.',
				'description'     	=> esc_html__( 'Type the message you want to display after successful form submission. Leave blank for default', 'divi-form-builder' ),
				'toggle_slug'     	=> 'notices',
				'depends_show_if'	=> 'text',
				'dynamic_content' 	=> 'text',
			),
			'success_layout'   		=> array(
				'label'           	=> esc_html__( 'Success Message Layout', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'default'			=> 'none',
				'description'     	=> esc_html__( 'Select Divi Layout what you want to display after successful form submission.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'notices',
				'options'			=> $divi_layouts,
				'depends_show_if'	=> 'layout',
			),
			'redirect_url_after_submission'		=> array(
				'label'           	=> esc_html__( 'Redirect Url After Submission', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Set the full url of the page to go after success submission.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'redirect',
				'depends_show_if'	=> 'on',
				'dynamic_content' 	=> 'url',
			),
			'redirect_after_failed' => array(
				'label'           	=> esc_html__( 'Redirect to another url after failed submission?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'If you want to redirect to another page or url after form submission is failed, please enable this option.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'redirect',
				'depends_show_if'	=> 'contact',
				'affects'			=> array(
					'failed_message_type',
					'redirect_url_after_failed'
				),
			),
			'failed_message_type'  => array(
				'label'           	=> esc_html__( 'Failed Message Type', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'default'         	=> 'text',
				'options'         	=> array(
					'text'           	=> esc_html__( 'Text', 'divi-form-builder' ),
					'layout'           	=> esc_html__( 'Divi Layout', 'divi-form-builder' )
				),
				'description'     	=> esc_html__( 'Select Message Type to simple text or custom divi layout.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'notices',
				'depends_show_if'	=> 'off',
				'affects'			=> array(
					'failed_message',
					'failed_layout'
				),
			),
			'failed_message'   	=> array(
				'label'           	=> esc_html__( 'Failed Message Text', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'						=> 'Sorry the form was not submitted, %%message%%',
				'description'     	=> esc_html__( 'Type the message you want to display after failed form submission. Leave blank for default', 'divi-form-builder' ),
				'toggle_slug'     	=> 'notices',
				'depends_show_if'	=> 'text',
				'dynamic_content' 	=> 'text',
			),
			'failed_layout'   		=> array(
				'label'           	=> esc_html__( 'Failed Message Layout', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'default'			=> 'none',
				'description'     	=> esc_html__( 'Select Divi Layout what you want to display after failed form submission.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'notices',
				'options'			=> $divi_layouts,
				'depends_show_if'	=> 'layout',
			),
			'redirect_url_after_failed'		=> array(
				'label'           	=> esc_html__( 'Redirect Url After Submission Failed', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Set the full url of the page to go after form submission is failed.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'redirect',
				'depends_show_if'	=> 'on',
				'dynamic_content' 	=> 'url',
			),
			'enable_submission_notification'	=> array(
				'label'           	=> esc_html__( 'Send email notification after submission?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'on',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'If you want to send email notification to Admin(or Custom Contact Email address) after submission, enable this option.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
				'show_if_not'		=> array( 'form_type' => array( 'contact' ) ),
			),
			'use_custom_email'	=> array(
				'label'           	=> esc_html__( 'Send to Email Address', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'default'         	=> 'on',
				'options'         	=> array(
					'on'  => esc_html__( 'Admin Email', 'divi-form-builder' ),
					'post_author'  => esc_html__( 'Author of current post', 'divi-form-builder' ),
					'acf'  => esc_html__( 'ACF Field', 'divi-form-builder' ),
					'off' => esc_html__( 'Custom (define below)', 'divi-form-builder' ),
				),
				'description'     	=> esc_html__( 'If you want to send email to Admin, enable this option.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
			),
			'acf_field_type'	=> array(
				'label'           => esc_html__( 'ACF Field Type', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'options'			=> array(
					'current'  => esc_html__( 'Current CPT', 'divi-form-builder' ),
					'linked'  => esc_html__( 'Linked CPT', 'divi-form-builder' )
				),
				'default'			=> 'current',
				'description'     => esc_html__( 'Choose where the ACF comes from - is it the currnet post or a linked on (post object).', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
				'show_if'	=> array('use_custom_email' => 'acf'),	
			),
			'acf_email_field_linked'	=> array(
				'label'           => esc_html__( 'Linked ACF Field', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'options'			=> $acf_fields,
				'description'     => esc_html__( 'Choose the ACF field that is the post object that is linked on this CPT.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
				'show_if'	=> array('acf_field_type' => 'linked'),	
			),
			'acf_email_field'	=> array(
				'label'           => esc_html__( 'Send to ACF Field', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'options'			=> $acf_fields,
				'description'     => esc_html__( 'Choose the ACF Field that you want to send this email to - used when you are using custom posts.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
				'show_if'	=> array('use_custom_email' => 'acf'),	
			),
			'custom_contact_email'	=> array(
				'label'           	=> esc_html__( 'Define a custom email address to send to', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Set the email address to send email via contact form.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
				'show_if'	=> array('use_custom_email' => 'off'),
			),
			'from_name'			=> array(
				'label'           	=> esc_html__( 'From Name', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'default'         	=> 'default',
				'options'         	=> array(
					'default'           	=> esc_html__( 'Site Title', 'divi-form-builder' ),
					'sender'           	=> esc_html__( 'Sender - From form field', 'divi-form-builder' ),
					'custom'           	=> esc_html__( 'Custom Name', 'divi-form-builder' )
				),
				'description'     	=> esc_html__( 'Specify the from name for the email, this appears on the email as who sent the email..', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
			),
			'from_name_field'		=> array(
				'label'           	=> esc_html__( 'Sender Name - Field ID', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'         	=> '',
				'description'     	=> esc_html__( 'Please input the id of the Name Field that you have added to the form.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
				'show_if'	=> array('from_name' => 'sender'),
			),
			'custom_from_name'			=> array(
				'label'           	=> esc_html__( 'From Name - Custom ', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'         	=> '',
				'description'     	=> esc_html__( 'Please input the name to be shown as "from" when sending the email.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
				'show_if'	=> array('from_name' => 'custom'),
			),
			'from_email'			=> array(
				'label'           	=> esc_html__( 'From Email', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'default'         	=> 'admin',
				'options'         	=> array(
					'admin'           	=> esc_html__( 'Default - Admin', 'divi-form-builder' ),
					'sender'           	=> esc_html__( 'Sender - From form field', 'divi-form-builder' ),
					'custom'           	=> esc_html__( 'Custom', 'divi-form-builder' )
				),
				'description'     	=> esc_html__( 'Select which email address that you want to appear as "from".', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
			),
			'from_email_field'		=> array(
				'label'           	=> esc_html__( 'Sender Email - Field ID', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'         	=> '',
				'description'     	=> esc_html__( 'Please input the id of the email Field that you have added to the form.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
				'show_if'	=> array('from_email' => 'sender'),
			),
			'custom_from_email'			=> array(
				'label'           	=> esc_html__( 'Custom From Email', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'         	=> '',
				'description'     	=> esc_html__( 'Please input the email address to show in Email Header.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
				'show_if'	=> array('from_email' => 'custom'),
			),
			'replyto_name'		=> array(
				'label'           	=> esc_html__( 'Reply-to Email Name Field ID', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'         	=> '',
				'description'     	=> esc_html__( 'Please input the field id for name to reply to.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
			),
			'replyto_email'		=> array(
				'label'           	=> esc_html__( 'Reply-to Email Address Field ID', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'         	=> '',
				'description'     	=> esc_html__( 'Please input the field id for email address to reply to.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
			),
			'email_cc'				=> array(
				'label'           	=> esc_html__( 'CC Email List', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Set the email address to send email for CC.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
			),
			'email_bcc'				=> array(
				'label'           	=> esc_html__( 'BCC Email List', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Set the email address to send email for BCC.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
			),
			'email_title'			=> array(
				'label'           	=> esc_html__( 'Email Subject', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Define Email subject to send.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
				'dynamic_content' 	=> 'text',
			),
			'email_template_html'	=> array(
				'label'           	=> esc_html__( 'Is Full HTML?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'If your email template is full html code - enable this.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
			),
			'email_template'		=> array(
				'label'           	=> esc_html__( 'Email Template', 'divi-form-builder' ),
				'type'            	=> 'textarea',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Define Email template how it will show on email.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email',
				'depends_show_if'	=> 'contact',
				'dynamic_content' 	=> 'text',
			),

			// SUBMISSION NOTIFCATION NOW

			'send_copy_to_sender'	=> array(
				'label'           	=> esc_html__( 'Enable Confirmation Email?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'If you want to send email an email to the sender/submitter or someone else - enable this.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email_confirmation',
			),
			'sender_setting'		=> array(
				'label'           	=> esc_html__( 'Send to email', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'default'         	=> 'sender',
				'options'         	=> array(
					'sender'           	=> esc_html__( 'Sender - From form field', 'divi-form-builder' ),
					'login_user'           	=> esc_html__( 'Logged In user', 'divi-form-builder' )
				),
				'description'     	=> esc_html__( 'Select who you want to send the email confirmation to. If you have this set as "sender" - make sure you have an email field and have the optoin "Is sender email address?" set to "yes".', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email_confirmation',
				'show_if'	=> array('send_copy_to_sender' => 'on'),
				'affects'			=> array(
					'sender_email_field'
				),
			),
			'sender_email_field'		=> array(
				'label'           	=> esc_html__( 'Sender Email - Field ID', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'         	=> '',
				'description'     	=> esc_html__( 'Please input the id of the email Field that you have added to the form.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email_confirmation',
				'show_if'	=> array('send_copy_to_sender' => 'on', 'sender_setting' => 'sender'),
			),
			'reply_from_name'			=> array(
				'label'           	=> esc_html__( 'From Name', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'default'         	=> 'default',
				'options'         	=> array(
					'default'           	=> esc_html__( 'Site Title', 'divi-form-builder' ),
					'sender'           	=> esc_html__( 'Sender - From form field', 'divi-form-builder' ),
					'custom'           	=> esc_html__( 'Custom Name', 'divi-form-builder' )
				),
				'description'     	=> esc_html__( 'Specify the from name for the email, this appears on the email as who sent the email.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email_confirmation',
				'show_if'	=> array('send_copy_to_sender' => 'on'),
			),
			'sender_name_field'		=> array(
				'label'           	=> esc_html__( 'Sender Name - Field ID', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'         	=> '',
				'description'     	=> esc_html__( 'Please input the id of the email Field that you have added to the form.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email_confirmation',
				'show_if'	=> array('send_copy_to_sender' => 'on', 'reply_from_name' => 'sender'),
			),
			// 'from_name_field'		=> array(
			// 	'label'           	=> esc_html__( 'Sender Name - Field ID', 'divi-form-builder' ),
			// 	'type'            	=> 'text',
			// 	'option_category' 	=> 'configuration',
			// 	'default'         	=> '',
			// 	'description'     	=> esc_html__( 'Please input the id of the Name Field that you have added to the form.', 'divi-form-builder' ),
			// 	'toggle_slug'     	=> 'email_confirmation',
			// 	'show_if'	=> array('send_copy_to_sender' => 'on', 'reply_from_name' => 'sender'),
			// ),
			'reply_custom_from_name'			=> array(
				'label'           	=> esc_html__( 'Custom From Name', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'         	=> '',
				'description'     	=> esc_html__( 'Please specify the name to show in the email.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email_confirmation',
				'depends_show_if'	=> 'custom',
				'show_if'	=> array('send_copy_to_sender' => 'on', 'reply_from_name' => 'custom'),
			),
			
			'reply_from_email'			=> array(
				'label'           	=> esc_html__( 'From Email', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'default'         	=> 'admin',
				'options'         	=> array(
					'admin'           	=> esc_html__( 'Default - Admin', 'divi-form-builder' ),
					'custom'           	=> esc_html__( 'Custom', 'divi-form-builder' )
				),
				'description'     	=> esc_html__( 'Select which email address that you want to appear as "from".', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email_confirmation',
				'show_if'	=> array( 'send_copy_to_sender' => 'on'),
			),
			'reply_custom_from_email'			=> array(
				'label'           	=> esc_html__( 'Custom From Email', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'         	=> '',
				'description'     	=> esc_html__( 'Please input the email address to show in Email Header.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email_confirmation',
				'show_if'	=> array('send_copy_to_sender' => 'on', 'reply_from_email' => 'custom'),
			),
			'reply_to_name'		=> array(
				'label'           	=> esc_html__( 'Reply-to Email Name', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'         	=> '',
				'description'     	=> esc_html__( 'Please input the name to reply to.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email_confirmation',
				'show_if'	=> array('send_copy_to_sender' => 'on'),
			),
			'reply_to_email'		=> array(
				'label'           	=> esc_html__( 'Reply-to Email Address', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'         	=> '',
				'description'     	=> esc_html__( 'Please input the email address to reply to.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email_confirmation',
				'show_if'	=> array('send_copy_to_sender' => 'on'),
			),
			'reply_email_title'			=> array(
				'label'           	=> esc_html__( 'Email Subject', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Define Email subject that will appear in the confirmation email.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email_confirmation',
				'show_if'	=> array('send_copy_to_sender' => 'on'),
				'dynamic_content' 	=> 'text',
			),
			'reply_email_template_html'	=> array(
				'label'           	=> esc_html__( 'Is Full HTML?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'If your email template is full html code - enable this.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email_confirmation',
			),
			'reply_email_template'		=> array(
				'label'           	=> esc_html__( 'Email Template', 'divi-form-builder' ),
				'type'            	=> 'textarea',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Define Email template that will appear in the confirmation email.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'email_confirmation',
				'show_if'	=> array('send_copy_to_sender' => 'on'),
				'dynamic_content' 	=> 'text',
			),
			'save_to_database'	=> array(
				'label'           	=> esc_html__( 'Save Entries to Database?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'If you want to save contact form submissions to database, please enable this option.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'extra_options',
				'depends_show_if'	=> 'contact',
			),
			'enable_bloom_subscription'	=> array(
				'label'           	=> esc_html__( 'Enable Bloom Subscription?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     	=> esc_html__( 'Enable this option to add registered user to subscription list.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'extra_options',
				'affects'			=> array(
					'bloom_email_list',
					'bloom_subscribe_text',
					'bloom_subscribe_chk',
					'bloom_name_field',
					'bloom_subscribe_chk_required',
					'bloom_lastname_field',
					'bloom_email_field'
				),
			),
			'bloom_name_field'	=> array(
				'label'           	=> esc_html__( 'Subscriber Name Field ID', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Input the field id of the Name Field from form for Subscriber.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'extra_options',
				'depends_show_if'	=> 'on',
				'dynamic_content' 	=> 'text',
				'default'			=> ''
			),
			'bloom_lastname_field'	=> array(
				'label'           	=> esc_html__( 'Subscriber Last Name Field ID', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Input the field id of the Last Name Field from form for Subscriber.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'extra_options',
				'depends_show_if'	=> 'on',
				'dynamic_content' 	=> 'text',
				'default'			=> ''
			),
			'bloom_email_field'	=> array(
				'label'           	=> esc_html__( 'Subscribe Email Field ID', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Input the field id of the Email Field from form for Subscriber.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'extra_options',
				'depends_show_if'	=> 'on',
				'dynamic_content' 	=> 'text',
				'default'			=> ''
			),
			'bloom_email_list'		=> array(
				'label'				=> esc_html__( 'Subscription Email List', 'divi-form-builder' ),
				'type'				=> 'select',
				'default'			=> 'none',
				'option_category'	=> 'configuration',
				'options'			=> $this->bloom_email_list,
				'description'		=> esc_html__( 'Choose the type of form', 'divi-form-builder' ),
				'depends_show_if'	=> 'on',
				'toggle_slug'     	=> 'extra_options',
			),
			'bloom_subscribe_text'	=> array(
				'label'           	=> esc_html__( 'Subscribe Checkbox Label', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'Define the label of subscription checkbox.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'extra_options',
				'depends_show_if'	=> 'on',
				'dynamic_content' 	=> 'text',
				'default'			=> 'Subscribe to our newsletter'
			),
			'bloom_subscribe_chk'	=> array(
				'label'           	=> esc_html__( 'Check Subscribe Checkbox As Default?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'If you enable this option, subscribe checkbox will be checked as default.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'extra_options',
				'depends_show_if'	=> 'on',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
			),
			'bloom_subscribe_chk_required'	=> array(
				'label'           	=> esc_html__( 'Make Subscribe Checkbox As Required?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'description'     	=> esc_html__( 'If you enable this option, subscribe checkbox will be required to submit.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'extra_options',
				'depends_show_if'	=> 'on',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'affects'			=> array(
					'bloom_required_message',
					'bloom_required_message_position'
				)
			),

			'bloom_required_message'    => array(
				'label'           	=> esc_html__( 'Subscribe Chckbox Required Message', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category' 	=> 'configuration',
				'default'         	=> 'This field is required.',
				'description'     	=> esc_html__( 'Define the error message that will show when subscribe checkbox is required.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'extra_options',
				'depends_show_if'	=> 'on',
			),
			'bloom_required_message_position'    => array(
				'label'           	=> esc_html__( 'Subscribe Chckbox Required Message Position', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'default'         	=> 'bottom',
				'description'     	=> esc_html__( 'Define the position of error message.', 'divi-form-builder' ),
				'options'			=> array(
					'top'			=> esc_html__( 'Top' ),
					'bottom'		=> esc_html__( 'Bottom')
				),
				'toggle_slug'     	=> 'extra_options',
				'depends_show_if'	=> 'on',
			),

			'select2'	=> array(
				'label'           	=> esc_html__( 'Enable Select2 for your Select Options?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description' => esc_html__( 'If you want to use Select2 on one or more of your select options in the filter, enable this. This will enqueue the Select2 JS and CSS to make this work.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'extra_options',
			),
			'select_arrow_color'=> array(
				'label'           => esc_html__( 'Select Arrow Color', 'divi-form-builder' ),
				'type'            => 'color-alpha',
				'default'         => '#666',
				'description'     => esc_html__( 'Set the color of the arrow for the select dropdown.', 'divi-form-builder' ),
				'toggle_slug'     => 'form_field',
				'tab_slug'     	  => 'advanced',
			),

			'button_alignment' => array(
				'label'            => esc_html__( 'Submit Button Alignment', 'et_builder' ),
				'type'             => 'text_align',
				'option_category'  => 'configuration',
				'options'          => et_builder_get_text_orientation_options( array( 'justified' ) ),
				'tab_slug'         => 'advanced',
				'default'					 => 'right',
				'toggle_slug'      => 'alignment',
				'description'      => esc_html__( 'Here you can define the alignment of Button', 'et_builder' ),
			),
			'multistep_button_alignment' => array(
				'label'            => esc_html__( 'Multistep Buttons Alignment', 'et_builder' ),
				'type'             => 'text_align',
				'option_category'  => 'configuration',
				'options'          => et_builder_get_text_orientation_options( ),
				'tab_slug'         => 'advanced',
				'default'		   => 'justified',
				'toggle_slug'      => 'alignment',
				'description'      => esc_html__( 'Here you can define the alignment of Multistep form Buttons', 'et_builder' ),
				'show_if'		   => array('multistep_enabled' => 'on'),	
			),
			'label_padding' => array(
                'label'          => esc_html__('Label Padding', 'divi-form-builder' ),
                'description'    => 'Set the padding for the field label.',
                'type'           => 'custom_padding',
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'margin_padding',
                'mobile_options' => true,
            ),
			'description_padding' => array(
                'label'          => esc_html__('Description Padding', 'divi-form-builder' ),
                'description'    => 'Set the padding for the field description.',
                'type'           => 'custom_padding',
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'margin_padding',
                'mobile_options' => true,
            ),

			'whole_form_padding'          => array(
				'label'           => esc_html__( 'Form Field Wrapper Padding', 'divi-form-builder' ),
				'type'           => 'custom_padding',
				'description'     => esc_html__( 'Set padding for the wrapper around the form fields', 'divi-form-builder' ),
				'default_unit' => 'px',
				'validate_unit' => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'margin_padding',
				'mobile_options'  => true,
			  ),
			  'whole_form_margin'          => array(
				'label'           => esc_html__( 'Form Field Wrapper Margin', 'divi-form-builder' ),
				'type'           => 'custom_margin',
				'description'     => esc_html__( 'Set margin for the wrapper around the form fields', 'divi-form-builder' ),
				'default_unit' => 'px',
				'validate_unit' => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'margin_padding',
				'mobile_options'  => true,
			),
			
			'radio_checkbox_checked_color' 	=> array(
				'default'           => "#2ea3f2",
				'label'             => esc_html__('Checkbox Checked Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the color of the dot when a radio or checkbox is checked.', 'divi-form-builder'),
				'tab_slug'         => 'advanced',
				'toggle_slug'		=> 'form_field',			
			),
			
			'bloom_checkbox_background' 	=> array(
				'default'           => "#eeeeee",
				'label'             => esc_html__('Bloom Checkbox Background Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the background color of the bloom checkbox (sign up).', 'divi-form-builder'),
				'tab_slug'         => 'advanced',
				'toggle_slug'		=> 'form_field',
				'show_if'			=> array('enable_bloom_subscription' => 'on')			
			),

			'multistep_enabled'              => array(
				'label'           => esc_html__( 'Enable Multistep?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     => esc_html__( 'Define whether this a multistep or a regular form.', 'divi-form-builder' ),
				'toggle_slug'     => 'multistep_options',
				'affects'		  => array(
					'progress_bar_step_title'
				)
			),
			'multistep_form_transition'=> array(
				'label'           => esc_html__( 'Form Transition Effect', 'divi-form-builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'default'         => 'fadeIn',
				'options'         => array(
					'none'  	=> esc_html__( 'None', 'divi-form-builder' ),
					'fadeIn' 	=> esc_html__( 'FadeIn', 'divi-form-builder' ),
					'scaleIn' 	=> esc_html__( 'ScaleIn', 'divi-form-builder' ),
					'scaleOut' 	=> esc_html__( 'ScaleOut', 'divi-form-builder' ),
					'slideHorz' => esc_html__( 'SlideHorz', 'divi-form-builder' ),
					'slideVert' => esc_html__( 'SlideVert', 'divi-form-builder' ),
				),
				'description'     => esc_html__( 'Define whether show of hide the progress bar', 'divi-form-builder' ),
				'toggle_slug'     => 'multistep_options',
				'show_if'			=> array('multistep_enabled' => 'on'),
			),
			'multistep_transition_speed'=> array(
				'label'           => esc_html__( 'Form Transition Effect Speed', 'divi-form-builder' ),
				'type'            => 'number',
				'option_category' => 'configuration',
				'default'         => '500',
				'description'     => esc_html__( 'Define speed for transition effect', 'divi-form-builder' ),
				'toggle_slug'     => 'multistep_options',
				'show_if'		  => array('multistep_enabled' => 'on'),
				'show_if_not'	  => array('multistep_form_transition' => 'none'),
			),
			'multistep_progress_bar_style'=> array(
				'label'           => esc_html__( 'Progress bar style', 'divi-form-builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'default'         => 'step',
				'options'         => array(
					'none'  		=> esc_html__( 'None', 'divi-form-builder' ),
					'basic' 	    => esc_html__( 'Basic', 'divi-form-builder' ),
					'lollipop'		=> esc_html__( 'Lollipop', 'divi-form-builder' ),
					'step' 			=> esc_html__( 'Step', 'divi-form-builder' ),
				),
				'description'     => esc_html__( 'Select the progress bar style of your choice', 'divi-form-builder' ),
				'toggle_slug'     => 'multistep_options',
				'show_if'			=> array('multistep_enabled' => 'on'),
			),
			'multistep_progress_bar_basic_height' => array(
				'label'       => esc_html__( 'Basic progress bar height', 'divi-form-builder' ),
				'type'        => 'range',
				'option_category'   => 'configuration',
				'default'			=> '35px',
				'fixed_unit'       => 'px',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '500',
					'step' => '1',
					''
				),
				'description' => esc_html__( 'Choose your basic progress bar height', 'divi-form-builder' ),
				'toggle_slug'       => 'multistep_options',
				'show_if'     => array('multistep_enabled' => 'on','multistep_progress_bar_style' => array('basic', 'lollipop')),
			),
			'multistep_progress_bar_lollipop_radius' => array(
				'label'       => esc_html__( 'Lollipop progress bar border radius', 'divi-form-builder' ),
				'type'        => 'range',
				'option_category'   => 'configuration',
				'default'			=> '20px',
				'fixed_unit'       => 'px',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
					''
				),
				'description' 		=> esc_html__( 'Choose your basic progress bar border radius', 'divi-form-builder' ),
				'toggle_slug'       => 'multistep_options',
				'show_if'     		=> array('multistep_enabled' => 'on','multistep_progress_bar_style' => 'lollipop'),
			),
			'multistep_progress_bar_active_color'           => array(
				'label'           => esc_html__( 'Progress bar active color', 'divi-form-builder' ),
				'type'            => 'color-alpha',
				'option_category' => 'configuration',
                'default'         => et_builder_accent_color(),
				'custom_color'    => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'progress_bar_colors',
				'sub_toggle'     => 'progress_bar_active_color',
                'show_if_not'     => array('multistep_enabled' => 'off', 'multistep_progress_bar_style' => 'none')
			),
			'multistep_progress_bar_inactive_color'           => array(
				'label'           => esc_html__( 'Progress bar inactive color', 'divi-form-builder' ),
				'type'            => 'color-alpha',
				'option_category' => 'configuration',
				'default'         => '#eee',
				'custom_color'    => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'progress_bar_colors',
				'sub_toggle'     => 'progress_bar_inactive_color',
				'show_if_not'     => array('multistep_enabled' => 'off', 'multistep_progress_bar_style' => 'none')
			),
			'multistep_show_progress_bar_percentage'=> array(
				'label'           => esc_html__( 'Progress bar show percentage', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     => esc_html__( 'Define whether show or hide the percentage numbers at the progress bar', 'divi-form-builder' ),
				'toggle_slug'     => 'multistep_options',
				'show_if_not'     => array('multistep_enabled' => 'off', 'multistep_progress_bar_style' => 'none')
			),
			'multistep_show_step_number_in_circle'=> array(
				'label'           => esc_html__( 'Progress bar show step number in circle?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'on',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     => esc_html__( 'Define whether show or hide the step number in circle at the progress bar.', 'divi-form-builder' ),
				'toggle_slug'     => 'multistep_options',
				'show_if'     => array('multistep_enabled' => 'on','multistep_progress_bar_style' => 'step'),
			),
			'multistep_show_step_icon_in_circle'=> array(
				'label'           => esc_html__( 'Progress bar show step icon in circle?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'on',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     => esc_html__( 'Define whether show or hide the step icon in circle at the progress bar.', 'divi-form-builder' ),
				'toggle_slug'     => 'multistep_options',
				'show_if'     => array('multistep_enabled' => 'on','multistep_show_step_number_in_circle' => 'off'),
			),
			'multistep_step_circle_radius' => array(
				'label'       => esc_html__( 'Progress bar Step border radius', 'divi-form-builder' ),
				'type'        => 'range',
				'option_category'   => 'configuration',
				'default'			=> '20px',
				'fixed_unit'       => 'px',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
					''
				),
				'description' 		=> esc_html__( 'Choose your basic progress bar step border radius', 'divi-form-builder' ),
				'toggle_slug'       => 'multistep_options',
				'show_if'     		=> array('multistep_enabled' => 'on','multistep_progress_bar_style' => 'step'),
			),
			'multistep_show_progress_bar_step_title'=> array(
				'label'           => esc_html__( 'Progress bar show step title', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     => esc_html__( 'Define whether show or hide the step titles at the progress bar', 'divi-form-builder' ),
				'toggle_slug'     => 'multistep_options',
				'show_if'     => array('multistep_enabled' => 'on','multistep_progress_bar_style' => 'step'),
			),
			'multistep_enter_go_next'              => array(
				'label'           => esc_html__( 'Go to next step on press Enter?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     => esc_html__( 'Set this ON if you want to press Enter key and go to the next step', 'divi-form-builder' ),
				'toggle_slug'     => 'multistep_options',
				'show_if'			=> array('multistep_enabled' => 'on'),
			),
			'multistep_progress_bar_margin'          => array(
				'label'           => esc_html__( 'Progress bar Margin', 'divi-form-builder' ),
				'type'           => 'custom_margin',
				'description'     => esc_html__( 'Set margin for your progress bar', 'divi-form-builder' ),
				'default_unit' => 'px',
				'validate_unit' => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'margin_padding',
				'mobile_options'  => true,
				'show_if'			=> array('multistep_enabled' => 'on'),
			),
			'multistep_progress_bar_width' => array(
				'label'            => esc_html__( 'Multistep Progress Bar Width', 'et_builder' ),
				'type'        => 'range',
				'option_category'   => 'configuration',
				'default'			=> '100%',
				'default_unit' => 'px',
				'validate_unit' => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'width',
				'description'      => esc_html__( 'Here you can define your progress bar width', 'et_builder' ),
				'show_if'			=> array('multistep_enabled' => 'on'),
			),
			'multistep_progress_bar_alignment' => array(
				'label'            => esc_html__( 'Multistep Progress Bar Alignment', 'et_builder' ),
				'type'             => 'text_align',
				'option_category'  => 'configuration',
				'options'          => et_builder_get_text_orientation_options( ),
				'tab_slug'         => 'advanced',
				'default'			=> 'justify',
				'toggle_slug'      => 'alignment',
				'description'      => esc_html__( 'Here you can define the alignment of Multistep form Buttons', 'et_builder' ),
				'show_if'			=> array('multistep_enabled' => 'on'),
			),
			'date_text_color' 	=> array(
				'default'           => "#000000",
				'label'             => esc_html__('Font Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the font color on the calender', 'divi-form-builder'),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'date_time_app',
			),
			'date_text_active' 	=> array(
				'default'           => "#ffffff",
				'label'             => esc_html__('Active Number Font Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the font color of the active date on the calender', 'divi-form-builder'),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'date_time_app',
			),
			'date_highlight_color' 	=> array(
				'default'           => "#000000",
				'label'             => esc_html__('Highlighted Date Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the font color of the highlighted date on the calender', 'divi-form-builder'),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'date_time_app',
			),
			'date_number_bg' 	=> array(
				'default'           => "#ffffff",
				'label'             => esc_html__('Number Background Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the background color of the number on the calender', 'divi-form-builder'),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'date_time_app',
			),
			'date_number_bg_active' 	=> array(
				'default'           => "#ffffff",
				'label'             => esc_html__('Active Number Background Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the active background color of the number on the calender', 'divi-form-builder'),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'date_time_app',
			),
			'date_highlight_bg' 	=> array(
				'default'           => "#ffffff",
				'label'             => esc_html__('Highlighted Date Background Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the background color of the highlighted date on the calender', 'divi-form-builder'),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'date_time_app',
			),
			'date_number_border_radius'  => array(
				'label'           => esc_html__( 'Number Border Radius', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the border radius for the number on the calender', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '100%',
				'default_unit'     => '%',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '1000',
					'step' => '1',
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'date_time_app',
			),
			
			'basic_captcha_background' 	=> array(
				'default'           => "#eeeeee",
				'label'             => esc_html__('Captcha Answer Background Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the background color of the answer input field.', 'divi-form-builder'),
				'tab_slug'         => 'advanced',
				'toggle_slug' => 'captcha',
				'sub_toggle'  => 'basic_captcha_answer',
				'show_if'			=> array('use_simple_captcha' => 'on')			
			),

		);

		$fields['google_recaptcha'] = array(
			'label'            => esc_html__( 'Add Google reCAPTCHA?', 'divi-form-builder' ),
			'type'            	=> 'yes_no_button',
			'option_category' 	=> 'configuration',
			'default'         	=> 'off',
			'options'         	=> array(
				'on'  => et_builder_i18n( 'Yes' ),
				'off' => et_builder_i18n( 'No' ),
			),
			'description'     	=> esc_html__( 'Enable this option to add google reCAPTCHA field.', 'divi-form-builder' ),
			'toggle_slug'     	=> 'spam',
		);

		$fields['recaptcha_sitekey_type'] = array(
			'label'            => esc_html__( 'reCaptcha Type?', 'divi-form-builder' ),
			'type'            	=> 'select',
			'option_category' 	=> 'configuration',
			'default'         	=> 'recaptcha_2',
			'options'         	=> array(
				'recaptcha_2'	=> esc_html__( 'reCaptcha v2', 'divi-form-builder' ),
				'recaptcha_3'	=> esc_html__( 'reCaptcha v3', 'divi-form-builder' ),
			),
			'description'     	=> esc_html__( 'Choose the type of Google recaptcha you want to use.', 'divi-form-builder' ),
			'toggle_slug'     	=> 'spam',
			'show_if'			=> array('google_recaptcha' => 'on')
		);

		$fields['recaptcha_sitekey'] = array(
			'label'            => esc_html__( 'Google reCAPTCHA Site Key', 'divi-form-builder' ),
			'type'            	=> 'text',
			'option_category' 	=> 'configuration',
			'default'         	=> '',
			'description'     	=> esc_html__( 'Add Google reCAPTCHA v2 Site Key.', 'divi-form-builder' ),
			'depends_show_if'	=> 'on',
			'toggle_slug'     	=> 'spam',
			'show_if'			=> array('recaptcha_sitekey_type' => 'recaptcha_2', 'google_recaptcha' => 'on')
		);

		$fields['recaptcha_sitekey_v3'] = array(
			'label'            => esc_html__( 'Google reCAPTCHA v3 Site Key', 'divi-form-builder' ),
			'type'            	=> 'text',
			'option_category' 	=> 'configuration',
			'default'         	=> '',
			'description'     	=> esc_html__( 'Add Google reCAPTCHA v3 Site Key.', 'divi-form-builder' ),
			'depends_show_if'	=> 'on',
			'toggle_slug'     	=> 'spam',
			'show_if'			=> array('recaptcha_sitekey_type' => 'recaptcha_3', 'google_recaptcha' => 'on')
		);

		$fields['recaptcha_seckey_v3'] = array(
			'label'            => esc_html__( 'Google reCAPTCHA v3 Secret Key', 'divi-form-builder' ),
			'type'            	=> 'text',
			'option_category' 	=> 'configuration',
			'default'         	=> '',
			'description'     	=> esc_html__( 'Add Google reCAPTCHA v3 Secret Key.', 'divi-form-builder' ),
			'depends_show_if'	=> 'on',
			'toggle_slug'     	=> 'spam',
			'show_if'			=> array('recaptcha_sitekey_type' => 'recaptcha_3', 'google_recaptcha' => 'on')
		);

		$fields['recaptcha_score_v3'] = array(
			'label'            => esc_html__( 'Google reCAPTCHA v3 Score', 'divi-form-builder' ),
			'type'            	=> 'range',
			'option_category' 	=> 'configuration',
			'default'         	=> '0.5',
			'range_settings'	=> array(
				'min'				=> '0.0',
				'max'				=> '1.0',
				'step'				=> '0.1'
			),
			'description'     	=> esc_html__( 'Add Google Invisible reCAPTCHA v2 Secret Key.', 'divi-form-builder' ),
			'depends_show_if'	=> 'on',
			'toggle_slug'     	=> 'spam',
			'show_if'			=> array('recaptcha_sitekey_type' => 'recaptcha_3', 'google_recaptcha' => 'on')
		);

		$fields['use_simple_captcha'] = array(
			'label'            => esc_html__( 'Add Basic CAPTCHA field?', 'divi-form-builder' ),
			'type'            	=> 'yes_no_button',
			'option_category' 	=> 'configuration',
			'default'         	=> 'off',
			'options'         	=> array(
				'on'  => et_builder_i18n( 'Yes' ),
				'off' => et_builder_i18n( 'No' ),
			),
			'description'     	=> esc_html__( 'Enable this option to add simple CAPTCHA field.', 'divi-form-builder' ),
			'toggle_slug'     	=> 'spam',
		);

		$fields['use_honeypot_captcha'] = array(
			'label'            => esc_html__( 'Add Honeypot CAPTCHA field?', 'divi-form-builder' ),
			'type'            	=> 'yes_no_button',
			'option_category' 	=> 'configuration',
			'default'         	=> 'on',
			'options'         	=> array(
				'on'  => et_builder_i18n( 'Yes' ),
				'off' => et_builder_i18n( 'No' ),
			),
			'description'     	=> esc_html__( 'Enable this option to add Honeypot CAPTCHA field.', 'divi-form-builder' ),
			'toggle_slug'     	=> 'spam',
		);

		return $fields;
	}

	public function get_mail_error_message( $error_obj ) {
		$this->email_error_message = $error_obj->get_error_message();
	}

	public function get_form_field_content() {
        return $this->content;
    }

    public function display_message( $submit_result, $messages ) {
    	$message_array = $messages[$submit_result];

    	$err_message = !empty( get_query_var('df_submit_message') )?get_query_var('df_submit_message'):'';

    	if ( $message_array['type'] == 'text' ) {
    		$message = str_ireplace( "%%message%%", $err_message, $message_array['text'] );
    		//$message = str_ireplace( "%%Message%%", $err_message, $message_array['text'] );
?>
		<div class="message_wrapper">
			<div class="message message_<?php echo $submit_result;?>">
			<?php echo $message;?>
			</div>
		</div>
		
<?php		
    	} else if ( $message_array['type'] == 'layout' && !empty( $message_array['layout'] ) ) {
			if ( $submit_result == 'success' ) {
				echo '<div class="message_success">';
			}
    		$content = apply_filters( 'the_content', get_post_field( 'post_content', $message_array['layout'] ) );
    		$err_message = !empty( get_query_var('df_submit_message') )?get_query_var('df_submit_message'):'';
    		$content = str_ireplace( "%%message%%", $err_message, $content );
			echo $content;
			if ( $submit_result == 'success' ) {
				echo '</div>';
			}
    	}
    }


	public function de_shortcode_parse_atts( $shortcode ) {
		// Store the shortcode attributes in an array heree
		$attributes = [];

		if (preg_match_all('/\w+\=\".*?\"/', $shortcode, $key_value_pairs)) {

			// Now split up the key value pairs
			foreach($key_value_pairs[0] as $kvp) {
				$kvp = str_replace('"', '', $kvp);
				$pair = explode('=', $kvp);
				$attributes[$pair[0]] = $pair[1];
			}
		}

		// Return the array
		return $attributes;
	}

	public function get_child_fields( ) {
		// Get the post content once
		$shortcode = 'de_fb_form_field';
		$content = $this->content_unprocessed; //self::get_child_modules('post')['et_pb_column']->_original_content;

		// $content = apply_filters( 'the_content', get_the_content( null, false, $post_id ) ); // Alternative if get_the_content() doesn't work for whatever reason
		// Double check that there is content
		if ($content) {
			// Shortcode regex
			$shortcode_regex = '/\['.$shortcode.'\s.*?]/';
			// Get all the shortcodes from the page
			if (preg_match_all($shortcode_regex, $content, $shortcodes)){
				// Store them here
				$final_array = [];
				// Extract the attributes from the shortcode
				foreach ($shortcodes[0] as $s) {
					$attributes = $this->de_shortcode_parse_atts( $s );
					// The return the post
					$final_array[] = $attributes;
				}
				// Return the array
				$results = $final_array;
				// Otherwise return an empty array if none are found
			} else {
				$results = [];
			}
			// Return it
			return $results;
		} else {
			return false;
		}
	}

	public function render( $attrs, $content, $render_slug ) {
		//parent::render( $attrs, $content, $render_slug );

		$form_title 		= $this->props['title'];
		$form_id 			= $this->props['form_id'];
		$submit_button_text = $this->props['submit_button_text'];
		$form_type			= $this->props['form_type'];

		$login_already_text			= $this->props['login_already_text'];
		$register_wrong_password_text			= $this->props['register_wrong_password_text'];
		
		
		$select2             = $this->props['select2']; 

		$custom_button  			= $this->props['custom_button'];
		$custom_icon          		= $this->props['button_icon'];
		$button_bg_color       		= $this->props['button_bg_color'];
		$button_use_icon  			= $this->props['button_use_icon'];
		$button_icon 				= $this->props['button_icon'];
		$button_icon_placement 		= $this->props['button_icon_placement'];
		$is_ajax_submit				= $this->props['is_ajax_submit'];
		$ajax_submit_button_text	= $this->props['ajax_submit_button_text'];
		$disable_submit_for_required = $this->props['disable_submit_for_required'];

		$button_alignment 		= $this->props['button_alignment'];
		$module_alignment 		= $this->props['module_alignment'];
		$multistep_button_alignment	= !empty($this->props['multistep_button_alignment'])?$this->props['multistep_button_alignment']:'justified';
		
		$scrollto_form_after_submit = $this->props['scrollto_form_after_submit'];
		$scrollto_form_offset 		= isset($this->props['scrollto_form_offset'])?$this->props['scrollto_form_offset']:'0px';

		$is_user_edit 			= $this->props['is_user_edit'];
		$default_user_role		= $this->props['default_user_role'];

		$save_to_database 		= $this->props['save_to_database'];

		$google_recaptcha 			= !empty( $this->props['google_recaptcha'] )?$this->props['google_recaptcha']:'off';
		$recaptcha_sitekey_type		= $this->props['recaptcha_sitekey_type'];
		$recaptcha_sitekey 			= $this->props['recaptcha_sitekey'];
		$recaptcha_sitekey_v3		= $this->props['recaptcha_sitekey_v3'];
		$recaptcha_seckey_v3		= $this->props['recaptcha_seckey_v3'];
		$recaptcha_score_v3			= $this->props['recaptcha_score_v3'];

		$use_simple_captcha			= !empty( $this->props['use_simple_captcha'] )?$this->props['use_simple_captcha']:'off';
		$use_honeypot_captcha 		= !empty( $this->props['use_honeypot_captcha'] )?$this->props['use_honeypot_captcha']:'on';
				
		$enable_bloom_subscription 	= !empty($this->props['enable_bloom_subscription'])?$this->props['enable_bloom_subscription']:'off';
		$bloom_email_list			= !empty($this->props['bloom_email_list'])?$this->props['bloom_email_list']:'none';
		$bloom_subscribe_text 		= !empty( $this->props['bloom_subscribe_text'] )?$this->props['bloom_subscribe_text']:'';
		$bloom_subscribe_chk 		= !empty( $this->props['bloom_subscribe_chk'] )?$this->props['bloom_subscribe_chk']:'off';
		$bloom_subscribe_chk_required = !empty( $this->props['bloom_subscribe_chk_required'])?$this->props['bloom_subscribe_chk_required']:'off';
		$bloom_required_message		= !empty( $this->props['bloom_required_message'])?$this->props['bloom_required_message']:'This field is required.';
		$bloom_required_message_position = !empty( $this->props['bloom_required_message_position'])?$this->props['bloom_required_message_position']:'bottom';
		$bloom_name_field_id		= !empty( $this->props['bloom_name_field'] )?str_replace(" ", "_", strtolower($this->props['bloom_name_field'])):'';
		$bloom_lastname_field_id	= !empty( $this->props['bloom_lastname_field'] )?str_replace(" ", "_", strtolower($this->props['bloom_lastname_field'])):'';
		$bloom_email_field_id		= !empty( $this->props['bloom_email_field'] )?str_replace(" ", "_", strtolower($this->props['bloom_email_field'])):'';

		$default_post_status 		= !empty( $this->props['default_post_status'] )?$this->props['default_post_status']:'';
		$edit_permission 			= !empty( $this->props['edit_permission'] )?$this->props['edit_permission']:'author';
		$edit_permission_role 		= !empty( $this->props['edit_permission_role'])?$this->props['edit_permission_role']:'author';

		$no_permission_notice 		= $this->props['no_permission_notice'];
		$enable_assign_terms		= !empty( $this->props['enable_assign_terms'] )?$this->props['enable_assign_terms']:'off';
		$message_position 			= !empty( $this->props['message_position'] )?$this->props['message_position']:'before_title';
		$redirect_after_success 	= !empty( $this->props['redirect_after_success'] )?$this->props['redirect_after_success']:'off';
		$success_message_type 		= !empty( $this->props['success_message_type'] )?$this->props['success_message_type']:'text';
		$success_message 			= !empty( $this->props['success_message'] )?$this->props['success_message']:'';
		$success_layout 			= !empty( $this->props['success_layout'] )?$this->props['success_layout']:'';
		$success_hide_form 			= !empty( $this->props['success_hide_form'] )?$this->props['success_hide_form']:'';
        $reset_form_on_submit 	    = $this->props['reset_form_on_submit'] == 'on' ? 'true' : 'false';

		$redirect_url_after_submission = !empty( $this->props['redirect_url_after_submission'] )?$this->props['redirect_url_after_submission']:'';

		$redirect_after_failed 		= !empty( $this->props['redirect_after_failed'] )?$this->props['redirect_after_failed']:'off';
		$failed_message_type 		= !empty( $this->props['failed_message_type'] )?$this->props['failed_message_type']:'text';
		$failed_message 			= !empty( $this->props['failed_message'] )?$this->props['failed_message']:'';
		$failed_layout 				= !empty( $this->props['failed_layout'] )?$this->props['failed_layout']:'';
		$redirect_url_after_failed 	= !empty( $this->props['redirect_url_after_failed'] )?$this->props['redirect_url_after_failed']:'';

		$enable_submission_notification = $this->props['enable_submission_notification'];
		$use_custom_email 				= $this->props['use_custom_email'];
		$acf_field_type 				= $this->props['acf_field_type'];
		$acf_email_field_linked 		= $this->props['acf_email_field_linked'];
		$acf_email_field 				= $this->props['acf_email_field'];
		$custom_contact_email 			= $this->props['custom_contact_email'];
		$from_name_field				= str_replace(" ", "_", strtolower($this->props['from_name_field']));
		$from_email_field				= str_replace(" ", "_", strtolower($this->props['from_email_field']));
		$replyto_email 					= str_replace(" ", "_", strtolower($this->props['replyto_email']));
		$replyto_name 					= str_replace(" ", "_", strtolower($this->props['replyto_name']));
		$from_name 						= $this->props['from_name'];
		$from_email 					= $this->props['from_email'];
		$custom_from_name				= $this->props['custom_from_name'];
		$custom_from_email				= $this->props['custom_from_email'];
		$email_cc 						= $this->props['email_cc'];
		$email_bcc 						= $this->props['email_bcc'];
		$email_title 					= $this->props['email_title'];
		$email_template_html 			= $this->props['email_template_html'];
		$email_template 				= $this->props['email_template'];
		$send_copy_to_sender 			= $this->props['send_copy_to_sender'];
		$sender_setting 				= $this->props['sender_setting'];
		$reply_from_name 				= $this->props['reply_from_name'];
		$reply_custom_from_name 		= $this->props['reply_custom_from_name'];
		$reply_from_email 				= $this->props['reply_from_email'];
		$reply_custom_from_email 		= $this->props['reply_custom_from_email'];
		$reply_to_email 				= $this->props['reply_to_email'];
		$reply_to_name 					= $this->props['reply_to_name'];
		$reply_email_title				= $this->props['reply_email_title'];
		$reply_email_template_html		= $this->props['reply_email_template_html'];
		$reply_email_template 			= $this->props['reply_email_template'];
		$sender_name_field 				= str_replace(" ", "_", strtolower($this->props['sender_name_field']));
		$sender_email_field				= str_replace(" ", "_", strtolower($this->props['sender_email_field']));
		$form_action_url				= $this->props['form_action_url'];

		
		$radio_checkbox_checked_color 	= $this->props['radio_checkbox_checked_color'];
		$bloom_checkbox_background 		= $this->props['bloom_checkbox_background'];
		
		$basic_captcha_background 		= $this->props['basic_captcha_background'];
		
		$label_padding 						= $this->props['label_padding'];
        $label_padding_tablet				= $this->props['label_padding_tablet'];
        $label_padding_phone				= $this->props['label_padding_phone'];
        $label_padding_last_edited			= $this->props['label_padding' . '_last_edited'];
        $label_padding_responsive_active 	= et_pb_get_responsive_status($label_padding_last_edited);
		
		$description_padding                              = $this->props['description_padding'];
        $description_padding_tablet                       = $this->props['description_padding_tablet'];
        $description_padding_phone                        = $this->props['description_padding_phone'];
        $description_padding_last_edited                  = $this->props['description_padding' . '_last_edited'];
        $description_padding_responsive_active            = et_pb_get_responsive_status($description_padding_last_edited);


        $multistep_enabled  					= $this->props['multistep_enabled'] == 'on';
        $multistep_form_transition 				= $this->props['multistep_form_transition'];
        $multistep_transition_speed 			= $this->props['multistep_transition_speed']?floatval($this->props['multistep_transition_speed']):200.00;
        $multistep_progress_bar_style 			= $this->props['multistep_progress_bar_style'];
		$multistep_progress_bar_basic_height 	= $this->props['multistep_progress_bar_basic_height'];
		$multistep_progress_bar_lollipop_radius = $this->props['multistep_progress_bar_lollipop_radius'];
        $multistep_progress_bar_active_color 	= $this->props['multistep_progress_bar_active_color'];
        $multistep_progress_bar_inactive_color	= $this->props['multistep_progress_bar_inactive_color'];
		$multistep_progress_bar_margin     		= $this->props['multistep_progress_bar_margin'];
		$multistep_progress_bar_margin_tablet   = $this->props['multistep_progress_bar_margin_tablet'];
		$multistep_progress_bar_margin_phone    = $this->props['multistep_progress_bar_margin_phone'];
        $multistep_enter_go_next 				= $this->props['multistep_enter_go_next'];
        $multistep_show_progress_bar_percentage	= $this->props['multistep_show_progress_bar_percentage'] == 'on';

        $multistep_show_step_number_in_circle 	= isset($this->props['multistep_show_step_number_in_circle'])?$this->props['multistep_show_step_number_in_circle']:'on';
        $multistep_show_step_icon_in_circle		= isset($this->props['multistep_show_step_icon_in_circle'])?$this->props['multistep_show_step_icon_in_circle']:'on';
        $multistep_step_circle_radius 			= isset($this->props['multistep_step_circle_radius'])?$this->props['multistep_step_circle_radius']:'20px';
        $multistep_show_progress_bar_step_title = $this->props['multistep_show_progress_bar_step_title'] == 'on';
		$multistep_progress_bar_width 			= $this->props['multistep_progress_bar_width'];
		$multistep_progress_bar_alignment		= $this->props['multistep_progress_bar_alignment'];

		$date_text_color        	= $this->props['date_text_color'];
		$date_text_active        	= $this->props['date_text_active'];
		$date_highlight_color 		= $this->props['date_highlight_color'];
		$date_number_bg        		= $this->props['date_number_bg'] ?: 'transparent';
		$date_number_bg_active 		= $this->props['date_number_bg_active'];
		$date_highlight_bg 			= $this->props['date_highlight_bg'];

		$date_number_border_radius        = $this->props['date_number_border_radius'] ?: '0px';

		$whole_form_padding    	= $this->props['whole_form_padding'];
        $whole_form_padding_tablet		= $this->props['whole_form_padding_tablet'];
        $whole_form_padding_phone		= $this->props['whole_form_padding_phone'];

		$whole_form_margin     = $this->props['whole_form_margin'];
        $whole_form_margin_tablet		= $this->props['whole_form_margin_tablet'];
        $whole_form_margin_phone		= $this->props['whole_form_margin_phone'];	
		$select_arrow_color			= $this->props['select_arrow_color'];	

		// Module classnames
		$this->add_classname(
			array(
				'clearfix',
				$this->get_text_orientation_classname(),
			)
		);

		// form field wrapper PADDING
		if ('' !== $whole_form_padding && '|||' !== $whole_form_padding) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .divi-form-wrapper',
                'declaration' => sprintf(
                'padding-top: %1$s; padding-right: %2$s; padding-bottom: %3$s; padding-left: %4$s;',
                esc_attr(et_pb_get_spacing($whole_form_padding, 'top', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_padding, 'right', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_padding, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_padding, 'left', '0px'))
                ),
            ));
        }
        if ('' !== $whole_form_padding_tablet && '|||' !== $whole_form_padding_tablet) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .divi-form-wrapper',
                'declaration' => sprintf(
                'padding-top: %1$s; padding-right: %2$s; padding-bottom: %3$s; padding-left: %4$s;',
                esc_attr(et_pb_get_spacing($whole_form_padding_tablet, 'top', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_padding_tablet, 'right', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_padding_tablet, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_padding_tablet, 'left', '0px'))
                ),
                'media_query' => ET_Builder_Element::get_media_query('max_width_980')
            ));
        }
        if ('' !== $whole_form_padding_phone && '|||' !== $whole_form_padding_phone) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .divi-form-wrapper',
                'declaration' => sprintf(
                'padding-top: %1$s; padding-right: %2$s; padding-bottom: %3$s; padding-left: %4$s;',
                esc_attr(et_pb_get_spacing($whole_form_padding_phone, 'top', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_padding_phone, 'right', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_padding_phone, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_padding_phone, 'left', '0px'))
                ),
                'media_query' => ET_Builder_Element::get_media_query('max_width_767')
            ));
        }

		// form field wrapper MARGIN
		if ('' !== $whole_form_margin && '|||' !== $whole_form_margin) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .divi-form-wrapper',
                'declaration' => sprintf(
                'margin-top: %1$s; margin-right: %2$s; margin-bottom: %3$s; margin-left: %4$s;',
                esc_attr(et_pb_get_spacing($whole_form_margin, 'top', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_margin, 'right', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_margin, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_margin, 'left', '0px'))
                ),
            ));
        }
        if ('' !== $whole_form_margin_tablet && '|||' !== $whole_form_margin_tablet) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .divi-form-wrapper',
                'declaration' => sprintf(
                'margin-top: %1$s; margin-right: %2$s; margin-bottom: %3$s; margin-left: %4$s;',
                esc_attr(et_pb_get_spacing($whole_form_margin_tablet, 'top', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_margin_tablet, 'right', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_margin_tablet, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_margin_tablet, 'left', '0px'))
                ),
                'media_query' => ET_Builder_Element::get_media_query('max_width_980')
            ));
        }
        if ('' !== $whole_form_margin_phone && '|||' !== $whole_form_margin_phone) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .divi-form-wrapper',
                'declaration' => sprintf(
                'margin-top: %1$s; margin-right: %2$s; margin-bottom: %3$s; margin-left: %4$s;',
                esc_attr(et_pb_get_spacing($whole_form_margin_phone, 'top', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_margin_phone, 'right', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_margin_phone, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($whole_form_margin_phone, 'left', '0px'))
                ),
                'media_query' => ET_Builder_Element::get_media_query('max_width_767')
            ));
        }

        $child_fields = $this->get_child_fields();

		$step_fields = array();

		if (is_array($child_fields)) {
        	foreach($child_fields as $child){
            	if( !empty($child['field_type'])){
                	if($child['field_type'] == 'step'){
	                	$step_fields[]=$child;
                	}
            	}
        	}
		}

        $hide_until_loaded 					= $this->props['hide_until_loaded'];
        $use_preload_animation				= $this->props['use_preload_animation'];
        $preload_anim_style					= $this->props['preload_anim_style'];

		$previous_step_button_icon = $this->props['previous_step_button_icon'];
        $previous_step_button_icon_placement = $this->props['previous_step_button_icon_placement'];
        $next_step_button_icon = $this->props['next_step_button_icon'];
		$next_step_button_icon_placement = $this->props['next_step_button_icon_placement'];

		$previous_step_button_icon = $previous_step_button_icon ?? 'N||divi||400';
		$previous_step_button_icon_arr = explode('||', $previous_step_button_icon);
		$previous_step_button_icon_font_family = ( !empty( $previous_step_button_icon_arr[1] ) && $previous_step_button_icon_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
		$previous_step_button_icon_font_weight = ( !empty( $previous_step_button_icon_arr[2] ))?$previous_step_button_icon_arr[2]:'400';
		$previous_step_button_icon_dis = preg_replace( '/(&amp;#x)|;/', '', et_pb_process_font_icon( $previous_step_button_icon ) );
		$previous_step_button_icon_dis = preg_replace( '/(&#x)|;/', '', $previous_step_button_icon_dis );
		$previous_step_button_icon_selector= $previous_step_button_icon_placement == 'right' ? 'after' : 'before';
		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => "body #page-container .et_pb_section %%order_class%% .df_step_prev::$previous_step_button_icon_selector",
			'declaration' => sprintf(
				'
                position: absolute;
                content:"\%1s";
                font-family:%2$s!important;
                font-weight:%3$s;
                ',$previous_step_button_icon_dis,
				$previous_step_button_icon_font_family,
				$previous_step_button_icon_font_weight
			),
		));

		$next_step_button_icon = $next_step_button_icon ?? 'N||divi||400';
		$next_step_button_icon_arr = explode('||', $next_step_button_icon);
		$next_step_button_icon_font_family = ( !empty( $next_step_button_icon_arr[1] ) && $next_step_button_icon_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
		$next_step_button_icon_font_weight = ( !empty( $next_step_button_icon_arr[2] ))?$next_step_button_icon_arr[2]:'400';
		$next_step_button_icon_dis = preg_replace( '/(&amp;#x)|;/', '', et_pb_process_font_icon( $next_step_button_icon ) );
		$next_step_button_icon_dis = preg_replace( '/(&#x)|;/', '', $next_step_button_icon_dis );
		$next_step_button_icon_selector= $next_step_button_icon_placement == 'right' ? 'after' : 'before';

		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => "body #page-container .et_pb_section %%order_class%% .df_step_next::$next_step_button_icon_selector",
			'declaration' => sprintf(
				'
                position: absolute;
                content:"\%1s";
                font-family:%2$s!important;
                font-weight:%3$s;
                ',$next_step_button_icon_dis,
				$next_step_button_icon_font_family,
				$next_step_button_icon_font_weight
			),
		));

		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => 'body #page-container .et_pb_section %%order_class%% p input[type=radio]:checked+label i:before',
			'declaration' => 'background-color: ' . $radio_checkbox_checked_color . ';',
		));

		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => 'body #page-container .et_pb_section %%order_class%% p input[type=checkbox]:checked+label i:before',
			'declaration' => 'color: ' . $radio_checkbox_checked_color . ';',
		));

		if ($enable_bloom_subscription == 'on') {
			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .et_pb_contact p #bloom_subscribe+label i',
				'declaration' => sprintf(
					'background-color: '.$bloom_checkbox_background.' !important;'
				),
			));
		}

		if ($use_simple_captcha == 'on') {
			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% input[type=text].maths_answer',
				'declaration' => sprintf(
					'background-color: '.$basic_captcha_background.' !important;'
				),
			));
		}
		
		if ('' !== $label_padding && '|||' !== $label_padding) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .field_label',
                'declaration' => sprintf(
                'padding-top: %1$s; padding-right: %2$s; padding-bottom: %3$s; padding-left: %4$s;',
                esc_attr(et_pb_get_spacing($label_padding, 'top', '0px')),
                esc_attr(et_pb_get_spacing($label_padding, 'right', '0px')),
                esc_attr(et_pb_get_spacing($label_padding, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($label_padding, 'left', '0px'))
                ),
            ));
        }
        if ('' !== $label_padding_tablet && '|||' !== $label_padding_tablet) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .field_label',
                'declaration' => sprintf(
                'padding-top: %1$s; padding-right: %2$s; padding-bottom: %3$s; padding-left: %4$s;',
                esc_attr(et_pb_get_spacing($label_padding_tablet, 'top', '0px')),
                esc_attr(et_pb_get_spacing($label_padding_tablet, 'right', '0px')),
                esc_attr(et_pb_get_spacing($label_padding_tablet, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($label_padding_tablet, 'left', '0px'))
                ),
                'media_query' => ET_Builder_Element::get_media_query('max_width_980')
            ));
        }
        if ('' !== $label_padding_phone && '|||' !== $label_padding_phone) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .field_label',
                'declaration' => sprintf(
                'padding-top: %1$s; padding-right: %2$s; padding-bottom: %3$s; padding-left: %4$s;',
                esc_attr(et_pb_get_spacing($label_padding_phone, 'top', '0px')),
                esc_attr(et_pb_get_spacing($label_padding_phone, 'right', '0px')),
                esc_attr(et_pb_get_spacing($label_padding_phone, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($label_padding_phone, 'left', '0px'))
                ),
                'media_query' => ET_Builder_Element::get_media_query('max_width_767')
            ));
        }
		
		if ('' !== $description_padding && '|||' !== $description_padding) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .df_field_description_text',
                'declaration' => sprintf(
                'padding-top: %1$s; padding-right: %2$s; padding-bottom: %3$s; padding-left: %4$s;',
                esc_attr(et_pb_get_spacing($description_padding, 'top', '0px')),
                esc_attr(et_pb_get_spacing($description_padding, 'right', '0px')),
                esc_attr(et_pb_get_spacing($description_padding, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($description_padding, 'left', '0px'))
                ),
            ));
        }
        if ('' !== $description_padding_tablet && '|||' !== $description_padding_tablet) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .df_field_description_text',
                'declaration' => sprintf(
                'padding-top: %1$s; padding-right: %2$s; padding-bottom: %3$s; padding-left: %4$s;',
                esc_attr(et_pb_get_spacing($description_padding_tablet, 'top', '0px')),
                esc_attr(et_pb_get_spacing($description_padding_tablet, 'right', '0px')),
                esc_attr(et_pb_get_spacing($description_padding_tablet, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($description_padding_tablet, 'left', '0px'))
                ),
                'media_query' => ET_Builder_Element::get_media_query('max_width_980')
            ));
        }

        if ('' !== $description_padding_phone && '|||' !== $description_padding_phone) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .df_field_description_text',
                'declaration' => sprintf(
                'padding-top: %1$s; padding-right: %2$s; padding-bottom: %3$s; padding-left: %4$s;',
                esc_attr(et_pb_get_spacing($description_padding_phone, 'top', '0px')),
                esc_attr(et_pb_get_spacing($description_padding_phone, 'right', '0px')),
                esc_attr(et_pb_get_spacing($description_padding_phone, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($description_padding_phone, 'left', '0px'))
                ),
                'media_query' => ET_Builder_Element::get_media_query('max_width_767')
            ));
        }

        /* PROGRESS BAR STYLES */

        /* PROGRESS BAR WIDTH */
		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => '%%order_class%% .df_progressbar',
			'declaration' => 'width: ' . $multistep_progress_bar_width . ';',
		));

		if ( $multistep_progress_bar_alignment != '' ) {
			$progressbar_alignment = 'center';
			if ( $multistep_progress_bar_alignment == 'left' ) {
				$progressbar_alignment = 'start';
			} else if ( $multistep_progress_bar_alignment == 'right' ) {
				$progressbar_alignment = 'end';
			}

			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .df_progressbar_container',
				'declaration' => 'justify-content: ' . $progressbar_alignment . ';',
			));
		}

        /* BASIC PROGRESS BAR HEIGHT */
		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => '%%order_class%% .df_progressbar_basic, %%order_class%% .df_progressbar_lollipop',
			'declaration' => 'height: ' . $multistep_progress_bar_basic_height . ';',
		));

		if ( $multistep_enabled && $multistep_progress_bar_style == 'lollipop' && $multistep_progress_bar_lollipop_radius != '' ){
			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .df_progressbar_lollipop',
				'declaration' => 'border-radius: ' . $multistep_progress_bar_lollipop_radius . ';',
			));
		}

		if ( $multistep_enabled && $multistep_progress_bar_style == 'step' && $multistep_step_circle_radius != '' ) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .df_progressbar_step li:before',
				'declaration' => 'border-radius: ' . $multistep_step_circle_radius . ';',
			));
		}

		/* BASIC ACTIVE PROGRESS BAR HEIGHT */
		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => '%%order_class%% .df_progressbar_basic .df_progressbar_active, %%order_class%% .df_progressbar_lollipop .df_progressbar_active',
			'declaration' => 'height: ' . $multistep_progress_bar_basic_height . ';',
		));

		/* BASIC ACTIVE PROGRESS BAR PERCENTAGE LINE HEIGHT */
		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => '%%order_class%% .df_progressbar_basic .df_progressbar_percentage, %%order_class%% .df_progressbar_lollipop .df_progressbar_percentage',
			'declaration' => 'line-height: ' . $multistep_progress_bar_basic_height . ';',
		));


		/* BASIC PROGRESS BAR INACTIVE COLOR */
		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => '%%order_class%% .df_progressbar_basic, %%order_class%% .df_progressbar_lollipop',
			'declaration' => 'background-color: ' . $multistep_progress_bar_inactive_color . ';',
		));

		/* BASIC PROGRESS BAR ACTIVE COLOR */
		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => '%%order_class%% .df_progressbar_basic .df_progressbar_active, %%order_class%% .df_progressbar_lollipop .df_progressbar_active',
			'declaration' => 'background-color: ' . $multistep_progress_bar_active_color . ';',
		));


		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => '%%order_class%% .df_progressbar li',
			'declaration' => 'color: ' . $multistep_progress_bar_inactive_color . ';',
		));

        ET_Builder_Element::set_style($render_slug, array(
			'selector'    => '%%order_class%% .df_progressbar li.active',
			'declaration' => 'color: ' . $multistep_progress_bar_active_color . ';',
		));

		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => '%%order_class%% .df_progressbar li.prev-active',
			'declaration' => 'color: ' . $multistep_progress_bar_active_color . ';',
		));

		if ('' !== $multistep_progress_bar_margin && '|||' !== $multistep_progress_bar_margin ) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .df_progressbar',
				'declaration' => sprintf(
					'margin-top: %1$s; margin-right: %2$s; margin-bottom: %3$s; margin-left: %4$s;',
					esc_attr(et_pb_get_spacing($multistep_progress_bar_margin , 'top', '0px')),
					esc_attr(et_pb_get_spacing($multistep_progress_bar_margin , 'right', '0px')),
					esc_attr(et_pb_get_spacing($multistep_progress_bar_margin , 'bottom', '0px')),
					esc_attr(et_pb_get_spacing($multistep_progress_bar_margin , 'left', '0px'))
				),
			));
		}
		if ('' !== $multistep_progress_bar_margin_tablet && '|||' !== $multistep_progress_bar_margin_tablet) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .df_progressbar',
				'declaration' => sprintf(
					'margin-top: %1$s; margin-right: %2$s; margin-bottom: %3$s; margin-left: %4$s;',
					esc_attr(et_pb_get_spacing($multistep_progress_bar_margin_tablet, 'top', '0px')),
					esc_attr(et_pb_get_spacing($multistep_progress_bar_margin_tablet, 'right', '0px')),
					esc_attr(et_pb_get_spacing($multistep_progress_bar_margin_tablet, 'bottom', '0px')),
					esc_attr(et_pb_get_spacing($multistep_progress_bar_margin_tablet, 'left', '0px'))
				),
				'media_query' => ET_Builder_Element::get_media_query('max_width_980')
			));
		}
		if ('' !== $multistep_progress_bar_margin_phone && '|||' !== $multistep_progress_bar_margin_phone) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .df_progressbar',
				'declaration' => sprintf(
					'margin-top: %1$s; margin-right: %2$s; margin-bottom: %3$s; margin-left: %4$s;',
					esc_attr(et_pb_get_spacing($multistep_progress_bar_margin_phone, 'top', '0px')),
					esc_attr(et_pb_get_spacing($multistep_progress_bar_margin_phone, 'right', '0px')),
					esc_attr(et_pb_get_spacing($multistep_progress_bar_margin_phone, 'bottom', '0px')),
					esc_attr(et_pb_get_spacing($multistep_progress_bar_margin_phone, 'left', '0px'))
				),
				'media_query' => ET_Builder_Element::get_media_query('max_width_767')
			));
		}

		wp_enqueue_script( 'de_fb_validate' );
		wp_enqueue_script( 'de_fb_validate_additional' );

        if($multistep_enabled){
	        wp_enqueue_script( 'multistep', DE_FB_URL . '/js/multistep.min.js', array(), DE_FB_VERSION );

	    	wp_localize_script( 'multistep', 'ms_obj', array(
	    		'next_on_enter'	=> $multistep_enter_go_next
	    	));

	        if ( $multistep_form_transition != "none" ) {
	        	ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .df_form_step.active',
					'declaration' => sprintf(
						'transition-duration: %1$s;',
						strval($multistep_transition_speed / 1000) . 's'
					)
				));
	        }

	        if ( $multistep_progress_bar_style == 'step' && ( $multistep_show_step_number_in_circle == 'on' || $multistep_show_step_icon_in_circle == 'on' ) ) { 
	        	ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .df_progressbar_step li:before, %%order_class%% .df_progressbar_step li',
					'declaration' => sprintf(
						'width: %1$s;height:%1$s;',
						$this->props['progress_bar_step_number_line_height']
					)
				));

				ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .df_progressbar_step li:after',
					'declaration' => sprintf(
						'top: %1$s;',
						strval(intval($this->props['progress_bar_step_number_line_height']) / 2) . 'px'
					)
				));

				if ( $multistep_show_progress_bar_percentage && $multistep_show_progress_bar_step_title ) {
					ET_Builder_Element::set_style($render_slug, array(
						'selector'    => '%%order_class%% .df_progressbar_step li',
						'declaration' => sprintf(
							'padding-top: %1$s;padding-bottom: %2$s;',
							$this->props['progress_bar_step_number_line_height'],
							(intval($this->props['progress_bar_step_number_line_height']) * 2) . 'px'
						)
					));

					ET_Builder_Element::set_style($render_slug, array(
						'selector'    => '%%order_class%% .df_progressbar_step .df_progressbar_percentage',
						'declaration' => sprintf(
							'top: %1$s;',
							$this->props['progress_bar_step_number_line_height']
						)
					));

					ET_Builder_Element::set_style($render_slug, array(
						'selector'    => '%%order_class%% .df_progressbar_step .df_step_title_text',
						'declaration' => sprintf(
							'top: %1$s;',
							(intval($this->props['progress_bar_step_number_line_height']) * 2) . 'px'
						)
					));
                } else if ( $multistep_show_progress_bar_percentage || $multistep_show_progress_bar_step_title ) {
                	ET_Builder_Element::set_style($render_slug, array(
						'selector'    => '%%order_class%% .df_progressbar_step li',
						'declaration' => sprintf(
							'padding-top: %1$s;padding-bottom: %1$s;',
							$this->props['progress_bar_step_number_line_height']
						)
					));

					ET_Builder_Element::set_style($render_slug, array(
						'selector'    => '%%order_class%% .df_progressbar_step .df_progressbar_percentage, %%order_class%% .df_progressbar_step .df_step_title_text',
						'declaration' => sprintf(
							'top: %1$s;',
							$this->props['progress_bar_step_number_line_height']
						)
					));
                } else {
                    ET_Builder_Element::set_style($render_slug, array(
						'selector'    => '%%order_class%% .df_progressbar_step li',
						'declaration' => sprintf(
							'padding-top: %1$s;padding-bottom: 0;',
							$this->props['progress_bar_step_number_line_height']
						)
					));
                }

                ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .df_progressbar_step .df_progressbar_icon:after, %%order_class%% .df_progressbar_step .df_progressbar_number',
					'declaration' => sprintf(
						'line-height: %1$s;',
						$this->props['progress_bar_step_number_line_height']
					)
				));
	        }
        }

		$messages 			= array(
			'success'		=> array(
				'type'		=> $success_message_type,
				'text'		=> $success_message,
				'layout'	=> $success_layout
			),
			'failed'		=> array(
				'type'		=> $failed_message_type,
				'text'		=> $failed_message,
				'layout'	=> $failed_layout
			)
		);

		$action_url = '';

		if ( $form_type == 'custom' && $form_action_url != '' ) {
			$action_url = $form_action_url;
		}

		$fb_js_obj = array( 'ajax_url' => admin_url('admin-ajax.php') );

		if ( $disable_submit_for_required == 'on' ) {
			$fb_js_obj['disable_submit_for_required'] = true;
		}

		wp_localize_script( 'de_fb_js', 'de_fb_obj', $fb_js_obj );

		global $wp_query, $post, $wpdb;

		$de_fb_form_num = $this->render_count();

		$form_key = $post->ID . '-' . $de_fb_form_num;

		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => 'body .ui-datepicker.date_picker_'.$form_key.' table, body .ui-datepicker.date_picker_'.$form_key.' .ui-datepicker-title, body .date_picker_'.$form_key.' .ui-datepicker-next span::after, body .date_picker_'.$form_key.' .ui-datepicker-prev span::after, body .date_picker_'.$form_key.' .ui-state-default, body .ui-widget-content.date_picker_'.$form_key.' .ui-state-default',
			'declaration' => 'color: '.$date_text_color.' !important;'
		));

		
		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => 'body .date_picker_'.$form_key.' .ui-state-default, body .ui-widget-content.date_picker_'.$form_key.' .ui-state-default',
			'declaration' => 'background-color: '.$date_number_bg.' !important;
				border-radius: '.$date_number_border_radius.' !important;'
		));

		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => 'body .ui-widget-content.date_picker_'.$form_key.' .ui-state-default.ui-state-active, body .ui-widget-content.date_picker_'.$form_key.' .ui-state-default:hover',
			'declaration' => 'color: '.$date_text_active.' !important;
				background-color: '.$date_number_bg_active.' !important;'
		));

		ET_Builder_Element::set_style($render_slug, array(
			'selector'    => 'body .ui-widget-content.date_picker_'.$form_key.' .ui-state-highlight, body .ui-widget-content.date_picker_'.$form_key.' .ui-state-highlight:hover',
			'declaration' => 'color: '.$date_highlight_color.' !important;
				background-color: '.$date_highlight_bg.' !important;'
		));

		$de_fb_settings = get_option( 'de_fb_settings', array() );

		$unique_id = self::$_->array_get( $this->props, '_unique_id' );

		if ( $unique_id == "" ) {
			$unique_id = $form_key;
		}

		if ( !isset( $de_fb_settings[$unique_id] ) ) {
			$de_fb_settings[$unique_id] = array();
		}

		$de_fb_settings[$unique_id] = array_merge( $de_fb_settings[$unique_id], array(
			'google_recaptcha' 			=> $google_recaptcha,
			'recaptcha_sitekey_type' 	=> $recaptcha_sitekey_type,
			'recaptcha_seckey_v3' 		=> $recaptcha_seckey_v3,
			'recaptcha_score_v3' 		=> $recaptcha_score_v3,
			'form_title'				=> $form_title,
			'enable_assign_terms'		=> $enable_assign_terms,
			'save_to_database'			=> $save_to_database,
			'message_position'			=> $message_position,
			'messages'					=> $messages,
			'enable_submission_notification' => $this->props['enable_submission_notification'],
			'use_custom_email' 			=> $this->props['use_custom_email'],
			'acf_field_type' 			=> $this->props['acf_field_type'],
			'acf_email_field_linked' 	=> $this->props['acf_email_field_linked'],
			'acf_email_field'  			=> $this->props['acf_email_field'],
			'custom_contact_email'		=> $this->props['custom_contact_email'],
			'from_name_field'			=> $this->props['from_name_field'],
			'from_email_field'			=> $this->props['from_email_field'],
			'from_name'					=> $this->props['from_name'],
			'replyto_name'				=> $this->props['replyto_name'],
			'replyto_email'				=> $this->props['replyto_email'],
			'custom_from_name'			=> $this->props['custom_from_name'],
			'from_email'				=> $this->props['from_email'],
			'custom_from_email'			=> $this->props['custom_from_email'],
			'email_cc'					=> $this->props['email_cc'],
			'email_bcc'					=> $this->props['email_bcc'],
			'email_title'				=> $this->props['email_title'],
			'email_template_html'		=> $this->props['email_template_html'],
			'email_template'			=> $this->props['email_template'],
			'send_copy_to_sender'		=> $this->props['send_copy_to_sender'],
			'sender_setting'			=> $this->props['sender_setting'],
			'sender_name_field'			=> $this->props['sender_name_field'],
			'sender_email_field'		=> $this->props['sender_email_field'],
			'reply_from_name'			=> $this->props['reply_from_name'],
			'reply_custom_from_name'	=> $this->props['reply_custom_from_name'],
			'reply_from_email'			=> $this->props['reply_from_email'],
			'reply_custom_from_email'	=> $this->props['reply_custom_from_email'],
			'reply_to_name'				=> $this->props['reply_to_name'],
			'reply_to_email'			=> $this->props['reply_to_email'],
			'reply_email_title'			=> $this->props['reply_email_title'],
			'reply_email_template_html'	=> $this->props['reply_email_template_html'],
			'reply_email_template'		=> $this->props['reply_email_template'],
			'bloom_name_field'			=> $bloom_name_field_id,
			'bloom_lastname_field'		=> $bloom_lastname_field_id,
			'bloom_email_field'			=> $bloom_email_field_id,
			'auto_login'				=> isset( $this->props['auto_login'] )?$this->props['auto_login']:'off'
		) );

		// ADDING CSS CLASSES

		if ( !$multistep_enabled || $multistep_button_alignment == '' ) {
			$this->add_classname( 'align-button_' . $button_alignment );	
		}		
		$this->add_classname( 'align-module_' . $module_alignment );

		if ( isset( $_POST['divi-form-submit'] ) && $_POST['divi-form-submit'] == 'yes' ) {
			$sform_type = $_POST['form_type'];

			if ( $sform_type == 'contact' && $form_key == $_POST['form_key'] ) {

				$submit_result = get_query_var( 'df_submit_result');
				$submit_form_key = get_query_var( 'df_submit_formkey' );
				$submit_error = get_query_var( 'df_submit_message' );

				$form_settings = $de_fb_settings[$unique_id];

				if ( $submit_result != 'failed' ) {
					$wp_upload_dir = wp_upload_dir();
					$upload_dir = $wp_upload_dir['basedir'] . '/de_fb_uploads/';
					$upload_url = $wp_upload_dir['baseurl'] . '/de_fb_uploads/';

					if (!file_exists($wp_upload_dir['basedir'] . '/de_fb_uploads')) {
			            mkdir($wp_upload_dir['basedir'] . '/de_fb_uploads', 0777, true);
			        }

					$uploaded_files = array();

					$form_id = $_POST['form_id'];

					$post_array = $_POST;
					unset( $post_array['form_type'] );
					unset( $post_array['divi-form-submit'] );
					unset( $post_array['form_id']);
					unset( $post_array['sender_email_field'] );

					$field_title_array = $post_array['field_title'];
					$field_names_array = array_unique( array_merge( $post_array['field_name'], array_values( $form_settings['fields'] ) ) );
					$field_ids_array = array_unique( array_merge( $post_array['field_id'], array_keys( $form_settings['fields'] ) ) );

					$processed_post_array = array();
					if ( !empty( $post_array ) ) {
						foreach ( $post_array as $key => $p_value ) {
							if ( is_array($p_value) ) {
								foreach ($p_value as $p_key => $value) {
									$p_value[$p_key] = wp_kses_post( $value );
								}
							} else {
								$p_value = wp_kses_post( $p_value );
							}
							$processed_post_array[ str_replace("de_fb_", "", $key) ] = $p_value;
						}
					}

					$email = get_bloginfo('admin_email');

					$header = array( 'Content-Type: text/html; charset=UTF-8' );
					$reply_header = array( 'Content-Type: text/html; charset=UTF-8' );

					$from_text = 'From: ';
					$reply_from_text = $from_text;

					if ( $from_name == 'default' ) {
						$from_text .= get_bloginfo( 'name' );
					} elseif ($from_name == 'sender' ) {
						$field_key = array_search( "de_fb_" . $from_name_field, $field_ids_array );
						if ( $field_key === FALSE ) {
							$field_key = array_search( $from_name_field, $field_ids_array );
						}
						if ( $field_key !== FALSE ) {
							$from_text .= ' ' . $post_array[ $field_names_array[ $field_key ] ];
						} else {
							$from_text .= get_bloginfo( 'name' );
						}
					} else {
						$from_text .= $custom_from_name;
					}

					if ( $reply_from_name == 'default') {
						$reply_from_text .= get_bloginfo( 'name' );
					} else if ( $reply_from_name == 'custom' ) {
						$reply_from_text .= $reply_custom_from_name;
					}

					if ( $from_email == 'admin' ) {
						$from_text .= ' <' . get_bloginfo( 'admin_email' ) . '>';
					} elseif ( $from_email == 'sender' ) {
						$field_key = array_search( "de_fb_" . $from_email_field, $field_ids_array );
						if ( $field_key === FALSE ) {
							$field_key = array_search( $from_email_field, $field_ids_array );
						}
						if ( $field_key !== FALSE ) {
							$from_text .= ' <' . $post_array[ $field_names_array[ $field_key ] ] . '>';
						} else {
							$from_text .= ' <' . get_bloginfo( 'admin_email' ) . '>';
						}
					} else {
						$from_text .= ' <' . $custom_from_email . '>';
					}

					if ( $reply_from_email == 'admin' ) {
						$reply_from_text .= ' <' . get_bloginfo( 'admin_email' ) . '>';
					} else if ( $reply_from_email == 'custom' ) {
						$reply_from_text .= ' <' . $reply_custom_from_email . '>';
					}

					$header[] = $from_text;
					$reply_header[] = $reply_from_text;

					if ( $replyto_email != '' ) {
						$field_key = array_search( "de_fb_" . $replyto_email, $field_ids_array );
						if ( $field_key === FALSE ) {
							$field_key = array_search( $replyto_email, $field_ids_array );
						}
						if ( $field_key !== FALSE ) {
							if ( $replyto_name != '' ) {
								$field_name_key = array_search( "de_fb_" . $replyto_name, $field_ids_array );
								if ( $field_name_key === FALSE ) {
									$field_name_key = array_search( $replyto_name, $field_ids_array );
								}
								if ( $field_name_key !== FALSE ) {
									$header[] = 'Reply-To: ' . $post_array[ $field_names_array[ $field_name_key ] ] . ' <' . $post_array[ $field_names_array[ $field_key ] ] . '>';
								} else {
									$header[] = 'Reply-To: ' . $post_array[ $field_names_array[ $field_key ] ];
								}
							}
						}
					}

					if ( $email_cc != '' ) {
						$header[] = 'CC: ' . $email_cc;
					}

					if ( $email_bcc != '' ) {
						$header[] = 'BCC: ' . $email_bcc;
					}

					if ( $reply_to_email != '' ) {
						if ( $reply_to_name != '' ) {
							$reply_header[] = 'Reply-To: '. $reply_to_name . ' <' . $reply_to_email . '>';
						} else {
							$reply_header[] = 'Reply-To: ' . $reply_to_email;
						}
					}
					
					$mail_attachs = array();
					$reply_mail_attachs = array();

					if ( $use_custom_email == 'off' && !empty( $custom_contact_email ) ) {
						$email = $custom_contact_email;
					}

					$form_key = $_POST['form_key'];
					$form_key_arr = explode('-', $form_key);

					$post_id = $form_key_arr[0];

					$cur_post = get_post( $post_id );

					if ( $use_custom_email == 'post_author' ) {
						$author = $cur_post->post_author;
						$author_data = get_userdata( $author );
						$email = $author_data->user_email;
					}

					if ( $use_custom_email == 'acf' && !empty( $acf_email_field ) ) {
						if ( $acf_field_type == 'current' ) {
							$email = get_field( $acf_email_field, $post_id );	
						} else if ( $acf_field_type == 'linked' ) {
							$acf_object = get_field_object( $acf_email_field_linked );
							$linked_field_val = get_field( $acf_email_field_linked, $post_id );
							if ( $acf_object['type'] == 'user' ) {
								$user_object = get_user_by( 'id', $linked_field_val );
								$email = $user_object->user_email;
							} else if ( $acf_object['type'] == 'post_object' ) {
								$email = get_field( $acf_email_field, $linked_field_val );
							}
						}
					}

					$title = esc_html__( 'New Message Arrived', 'divi-form-builder' );

					if ( !empty( $email_title ) ) {
						$title = htmlspecialchars_decode($email_title);
					}

					$reply_title = esc_html__( 'We received your message', 'divi-form-builder' );
					if ( !empty( $reply_email_title ) ) {
						$reply_title = $reply_email_title;
					}

					unset( $post_array['field_title'] );
					unset( $post_array['custom_contact_email']);

					if ( !empty( $email_template ) ) {
						$body = html_entity_decode( $email_template );
					} else {
						$body = '<h2>' . $title . '</h2>';
					}

					if ( !empty( $reply_email_template ) ) {
						$reply_body = html_entity_decode( $reply_email_template );
					} else {
						$reply_body = '<h2>' . $reply_title . '</h2>';
					}

					$de_form_id = 0;

					if ( $save_to_database == 'on' ) {
						$tbl_form_name = $wpdb->prefix . 'de_contact_forms';

						if ( $unique_id != $form_key ) {
							$form_row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_form_name} WHERE form_no = %s", $unique_id ) );	
						} else {
							$form_row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_form_name} WHERE post_id = %d AND form_no = %s", $post->ID, $unique_id ) );

							if ( empty( $form_row ) ) {
								$form_row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_form_name} WHERE post_id = %d AND form_no = %s", $post->ID, $de_fb_form_num ) );	
							}							
						}
						

						$save_form_title = $form_title;
						if ( $save_form_title == "" ) {
							$save_form_title = 'Contact Form #' . $unique_id;
						}

						$de_form_arr = array(
							'post_id' 	=> $post->ID,
							'form_no' 	=> $unique_id,
							'form_name'	=> $save_form_title
						);

						if ( empty( $form_row ) ) {
							$wpdb->insert( $tbl_form_name, $de_form_arr );
							$de_form_id = $wpdb->insert_id;
						} else {
							$wpdb->update( $tbl_form_name, $de_form_arr, array( 'id' => $form_row->id ) );
							$de_form_id = $form_row->id;
						}
					}

					$entry_array = array();
					
					foreach ($field_names_array as $key => $field_name ) {
						$field_val = '';
						$field_id = $field_ids_array[$key];
						$field_key = $field_ids_array[$key];
						$field_id = str_replace( "de_fb_", "", $field_id );
						if ( !empty( $post_array[$field_name] ) ) {

							if ( isset($_POST['signature']) && in_array( $field_name, $_POST['signature'] ) ) {
								$field_val = '<img src="' . wp_get_attachment_image_url( $post_array[$field_name] ) . '" width="300" height="150" style="max-width:100%;height:auto;">';
							} else {
								if ( isset( $form_settings['file_fields'][$field_key] ) && $form_settings['file_fields'][$field_key] == $field_name ) {
									$field_val = "";
									$field_val_arr = explode( ",", $post_array[$field_name] );
									if ( !is_array( $field_val_arr) ) {
										$field_val_arr = array( $field_val_arr );
									}
									if ( stripos( $body, "%%{$field_id}%%") !== false || $email_template == "" ) {
										foreach ( $field_val_arr as $val ) {
											$mail_attachs[] = get_attached_file( $val );
										}											
									}
									if ( stripos( $reply_body, "%%{$field_id}%%") !== false || $reply_email_template == "" ) {
										foreach ( $field_val_arr as $val ) {
											$reply_mail_attachs[] = get_attached_file( $val );
										}											
									}
								} else if ( is_array( $post_array[$field_name] ) ) {
									$field_val = implode( ',', $post_array[$field_name] );	
								} else {
									$field_val = $post_array[$field_name];	
								}
							}

							if ( is_array( $post_array[$field_name] ) ) {
								if ( !empty( $email_template ) ) {
									$body = str_ireplace( "%%{$field_id}%%", wp_strip_all_tags( implode(',', $post_array[$field_name]) ), $body );
								} else {
									$body .= '<p><label>' . $field_title_array[$key] . ':</label>' . implode(',', $post_array[$field_name]) . '</p>';
								}

								if ( !empty( $reply_email_template ) ) {
									$reply_body = str_ireplace( "%%{$field_id}%%", wp_strip_all_tags( implode(',', $post_array[$field_name]) ), $reply_body );
								} else {
									$reply_body .= '<p><label>' . $field_title_array[$key] . ':</label>' . implode(',', $post_array[$field_name]) . '</p>';
								}

								$title = str_ireplace( "%%{$field_id}%%", implode(',', $post_array[$field_name]), $title);
								$reply_title = str_ireplace("%%{$field_id}%%", implode(',', $post_array[$field_name]), $reply_title);
							} else {
								if ( !empty( $email_template ) ) {
									$body = str_ireplace( "%%{$field_id}%%", $field_val, $body );
								} else {
									$body .= '<p><label>' . $field_title_array[$key] . ':</label>' . $field_val . '</p>';
								}

								if ( !empty( $reply_email_template ) ) {
									$reply_body = str_ireplace( "%%{$field_id}%%", $field_val, $reply_body );
								} else {
									$reply_body .= '<p><label>' . $field_title_array[$key] . ':</label>' . $field_val . '</p>';
								}

								$title = str_ireplace( "%%{$field_id}%%", $field_val, $title);
								$reply_title = str_ireplace("%%{$field_id}%%", $field_val, $reply_title);
							}
							
						} else if ( !empty( $uploaded_files[$field_name] ) ) {
							$file_name = basename( $uploaded_files[$field_name] );
							if ( !empty( $email_template ) ) {
								$body = str_ireplace( "%%{$field_id}%%", '<a href="' . $uploaded_files[$field_name] . '" target="_blank">' . $file_name. '</a>', $body );
							} else {
								$body .= '<p><label>' . $field_title_array[$key] . ':</label><a href="' . $uploaded_files[$field_name] . '" target="_blank">' . $file_name. '</a></p>';	
							}

							if ( !empty( $reply_email_template ) ) {
								$reply_body = str_ireplace( "%%{$field_id}%%", '<a href="' . $uploaded_files[$field_name] . '" target="_blank">' . $file_name. '</a>', $reply_body );
							} else {
								$reply_body .= '<p><label>' . $field_title_array[$key] . ':</label><a href="' . $uploaded_files[$field_name] . '" target="_blank">' . $file_name. '</a></p>';	
							}

							$field_val = '<a href="' . $uploaded_files[$field_name] . '" target="_blank">' . $file_name. '</a>';
						} else {
							if ( !empty( $email_template ) ) {
								$body = str_ireplace( "%%{$field_id}%%", '', $body );
							}
							if ( !empty( $reply_email_template ) ) {
								$reply_body = str_ireplace( "%%{$field_id}%%", '', $reply_body );
							}
						}

						if ( !empty( $post_array[$field_name] ) && isset( $form_settings['file_fields'][$field_key] ) && $form_settings['file_fields'][$field_key] == $field_name ) {
							$field_val = '';
							$field_val_arr = explode( ",", $post_array[$field_name] );
							if ( !is_array( $field_val_arr) ) {
								$field_val_arr = array( $field_val_arr );
							}

							foreach($field_val_arr as $val ) {
								$url = wp_get_attachment_url( $val );
								$field_title = get_the_title($val);
								if ( $field_val == '' ) {
									$field_val = '<a href="' . $url . '">' . $field_title . '</a>';
								} else {
									$field_val = $field_val . ',<a href="' . $url . '">' . $field_title . '</a>';
								}
							}
						}

						$entry_array[] = array(
							'field_title' => $field_title_array[$key],
							'field_val' => $field_val
						);
					}

					if ( $save_to_database == 'on' ) {
						$tbl_form_entry_name = $wpdb->prefix.'de_contact_form_entries';

						$de_form_entry_arr = array(
							'form_id' 		=> $de_form_id,
							'form_entry' 	=> serialize( $entry_array ),
							'insert_date'	=> date('Y-m-d H:i:s')
						);

						$wpdb->insert( $tbl_form_entry_name, $de_form_entry_arr );
					}

					if ( $email_template_html == 'on' ) {
						$body = str_replace( "\r\n", "", $body);
						$body = str_replace( "\n", "", $body);
					} else {
						$body = str_replace( "\r\n", "<br/>", $body);
						$body = str_replace( "\n", "<br/>", $body);
					}

					// $body = str_replace( "\n", "<br/>", $body);
					$body = stripslashes( $body );

					$body = apply_filters( 'df_contact_body', $body, $post_array );

					$title = preg_replace( sprintf( "/%s.*?%s/", preg_quote( '%%' ), preg_quote( '%%' ) ), '', $title);
					$title = stripslashes( $title );

					$email = apply_filters( 'df_contact_recipient', $email, $form_id, $processed_post_array );

					$mail_result = wp_mail( $email, $title, $body, $header, $mail_attachs );

					$submit_result = '';

					if ( $mail_result ) {
						$submit_result = 'success';
						set_query_var( 'df_submit_result', 'success' );
						if ( $send_copy_to_sender == 'on' ){
							if ( $sender_setting == 'sender' ) {
								$sender_name = isset($post_array[$form_settings['fields']['de_fb_' . $sender_name_field]])?$post_array[$form_settings['fields']['de_fb_' . $sender_name_field]]:(isset($post_array[$form_settings['fields'][$sender_name_field]])?$post_array[$form_settings['fields'][$sender_name_field]]:'');
								$sender_email = isset($post_array[$form_settings['fields']['de_fb_' . $sender_email_field]])?$post_array[$form_settings['fields']['de_fb_' . $sender_email_field]]:(isset($post_array[$form_settings['fields'][$sender_email_field]])?$post_array[$form_settings['fields'][$sender_email_field]]:'');
							} else if ( $sender_setting == 'login_user') {
								$sender_name = ($login_user->ID != 0)?$login_user->display_name:'';
								$sender_email = ($login_user->ID != 0)?$login_user->user_email:'';
							}

							if ( $sender_email != '' ) {

								if ( $reply_email_template_html  == 'on' ) {
									$reply_body = str_replace( "\r\n", "", $reply_body);
									$reply_body = str_replace( "\n", "", $reply_body);
								} else {
									$reply_body = str_replace( "\r\n", "<br/>", $reply_body);	
									$reply_body = str_replace( "\n", "<br/>", $reply_body);
								}

								// $reply_body = str_replace( "\n", "<br/>", $reply_body);
								$reply_body = stripslashes( $reply_body );

								$reply_body = apply_filters( 'df_confirmation_body', $reply_body, $post_array );
								$reply_title = preg_replace( "/" . preg_quote( '%%' ) . ".*?" . preg_quote( '%%' ) . "/", '', $reply_title);
								$reply_title = stripslashes( $reply_title );

								//$sender_email = apply_filters( 'df_confirmation_recipient', $sender_email, $processed_post_array );

								$send_mail_result = wp_mail( $sender_email, $reply_title, $reply_body, $reply_header, $reply_mail_attachs );

								if ( !$send_mail_result ) {
									set_query_var( 'df_submit_result', 'failed' );
									$submit_result = 'failed';
									$err_message = esc_html__( $this->email_error_message, 'divi-form-builder' );
									set_query_var( 'df_submit_message', $err_message );
								}
							} else {
								set_query_var( 'df_submit_result', 'failed' );
								$submit_result = 'failed';
								$err_message = esc_html__( 'Form was submitted successfully. There was a problem with the email confirmation, no sender email ID specified.', 'divi-form-builder' );
								set_query_var( 'df_submit_message', $err_message );	
							}
						}
					} else {
						set_query_var( 'df_submit_result', 'failed' );
						$submit_result = 'failed';
						$err_message = esc_html__( $this->email_error_message, 'divi-form-builder' );
						set_query_var( 'df_submit_message', $err_message );
					}
				} else {
					$err_message = esc_html__('There is an issue with Captcha.', 'divi-form-builder');
					set_query_var( 'df_submit_message', $err_message );
				}

				set_query_var( 'df_submit_formkey', $form_key );

				$redirect_url_after_submission = !empty( $_POST['redirect_url_after_submission'] )?$_POST['redirect_url_after_submission']:'';
				$redirect_url_after_failed = !empty( $_POST['redirect_url_after_failed'] )?$_POST['redirect_url_after_failed']:'';

				if ( $submit_result == 'success' && $redirect_url_after_submission != '' ) {
					do_action( 'df_before_redirect', $form_id, $submit_result, $redirect_url_after_submission );
				?>
				<script>
					document.location.href="<?php echo $redirect_url_after_submission;?>";
				</script>
				<?php
					exit;
				}

				if ( $submit_result == 'failed' && $redirect_url_after_failed != '' ) {
					do_action( 'df_before_redirect', $form_id, $submit_result, $redirect_url_after_failed );
				?>
				<script>
					document.location.href="<?php echo $redirect_url_after_failed;?>";
				</script>
				<?php
					exit;
				}

			}
		}

		$submit_result = get_query_var('df_submit_result');
		$submit_form_key = get_query_var( 'df_submit_formkey' );

		update_option( 'de_fb_settings', $de_fb_settings );
		
		// ADDING CUSTOM CSS

		if( $custom_button == 'on' ){
			if( $button_icon !== '' ){

				$button_icon_arr = explode('||', $button_icon);

				$button_icon_font_family = ( !empty( $button_icon_arr[1] ) && $button_icon_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
				$button_icon_font_weight = ( !empty( $button_icon_arr[2] ))?$button_icon_arr[2]:'400';

				$iconContent = DE_FormBuilder::et_icon_css_content( esc_attr($button_icon) );
				$iconSelector = '';
				if( $button_icon_placement == 'right' ){
					$iconSelector = '%%order_class%% .divi-form-submit.et_pb_button:after';
				} elseif ( $button_icon_placement == 'left' ) {
					$iconSelector = '%%order_class%% .divi-form-submit.et_pb_button:before';
				}
				if( !empty( $iconContent ) && !empty( $iconSelector ) ){
					ET_Builder_Element::set_style( $render_slug, array(
						'selector' => $iconSelector,
						'declaration' => "content: '{$iconContent}'!important;
						font-family:{$button_icon_font_family}!important;
						font-weight:{$button_icon_font_weight};
						display: inline-block;
						line-height: inherit;
						font-size: inherit!important;
						margin-left: .3em;
						left: auto;
						display: inline-block;"
						)
					);
				}
			}
			// fix the button padding if has no icon
			if( $button_use_icon == 'off' ){
				ET_Builder_Element::set_style( $render_slug, array(
					'selector' => '%%order_class%% .divi-form-submit.et_pb_button',
					'declaration' => "padding: 0.3em 1em!important"
					)
				);
			}
			// button background
			if( !empty( $button_bg_color ) ){
					ET_Builder_Element::set_style( $render_slug, array(
						'selector' => '%%order_class%% .divi-form-submit.et_pb_button',
						'declaration' => "background-color:". esc_attr( $button_bg_color ) ."!important;",
					) );
			}
			// button text
			if( !empty( $button_text_color ) ){
					ET_Builder_Element::set_style( $render_slug, array(
						'selector' => '%%order_class%% .divi-form-submit.et_pb_button',
						'declaration' => "color:". esc_attr( $button_text_color ) ."!important;",
					) );
			}
			// button text hover
			if( !empty( $button_text_color__hover ) ){
					ET_Builder_Element::set_style( $render_slug, array(
						'selector' => '%%order_class%% .divi-form-submit.et_pb_button',
						'declaration' => "color:". esc_attr( $button_text_color__hover ) ."!important;",
					) );
			}
		}

		

		ET_Builder_Element::set_style( $render_slug, array(
			'selector' => '%%order_class%% .select2-container--default .select2-selection--single .select2-selection__arrow b, %%order_class%% .et_pb_contact_field[data-type=select]:after',
			'declaration' => "border-top-color: " . $select_arrow_color . " !important;",
		) );

		if ($select2 == "on") {


			wp_enqueue_style('divi-form-builder-select2-css');
			wp_enqueue_script('divi-form-builder-select2-js');

			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => '%%order_class%% .select2-selection',
				'declaration' => "padding: 16px 20px 16px 16px !important;height: auto !important;",
			) );

			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => '%%order_class%% .select2-container--default .select2-selection--single .select2-selection__rendered',
				'declaration' => "line-height: inherit !important;",
			) );

			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => '%%order_class%% .select2-container--default .select2-selection--single .select2-selection__arrow',
				'declaration' => "height:100%;",
			) );

			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => '%%order_class%% .select2-container--default .select2-selection--single .select2-selection__arrow b',
				'declaration' => "border: 6px solid transparent;border-top-color: #666;right: 30px;left: auto;",
			) );

			

			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => '.select2-search--dropdown',
				'declaration' => "top: -10px;position: relative;",
			) );

			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => '.select2-container--open .select2-dropdown--below',
				'declaration' => "overflow:visible;",
			) );
			

			

		}

		ob_start();
		
		
		if ($select2 == "on") {
			?>
			<script>
			jQuery(document).ready(function ($) {
				$('#fb_form_<?php echo $form_key;?>').find('.select2').select2({width: '100%'});
			});
			</script>
			<?php
		}
		
		do_action( 'de_fb_before_form_render' );

		

		$pid = !empty($_REQUEST['pid'])?$_REQUEST['pid']:'';

		if ( !empty( $pid ) ) {

			if ( !in_array( $form_type, array( 'register', 'login', 'contact', 'custom' ) ) ) {

				$no_permission = false;
				if ( $edit_permission == 'author' ) {

					// if current logged in user can edit the post with the id $pid and is the author of the post with the id $pid || is administrator 
					if ( current_user_can( 'edit_post', $pid ) && ( get_current_user_id() == get_post_field( 'post_author', $pid ) || current_user_can( 'administrator' ) ) ) {
					} else {
						$no_permission = true;
					}
				} else if ( $edit_permission == 'role' ) {
					$user = wp_get_current_user();
					if ( !in_array( $edit_permission_role, (array) $user->roles ) ) {
						$no_permission = true;
					}
				}

				if ( $no_permission ) {
					echo wp_kses_post( $no_permission_notice );
					$result = ob_get_clean();
					return $result;
				}
			}
		}

		if ( $form_type == 'login' && is_user_logged_in() ) {
			// This part should be done by settings.
				
				$login_already_text = preg_replace( '/(<p>)?<!-- (\/)?divi:(.+?) (\/?)-->(<\/p>)?/', '<!-- $2divi:$3 $4-->', $login_already_text );
				
				// Convert GB embeds to iframes
				$login_already_text = preg_replace_callback(
					'/<!-- divi:core-embed\/youtube {"url":"([^"]+)"[\s\S]+?<!-- \/divi:core-embed\/youtube -->/',
						array( $this, 'convert_embeds' ),
						$login_already_text
				);
				?>
				
				<div class="user_logged_in">
					<?php echo $login_already_text; ?>
				</div>
				<?php
				
				$result = ob_get_clean();
				return $result;
		}

		if ( !empty($submit_result) && $message_position == 'before_title' && $submit_form_key == $form_key ) {
			$this->display_message( $submit_result, $messages );
		}

		if ( !empty( $submit_result ) && $scrollto_form_after_submit == 'on' && $submit_form_key == $form_key ) {

			
		// MERGE - not sure if it should be: "fb_form_" . $form_key instead of $form_type
			$scrollto_form_offset = intval( $scrollto_form_offset );
?>
		<script>
			jQuery(document).ready(function($){
				if ( $("#fb_form_<?php echo $form_key;?>").offset().top != 0) {
					$('html, body').animate({
	                    scrollTop: $("#fb_form_<?php echo $form_key;?>").offset().top + <?php echo $scrollto_form_offset;?>
	                }, 500);
				} else {
					setTimeout( function() {
						$('html, body').animate({
		                    scrollTop: $("#fb_form_<?php echo $form_key;?>").offset().top  + <?php echo $scrollto_form_offset;?>
		                }, 500);
					}, 800);
				}
			});
		</script>
<?php
		}

		$additional_classes = '';

		if ( $hide_until_loaded == 'on' && $use_preload_animation == 'on' ) {
?>
		<div class="form_loading <?php echo $preload_anim_style;?>">
			<div class="ajax-loading">
				<div class="divi-style">
				</div>
				<div class="lines">
					<div class="line"></div>
					<div class="line"></div>
					<div class="line"></div>
				</div>
				<div class="spinner donut-cont">
					<div class="donut"></div>
				</div>
				<div class="spinner donutmulti-cont">
					<div class="donut multi"></div>
				</div>
				<div class="spinner ripple-cont">
					<div class="ripple"></div>
				</div>
			</div>
		</div>
<?php			
		}

		if ( $multistep_enabled ){
			if ( $multistep_form_transition !== 'none' ) {
				$additional_classes = 'animation_' . $multistep_form_transition;
			}

			if ( $multistep_button_alignment != '' ) {
				$additional_classes = $additional_classes . ' button_align_' . $multistep_button_alignment;
			}
		}

		if ( $disable_submit_for_required == 'on' ) {
			$additional_classes .= ' disable_submit_for_required';
		}

		if ( $hide_until_loaded == 'on' ) {
			$additional_classes .= ' hide_until_loaded';
		}
		
?>
		<form
                method="POST"
                enctype="multipart/form-data"
                action="<?php echo $action_url;?>"
                id="fb_form_<?php echo $form_key;?>"
                class="et_pb_contact fb_form <?php echo $multistep_enabled?'multistep':'';?> <?php echo $additional_classes;?>"
                data-ajax-btn="<?php echo esc_attr($ajax_submit_button_text)?>"
                data-ajax-hide-sub="<?php echo esc_attr($success_hide_form)?>"
                data-reset-form-on-submit="<?php echo esc_attr($reset_form_on_submit)?>"
                style="<?php echo ( $hide_until_loaded == 'on')?'display: none;':'';?>">
			<h3 class="form-title"><?php echo $form_title;?></h3>
<?php
		if ( !empty($submit_result) && $message_position == 'after_title' && $submit_form_key == $form_key ) {
			$this->display_message( $submit_result, $messages );
		}

?>
			<?php if($multistep_enabled){ ?>
                    <?php if($multistep_progress_bar_style == "basic" || $multistep_progress_bar_style == "lollipop"){ ?>
                        <div class="df_progressbar_container">
                            <div class="df_progressbar df_progressbar_<?php echo $multistep_progress_bar_style;?>" data-style="<?php echo esc_attr($multistep_progress_bar_style)?>">
	                            <?php if($multistep_show_progress_bar_percentage){ ?>
                                    <div class="df_progressbar_percentage">0%</div>
	                            <?php } ?>
                                <div class="df_progressbar_active"></div>
                            </div>
                        </div>
                    <?php }elseif($multistep_progress_bar_style == "step"){ ?>
                            <div class="df_progressbar_container">
                                <ul class="df_progressbar df_progressbar_step" data-style="<?php echo esc_attr($multistep_progress_bar_style)?>">
                                    <li class="active">
                                    	<?php if($multistep_show_step_number_in_circle == 'on'){ ?>
                                            <div class="df_progressbar_number">1</div>
                                        <?php } ?>
                                        <?php 

                                        	if( $multistep_show_step_number_in_circle == 'off' && $multistep_show_step_icon_in_circle == 'on'){ 
                                        		$step_icon = isset($step_fields[0]['step_icon'])?$step_fields[0]['step_icon']:'N||divi||400';
                                        		$step_icon_arr = explode('||', $step_icon);
												$step_icon_font_family = ( !empty( $step_icon_arr[1] ) && $step_icon_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
												$step_icon_font_weight = ( !empty( $step_icon_arr[2] ))?$step_icon_arr[2]:'400';
												$step_icon_dis = preg_replace( '/(&amp;#x)|;/', '', et_pb_process_font_icon( $step_icon ) );
												$step_icon_dis = preg_replace( '/(&#x)|;/', '', $step_icon_dis );

												ET_Builder_Element::set_style($render_slug, array(
													'selector'    => '%%order_class%% .df_progressbar_icon_0::after',
													'declaration' => sprintf(
														'
														position: absolute;
														content:"\%1s";
														font-family:%2$s!important;
														font-weight:%3$s;
														',$step_icon_dis,
														$step_icon_font_family,
														$step_icon_font_weight
													),
												));
                                        ?>
                                            <div class="df_progressbar_icon df_progressbar_icon_0"></div>
                                        <?php } ?>
                                        <?php if($multistep_show_progress_bar_percentage){ ?>
                                            <div class="df_progressbar_percentage">0%</div>
                                        <?php } ?>
	                                    <?php if($multistep_show_progress_bar_step_title){ ?>
                                            <div class="df_step_title_text">
                                                <?php echo $step_fields[0]['field_title']; ?>
                                            </div>
	                                    <?php } ?>
                                    </li>
                                    <?php for($i=1; $i< count($step_fields); $i++){ ?>
                                        <li>
                                        	<?php 
                                        	if( $multistep_show_step_number_in_circle == 'off' && $multistep_show_step_icon_in_circle == 'on'){ 
                                        		$step_icon = isset($step_fields[$i]['step_icon'])?$step_fields[$i]['step_icon']:'N||divi||400';
                                        		$step_icon_arr = explode('||', $step_icon);
												$step_icon_font_family = ( !empty( $step_icon_arr[1] ) && $step_icon_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
												$step_icon_font_weight = ( !empty( $step_icon_arr[2] ))?$step_icon_arr[2]:'400';
												$step_icon_dis = preg_replace( '/(&amp;#x)|;/', '', et_pb_process_font_icon( $step_icon ) );
												$step_icon_dis = preg_replace( '/(&#x)|;/', '', $step_icon_dis );
												ET_Builder_Element::set_style($render_slug, array(
													'selector'    => '%%order_class%% .df_progressbar_icon_' . $i . '::after',
													'declaration' => sprintf(
														'
														position: absolute;
														content:"\%1s";
														font-family:%2$s!important;
														font-weight:%3$s;
														',$step_icon_dis,
														$step_icon_font_family,
														$step_icon_font_weight
													),
												));
                                        	?>
	                                            <div class="df_progressbar_icon df_progressbar_icon_<?php echo $i;?>"></div>
	                                        <?php } ?>
                                        	<?php if($multistep_show_step_number_in_circle == 'on'){ ?>
	                                            <div class="df_progressbar_number"><?php echo $i+1;?></div>
	                                        <?php } ?>
                                            <?php if($multistep_show_progress_bar_percentage){ ?>
                                                <div class="df_progressbar_percentage">
                                                    <?php
                                                    $current_percentage = round($i/(count($step_fields)-1) * 100);
                                                    echo esc_html("$current_percentage %");
                                                    ?>
                                                </div>
                                            <?php } ?>
                                            <?php if($multistep_show_progress_bar_step_title){ ?>
                                                <div class="df_step_title_text">
                                                    <?php echo $step_fields[$i]['field_title']; ?>
                                                </div>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                    <?php } ?>
            <?php } ?>
			<div class="divi-form-wrapper">
                <?php if($multistep_enabled){ ?>
                <?php } ?>
<?php		
		
		if ( !in_array( $form_type , array( 'register', 'login', 'contact', 'custom' ) ) && !empty( $default_post_status ) ) {
			echo '<input type="hidden" value="' . $default_post_status . '" name="post_status">';
		}

		if ( $form_type == 'register' && $is_user_edit == 'on' && 0 != get_current_user_id() ) {
			echo '<input type="hidden" name="ID" value="' . get_current_user_id() . '">';
		}

		if ( $form_type == 'register' && $is_user_edit == 'off' ) {
			echo '<input type="hidden" name="role" value="' . $default_user_role . '">';
		}

		$form_field_content = $this->get_form_field_content();

		echo $form_field_content;

?>
                 <?php if( $multistep_enabled && count($step_fields) > 0 ){ ?>
                    </div>
                <?php } ?>
			</div>
<?php
		if ( $redirect_after_success == 'on' && !empty($redirect_url_after_submission) ){
			echo '<input type="hidden" value="' . $redirect_url_after_submission . '" name="redirect_url_after_submission">';
		}

		if ( $redirect_after_failed == 'on' && !empty($redirect_url_after_failed) ){
			echo '<input type="hidden" value="' . $redirect_url_after_failed . '" name="redirect_url_after_failed">';
		}

		echo '<input type="hidden" value="' . $form_key . '" name="form_key">';
		echo '<input type="hidden" value="' . $unique_id . '" name="unique_id">';

		if ( $this->is_bloom_enabled && $enable_bloom_subscription == 'on' && $bloom_email_list != 'none' ) {
?>
				<div class="et_pb_column_4_4 et_pb_contact bloom_subscribe">
					<p>
					<input type="checkbox" id="bloom_subscribe_<?php echo $form_key;?>" name="bloom_subscribe" value="<?php echo $bloom_email_list;?>" <?php echo ($bloom_subscribe_chk == 'on')?'checked':'';?> <?php echo ($bloom_subscribe_chk_required == 'on')?'required':'';?>>
					<label for="bloom_subscribe_<?php echo $form_key;?>"><i></i><?php echo esc_html__($bloom_subscribe_text, 'divi-form-builder' );?></label>
					</p>
				</div>
<?php			
		}

		if ( !empty($submit_result) && $message_position == 'before_button' && $submit_form_key == $form_key ) {
			$this->display_message( $submit_result, $messages );
		}
?>
			<div class="et_contact_bottom_container">
				<div class="submit-container">
<?php

$sum = '';

		$data_sitekey = '';

		$submit_button_attr = '';

		if ( $google_recaptcha == 'on' ) {
			if ( $recaptcha_sitekey_type == 'recaptcha_2' ) {
				$data_sitekey = $recaptcha_sitekey;
?>
				<script src="https://www.google.com/recaptcha/api.js" async defer></script>
				<div class="g-recaptcha captcha-field" data-sitekey="<?php echo $recaptcha_sitekey;?>"></div>
				<script>
				jQuery(document).ready(function($){
					jQuery("#fb_form_<?php echo $form_key;?>").submit(function(e){
						$(this).find('.divi-form-submit').prop('disabled', true);
						var required_check = true;
						var form = jQuery(this);
                        let required_fields = $('.required',form);
                        if ( required_fields.length > 0 ) {
                            $.each(required_fields, function (index, element) {
                            	// If this is bloom subscribe checkbox
                            	$(this).closest('.et_pb_contact_field').parent().find('.error').remove();
                                if ( !$(this).closest('.de_fb_form_field').hasClass('condition-hide') ) {
                                    var field_type = $(this).closest('.et_pb_contact_field').data('type');

                                    if ( field_type == 'checkbox' || field_type == 'radio' ) {
                                        if ( jQuery(this).find('input:checked').length == 0 ) {
                                            required_check = false;
                                        }
                                    } else if ( field_type == 'file' || field_type == 'image' ) {
                                    	var value_field_id = jQuery(this).find('input.upload_field').attr('id') + '_value';
                                        if ( jQuery(this).find('.files .template-upload').length == 0 && jQuery(this).find('#' + value_field_id).val() == "") {
                                            required_check = false;
                                        }
                                    } else {
                                        if ( jQuery(this).val() == '' ) {
                                            required_check = false;
                                        }
                                    }
                                    if ( !required_check ) {
                                        var required_message = $(this).attr('data-required_message');
                                        var required_message_pos = $(this).attr('data-required_position');

                                        if ( required_message_pos == 'top' ) {
                                            $(this).closest('.et_pb_contact_field').before('<p class="error">' + required_message + '</p>');
                                        } else {
                                            $(this).closest('.et_pb_contact_field').after('<p class="error">' + required_message + '</p>');
                                        }
                                    }
                                }
                            });
                        }

                        if ( form.find('.bloom_subscribe').find('input[required]').length > 0 ) {
                    		form.find('.bloom_subscribe').find('.error').remove();
                    		if ( !form.find('.bloom_subscribe').find('input[required]').is(":checked") ) {
                    			required_check = false;
                    			var required_message = "<?php echo $bloom_required_message;?>";
                                var required_message_pos = "<?php echo $bloom_required_message_position;?>";

                                if ( required_message_pos == 'top' ) {
                                    form.find('.bloom_subscribe').prepend('<p class="error">' + required_message + '</p>');
                                } else {
                                    form.find('.bloom_subscribe').append('<p class="error">' + required_message + '</p>');
                                }
                    		}
                    	}

                    	if ( !required_check ) {
                            $('html, body').animate({
                                scrollTop: form.offset().top - 10
                            }, 300);

                            form.find('.divi-form-submit').removeProp('disabled');
                            form.find('.divi-form-submit').removeAttr('disabled');
                        }

					    var validation_result = form.valid();

					    if ( !validation_result ) {
					    	e.preventDefault();
                            //Here we check for an icon related to this input and we retrieve it to move it to correct place
                            //since validate function places the label before the icon -
                            let inputs_error= $('.input-field.error',form);
                            inputs_error.each(function (){
                                let input=$(this);
                                let parent=input.parent();
                                let label = $('label.error',parent).first();
                                let icon = $('.dfb_input_icon',parent).first();
                                if(icon.length){
                                    if(input.next().is(label)){
                                        label.detach().appendTo(parent.parent());
                                    }
                                }
                            });
					    	$(this).find('.divi-form-submit').removeProp('disabled');
				    		$(this).find('.divi-form-submit').removeAttr('disabled');
					    	return false;
					    }

				<?php if ( $form_type == 'register' ) { ?>
						if ( $('#fb_form_<?php echo $form_key;?> input[name="de_fb_user_pass"]').length > 0 && $('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').length > 0 ) {
                            var user_pass = $('#fb_form_<?php echo $form_key;?> input[name="de_fb_user_pass"]').val();
							var pass_confirm = $('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').val();

							if ( user_pass !== pass_confirm ) {
								$('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').closest('.et_pb_contact_field').parent().find('.error').remove();
								$('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').closest('.et_pb_contact_field').after('<p class="error">' + "<?php echo esc_html__($register_wrong_password_text, 'divi-form-builder');?>" + '</p>');
								$('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').focus();
								$(this).find('.divi-form-submit').removeProp('disabled');
				    			$(this).find('.divi-form-submit').removeAttr('disabled');
								return false;
							}
						}
				<?php } ?>
					    if ( $(this).find('.signature-field').length > 0 ) {
					    	$.each( $(this).find('.signature-field'), function(i) {
					    		var field_id = $(this).find('input').attr('id');
					    		$("#" + field_id).val( fb_signature.signature_objs['signaturePad_' + field_id].toDataURL() );
					    	});
					    }
					    if ( required_check ) {
					    	e.preventDefault();
					    	var response = grecaptcha.getResponse();
							if (response.length !== 0) {
								if ( form.find('.file_preview_container .template-upload').length > 0 ) {
									e.preventDefault();
				                	de_fb_ajax_files_upload( form );
				                } else {
				                	if ( form.find('.divi-form-submit').hasClass('de_fb_ajax_submit')) {
					                	de_fb_ajax_form_submit( form[0] );
					                	e.preventDefault();
					                } else {
					                	jQuery("#fb_form_<?php echo $form_key;?>").unbind('submit').submit();
					                }	
				                }
							}
					    } else {
					    	e.preventDefault();
					    }
					    $(this).find('.divi-form-submit').removeProp('disabled');
					    $(this).find('.divi-form-submit').removeAttr('disabled');
					});
				});
				</script>
<?php 
			} else if ( $recaptcha_sitekey_type == 'recaptcha_3' ) {
				$data_sitekey = $recaptcha_sitekey_v3;
?>
				<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $data_sitekey;?>"></script>
				<input type="hidden" id="recaptcha_token" name="recaptcha_token" value="">
				<script>
			jQuery(document).ready(function($){
					jQuery("#fb_form_<?php echo $form_key;?>").submit(function(e){
						e.preventDefault();
						$(this).find('.divi-form-submit').prop('disabled', true);
						var required_check = true;
						var form = jQuery(this);
                        let required_fields = $('.required',form);
                        if ( required_fields.length > 0 ) {
                            $.each(required_fields, function (index, element) {
                                $(this).closest('.et_pb_contact_field').parent().find('.error').remove();
                                if ( !$(this).closest('.de_fb_form_field').hasClass('condition-hide') ) {
                                    var field_type = $(this).closest('.et_pb_contact_field').data('type');

                                    if ( field_type == 'checkbox' || field_type == 'radio' ) {
                                        if ( jQuery(this).find('input:checked').length == 0 ) {
                                            required_check = false;
                                        }
                                    } else if ( field_type == 'file' || field_type == 'image' ) {
                                        var value_field_id = jQuery(this).find('input.upload_field').attr('id') + '_value';
                                        if ( jQuery(this).find('.files .template-upload').length == 0 && jQuery(this).find('#' + value_field_id).val() == "") {
                                            required_check = false;
                                        }
                                    } else {
                                        if ( jQuery(this).val() == '' ) {
                                            required_check = false;
                                        }
                                    }
                                    if ( !required_check ) {
                                        var required_message = $(this).attr('data-required_message');
                                        var required_message_pos = $(this).attr('data-required_position');

                                        if ( required_message_pos == 'top' ) {
                                            $(this).closest('.et_pb_contact_field').before('<p class="error">' + required_message + '</p>');
                                        } else {
                                            $(this).closest('.et_pb_contact_field').after('<p class="error">' + required_message + '</p>');
                                        }
                                    }
                                }
                            });
                        }

                        if ( form.find('.bloom_subscribe').find('input[required]').length > 0 ) {
                    		form.find('.bloom_subscribe').find('.error').remove();
                    		if ( !form.find('.bloom_subscribe').find('input[required]').is(":checked") ) {
                    			required_check = false;
                    			var required_message = "<?php echo $bloom_required_message;?>";
                                var required_message_pos = "<?php echo $bloom_required_message_position;?>";

                                if ( required_message_pos == 'top' ) {
                                    form.find('.bloom_subscribe').prepend('<p class="error">' + required_message + '</p>');
                                } else {
                                    form.find('.bloom_subscribe').append('<p class="error">' + required_message + '</p>');
                                }
                    		}
                    	}

                    	if ( !required_check ) {
                            $('html, body').animate({
                                scrollTop: form.offset().top - 10
                            }, 300);

                            form.find('.divi-form-submit').removeProp('disabled');
                            form.find('.divi-form-submit').removeAttr('disabled');
                        }

					    var validation_result = form.valid();

					    if ( !validation_result ) {
					    	e.preventDefault();
                            //Here we check for an icon related to this input and we retrieve it to move it to correct place
                            //since validate function places the label before the icon -
                            let inputs_error= $('.input-field.error',form);
                            inputs_error.each(function (){
                                let input=$(this);
                                let parent=input.parent();
                                let label = $('label.error',parent).first();
                                let icon = $('.dfb_input_icon',parent).first();
                                if(icon.length){
                                    if(input.next().is(label)){
                                        label.detach().appendTo(parent.parent());
                                    }
                                }
                            });
					    	$(this).find('.divi-form-submit').removeProp('disabled');
				    		$(this).find('.divi-form-submit').removeAttr('disabled');
					    	return false;
					    }

				<?php if ( $form_type == 'register' ) { ?>
						if ( $('#fb_form_<?php echo $form_key;?> input[name="de_fb_user_pass"]').length > 0 && $('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').length > 0 ) {
							var user_pass = $('#fb_form_<?php echo $form_key;?> input[name="de_fb_user_pass"]').val();
							var pass_confirm = $('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').val();
							if ( user_pass !== pass_confirm ) {
								$('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').closest('.et_pb_contact_field').parent().find('.error').remove();
								$('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').closest('.et_pb_contact_field').after('<p class="error">' + "<?php echo esc_html__($register_wrong_password_text, 'divi-form-builder');?>" + '</p>');
								$('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').focus();
								form.find('.divi-form-submit').removeProp('disabled');
				    			form.find('.divi-form-submit').removeAttr('disabled');
								return false;
							}
						}
				<?php } ?>

					    if ( $(this).find('.signature-field').length > 0 ) {
					    	$.each( $(this).find('.signature-field'), function(i) {
					    		var field_id = $(this).find('input').attr('id');
					    		$("#" + field_id).val( fb_signature.signature_objs['signaturePad_' + field_id].toDataURL() );
					    	});
					    }
					    if ( required_check ) {
					    	grecaptcha.ready(function() {
					            grecaptcha.execute('<?php echo $data_sitekey;?>', {action: 'submit'}).then(function(token) {
					                jQuery("#recaptcha_token").val(token);
					                if ( form.find('.file_preview_container .template-upload').length > 0 ) {
					                	e.preventDefault();
					                	de_fb_ajax_files_upload( form );
					                } else {
					                	if ( form.find('.divi-form-submit').hasClass('de_fb_ajax_submit')) {
						                	de_fb_ajax_form_submit( form[0] );
						                	e.preventDefault();
						                } else {
						                	jQuery("#fb_form_<?php echo $form_key;?>").unbind('submit').submit();
						                }	
					                }
					            });
					        });
					    } else {
					    	e.preventDefault();
					    }
					    $(this).find('.divi-form-submit').removeProp('disabled');
					    $(this).find('.divi-form-submit').removeAttr('disabled');
					});
				});
				</script>
<?php				
			}
		} else if ( $use_simple_captcha == 'on' ) {
			$first_digit = rand( 1, 15 );
			$second_digit = rand( 1, 15 );
			$sum = md5( $first_digit + $second_digit );
			echo '<div class="et_pb_column_4_4 maths_captcha captcha-field" style="display: inline;">
			<label>' . $first_digit . ' + ' . $second_digit . ' = </label>
			<input class="maths_answer" type="text" name="simple_captcha" required value=""/>
			</div>';
		}

		if ( $google_recaptcha != "on" ){
?>
			<script>
			jQuery(document).ready(function($){
				jQuery("#fb_form_<?php echo $form_key;?>").submit(function(e){
					e.preventDefault();
					$(this).find('.divi-form-submit').prop('disabled', true);
					var required_check = true;
					var form = jQuery(this);
                    let required_fields = $('.required',form);
                    if ( required_fields.length > 0 ) {
                        $.each(required_fields, function (index, element) {
                            $(this).closest('.et_pb_contact_field').parent().find('.error').remove();
                            if ( !$(this).closest('.de_fb_form_field').hasClass('condition-hide') ) {
                                var field_type = $(this).closest('.et_pb_contact_field').data('type');

                                if ( field_type == 'checkbox' || field_type == 'radio' ) {
                                    if ( jQuery(this).find('input:checked').length == 0 ) {
                                        required_check = false;
                                    }
                                } else if ( field_type == 'file' || field_type == 'image' ) {
                                    var value_field_id = jQuery(this).find('input.upload_field').attr('id') + '_value';
                                    if ( jQuery(this).find('.files .template-upload').length == 0 && jQuery(this).find('#' + value_field_id).val() == "") {
                                        required_check = false;
                                    }
                                } else {
                                    if ( jQuery(this).val() == '' ) {
                                        required_check = false;
                                    }
                                }
                                if ( !required_check ) {
                                    var required_message = $(this).attr('data-required_message');
                                    var required_message_pos = $(this).attr('data-required_position');

                                    if ( required_message_pos == 'top' ) {
                                        $(this).closest('.et_pb_contact_field').before('<p class="error">' + required_message + '</p>');
                                    } else {
                                        $(this).closest('.et_pb_contact_field').after('<p class="error">' + required_message + '</p>');
                                    }
                                }
                            }
                        });
                    }

                    if ( form.find('.bloom_subscribe').find('input[required]').length > 0 ) {
                		form.find('.bloom_subscribe').find('.error').remove();
                		if ( !form.find('.bloom_subscribe').find('input[required]').is(":checked") ) {
                			required_check = false;
                			var required_message = "<?php echo $bloom_required_message;?>";
                            var required_message_pos = "<?php echo $bloom_required_message_position;?>";

                            if ( required_message_pos == 'top' ) {
                                form.find('.bloom_subscribe').prepend('<p class="error">' + required_message + '</p>');
                            } else {
                                form.find('.bloom_subscribe').append('<p class="error">' + required_message + '</p>');
                            }
                		}
                	}

                	if ( !required_check ) {
                        $('html, body').animate({
                            scrollTop: form.offset().top - 10
                        }, 300);

                        form.find('.divi-form-submit').removeProp('disabled');
                        form.find('.divi-form-submit').removeAttr('disabled');
                    }

				    var validation_result = form.valid();

				    if ( !validation_result ) {
				    	e.preventDefault();
                        //Here we check for an icon related to this input and we retrieve it to move it to correct place
                        //since validate function places the label before the icon -
                        let inputs_error= $('.input-field.error',form);
                        inputs_error.each(function (){
                            let input=$(this);
                            let parent=input.parent();
                            let label = $('label.error',parent).first();
                            let icon = $('.dfb_input_icon',parent).first();
                            if(icon.length){
                                if(input.next().is(label)){
                                    label.detach().appendTo(parent.parent());
                                }
                            }
                        });
				    	$(this).find('.divi-form-submit').removeProp('disabled');
				    	$(this).find('.divi-form-submit').removeAttr('disabled');
				    	return false;
				    }

			<?php if ( $form_type == 'register' ) { ?>
					if ( $('#fb_form_<?php echo $form_key;?> input[name="de_fb_user_pass"]').length > 0 && $('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').length > 0 ) {
						var user_pass = $('#fb_form_<?php echo $form_key;?> input[name="de_fb_user_pass"]').val();
						var pass_confirm = $('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').val();
						if ( user_pass !== pass_confirm ) {
							$('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').closest('.et_pb_contact_field').parent().find('.error').remove();
							$('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').closest('.et_pb_contact_field').after('<p class="error">' + "<?php echo esc_html__($register_wrong_password_text, 'divi-form-builder');?>" + '</p>');
							$('#fb_form_<?php echo $form_key;?> input[name="de_fb_pass_repeat"]').focus();
							form.find('.divi-form-submit').removeProp('disabled');
				    		form.find('.divi-form-submit').removeAttr('disabled');
							return false;
						}
					}
			<?php } ?>
				    if ( $(this).find('.signature-field').length > 0 ) {
				    	$.each( $(this).find('.signature-field'), function(i) {
				    		var field_id = $(this).find('input').attr('id');
				    		$("#" + field_id).val( fb_signature.signature_objs['signaturePad_' + field_id].toDataURL() );
				    	});
				    }
				    if ( required_check ) {
				    	if ( form.find('.file_preview_container .template-upload').length > 0 ) {
				    		e.preventDefault();
		                	de_fb_ajax_files_upload( form );
		                } else {
		                	if ( form.find('.divi-form-submit').hasClass('de_fb_ajax_submit')) {
			                	de_fb_ajax_form_submit( form[0] );
			                	e.preventDefault();
			                } else {
			                	jQuery("#fb_form_<?php echo $form_key;?>").unbind('submit').submit();
			                }	
		                }
				    } else {
				    	e.preventDefault();
				    }
				    $(this).find('.divi-form-submit').removeProp('disabled');
				    $(this).find('.divi-form-submit').removeAttr('disabled');
				});
			});
			</script>
<?php			
		}

		$pid = !empty($_REQUEST['pid'])?$_REQUEST['pid']:'';

		if ( !empty( $pid ) ) {

			$post_object = get_post( $pid );

			if ( ($post_object instanceof WP_post) && $post_object->post_type == $form_type) {
				echo '<input type="hidden" name="ID" value="' . $pid . '"/>';
			}
			
		}

		$submit_class="";
		if ( $is_ajax_submit == 'on' ) {
			$submit_class = "de_fb_ajax_submit";
		}
?>

                    <button class="divi-form-submit et_pb_button <?php echo $submit_class;?>" type="<?php echo ($is_ajax_submit == 'on')?'submit':'submit';?>"><?php echo $submit_button_text;?></button>

                <input type="hidden" name="form_type" value="<?php echo $form_type;?>">
				<input type="hidden" name="divi-form-submit" value="yes">
				<input type="hidden" name="form_id" value="<?php echo $form_id;?>">
<?php
			if ( $use_honeypot_captcha == 'on' ) {
				echo '<input type="text" name="form_type_confirm" style="display:none!important;" tabindex="-1" autocomplete="off">';
			}
			if ( $google_recaptcha != 'on' && $use_simple_captcha == 'on' ) {
				echo '<input type="hidden" name="s_nonce" value="' . $sum . '" />';
			}
?>
				</div>
			</div>
		</form>
<?php 
		if ( !empty($submit_result) && $message_position == 'after_button' && $submit_form_key == $form_key ) {
			$this->display_message( $submit_result, $messages );
		}

		if ( $success_hide_form == 'on' ) {
?>
<script>
	jQuery(document).ready(function($){
		if ($('.message_success').length > 0) {
            $('#fb_form_<?php echo $form_key;?> .form-title').addClass('hidethis');
			$('#fb_form_<?php echo $form_key;?> .df_progressbar_container').addClass('hidethis');
			$('#fb_form_<?php echo $form_key;?> .divi-form-wrapper').addClass('hidethis');
			$('#fb_form_<?php echo $form_key;?> .divi-form-submit').addClass('hidethis');
			$('#fb_form_<?php echo $form_key;?> .bloom_subscribe').addClass('hidethis');
		}
	});
</script>
<?php 	} ?>

<script>
	jQuery(document).ready(function($){
		$('#fb_form_<?php echo $form_key;?>').validate({
		  normalizer: function(value) {
		    // Trim the value of every element
		    return $.trim(value);
		  },
		  errorPlacement : function( error, element ) {
			element.parent().append( error ); // default error placement
		  }
		});

		if ( $('.de_fb_autocomplete').length > 0 && typeof init_autocomplete_fields == 'function' ) {
			init_autocomplete_fields();
		}
	});
	
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}
</script>
<?php		
		$result = ob_get_clean();

		return $result;
	}

	
}

new DE_FB_Form;
