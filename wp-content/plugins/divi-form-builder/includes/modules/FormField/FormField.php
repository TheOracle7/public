<?php

class DE_FB_FormField extends ET_Builder_Module {

	public $slug       = 'de_fb_form_field';
	public $vb_support = 'on';

	public $folder_name = '';
	public $advanced_setting_title_text = '';
	public $settings_text = '';
    public $text_shadow = '';
    public $margin_padding = '';
    public $_additional_fields_options = array();
    public $_original_content = '';

	protected $module_credits = array(
		'module_uri' => 'https://diviengine.com',
		'author'     => 'Divi Engine',
		'author_uri' => 'https://diviengine.com',
	);

	public function init() {
		$this->name = esc_html__( 'Form Field - Divi Form Builder', 'divi-form-builder' );
        $this->folder_name = 'divi_form_builder';
		$this->type                        = 'child';
		$this->child_title_var             = 'admin_title';
		$this->child_title_fallback_var    = 'field_title';
		$this->advanced_setting_title_text = esc_html__( 'New Field', 'divi-form-builder' );
		$this->settings_text               = esc_html__( 'Field Settings', 'divi-form-builder' );
        $this->folder_name = 'divi_form_builder';

		$this->settings_modal_toggles = array(
            'general' => array(
                'toggles' => array(
                	'main_content'      	=> esc_html__( 'Main Options', 'divi-form-builder' ),
                    'field_options'      	=> esc_html__( 'Field Options', 'divi-form-builder' ),
                    'layout_options'    	=> esc_html__( 'Layout Options', 'divi-form-builder' ),
                    'field_mapping'    		=> esc_html__( 'Mapping Options', 'divi-form-builder' ),
                    'conditional_logic' 	=> esc_html__( 'Conditional Logic', 'divi-form-builder' ),
                    'date_time_app'    		=> esc_html__( 'Date/Time Appearance', 'divi-form-builder' ),
                    'signature_option'		=> esc_html__( 'Signature Options', 'divi-form-builder'),
                    'radio_checkbox_image'		=> esc_html__( 'Radio/Checkbox Image Options', 'divi-form-builder')
                    
                ),
            ),
			'advanced' => array(
				'toggles' => array(
					'file_image_upload'		=> array(
						'title' => esc_html__( 'Upload Elements', 'divi-form-builder'),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'upload_icon_toggle'     => array(
								'name' => esc_html__( 'Icon', 'divi-form-builder')
							),
							'upload_desc_toggle'     => array(
								'name' => esc_html__( 'Description', 'divi-form-builder')
							),
							'upload_preview_toggle'     => array(
								'name' => esc_html__( 'Preview', 'divi-form-builder')
							),
							'upload_preview_progressbar'     => array(
								'name' => esc_html__( 'Progress Bar', 'divi-form-builder')
							),
							'upload_edit_preview'     => array(
								'name' => esc_html__( 'Edit', 'divi-form-builder')
							)
						)
					),
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
				)
			)
		);

		$this->main_css_element = '%%order_class%%';

		$this->advanced_fields = array(
			'borders'        => array(
				'default' => array(
					'css'          => array(
						'main'      => array(
							'border_radii'  => "{$this->main_css_element} .signature-field canvas, {$this->main_css_element} .select2-container--default .select2-search--dropdown .select2-search__field, {$this->main_css_element} .select2-dropdown, {$this->main_css_element} .select2-container--default .select2-selection--single,{$this->main_css_element} p.et_pb_contact_field textarea,{$this->main_css_element} p.et_pb_contact_field select,{$this->main_css_element} p.et_pb_contact_field input,{$this->main_css_element} .dropzone",
							'border_styles' => "{$this->main_css_element} .signature-field canvas, {$this->main_css_element} .select2-container--default .select2-search--dropdown .select2-search__field, {$this->main_css_element} .select2-dropdown, {$this->main_css_element} .select2-container--default .select2-selection--single,{$this->main_css_element} p.et_pb_contact_field textarea,{$this->main_css_element} p.et_pb_contact_field select,{$this->main_css_element} p.et_pb_contact_field input,{$this->main_css_element} .dropzone",
						),
						'important' => 'plugin_only',
					),
					'label_prefix' => esc_html__( 'Inputs', 'et_builder' ),
				),
				'upload_preview' => array(
					'css'          => array(
						'main'      => array(
							'border_radii'  => "{$this->main_css_element} .file_upload_item",
							'border_styles' => "{$this->main_css_element} .file_upload_item",
						),
						'important' => 'plugin_only',
					),
					'label_prefix' => esc_html__( 'Upload Preview Container', 'et_builder' ),
				),
				'upload_preview_image' => array(
					'css'          => array(
						'main'      => array(
							'border_radii'  => "{$this->main_css_element} .preview canvas",
							'border_styles' => "{$this->main_css_element} .preview canvas",
						),
						'important' => 'plugin_only',
					),
					'label_prefix' => esc_html__( 'Upload Preview Image', 'et_builder' ),
				),
				'progress_bar' => array(
					'css'          => array(
						'main'      => array(
							'border_radii'  => "{$this->main_css_element} .progress",
							'border_styles' => "{$this->main_css_element} .progress",
						),
						'important' => 'plugin_only',
					),
					'label_prefix' => esc_html__( 'Upload Progress Bar', 'et_builder' ),
				),
				'radio_image'   => array(
					'css'             => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .radio_image_cont label .label_wrapper, {$this->main_css_element}.radio_image_cont label .label_wrapper",
							'border_styles' => "{$this->main_css_element} .radio_image_cont label .label_wrapper, {$this->main_css_element}.radio_image_cont label .label_wrapper",
						),
					),
					'label_prefix'    => et_builder_i18n( 'Radio/Checkbox Image' ),
				),
				'radio_active_image'   => array(
					'css'             => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .radio_image_cont input:checked + label .label_wrapper, {$this->main_css_element}.radio_image_cont input:checked + label .label_wrapper",
							'border_styles' => "{$this->main_css_element} .radio_image_cont input:checked + label .label_wrapper, {$this->main_css_element}.radio_image_cont input:checked + label .label_wrapper",
						),
					),
					'label_prefix'    => et_builder_i18n( 'Radio/Checkbox Active Image' ),
				),
			),
			'fonts'          => array(
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
				'radio_active_text' => array(
					'label'    => esc_html__( 'Radio/Checkbox Active', 'divi-form-builder' ),
					'css'      => array(
							'main' => "%%order_class%%.radio_image_cont input:checked+label .label_wrapper, %%order_class%%.radio_image_cont input:checked+label .label_wrapper span",
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
				'required_error_notice' => array(
					'label'    => esc_html__( 'Required Error Notice', 'divi-form-builder' ),
					'css'      => array(
						'main' => "%%order_class%% .error",
						'important' => 'plugin_only',
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
			),
			'box_shadow'     => array(
				'default' => array(
					'css' => array(
						'main' => implode(
							', ',
							array(
								'%%order_class%% .signature-field canvas',
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
								'%%order_class%% .radio_image_cont label .label_wrapper',
								'%%order_class%%.radio_image_cont label .label_wrapper',
							)
						),
					),
				),
				'radio_active_image'   => array(
					'css'             => array(
						'main' => implode(
							', ',
							array(
								'%%order_class%% .radio_image_cont input:checked + label .label_wrapper',
								'%%order_class%%.radio_image_cont input:checked + label .label_wrapper'
							)
						),
					),
					'label'    => esc_html__( 'Radio/Checkbox Active Image' ),
				),
			),
			'button' => array(
				'radio_checkbox_button' => array(
					'label' => esc_html__( 'Radio/Checkbox Button', 'divi-form-builder' ),
					'css' => array(
						'main' => "{$this->main_css_element}.dfb_radio_button .et_pb_contact_field_checkbox label",
						'important' => 'all',
					),
					'box_shadow'  => array(
						'css' => array(
							'main' => "{$this->main_css_element}.dfb_radio_button .et_pb_contact_field_checkbox label",
									'important' => 'all',
						),
					),
					'margin_padding' => array(
						'css'           => array(
							'main' => "{$this->main_css_element}.dfb_radio_button .et_pb_contact_field_checkbox label",
							'important' => 'all',
						),
					),
					'use_alignment' => false,
				),
				'radio_checkbox_button_active' => array(
					'label' => esc_html__( 'Active Radio/Checkbox Button', 'divi-form-builder' ),
					'css' => array(
						'main' => "{$this->main_css_element}.dfb_radio_button .et_pb_contact_field_checkbox input:checked + label",
						'important' => 'all',
					),
					'box_shadow'  => array(
						'css' => array(
							'main' => "{$this->main_css_element}.dfb_radio_button .et_pb_contact_field_checkbox input:checked + label",
									'important' => 'all',
						),
					),
					'margin_padding' => array(
						'css'           => array(
							'main' => "{$this->main_css_element}.dfb_radio_button .et_pb_contact_field_checkbox input:checked + label",
							'important' => 'all',
						),
					),
					'use_alignment' => false,
				),
				'edit_preview_button' => array(
					'label' => esc_html__( 'Image Preview Edit Button', 'divi-form-builder' ),
					'css' => array(
						'main' => "{$this->main_css_element} .et_pb_button.edit-image,{$this->main_css_element} .et_pb_button.close-edit-image",
						'important' => 'all',
					),
					'box_shadow'  => array(
						'css' => array(
							'main' => "{$this->main_css_element} .et_pb_button.edit-image, {$this->main_css_element} .et_pb_button.close-edit-image",
									'important' => 'all',
						),
					),
					'margin_padding' => array(
						'css'           => array(
							'main' => "{$this->main_css_element} .et_pb_button.edit-image, {$this->main_css_element} .et_pb_button.close-edit-image",
							'important' => 'all',
						),
					),
					'use_alignment' => false,
				),
			),
			'margin_padding' => array(
				'default' => array(
					'css' => array(
						'main'    => '%%order_class%%',
						'padding' => '%%order_class%%',
						'margin'  => '%%order_class%%',
					),
				),
			),
			'max_width'      => array(
				'css' => array(
					'module_alignment' => '%%order_class%%.et_pb_contact_form_container.et_pb_module',
				),
			),
			'text'           => array(
				'css' => array(
					'text_orientation' => '%%order_class%% input, .et_pb_contact %%order_class%%  p textarea, .js .et_pb_contact %%order_class%%  .tmce-active textarea.wp-editor-area, %%order_class%% label',
					'text_shadow'      => '%%order_class%%, %%order_class%% input, .et_pb_contact %%order_class%%  p textarea, .js .et_pb_contact %%order_class%%  .tmce-active textarea.wp-editor-area, %%order_class%% label, %%order_class%% select',
				),
			),
			'form_field'     => array(
				'form_field' => array(
					'label'          => esc_html__( 'Fields', 'et_builder' ),
					'css'            => array(
						'main'                         => '.de_fb_form %%order_class%% .select2-container, .de_fb_form %%order_class%% input, .de_fb_form %%order_class%% .divi-form-builder-field',
						'background_color'             => '.de_fb_form %%order_class%% .select2-selection, .de_fb_form %%order_class%% .divi-form-builder-field, .de_fb_form %%order_class%% p input, .et_pb_contact .de_fb_form %%order_class%%  p textarea, .js .et_pb_contact .de_fb_form %%order_class%%  .tmce-active textarea.wp-editor-area,  .de_fb_form %%order_class%% p input[type="checkbox"] + label i, .de_fb_form %%order_class%% p input[type="radio"] + label i',
						'background_color_hover'       => '.de_fb_form %%order_class%% .select2-selection:hover, .de_fb_form %%order_class%% .divi-form-builder-field:hover, .de_fb_form %%order_class%% p input:hover, .et_pb_contact .de_fb_form %%order_class%%  p textarea:hover, .js .et_pb_contact .de_fb_form %%order_class%%  .tmce-active textarea.wp-editor-area:hover, .de_fb_form %%order_class%% p input[type="checkbox"]:hover + label i, .de_fb_form %%order_class%% p input[type="radio"]:hover + label i',
						'focus_background_color'       => '.de_fb_form %%order_class%% .select2-selection:focus, .de_fb_form %%order_class%% .divi-form-builder-field:focus, .de_fb_form %%order_class%% p input:focus, .et_pb_contact .de_fb_form %%order_class%%  p textarea:focus,.js .et_pb_contact .de_fb_form %%order_class%%  .tmce-active textarea.wp-editor-area:focus, .de_fb_form %%order_class%% p input[type="checkbox"]:active + label i, .de_fb_form %%order_class%% p input[type="radio"]:active + label i',
						'focus_background_color_hover' => '.de_fb_form %%order_class%% .select2-selection:focus:hover, .de_fb_form %%order_class%% .divi-form-builder-field:focus:hover, .de_fb_form %%order_class%% p input:focus:hover, .et_pb_contact .de_fb_form %%order_class%%  p textarea:focus:hover,.js .et_pb_contact .de_fb_form %%order_class%%  .tmce-active textarea.wp-editor-area:focus:hover, .de_fb_form %%order_class%% p input[type="checkbox"]:active:hover + label i, .de_fb_form %%order_class%% p input[type="radio"]:active:hover + label i',
						'form_text_color'              => '.de_fb_form %%order_class%% .select2-selection__rendered, .de_fb_form %%order_class%% .divi-form-builder-field, .de_fb_form %%order_class%% p input, .et_pb_contact .de_fb_form %%order_class%%  p textarea,.js .et_pb_contact .de_fb_form %%order_class%%  .tmce-active textarea.wp-editor-area, .de_fb_form %%order_class%% p input[type="checkbox"] + label, .de_fb_form %%order_class%% p input[type="radio"] + label, .de_fb_form %%order_class%% p input[type="checkbox"]:checked + label i:before',
						'form_text_color_hover'        => '.de_fb_form %%order_class%% .select2-selection__rendered:hover, .de_fb_form %%order_class%% .divi-form-builder-field:hover, .de_fb_form %%order_class%% p input:hover, .et_pb_contact .de_fb_form %%order_class%%  p textarea:hover,.js .et_pb_contact .de_fb_form %%order_class%%  .tmce-active textarea.wp-editor-area:hover, .de_fb_form %%order_class%% p input[type="checkbox"]:hover + label, .de_fb_form %%order_class%% p input[type="radio"]:hover + label, .de_fb_form %%order_class%% p input[type="checkbox"]:checked:hover + label i:before',
						'focus_text_color'             => '.de_fb_form %%order_class%% .select2-selection__rendered:focus, .de_fb_form %%order_class%% p input:focus, .et_pb_contact .de_fb_form %%order_class%%  p textarea:focus,.js .et_pb_contact .de_fb_form %%order_class%%  .tmce-active textarea.wp-editor-area:focus',
						'focus_text_color_hover'       => '.de_fb_form %%order_class%% .select2-selection__rendered:focus:hover, .de_fb_form %%order_class%% p input:focus:hover, .et_pb_contact .de_fb_form %%order_class%%  p textarea:focus:hover,.js .et_pb_contact .de_fb_form %%order_class%%  .tmce-active textarea.wp-editor-area:focus:hover',
						'padding'                      => '.de_fb_form %%order_class%% .select2-container, .de_fb_form %%order_class%% .divi-form-builder-field, .de_fb_form %%order_class%% .et_pb_contact_field input, .et_pb_contact .de_fb_form %%order_class%%  p textarea, .js .et_pb_contact .de_fb_form %%order_class%%  .tmce-active textarea.wp-editor-area',
						'margin'                       => '.de_fb_form %%order_class%% .select2-container, .de_fb_form %%order_class%% .divi-form-builder-field, .de_fb_form %%order_class%% .et_pb_contact_field',
					),
					'box_shadow'     => false,
					'border_styles'  => false,
					'font_field'     => array(
						'css' => array(
							'main'  => implode(
								', ',
								array(
									"{$this->main_css_element} .select2-selection__rendered",
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
								"{$this->main_css_element} .select2-selection__rendered:hover",
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
				),
			),
		);
	}

	public function get_acf_fields(  ){

		$acf_fields = array();

        if ( empty( $acf_fields ) ) {
			$acf_fields = array(
				"none" => esc_html__('Please select an ACF field', 'divi-machine')
			);

			if ( function_exists( 'acf_get_field_groups' ) ) {
				$field_groups = acf_get_field_groups();
				foreach ( $field_groups as $group ) {
					// DO NOT USE here: $fields = acf_get_fields($group['key']);
					// because it causes repeater field bugs and returns "trashed" fields
					$acf_fields[ $group['title'] ] = array();
					$fields = get_posts(array(
						'posts_per_page'   => -1,
						'post_type'        => 'acf-field',
						'orderby'          => 'title',
						'order'            => 'ASC',
						'suppress_filters' => true, // DO NOT allow WPML to modify the query
						'post_parent'      => $group['ID'],
						'post_status'       => 'publish',
						'update_post_meta_cache' => false
					));

					foreach ( $fields as $field ) {

						$acf_fields[ $group['title'] ][$field->post_name] = $field->post_title;

					}

				}
			}

			$fields_all = get_posts(array(
				'posts_per_page'   => -1,
				'post_type'        => 'acf-field',
				'orderby'          => 'name',
				'order'            => 'ASC',
				'post_status'       => 'publish',
			));

			if ( !empty( $fields_all ) ) {
				foreach ( $fields_all as $field ) {

					$post_parent = $field->post_parent;
					if ( $post_parent ) {
						$post_parent_obj = get_post( $post_parent );
						if ( $post_parent_obj ) {
							$post_parent_name = $post_parent_obj->post_title;
							$grandparent = wp_get_post_parent_id($post_parent);
							if ( $grandparent ) {
								$grandparent_obj = get_post( $grandparent );
								if ( ! empty( $grandparent_obj ) ) {
									$grandparent_name = $grandparent_obj->post_title;
									if ( isset( $acf_fields[ $grandparent_name ] ) && isset( $acf_fields[ $grandparent_name ][ $post_parent_obj->post_name ] ) ) {
										//unset( $acf_fields[$grandparent_name][$post_parent_obj->post_name] );
									}

									$acf_fields[ $grandparent_name ][ $field->post_name ] = $post_parent_name . ' - ' . $field->post_title;
								}
							}
						}
					}
				}
			}
		}

		foreach( $acf_fields as $key => $value ){
			if ( is_array( $value ) ) {
				asort( $acf_fields[$key] );	
			}			
		}

		return $acf_fields;
    }

	public function get_fields() {


		$divi_library_options = DE_FormBuilder::get_divi_layouts();

		$acf_fields = $this->get_acf_fields();

		// count 69
		$languages_list = array(
		    "af" => "Afrikaans",
		    "sq" => "shqip",
		    "ar" => "العربية",
		    "hy" => "հայերեն",
		    "az" => "azərbaycan dili",
		    "eu" => "euskara",
		    "be" => "беларуская",
		    "bs" => "bosanski",
		    "bg" => "български",
		    "ca" => "català",
		    "zh-HK" => "中文（香港）",
		    "zh-CN" => "中文（简体）",
		    "zh-TW" => "中文（繁體）",
		    "hr" => "hrvatski",
		    "cs" => "čeština",
		    "da" => "dansk",
		    "nl" => "Nederlands",
		    "en-AU" => "English (Australia)",
		    "en-NZ" => "English (New Zealand)",
		    "en-GB" => "English (United Kingdom)",
		    "eo" => "esperanto",
		    "et" => "eesti",
		    "fo" => "føroyskt",
		    "fi" => "suomi",
		    "fr" => "français",
		    "fr-CA" => "français (Canada)",
		    "fr-CH" => "français (Suisse)",
		    "gl" => "galego",
		    "ka" => "ქართული",
		    "de" => "Deutsch",
		    "de-AT" => "Deutsch (Österreich)",
		    "el" => "Ελληνικά",
		    "he" => "עברית",
		    "hi" => "हिन्दी",
		    "hu" => "magyar",
		    "is" => "íslenska",
		    "id" => "Indonesia",
		    "it" => "italiano",
		    "it-CH" => "italiano (Svizzera)",
		    "ja" => "日本語",
		    "kk" => "қазақ тілі",
		    "km" => "ខ្មែរ",
		    "ko" => "한국어",
		    "ky" => "кыргызча",
		    "lv" => "latviešu",
		    "lt" => "lietuvių",
		    "mk" => "македонски",
		    "ms" => "Bahasa Melayu",
		    "ml" => "മലയാളം",
		    "no" => "norsk",
		    "nb" => "norsk bokmål",
		    "nn" => "nynorsk",
		    "fa" => "فارسی",
		    "pl" => "polski",
		    "pt" => "português",
		    "pt-BR" => "português (Brasil)",
		    "ro" => "română",
		    "rm" => "rumantsch",
		    "ru" => "русский",
		    "sr" => "српски",
		    "sk" => "slovenčina",
		    "sl" => "slovenščina",
		    "es" => "español",
		    "sv" => "svenska",
		    "ta" => "தமிழ்",
		    "th" => "ไทย",
		    "tr" => "Türkçe",
		    "uk" => "українська",
		    "vi" => "Tiếng Việt"
		);

		$labels = array(
			'link_url'      => esc_html__( 'Link URL', 'divi-form-builder' ),
			'link_text'     => esc_html__( 'Link Text', 'divi-form-builder' ),
			'link_cancel'   => esc_html__( 'Discard Changes', 'divi-form-builder' ),
			'link_save'     => esc_html__( 'Save Changes', 'divi-form-builder' ),
			'link_settings' => esc_html__( 'Option Link', 'divi-form-builder' ),
		);

		$registered_post_types = et_get_registered_post_type_options( false, false );

		unset($registered_post_types['attachment']);
		//unset($registered_post_types['project']);

		$field_mapping_types = array();

		$field_mapping_types[] = 'post_default_field';
		$field_mapping_options = array(
			'default'		=> esc_html__( 'Post Default Field (Post/Page/Products/CPT only)', 'divi-form-builder' ),
		);

		foreach ( $registered_post_types as $key => $post_type ) {
			$post_obj = get_post_type_object($key);
			$field_mapping_types[] = $key . '_taxonomy_field';
			$field_mapping_options[ $key . '_taxonomy'] = esc_html__( $post_obj->labels->singular_name . ' Taxonomy Field (' . $post_obj->labels->singular_name . ' Form only)', 'divi-form-builder' );
		}

		$field_mapping_options['acf'] = esc_html__( 'ACF Field', 'divi-form-builder' );
		$field_mapping_options['custom_meta'] = esc_html__( 'Custom Meta Field (Post/Page/Products/CPT only)', 'divi-form-builder' );
		$field_mapping_options['user_default'] = esc_html__( 'User Field (Register/Login form only)', 'divi-form-builder' );
		$field_mapping_options['user_meta'] = esc_html__( 'User Meta Field (Register/Login Form only)', 'divi-form-builder' );
		$field_mapping_options['custom'] = esc_html__( 'Custom (Advanced Users or Developers)', 'divi-form-builder' );
		$field_mapping_types[] = 'acf_field';
		$field_mapping_types[] = 'custom_meta_field_name';
		$field_mapping_types[] = 'user_default_field';
		$field_mapping_types[] = 'user_field_name';
		$field_mapping_types[] = 'custom_field_name';

		$fields = array(
			'field_title' 			=> array(
				'label'				=> esc_html__( 'Field Title', 'divi-form-builder' ),
				'type'				=> 'text',
				'description'		=> esc_html__( 'Change the name of the field.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_options',
				'dynamic_content'	=> 'text',
				'option_category'	=> 'configuration',
			),
			'admin_title' 			=> array(
				'label'				=> esc_html__( 'Admin Title', 'divi-form-builder' ),
				'type'				=> 'text',
				'description'		=> esc_html__( 'The name will be shown in visual builder settings modal.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_options',
				'dynamic_content'	=> 'text',
				'option_category'	=> 'configuration',
			),
			'field_id'				=> array(
				'label'				=> esc_html__( 'Field ID', 'divi-form-builder' ),
				'type'				=> 'text',
				'description'		=> esc_html__( 'Define the unique ID of this field. You should use only English characters without special characters and spaces.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_options',
				'default_on_front'	=> '',
				'option_category'	=> 'configuration',
			),
			'field_type'			=> array(
				'label'				=> esc_html__( 'Type', 'divi-form-builder' ),
				'type'				=> 'select',
				'default'			=> 'input',
				'option_category'	=> 'basic_option',
				'options'			=> array(
					'input'			=> esc_html__( 'Input Field', 'divi-form-builder' ),
					'number'		=> esc_html__( 'Number Input Field', 'divi-form-builder' ),
					'email'			=> esc_html__( 'Email Field', 'divi-form-builder' ),
					'password'		=> esc_html__( 'Password Field', 'divi-form-builder' ),
					'hidden'		=> esc_html__( 'Hidden Field', 'divi-form-builder' ),
					'text'			=> esc_html__( 'Textarea', 'divi-form-builder' ),
					'checkbox'		=> esc_html__( 'Checkboxes', 'divi-form-builder' ),
					'radio'			=> esc_html__( 'Radio Buttons', 'divi-form-builder' ),
					'select'		=> esc_html__( 'Select Dropdown', 'divi-form-builder' ),
					'image'			=> esc_html__( 'Image Upload', 'divi-form-builder' ),
					'file'			=> esc_html__( 'File Upload Field', 'divi-form-builder'),
					'datepicker'	=> esc_html__( 'Date Field', 'divi-form-builder'),
					'datetimepicker'=> esc_html__( 'Date/Time Field', 'divi-form-builder'),
					'html_content'  => esc_html__( 'Content(Text, Code or Divi Library)', 'divi-form-builder'),
					'signature'		=> esc_html__( 'Digital Signature Field', 'divi-form-builder' ),
					 'step'			=> esc_html__( 'Form Step', 'divi-form-builder' ),
				),
				'description'		=> esc_html__( 'Choose the type of field', 'divi-form-builder' ),
				'affects'			=> array(
					'is_google_address',
					'checkbox_auto_detect',
					'booleancheckbox_options',
					'radio_auto_detect',
					'select_auto_detect',
					'select_placeholder',
					'min_length',
					'max_length',
					'min_number',
					'max_number',
					'number_increase_step',
					'allowed_symbols',
					'use_wysiwyg_editor',
					'textarea_rows',
					'html_content_type',
					'date_time_picker_lang',
					'signature_pencolor',
					'signature_background',
					'signature_clear',
					'signature_clear_icon',
					'required_message',
					'required_message_position'
				),
				'toggle_slug'		=> 'field_options',
				'computed_affects' => array(
                  '_divilayoutcontent',
                ),
			),
			'add_field_prefix'		=> array(
				'label'           => esc_html__( 'Add Form Builder Prefix to field?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'on',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     => esc_html__( 'Disable this option to use your input directly for Field ID and Name, please disable this option for only specific case like google tag manager field.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
			),
			'is_google_address'	=> array(
				'label'           => esc_html__( 'Is Google Address Field?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'depends_show_if' => 'input',
				'description'     => esc_html__( 'Enable this option if you want to use google map address autocomplete api for this field.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
			),
			'min_number'			=> array(
				'label'				=> esc_html__( 'Minimum Number Value', 'divi-form-builder' ),
				'type'				=> 'text',
				'option_category'	=> 'basic_option',
				'default'			=> '0',
				'description'		=> esc_html__( 'Define the minimum number value for the number field.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_options',
				'show_if' 			=> array('field_type' => 'number'),
			),
			'max_number'			=> array(
				'label'				=> esc_html__( 'Maximum Number Value', 'divi-form-builder' ),
				'type'				=> 'text',
				'option_category'	=> 'basic_option',
				'default'			=> '',
				'description'		=> esc_html__( 'Define the maximum number value for the number field.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_options',
				'show_if' 			=> array('field_type' => 'number'),
			),
			'number_increase_step'	=> array(
				'label'				=> esc_html__( 'Number Increase Step Value', 'divi-form-builder' ),
				'type'				=> 'text',
				'option_category'	=> 'basic_option',
				'default'			=> '0',
				'description'		=> esc_html__( 'Define the increase step value for the number field.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_options',
				'show_if' 			=> array('field_type' => 'number'),
			),
			'hidden_value'			=> array(
				'label'				=> esc_html__( 'Hidden Field Value', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'basic_option',
				'options'			=> array(
					'page_name'			=> esc_html__( 'Page Name', 'divi-form-builder' ),
					'page_url'			=> esc_html__( 'Page URL', 'divi-form-builder' ),
					'acf'			=> esc_html__( 'ACF Field', 'divi-form-builder' ),
					'custom'			=> esc_html__( 'Custom Text', 'divi-form-builder' ),
				),
				'default'			=> '',
				'description'		=> esc_html__( 'Define the hidden value for the field.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_options',
				'show_if' => array('field_type' => 'hidden'),
			),
			'hidden_value_acf'		  => array(
				'label'           => esc_html__( 'Hidden Value ACF Field', 'divi-form-builder' ),
				'type'            => 'select',
				'options'			=> $acf_fields,
				'option_category'	=> 'basic_option',
				'default'         => 'Hidden Value',
				'description'     => esc_html__( 'Choose the ACF Field that you want to have the value for the hidden field.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if' => array('hidden_value' => 'acf'),
			),
			'hidden_value_custom'		  => array(
				'label'           => esc_html__( 'Hidden Value Custom Text', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category'	=> 'basic_option',
				'default'         => 'Hidden Value',
				'description'     => esc_html__( 'Add the custom text that you want to appear on the hidden field.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if' => array('hidden_value' => 'custom'),
			),
			'step_prev_text'		=> array(
				'label'           	=> esc_html__( 'Previous Button Text', 'divi-form-builder' ),
				'type'            	=> 'text',
				'option_category'	=> 'basic_option',
				'default'         	=> 'Prev',
				'description'     	=> esc_html__( 'Text for previous step button', 'divi-form-builder' ),
				'toggle_slug'     	=> 'field_options',
				'show_if' 			=> array('field_type' => 'step'),
			),
			'step_next_text'		  => array(
				'label'           => esc_html__( 'Next Button Text', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category'	=> 'basic_option',
				'default'         => 'Next',
				'description'     => esc_html__( 'Text for next step button', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if' => array('field_type' => 'step'),
			),
			'step_icon'				=> array(
				'label'             => esc_html__( 'Progress Bar Step Icon', 'divi-form-builder' ),
				'type'              => 'select_icon',
				'option_category'	=> 'basic_option',
				'class'             => array( 'et-pb-font-icon' ),
				'description'       => esc_html__( 'Choose the step icon', 'divi-form-builder' ),
				'show_if' 			=> array('field_type' => 'step'),
				'default'			=> 'N||divi||400',
				'toggle_slug'     	=> 'field_options',
			),
			'html_content_type'			=> array(
				'label'				=> esc_html__( 'Content Type', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'basic_option',
				'options'			=> array(
					'text'			=> esc_html__( 'Text', 'divi-form-builder' ),
					'code'			=> esc_html__( 'Code', 'divi-form-builder' ),
					'divi_library'			=> esc_html__( 'Divi Library Layout', 'divi-form-builder' ),
				),
				'description'		=> esc_html__( 'Choose the type of content field you want to use. You can text editor, html or even select a layout you made in the Divi Library.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_options',
				'default'			=> 'text',
				'depends_show_if' => 'html_content',
				'computed_affects' => array(
                  '_divilayoutcontent',
                ),
			),
			'html_content_divi_layout'			=> array(
				'label'				=> esc_html__( 'Content Library Layout', 'divi-form-builder' ),
				'type'				=> 'select',
				'default'			=> 'input',
				'option_category'	=> 'basic_option',
				'options'           => $divi_library_options,
				'description'		=> esc_html__( 'Choose the Divi Library layout you want to show as this field.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_options',
				'show_if' => array('html_content_type' => 'divi_library'),
				'computed_affects' => array(
                  '_divilayoutcontent',
                ),
			),
			'html_content_editor'             => array(
				'label'				=> esc_html__( 'Content Text', 'divi-form-builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Add the text or content that you want to appear.', 'et_builder' ),
				'toggle_slug'		=> 'field_options',
				'show_if' => array('html_content_type' => 'text', 'field_type' => 'html_content'),
			),
			'html_content_code' => array(
				'label'           => esc_html__( 'Code', 'et_builder' ),
				'type'            => 'codemirror',
				'mode'            => 'html',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Add custom code that you might want to appear.', 'et_builder' ),
				'toggle_slug'     => 'field_options',
				'show_if' => array('html_content_type' => 'code'),
			),
			'use_wysiwyg_editor'	=> array(
				'label'           => esc_html__( 'Use Wysiwyg Editor?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'depends_show_if' => 'text',
				'description'     => esc_html__( 'Define to use Wysiwyg Editor or normal textarea.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'affects'		  => array(
					'show_media_button'
				)
			),
			'show_media_button'	=> array(
				'label'           => esc_html__( 'Show Add Media Button?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'on',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'depends_show_if' => 'on',
				'description'     => esc_html__( 'If you want to disable Add Media Button, disable this option.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
			),
			'textarea_rows'		  => array(
				'label'           => esc_html__( 'Textarea Rows', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => '8',
				'depends_show_if' => 'text',
				'description'     => esc_html__( 'Define rows of the textarea field.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
			),
			'textarea_limit'		  => array(
				'label'           => esc_html__( 'Textarea Character Limit', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => '',
				'description'     => esc_html__( 'Define limit of the characters for textarea field. Empty for no limit.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'		  => array('field_type' => 'text')
			),

			'hide_upload_image_preview' 	=> array(
				'label'				=> esc_html__( 'Hide Upload Image Preview', 'divi-form-builder' ),
				'type'				=> 'yes_no_button',
				'option_category'	=> 'configuration',
				'default'			=> 'off',
				'options'			=> array(
					'on'				=> esc_html__('Yes', 'divi-form-builder'),
					'off'				=> esc_html__('No', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Enable this if you want to hide the image preview that appears when you upload a file or image', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_options',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
			),
			'hide_upload_prev_title' 	=> array(
				'label'				=> esc_html__( 'Hide Upload Preview Title', 'divi-form-builder' ),
				'type'				=> 'yes_no_button',
				'option_category'	=> 'configuration',
				'default'			=> 'off',
				'options'			=> array(
					'on'				=> esc_html__('Yes', 'divi-form-builder'),
					'off'				=> esc_html__('No', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Enable this if you want to hide the preview title that appears when you upload a file or image', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_options',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
			),
			'hide_upload_prev_size' 	=> array(
				'label'				=> esc_html__( 'Hide Upload Preview File Size', 'divi-form-builder' ),
				'type'				=> 'yes_no_button',
				'option_category'	=> 'configuration',
				'default'			=> 'off',
				'options'			=> array(
					'on'				=> esc_html__('Yes', 'divi-form-builder'),
					'off'				=> esc_html__('No', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Enable this if you want to hide the preview file size that appears when you upload a file or image', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_options',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
			),
			'hide_upload_prev_progressbar' 	=> array(
				'label'				=> esc_html__( 'Hide Upload Preview Progress Bar', 'divi-form-builder' ),
				'type'				=> 'yes_no_button',
				'option_category'	=> 'configuration',
				'default'			=> 'off',
				'options'			=> array(
					'on'				=> esc_html__('Yes', 'divi-form-builder'),
					'off'				=> esc_html__('No', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Enable this if you want to hide the preview progress bar that appears when you upload a file or image', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_options',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
			),
			'upload_alt_title' 	=> array(
				'label'				=> esc_html__( 'Upload alt Title', 'divi-form-builder' ),
				'type'				=> 'text',
				'description'		=> esc_html__( '', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_options',
				'default'			=> esc_html__( 'No file chosen', 'divi-form-builder' ),
				'show_if'			=> array( 'field_type' => array('file', 'image')),
			),
			'max_upload_file_counts'	  => array(
				'label'           => esc_html__( 'Max Upload File Counts', 'divi-form-builder' ),
				'type'            => 'number',
				'option_category' => 'configuration',
				'default'         => '5',
				'description'     => esc_html__( 'Define the max number of files to be uploaded - has to be an integer e.g. 5', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('image', 'file')),
			),
			'max_file_counts_error'		=> 	array(
				'label'           => esc_html__( 'Max Number of Files Exceed Error', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => 'Maximum number of files exceeded.',
				'description'     => esc_html__( 'When the user tries to upload number of files larger than the max file counts, specify the error message that appears.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('image', 'file')),
			),
			'max_upload_file_size'		  => array(
				'label'           => esc_html__( 'Max Upload File Size', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => '10000000',
				'description'     => esc_html__( 'Define the max file size in number - has to be an integer. 10000000 = 10MB', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('image', 'file')),
			),
			'max_upload_file_size_error'		  => array(
				'label'           => esc_html__( 'Max Upload File Size Error', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => 'The file uploaded exceeds the maximum file size allowed, please upload a smaller version.',
				'description'     => esc_html__( 'When the user tries to upload a file larger than the max file size, specify the error message that appears.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('image', 'file')),
			),
			'accepted_file_types_image'		  => array(
				'label'           => esc_html__( 'Accepted File Types', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => 'gif,jpeg,png',
				'description'     => esc_html__( 'Specify the accepted file types, comma seperated.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'		  => array('field_type' => 'image')
			),
			'accepted_file_types_file'		  => array(
				'label'           => esc_html__( 'Accepted File Types', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => 'pdf,csv,docx',
				'description'     => esc_html__( 'Specify the accepted file types, comma seperated.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'		  => array('field_type' => 'file')
			),
			'accepted_file_types_image_error'		  => array(
				'label'           => esc_html__( 'Accepted File Types Error', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => 'The file uploaded is not one of our accepted file types, please another version.',
				'description'     => esc_html__( 'When the user tries to upload a file that is not accepted, specify the error message that appears.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('image', 'file')),
			),
			'upload_error_hide_delay'		  => array(
				'label'           => esc_html__( 'Upload Error Hide Delay(milliseconds)', 'divi-form-builder' ),
				'type'            => 'range',
				'option_category' => 'configuration',
				'default'         => '4000ms',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '20000',
					'step' => '1',
				),
				'allowed_units'    => array( 'ms' ),
				'description'     => esc_html__( 'Set the time it takes for the error message to go away for the upload field', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('image', 'file')),
			),
			'edit_button_text'		  => array(
				'label'           => esc_html__( 'If on edit form - button text to show upload box', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => 'Change image',
				'description'     => esc_html__( 'When you have this field on the edit form and this is an image, we will hide the upload section hidden and have a button to click to reveal. Specify the text here.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('image')),
			),
			'close_edit_button_text'		  => array(
				'label'           => esc_html__( 'If on edit form - button text to close the upload box', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => 'Close',
				'description'     => esc_html__( 'When you click the buttom to edit the image, the upload box will appear. Change the text of the button to hide this upload box again.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('image')),
			),
			'edit_image_instructions'		  => array(
				'label'           => esc_html__( 'Edit image instructions text', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => 'Please upload the image above in the order of the images below.',
				'description'     => esc_html__( 'When you edit the image it will show a preview. Add instructions on how to edit the image. They need to upload the image into the drop box in the order the images are loaded', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('image', 'file')),
			),
			'remove_file_from_media'		  => array(
				'label'           => esc_html__( 'If on edit form - Remove File From Media library?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     => esc_html__( 'If you want remove file from media library when you remove file on edit form, enable this.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('image', 'file')),
			),
			'remove_file_confirm_message'		  => array(
				'label'           => esc_html__( 'If on edit form - Remove File Confirm Message ', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => 'Are you really want to remove this file?',
				'description'     => esc_html__( 'When you remove file on edit form, this confirm message will appear.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('image', 'file')),
			),

			'signature_pencolor'=> array(
				'label'           => esc_html__( 'Signature Pen Color', 'divi-form-builder' ),
				'type'            => 'color-alpha',
				'default'         => '#000',
				'depends_show_if' => 'signature',
				'description'     => esc_html__( 'Color of the Pen.', 'divi-form-builder' ),
				'toggle_slug'     => 'signature_option',
				'tab_slug'     	  => 'advanced',
			),
			'signature_background'=> array(
				'label'           => esc_html__( 'Signature Background', 'divi-form-builder' ),
				'type'            => 'color-alpha',
				'default'         => '#efefef',
				'depends_show_if' => 'signature',
				'description'     => esc_html__( 'Background color of Signature Field.', 'divi-form-builder' ),
				'toggle_slug'     => 'signature_option',
				'tab_slug'     	  => 'advanced',
			),
			'signature_clear'	=> array(
				'label'           => esc_html__( 'Add Clear "x" icon"?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'default'         => 'on',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     => esc_html__( 'Enable this option to add clear button.', 'divi-form-builder' ),
				'depends_show_if' => 'signature',
				'toggle_slug'     => 'signature_option',
				'tab_slug'     	  => 'advanced',
				'affects'		  => array(
					'signature_clear_icon',
					'signature_clear_icon_color',
					'signature_clear_icon_size',
					'signature_clear_icon_top'

				)
			),
			'signature_clear_icon' => array(
				'label'               => esc_html__( 'Clear Icon', 'divi-form-builder' ),
				'type'                => 'select_icon',
				'class'               => array( 'et-pb-font-icon' ),
				'description'         => esc_html__( 'Choose the input icon', 'divi-form-builder' ),
				'depends_show_if' 	=> 'on',
				'default'			=> 'M||divi||400',
				'toggle_slug'     	=> 'signature_option',
				'tab_slug'     	 	=> 'advanced',
			),
			'signature_clear_icon_color' => array(
				'label'               => esc_html__( 'Icon Color', 'divi-form-builder' ),
				'type'              => 'color-alpha',
				'description'       => esc_html__( 'Here you can define a custom color for your icon.', 'divi-form-builder' ),
				'depends_show_if' 	=> 'on',
				'default'         => '#000000',
				'toggle_slug'     	=> 'signature_option',
				'tab_slug'     	 	=> 'advanced',
			),
			'signature_clear_icon_size' => array(
				'label'               => esc_html__( 'Icon Font Size', 'divi-form-builder' ),
				'type'            => 'range',
				'default'         => '18px',
				'default_unit'    => 'px',
				'default_on_front'=> '',
				'range_settings' => array(
					'min'  => '1',
					'max'  => '120',
					'step' => '1',
				),
				'depends_show_if' 	=> 'on',
				'toggle_slug'     	=> 'signature_option',
				'tab_slug'     	 	=> 'advanced',
			),
			'signature_clear_icon_top' => array(
				'label'               => esc_html__( 'Icon From Top', 'divi-form-builder' ),
				'description'       => esc_html__( 'Choose how far from the top you want the icon', 'divi-form-builder' ),
				'type'            => 'range',
				'default'         => '20px',
				'default_unit'    => 'px',
				'default_on_front'=> '',
				'range_settings' => array(
					'min'  => '0',
					'max'  => '500',
					'step' => '1',
				),
				'depends_show_if' 	=> 'on',
				'toggle_slug'     	=> 'signature_option',
				'tab_slug'     	 	=> 'advanced',
			),
			'checkbox_checked'           => array(
				'label'           => esc_html__( 'Checked By Default', 'divi-form-builder' ),
				'description'     => esc_html__( 'If enabled, the check mark will be automatically selected for the visitor. They can still deselected it.', 'divi-form-builder' ),
				'type'            => 'hidden',
				'option_category' => 'layout',
				'default'         => 'off',
				'depends_show_if' => 'checkbox',
				'toggle_slug'     => 'field_options',
			),
			'checkbox_auto_detect'		=> array(
				'label'           => esc_html__( 'Auto Detect from mapped field?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'depends_show_if' => 'checkbox',
				'affects'		  => array(
					'exclude_checkbox_options',
					'checkbox_options'
				),
				'description'     => esc_html__( 'Choose if you want the field to be auto populated by the mapped field', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
			),
			'exclude_checkbox_options'	=> array(
				'label'           => esc_html__( 'Exclude auto-detected options', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => '',
				'description'     => esc_html__( 'If you want exclude some options from auto detected options, please set here separated by comma.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'depends_show_if'  => 'on',
			),
			'checkbox_options'           => array(
				'label'           => esc_html__( 'Options', 'divi-form-builder' ),
				'type'            => 'sortable_list',
				'checkbox'        => true,
				'option_category' => 'basic_option',
				'depends_show_if' => 'off',
				'toggle_slug'     => 'field_options',
				'right_actions'   => 'move|link|copy|delete',
				'labels'          => $labels,
			),
			'booleancheckbox_options'    => array(
				'label'           => esc_html__( 'Options', 'divi-form-builder' ),
				'type'            => 'sortable_list',
				'checkbox'        => true,
				'option_category' => 'basic_option',
				'depends_show_if' => 'booleancheckbox',
				'toggle_slug'     => 'field_options',
				'right_actions'   => 'move|link|copy|delete',
				'labels'          => $labels,
			),
			'radio_auto_detect'		=> array(
				'label'           => esc_html__( 'Auto Detect from mapped field?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'depends_show_if' => 'radio',
				'affects'		  => array(
					'exclude_radio_options',
					'radio_options'
				),
				'description'     => esc_html__( 'Choose if you want the field to be auto populated by the mapped field', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
			),
			'exclude_radio_options'	=> array(
				'label'           => esc_html__( 'Exclude auto-detected options', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => '',
				'description'     => esc_html__( 'If you want exclude some options from auto detected options, please set here separated by comma.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'depends_show_if'  => 'on',
			),
			'radio_options'              => array(
				'label'           => esc_html__( 'Options', 'divi-form-builder' ),
				'type'            => 'sortable_list',
				'radio'           => true,
				'option_category' => 'basic_option',
				'depends_show_if' => 'off',
				'toggle_slug'     => 'field_options',
				'right_actions'   => 'move|link|copy|delete',
				'labels'          => $labels,
			),

			'radio_checkbox_image'		=> array(
				'label'           => esc_html__( 'Add Images to the Options?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'show_if'			=> array( 'field_type' => array('checkbox', 'radio')),
				'description'     => esc_html__( 'Enable this if you want to have images as checkbox/radio options', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
			),
			'radio_checkbox_image_ids'            => array(
				'label'            => esc_html__( 'Image Options', 'et_builder' ),
				'description'      => esc_html__( 'Choose the images that you would like to appear as part of the options. Make sure that the options order is the same as the gallery.', 'et_builder' ),
				'type'             => 'upload-gallery',
				'option_category'  => 'basic_option',
				'toggle_slug'      => 'radio_checkbox_image',
				'show_if'			=> array( 'radio_checkbox_image' => array('on')),
				'computed_affects' => array(
					'_radio_images',
				),
			),
			'radio_show_for_image'	=> array(
				'label'           	=> esc_html__( 'Show Radiobox for Image options?', 'divi-form-builder' ),
				'type'            	=> 'yes_no_button',
				'option_category' 	=> 'configuration',
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'show_if'			=> array( 'radio_checkbox_image' => array('on')),
				'description'     	=> esc_html__( 'Show or Hide radio/checkbox selection for Image Options?', 'divi-form-builder' ),
				'toggle_slug'     	=> 'radio_checkbox_image',
			),
			'radio_image_label_position'	=> array(
				'label'           	=> esc_html__( 'Image options label position', 'divi-form-builder' ),
				'type'            	=> 'select',
				'option_category' 	=> 'configuration',
				'default'         	=> 'bottom',
				'options'         	=> array(
					'top'  => et_builder_i18n( 'Top' ),
					'right' => et_builder_i18n( 'Right' ),
					'bottom' => et_builder_i18n( 'Bottom' ),
					'left' => et_builder_i18n( 'Left' ),
					'hide' => et_builder_i18n( 'Hide' ),
				),
				'show_if'			=> array( 'radio_checkbox_image' => array('on')),
				'description'     	=> esc_html__( 'Select the position where you want to show the label.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'radio_checkbox_image',
			),
			'radio_checkbox_max_width' => array(
				'label'             => esc_html__( 'Radio / Checkbox Image Max Width', 'divi-form-builder' ),
				'type'              => 'range',
				'option_category'   => 'configuration',
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'radio_checkbox_image',
				'description'       => esc_html__( 'Adjust the max width of the image for the radio image.', 'divi-form-builder' ),
				'default'           => "100px",
				'default_unit'      => 'px',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '3000',
					'step' => '1',
				),
			),

			'radio_checkbox_same_height' => array(
				'label'             => esc_html__( 'Make Radio / Checkbox Image as same height', 'divi-form-builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'radio_checkbox_image',
				'description'       => esc_html__( 'Make height of all image for the checkbox/radio image.', 'divi-form-builder' ),
				'default'         	=> 'off',
				'options'         	=> array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'show_if'			=> array( 'radio_checkbox_image' => array('on')),
			),

			'select_auto_detect'  => array(
				'label'           => esc_html__( 'Auto Detect from mapped field?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'depends_show_if' => 'select',
				'affects'		  => array(
					'exclude_select_options',
					'select_options'
				),
				'description'     => esc_html__( 'Choose if you want the field to be auto populated by the mapped field', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
			),
			'exclude_select_options'	=> array(
				'label'           => esc_html__( 'Exclude auto-detected options', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => '',
				'description'     => esc_html__( 'If you want exclude some options from auto detected options, please set here separated by comma.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'depends_show_if'  => 'on',
			),
			'select_placeholder'  => array(
				'label'           => esc_html__( 'Enable Select Placeholder?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'depends_show_if' => 'select',
				'description'     => esc_html__( 'Choose if you want a placeholder to be shown first', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
			),			
			'select_placeholder_text'  => array(
				'label'           => esc_html__( 'Placeholder Text', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => '-- Select Option --',
				'show_if' => array('select_placeholder' => 'on'),
				'description'     => esc_html__( 'Define the text to be shown as the placeholder for the select option', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
			),
			'select_options'             => array(
				'label'           => esc_html__( 'Options', 'divi-form-builder' ),
				'type'            => 'sortable_list',
				'option_category' => 'basic_option',
				'depends_show_if' => 'off',
				'toggle_slug'     => 'field_options',
			),
			'select_arrow_color'=> array(
				'label'           => esc_html__( 'Select Arrow Color', 'divi-form-builder' ),
				'type'            => 'color-alpha',
				'default'         => '#666',
				'description'     => esc_html__( 'Set the color of the arrow for the select dropdown.', 'divi-form-builder' ),
				'toggle_slug'     => 'form_field',
				'tab_slug'     	  => 'advanced',
			),
			'icon_top_position'=> array(
				'label'           => esc_html__( 'Field Icon Top Position', 'divi-form-builder' ),
				'type'            => 'range',
				'default'         => '20px',
				'description'     => esc_html__( 'Set the color of the arrow for the select dropdown.', 'divi-form-builder' ),
				'toggle_slug'     => 'form_field',
				'tab_slug'     	  => 'advanced',
				'unit'        	  => 'px',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'show_if' 		  =>  array( 'field_type' => array('input', 'email', 'password', 'select', 'datepicker', 'datetimepicker') ),
			),
			'email_message'    => array(
				'label'           => esc_html__( 'Email Input Error Message', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => 'Please input correct email address.',
				'description'     => esc_html__( 'Define the error message that will show when email address format is wrong.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('email')),
			),
			'min_length'                 => array(
				'label'           => esc_html__( 'Minimum Length', 'divi-form-builder' ),
				'description'     => esc_html__( 'Leave at 0 to remove restriction', 'divi-form-builder' ),
				'type'            => 'range',
				'default'         => '0',
				'unitless'        => true,
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '255',
					'step' => '1',
				),
				'option_category' => 'basic_option',
				'show_if' 		  =>  array( 'field_type' => array('input', 'password') ),
				'toggle_slug'     => 'field_options',
			),
			'max_length'                 => array(
				'label'           => esc_html__( 'Maximum Length', 'divi-form-builder' ),
				'description'     => esc_html__( 'Leave at 0 to remove restriction', 'divi-form-builder' ),
				'type'            => 'range',
				'default'         => '0',
				'unitless'        => true,
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '255',
					'step' => '1',
				),
				'option_category' => 'basic_option',
				'show_if' 		  =>  array( 'field_type' => array('input', 'password') ),
				'toggle_slug'     => 'field_options',
			),
			'minlength_message'    => array(
				'label'           => esc_html__( 'Minimum Length Error Message', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => 'Your input is too short.',
				'description'     => esc_html__( 'Define the error message that will show when input length is less.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('input', 'text', 'email')),
				'show_if_not'		=> array( 'min_length' => '0' )
			),
			'allowed_symbols'            => array(
				'label'           => esc_html__( 'Allowed Symbols', 'divi-form-builder' ),
				'type'            => 'select',
				'default'         => 'all',
				'options'         => array(
					'all'          => esc_html__( 'All', 'divi-form-builder' ),
					'letters'      => esc_html__( 'Letters Only (A-Z)', 'divi-form-builder' ),
					'numbers'      => esc_html__( 'Numbers Only (0-9)', 'divi-form-builder' ),
					'alphanumeric' => esc_html__( 'Alphanumeric Only (A-Z, 0-9)', 'divi-form-builder' ),
				),
				'option_category' => 'basic_option',
				'depends_show_if' => 'input',
				'toggle_slug'     => 'field_options',
			),
			'pattern_message'    => array(
				'label'           => esc_html__( 'Pattern Failed Message', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => 'Invalid Format.',
				'description'     => esc_html__( 'Define the error message that will show when unallowed character has input.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('input', 'text', 'email')),
				'show_if_not'		=> array( 'allowed_symbols' => 'all' )
			),
			'required_mark'              => array(
				'label'           => esc_html__( 'Required Field', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     => esc_html__( 'Define whether the field should be required or optional.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
			),
			'required_message'    => array(
				'label'           => esc_html__( 'Required Field Message', 'divi-form-builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => 'This field is required.',
				'description'     => esc_html__( 'Define the error message that will show when it is required field.', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('input', 'text', 'email', 'checkbox', 'radio', 'file', 'image','select','datepicker','datetimepicker'), 'required_mark' => 'on'),
			),
			'required_message_position'    => array(
				'label'           => esc_html__( 'Required Message Position', 'divi-form-builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'default'         => 'bottom',
				'description'     => esc_html__( 'Define the position of error message.', 'divi-form-builder' ),
				'options'			=> array(
					'top'			=> esc_html__( 'Top' ),
					'bottom'		=> esc_html__( 'Bottom')
				),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('input', 'text', 'checkbox', 'radio', 'file', 'image'), 'required_mark' => 'on'),
			),
			'go_next_step_on_change'       => array(
				'label'           => esc_html__( 'Go to Next Step when change option on multistep form?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'show_if' 		  => array( 'field_type' => array('radio', 'select') ),
				'toggle_slug'     => 'field_options',
				'default'		  => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     => esc_html__( 'If you go to the next step when change radio or select options, enable this.', 'divi-form-builder' ),
			),
			'date_time_picker_lang' => array(
				'label'           	=> esc_html__( 'Date & Time picker Language', 'divi-form-builder' ),
				'type'            	=> 'select',
				'default'         	=> 'en-GB',
				'options'         	=> $languages_list,
				'option_category' 	=> 'basic_option',
				'show_if'			=> array( 'field_type' => array('datepicker', 'datetimepicker')),
				'description'     	=> esc_html__( 'Please select the language for date & time picker.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'field_options',
			),
			'date_format' => array(
				'label'           	=> esc_html__( 'Calender Date Format', 'divi-form-builder' ),
				'type'            	=> 'text',
				'default'         	=> 'yy-mm-dd',
				'option_category' 	=> 'basic_option',
				'show_if'			=> array( 'field_type' => array('datepicker', 'datetimepicker')),
				'description'     	=> esc_html__( 'Please specify the date format that you want to show.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'field_options',
			),
			'date_time_format' => array(
				'label'           	=> esc_html__( 'Calender Time Format', 'divi-form-builder' ),
				'type'            	=> 'text',
				'default'         	=> 'hh:mm',
				'option_category' 	=> 'basic_option',
				'show_if'			=> array( 'field_type' => array('datetimepicker')),
				'description'     	=> esc_html__( 'Please specify the time format that you want to show.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'field_options',
			),
			'field_mapping_type'	=> array(
				'label'				=> esc_html__( 'Field Mapping Type', 'divi-form-builder' ),
				'type'				=> 'select',
				'default'			=> 'default',
				'option_category'	=> 'configuration',
				'options'			=> $field_mapping_options,
				'description'		=> esc_html__( 'Choose the mapping type - for example if you want to map to the post title, choose "post default fields"', 'divi-form-builder' ),
				'affects'			=> $field_mapping_types,
				'toggle_slug'		=> 'field_mapping',
			),
			'acf_field' 			=> array(
				'label'				=> esc_html__( 'ACF Field Mapping', 'divi-form-builder' ),
				'type'				=> 'select',
				'default'			=> 'none',
				'option_category'	=> 'layout_option',
				'options'			=> $acf_fields,
				'description'		=> esc_html__( 'Choose ACF field to map with this field.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_mapping',
				'depends_show_if' 	=> 'acf',
			),
			'custom_meta_field_name'=> array(
				'label'				=> esc_html__( 'Custom Meta Field Mapping', 'divi-form-builder' ),
				'type'				=> 'text',
				'option_category'	=> 'layout_option',
				'description'		=> esc_html__( 'Input field name to map with this field for example custom meta value.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_mapping',
				'depends_show_if'	=> 'custom_meta',
			),
			'user_default_field' 	=> array(
				'label'				=> esc_html__( 'Default User Field Mapping', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'layout_option',
				'options'			=> array(
					'none'			=> esc_html__( 'No Select', 'divi-form-builder' ),
					'user_nicename'	=> esc_html__( 'User Name', 'divi-form-builder' ),
					'first_name'	=> esc_html__( 'First Name', 'divi-form-builder' ),
					'last_name'		=> esc_html__( 'Last Name', 'divi-form-builder' ),
					'nickname'		=> esc_html__( 'Nick Name', 'divi-form-builder' ),
					'user_login'	=> esc_html__( 'Login Name', 'divi-form-builder' ),
					'user_email'	=> esc_html__( 'Email Address', 'divi-form-builder' ),
					'user_url'		=> esc_html__( 'User Url', 'divi-form-builder' ),
					'display_name'	=> esc_html__( 'Display Name', 'divi-form-builder' ),
					'user_pass'		=> esc_html__( 'Password', 'divi-form-builder' ),
					'pass_repeat'	=> esc_html__( 'Password Confirm', 'divi-form-builder' ),
					'role'			=> esc_html__( 'User Role', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Select the user field type for this field.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_mapping',
				'depends_show_if'	=> 'user_default',
			),
			'user_field_name'	=> array(
				'label'				=> esc_html__( 'User Meta Field Name', 'divi-form-builder' ),
				'type'				=> 'text',
				'option_category'	=> 'layout_option',
				'description'		=> esc_html__( 'Input field name to map this field with user meta.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_mapping',
				'depends_show_if'	=> 'user_meta',
			),
			'custom_field_name'		=> array(
				'label'				=> esc_html__( 'Custom Field Mapping', 'divi-form-builder' ),
				'type'				=> 'text',
				'option_category'	=> 'layout_option',
				'description'		=> esc_html__( 'Input field name to use in form.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_mapping',
				'depends_show_if'	=> 'custom',
			),
			'post_default_field' 	=> array(
				'label'				=> esc_html__( 'Default Field Mapping', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'layout_option',
				'options'			=> array(
					'none'			=> esc_html__( 'No Select', 'divi-form-builder' ),
					'post_title'	=> esc_html__( 'Post Title', 'divi-form-builder' ),
					'post_content'	=> esc_html__( 'Post Content', 'divi-form-builder' ),
					'post_excerpt'	=> esc_html__( 'Post Excerpt', 'divi-form-builder' ),
					'post_status'	=> esc_html__( 'Post Status', 'divi-form-builder' ),
					'post_name'		=> esc_html__( 'Post Slug', 'divi-form-builder' ),
					'post_thumbnail'=> esc_html__( 'Featured Image', 'divi-form-builder' ),
					'post_parent'	=> esc_html__( 'Parent Post', 'divi-form-builder' ),
					'post_type'		=> esc_html__( 'Post Type', 'divi-form-builder' ),
				),
				'description'		=> esc_html__( 'Select the columns count of the field label for 12 columns.', 'divi-form-builder' ),
				'toggle_slug'		=> 'field_mapping',
				'depends_show_if'	=> 'default',
			),
			'field_grid_column' 	=> array(
				'label'				=> esc_html__( 'Columns for Grid', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'layout_option',
				'default'			=> 'et_pb_column_4_4',
				'options'			=> array(
					'et_pb_column_4_4'				=> esc_html__('Full (4/4)', 'divi-form-builder'),
					'et_pb_column_3_4'				=> esc_html__('Three Quarter (3/4)', 'divi-form-builder'),
					'et_pb_column_2_3'				=> esc_html__('Two Thirds (2/3)', 'divi-form-builder'),
					'et_pb_column_1_2'				=> esc_html__('Half (1/2)', 'divi-form-builder'),
					'et_pb_column_1_3'				=> esc_html__('Third (1/3)', 'divi-form-builder'),
					'et_pb_column_1_4'				=> esc_html__('Quarter (1/4)', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Select Columns count of the field.', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
			),
			'upload_description' 	=> array(
				'label'				=> esc_html__( 'Upload Description', 'divi-form-builder' ),
				'type'				=> 'text',
				'option_category'	=> 'layout_option',
				'default'			=> esc_html__( 'Drop files here or Click to select file.', 'divi-form-builder' ),
				'description'		=> esc_html__( 'Set the text that appears to notify the visitor to click or drag images/files to upload', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
			),
			// Upload Section
			'icon_image_width'                 => array(
				'label'           => esc_html__( 'Upload Icon / Image Width', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the width of the icon or image', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '50px',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '-1500',
					'max'  => '1500',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'sub_toggle'		=> 'upload_icon_toggle'
			),
			'icon_image_height'                 => array(
				'label'           => esc_html__( 'Upload Icon / Image Height', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the height of the icon or image', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '50px',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '-1500',
					'max'  => '1500',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'sub_toggle'		=> 'upload_icon_toggle'
			),
			'upload_primary_color' 	=> array(
				'default'           => "#000000",
				'label'             => esc_html__('Upload Icon Primary Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the Primary Color of the SVG Icons set above.', 'divi-form-builder'),
				'option_category' 	=> 'layout_option',
				'tab_slug'         	=> 'advanced',
				'toggle_slug'     	=> 'file_image_upload',
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
				'show_if_not'			=> array( 'upload_icon_style' => array('custom_upload')),
				'sub_toggle'		=> 'upload_icon_toggle'
			),
			'upload_secondary_color' 	=> array(
				'default'           	=> "#efefef",
				'label'             	=> esc_html__('Upload Icon Secondary Color', 'divi-form-builder'),
				'type'              	=> 'color-alpha',
				'description'       	=> esc_html__('Change the Secondary Color of the SVG Icons set above.', 'divi-form-builder'),
				'option_category' 		=> 'layout_option',
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'show_if'				=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
				'show_if_not'			=> array( 'upload_icon_style' => array('custom_upload')),
				'sub_toggle'		=> 'upload_icon_toggle'
			),
			'upload_quarterly_color' 	=> array(
				'default'           	=> "#656565",
				'label'             	=> esc_html__('Upload Icon Quarterly Color', 'divi-form-builder'),
				'type'              	=> 'color-alpha',
				'description'       	=> esc_html__('Change the Quarterly Color of the SVG Icons set above.', 'divi-form-builder'),
				'option_category' 		=> 'layout_option',
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'show_if'				=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
				'show_if_not'			=> array( 'upload_icon_style' => array('custom_upload')),
				'sub_toggle'		=> 'upload_icon_toggle'
			),
			'icon_alignment_horizontal'  => array(
				'label'           => esc_html__( 'Upload Icon Distance Horizontal', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the distance from the edge', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '50%',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '-1500',
					'max'  => '1500',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'sub_toggle'		=> 'upload_icon_toggle'
			),
			'icon_alignment_vertical'                 => array(
				'label'           => esc_html__( 'Upload Icon Distance Vertical', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the distance from the top or bottom', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '30px',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '-1500',
					'max'  => '1500',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'sub_toggle'		=> 'upload_icon_toggle'
			),
			'upload_desc_horizontal'  => array(
				'label'           => esc_html__( 'Upload Description Horizontal', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the distance from the edge', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '0',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '-1500',
					'max'  => '1500',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'sub_toggle'		=> 'upload_desc_toggle'
			),
			'upload_desc_vertical'                 => array(
				'label'           => esc_html__( 'Upload Description Vertical', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the distance from the top or bottom', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '20px',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '-1500',
					'max'  => '1500',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'sub_toggle'		=> 'upload_desc_toggle'
			),
			'upload_bg_drag' => array(
				'default'           => "",
				'label'             => esc_html__('Upload Section Drag Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the color of the background when dragging the media over the section.', 'divi-form-builder'),
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
				'option_category' 	=> 'layout_option',
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'hover'             => 'tabs',
				'mobile_options'    => true,
				'sub_toggle'		=> 'upload_desc_toggle'
			),

			// Upload Preview Section
			'preview_bg_color' => array(
				'default'           => "",
				'label'             => esc_html__('Preview Background Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the color of the background of the preview box that appears.', 'divi-form-builder'),
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
				'option_category' 	=> 'layout_option',
				'tab_slug'         	=> 'advanced',
				'default'			=> '#ffffff',
				'toggle_slug'     	=> 'file_image_upload',
				'sub_toggle'		=> 'upload_preview_toggle'
			),
			'preview_image_width'  => array(
				'label'           => esc_html__( 'Image Preview Width', 'divi-form-builder' ),
				'description'     => esc_html__( 'When a image is uploaded, there will be a preview - set the width of it here', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '100px',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '1000',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'sub_toggle'		=> 'upload_preview_toggle'
			),
			'preview_image_height'  => array(
				'label'           => esc_html__( 'Image Preview Height', 'divi-form-builder' ),
				'description'     => esc_html__( 'When a image is uploaded, there will be a preview - set the width of it here', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '100px',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '1000',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'sub_toggle'		=> 'upload_preview_toggle'
			),
			'preview_icon_remove' => array(
				'label'               => esc_html__( 'Upload Remove Icon', 'divi-form-builder' ),
				'type'                => 'select_icon',
				'class'               => array( 'et-pb-font-icon' ),
				'description'         => esc_html__( 'Choose the icon to appear when you want to remove an image from the upload', 'divi-form-builder' ),
				'default'			=> 'M||divi||400',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
				'toggle_slug'     	=> 'file_image_upload',
				'sub_toggle'		=> 'upload_preview_toggle',
				'tab_slug'     	 	=> 'advanced',
			),
			'preview_icon_remove_size'  => array(
				'label'           => esc_html__( 'Upload Remove Icon Size', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the size of the remove icon', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '30px',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '1000',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
				'toggle_slug'     	=> 'file_image_upload',
				'sub_toggle'		=> 'upload_preview_toggle',
				'tab_slug'     	 	=> 'advanced',
			),
			'preview_icon_remove_color' => array(
				'default'           => "",
				'label'             => esc_html__('Upload Remove Icon Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the color of the icon that appears on the preview to remove it.', 'divi-form-builder'),
				'show_if'			=> array( 'field_type' => array('file', 'image')),
				'option_category' 	=> 'layout_option',
				'tab_slug'         	=> 'advanced',
				'default'			=> '#000000',
				'toggle_slug'     	=> 'file_image_upload',
				'sub_toggle'		=> 'upload_preview_toggle'
			),
			'preview_dis_vertical'                 => array(
				'label'           => esc_html__( 'Image Preview Distance Vertical', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the distance from the top or bottom', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '20px',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '-1500',
					'max'  => '1500',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'sub_toggle'		=> 'upload_preview_toggle'
			),
			'preview_dis_horizontal'                 => array(
				'label'           => esc_html__( 'Image Preview Distance Horizontal', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the distance from the left', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '0px',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '-1500',
					'max'  => '1500',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'sub_toggle'		=> 'upload_preview_toggle'
			),


			'upload_progress_bar_style' 	=> array(
				'label'				=> esc_html__( 'Progress Bar Style', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'layout_option',
				'tab_slug'         	=> 'advanced',
				'default'			=> 'progress-striped',
				'options'			=> array(
					'progress-striped'				=> esc_html__('Stripes', 'divi-form-builder'),
					'progress-solid'				=> esc_html__('Solid Color', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Choose the appearance of the progress bar.', 'divi-form-builder' ),
				'toggle_slug'     	=> 'file_image_upload',
				'sub_toggle'		=> 'upload_preview_progressbar',
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
			),
			'upload_progress_bar_color' => array(
				'default'           => "",
				'label'             => esc_html__('Progress Bar Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the color of the progress bar background.', 'divi-form-builder'),
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
				'option_category' 	=> 'layout_option',
				'tab_slug'         	=> 'advanced',
				'default'			=> '#000000',
				'toggle_slug'     	=> 'file_image_upload',
				'sub_toggle'		=> 'upload_preview_progressbar'
			),
			'upload_progress_bar_height'                 => array(
				'label'           => esc_html__( 'Progress Bar Height', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the height of the progress bar', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '20px',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '1500',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'sub_toggle'		=> 'upload_preview_progressbar'
			),
			'upload_progress_bar_width'                 => array(
				'label'           => esc_html__( 'Progress Bar Width', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the width of the progress bar', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '100%',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '1500',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'sub_toggle'		=> 'upload_preview_progressbar'
			),
			// design settings for upload edit remove icon
			'edit_icon_remove' => array(
				'label'               => esc_html__( 'Edit Remove Icon', 'divi-form-builder' ),
				'type'                => 'select_icon',
				'class'               => array( 'et-pb-font-icon' ),
				'description'         => esc_html__( 'Choose the icon to appear when you want to remove an image from the edit image section', 'divi-form-builder' ),
				'default'			=> 'M||divi||400',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
				'toggle_slug'     	=> 'file_image_upload',
				'sub_toggle'		=> 'upload_edit_preview',
				'tab_slug'     	 	=> 'advanced',
			),
			// edit remove icon color
			'edit_icon_remove_color' => array(
				'label'             => esc_html__('Edit Remove Icon Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the color of the edit remove icon.', 'divi-form-builder'),
				'show_if'			=> array( 'field_type' => array('file', 'image')),
				'option_category' 	=> 'layout_option',
				'tab_slug'         	=> 'advanced',
				'default'			=> '#fff',
				'toggle_slug'     	=> 'file_image_upload',
				'sub_toggle'		=> 'upload_edit_preview'
			),
			// edit remove icon background color
			'edit_icon_remove_bg_color' => array(
				'label'             => esc_html__('Edit Remove Icon Background Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the background color of the edit remove icon.', 'divi-form-builder'),
				'show_if'			=> array( 'field_type' => array('file', 'image')),
				'option_category' 	=> 'layout_option',
				'tab_slug'         	=> 'advanced',
				'default'			=> '#000000',
				'toggle_slug'     	=> 'file_image_upload',
				'sub_toggle'		=> 'upload_edit_preview'
			),
			// edit remove icon font size - default 22px
			'edit_icon_remove_font_size' => array(
				'label'           => esc_html__( 'Edit Remove Icon Font Size', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the font size of the edit remove icon', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '22px',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '1500',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'file_image_upload',
				'sub_toggle'		=> 'upload_edit_preview'
			),



			// END DESIGN SETTINGS FOR UPLOAD
			'upload_icon' 	=> array(
				'label'				=> esc_html__( 'Show Upload Icon', 'divi-form-builder' ),
				'type'				=> 'yes_no_button',
				'option_category'	=> 'layout_option',
				'default'			=> 'on',
				'options'			=> array(
					'on'				=> esc_html__('Yes', 'divi-form-builder'),
					'off'				=> esc_html__('No', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Choose if you want to show an icon or not with the upload option', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
				'show_if'			=> array( 'field_type' => array('file', 'image')),
			),
			'upload_icon_style' 	=> array(
				'label'				=> esc_html__( 'Upload Icon Style', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'layout_option',
				'default'			=> 'document',
				'options'			=> array(
					'document'				=> esc_html__('Document', 'divi-form-builder'),
					'camera'				=> esc_html__('Camera', 'divi-form-builder'),
					'upload'				=> esc_html__('Upload', 'divi-form-builder'),
					'upload-circle'				=> esc_html__('Upload Circle', 'divi-form-builder'),
					'custom_upload'				=> esc_html__('Custom Upload', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Choose either one of our premade icons for the upload or choose to upload your own.', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
			),

			'icon_upload'                 => array(
				'label'              => esc_html__( 'Icon Upload' ),
				'type'               => 'upload',
				'option_category'	=> 'layout_option',
				'toggle_slug'		=> 'layout_options',
				'upload_button_text' => esc_html__( 'Upload an icon' ),
				'choose_text'        => esc_attr__( 'Choose an icon', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As icon', 'et_builder' ),
				'hide_metadata'      => true,
				'description'        => esc_html__( 'Upload your desired icon, or type in the URL to the image you would like to display.', 'et_builder' ),
				'show_if'			=> array(
					'upload_icon' => array('on'),
					'upload_icon_style' => array('custom_upload'),
					'field_type' => array('file', 'image'),
				),
			),
			'file_upload_alignment' 	=> array(
				'label'				=> esc_html__( 'Icon/Text Alignment', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'layout_option',
				'default'			=> 'left',
				'options'			=> array(
					'left'				=> esc_html__('Left', 'divi-form-builder'),
					'right'				=> esc_html__('Right', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Choose the alignment for your file upload icon/text', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
			),

			'upload_padding'  => array(
				'label'           => esc_html__( 'Upload Section Padding', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the padding for the section', 'divi-form-builder' ),
                'type'           => 'custom_padding',
				'tab_slug'         => 'advanced',
				'toggle_slug'     => 'margin_padding',
                'mobile_options' => true,
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
			),

			'upload_icon_drag_animation' 	=> array(
				'label'				=> esc_html__( 'Upload Icon Drag Animation', 'divi-form-builder' ),
				'type'				=> 'select',
				'default'			=> 'wobble-hor-bottom',
				'option_category' => 'layout_option',
				'options'			=> array(
					'wobble-hor-bottom'			=> esc_html__('Wobble', 'divi-form-builder'),
					'vibrate-1'			=> esc_html__('Vibrate', 'divi-form-builder'),
					'shake-horizontal'			=> esc_html__('Left', 'divi-form-builder'),
					'jello-horizontal'			=> esc_html__('Jello', 'divi-form-builder'),
					'heartbeat'			=> esc_html__('Pulsate', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Choose the animation of the icon when you drag the media over.', 'divi-form-builder' ),
				'toggle_slug'     => 'layout_options',
				'show_if'			=> array( 'upload_icon' => array('on'), 'field_type' => array('file', 'image')),
			),

			'field_label_position' 	=> array(
				'label'				=> esc_html__( 'Field Label Position', 'divi-form-builder' ),
				'type'				=> 'select',
				'default'			=> 'none',
				'option_category'	=> 'layout_option',
				'options'			=> array(
					'none'			=> esc_html__('None', 'divi-form-builder'),
					'top'			=> esc_html__('Top', 'divi-form-builder'),
					'left'			=> esc_html__('Left', 'divi-form-builder'),
					'right'			=> esc_html__('Right', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Select the position of the field label.', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
				'affects'			=> array(
					'field_label_width'
				),
			),
			'radio_checkbox_field_style' 	=> array(
				'label'				=> esc_html__( 'Radio/Checkbox Field Style', 'divi-form-builder' ),
				'type'				=> 'select',
				'default'			=> 'default',
				'option_category'	=> 'layout_option',
				'options'			=> array(
					'default'			=> esc_html__('Default Divi', 'divi-form-builder'),
					'button'			=> esc_html__('Button', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Choose the style of the radio or checkbox field.', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
				'show_if'			=> array('field_type' => array ('radio', 'checkbox')),
			),

			'radio_checkbox_checked_color' 	=> array(
				'default'           => "#2ea3f2",
				'label'             => esc_html__('Checkbox Checked Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the color of the dot when a radio or checkbox is checked.', 'divi-form-builder'),
				'tab_slug'         => 'advanced',
				'toggle_slug'		=> 'form_field',
				'show_if'			=> array('field_type' => array ('radio', 'checkbox'), 'radio_checkbox_field_style' => array('default')),
			),

			'checkbox_radio_inline' 	=> array(
				'label'				=> esc_html__( 'Make Radio/Checkbox Options a grid or in one line?', 'divi-form-builder' ),
				'type'				=> 'yes_no_button',
				'option_category'	=> 'layout_option',
				'default'			=> 'off',
				'options'			=> array(
					'on'				=> esc_html__('Yes', 'divi-form-builder'),
					'off'				=> esc_html__('No', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Choose if you want the buttons to be inline or not', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
				'show_if'			=> array('field_type' => array ('radio', 'checkbox')),
			),

			'select2' 	=> array(
				'label'				=> esc_html__( 'Enable Select2? (make sure to enable in Form module too)', 'divi-form-builder' ),
				'type'				=> 'yes_no_button',
				'option_category'	=> 'layout_option',
				'default'			=> 'off',
				'options'			=> array(
					'on'				=> esc_html__('Yes', 'divi-form-builder'),
					'off'				=> esc_html__('No', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'If you want to have Select2 - enable this. Make sure you enable it here but also on the parent form - which will then only load the code needed (save speed if not using)', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
				'show_if'			=> array('field_type' => array ('select')),
			),

			'checkbox_radio_inline_full' 	=> array(
				'label'				=> esc_html__( 'Show Radio Options in one line?', 'divi-form-builder' ),
				'type'				=> 'yes_no_button',
				'option_category'	=> 'layout_option',
				'default'			=> 'off',
				'options'			=> array(
					'on'				=> esc_html__('Yes', 'divi-form-builder'),
					'off'				=> esc_html__('No', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'If you want the Radio/Checkbox values to be one one line and equally take up the space, enable this. We will use display:flex to make this happen', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
				'show_if'			=> array( 'checkbox_radio_inline' => array('on') ),
			),

			'checkbox_radio_inilne_gap'                 => array(
				'label'           => esc_html__( 'Gap Between Radio/Checkbox Items', 'divi-form-builder' ),
				'description'     => esc_html__( 'Set the gap between the radio or checkbox values', 'divi-form-builder' ),
				'type'            => 'range',
				'default'          => '10px',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings'  => array(
					'min'  => '-1500',
					'max'  => '1500',
					'step' => '1',
				),
				'option_category' => 'layout_option',
				'show_if'			=> array( 'checkbox_radio_inline' => array('on')),
				'show_if_not'			=> array( 'checkbox_radio_inline_full' => array('on')),
				'toggle_slug'     => 'layout_options',
			),

			'checkbox_radio_grid_cols' 	=> array(
				'label'				=> esc_html__( 'Grid Columns', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'layout_option',
				'default'			=> '1',
				'options'			=> array(
					'1'				=> esc_html__('1 Column', 'divi-form-builder'),
					'2'				=> esc_html__('2 Columns', 'divi-form-builder'),
					'3'				=> esc_html__('3 Columns', 'divi-form-builder'),
					'4'				=> esc_html__('4 Columns', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'If you want to show the options in grid columns, please select the columns count.', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
				'show_if'			=> array( 'checkbox_radio_inline' => array('on') ),
				'show_if_not'			=> array( 'checkbox_radio_inline_full' => array('on')),
			),

			
			'radio_checkbox_padding'          => array(
				'label'           => esc_html__( 'Radio/Checkbox Padding', 'divi-form-builder' ),
				'type'           => 'custom_padding',
				'description'     => esc_html__( 'Set padding for each individual Radio/Checkbox', 'divi-form-builder' ),
				'default_unit' => 'px',
				'validate_unit' => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'margin_padding',
				'mobile_options'  => true,
			  ),
			  'radio_checkbox_margin'          => array(
				'label'           => esc_html__( 'Radio/Checkbox Margin', 'divi-form-builder' ),
				'type'           => 'custom_margin',
				'description'     => esc_html__( 'Set margin for each individual Radio/Checkbox', 'divi-form-builder' ),
				'default_unit' => 'px',
				'validate_unit' => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'margin_padding',
				'mobile_options'  => true,
			),

			'enable_placeholder' 	=> array(
				'label'				=> esc_html__( 'Enable Placeholder?', 'divi-form-builder' ),
				'type'				=> 'yes_no_button',
				'option_category'	=> 'layout_option',
				'default'			=> 'on',
				'options'			=> array(
					'on'				=> esc_html__('Yes', 'divi-form-builder'),
					'off'				=> esc_html__('No', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'If you want to enable a placeholder (default is the label text), enable this.', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options'
			),

			'field_placeholder' 	=> array(
				'label'				=> esc_html__( 'Custom Placeholder Text', 'divi-form-builder' ),
				'type'				=> 'text',
				'description'		=> esc_html__( 'By default we will get the field name as the placeholder. If you want it to be custom text, add it here.', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
				'default'			=> '',
				'option_category'	=> 'layout_option',
				'show_if' 			=> array('enable_placeholder' => 'on')
			),

			'description_text' 	=> array(
				'label'				=> esc_html__( 'Add a description?', 'divi-form-builder' ),
				'type'				=> 'yes_no_button',
				'option_category'	=> 'layout_option',
				'default'			=> 'off',
				'options'			=> array(
					'on'				=> esc_html__('Yes', 'divi-form-builder'),
					'off'				=> esc_html__('No', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'If you want to add a description for the field, enable this.', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
			),
			'description_text_location' 	=> array(
				'label'				=> esc_html__( 'Description Location', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'layout_option',
				'options'			=> array(
					'above'				=> esc_html__('Above', 'divi-form-builder'),
					'below'				=> esc_html__('Below', 'divi-form-builder'),
				),
				'default'			=> 'above',
				'description'		=> esc_html__( 'Choose where the description text appears.', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
				'show_if'			=> array('description_text' => 'on'),
			),
			'description_text_text' 	=> array(
				'label'				=> esc_html__( 'Description Text', 'divi-form-builder' ),
				'type'				=> 'text',
				'option_category'	=> 'layout_option',
				'default'			=> 'Here is your field description',
				'description'		=> esc_html__( 'Add a description for your field.', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
				'show_if'			=> array('description_text' => 'on'),
			),

			'use_icon' => array(
				'label'           => esc_html__( 'Enable an Icon on the input?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout_option',
				'toggle_slug'     => 'layout_options',
				'default'		  => 'off',
				'options'         => array(
				  'off' => esc_html__( 'No', 'divi-form-builder' ),
				  'on'  => esc_html__( 'Yes', 'divi-form-builder' ),
				),
				'description' => esc_html__( 'Enable this if you want to have an icon on the input field.', 'divi-form-builder' ),
				'default_on_front'=> 'off',
				'show_if'     => array('field_type' => array('input','email','password')),
			  ),
			  'font_icon' => array(
				  'label'               => esc_html__( 'Icon', 'divi-form-builder' ),
				  'type'                => 'select_icon',
				  'class'               => array( 'et-pb-font-icon' ),
				  'description'         => esc_html__( 'Choose the input icon', 'divi-form-builder' ),
				  'show_if'     => array('use_icon' => 'on', 'field_type' => array('input','email','password')),
				  'option_category' => 'layout_option',
				  'toggle_slug'     => 'layout_options',
				),
			  'icon_color' => array(
				  'label'               => esc_html__( 'Icon Color', 'divi-form-builder' ),
				  'type'              => 'color-alpha',
				  'description'       => esc_html__( 'Here you can define a custom color for your icon.', 'divi-form-builder' ),
				  'show_if'     => array('use_icon' => 'on', 'field_type' => array('input','email','password')),
				  'option_category' => 'layout_option',
				  'toggle_slug'     => 'layout_options',
				),
			  'icon_font_size' => array(
				  'label'               => esc_html__( 'Icon Font Size', 'divi-form-builder' ),
				  'type'            => 'range',
				  'default'         => '18px',
				  'default_unit'    => 'px',
				  'default_on_front'=> '',
				  'range_settings' => array(
				  'min'  => '1',
				  'max'  => '120',
				  'step' => '1',
				  ),
				  'show_if'     => array('use_icon' => 'on', 'field_type' => array('input','email','password')),
				  'option_category' => 'layout_option',
				  'toggle_slug'     => 'layout_options',
				),

				  'password_font_icon' => array(
					  'label'               => esc_html__( 'Password Secondary Icon', 'divi-form-builder' ),
					  'description'       => esc_html__( 'If you are using a password field - choose the secondary icon when you click to show.', 'divi-form-builder' ),
					  'type'                => 'select_icon',
					  'class'               => array( 'et-pb-font-icon' ),
					  'description'         => esc_html__( 'Choose the input icon', 'divi-form-builder' ),
					  'show_if'     => array('use_icon' => 'on', 'field_type' => array('password')),
					  'option_category' => 'layout_option',
					  'toggle_slug'     => 'layout_options',
					),
				  'password_icon_color' => array(
					  'label'               => esc_html__( 'Password Secondary Icon Color', 'divi-form-builder' ),
					  'type'              => 'color-alpha',
					  'description'       => esc_html__( 'Here you can define a custom color for your icon.', 'divi-form-builder' ),
					  'show_if'     => array('use_icon' => 'on', 'field_type' => array('password')),
					  'option_category' => 'layout_option',
					  'toggle_slug'     => 'layout_options',
					),

			'field_label_width' 	=> array(
				'label'				=> esc_html__( 'Field Label Width', 'divi-form-builder' ),
				'type'				=> 'select',
				'option_category'	=> 'layout_option',
				'default'			=> 'et_pb_column_1_4',
				'options'			=> array(
					'et_pb_column_3_4'				=> esc_html__('Three Quarter (3/4)', 'divi-form-builder'),
					'et_pb_column_2_3'				=> esc_html__('Two Thirds (2/3)', 'divi-form-builder'),
					'et_pb_column_1_2'				=> esc_html__('Half (1/2)', 'divi-form-builder'),
					'et_pb_column_1_3'				=> esc_html__('Third (1/3)', 'divi-form-builder'),
					'et_pb_column_1_4'				=> esc_html__('Quarter (1/4)', 'divi-form-builder'),
				),
				'description'		=> esc_html__( 'Select the width of the field label.', 'divi-form-builder' ),
				'toggle_slug'		=> 'layout_options',
				'show_if'			=> array( 'field_label_position' => array('left', 'right')),
			),
			'conditional_logic'          => array(
				'label'           => esc_html__( 'Enable', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'default'         => 'off',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'affects'         => array(
					'conditional_logic_rules',
					'conditional_logic_relation',
				),
				'description'     => et_get_safe_localization( __( 'Enabling conditional logic makes this field only visible when any or all of the rules below are fulfilled<br><strong>Note:</strong> Only fields with an unique and non-empty field ID can be used', 'divi-form-builder' ) ),
				'toggle_slug'     => 'conditional_logic',
			),
			'conditional_logic_relation' => array(
				'label'           => esc_html__( 'Relation', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'on'  => esc_html__( 'All', 'divi-form-builder' ),
					'off' => esc_html__( 'Any', 'divi-form-builder' ),
				),
				'default'         => 'off',
				'button_options'  => array(
					'button_type' => 'equal',
				),
				'depends_show_if' => 'on',
				'description'     => esc_html__( 'Choose whether any or all of the rules should be fulfilled', 'divi-form-builder' ),
				'toggle_slug'     => 'conditional_logic',
			),
			'conditional_logic_rules'    => array(
				'label'           => esc_html__( 'Rules', 'divi-form-builder' ),
				'type'            => 'conditional_logic',
				'option_category' => 'layout',
				'depends_show_if' => 'on',
				'toggle_slug'     => 'conditional_logic',
			),
			'date_font_icon'		=> array(
				'label'             => esc_html__( 'Date Input Icon', 'divi-form-builder' ),
				'type'              => 'select_icon',
				'class'             => array( 'et-pb-font-icon' ),
				'description'       => esc_html__( 'Choose the Date Input icon', 'divi-form-builder' ),
				'show_if'			=> array( 'field_type' => array('datepicker', 'datetimepicker') ),
				'default'			=> '&#xe023;||divi||400',
				'toggle_slug'     	=> 'date_time_app',
				'tab_slug'       => 'advanced',
			),
			'date_font_icon_size'	=> array(
				'label'               => esc_html__( 'Date Input Icon Font Size', 'divi-form-builder' ),
				'type'            => 'range',
				'default'         => '18px',
				'default_unit'    => 'px',
				'default_on_front'=> '',
				'range_settings' => array(
					'min'  => '1',
					'max'  => '120',
					'step' => '1',
				),
				'show_if'			=> array( 'field_type' => array('datepicker', 'datetimepicker') ),
				'toggle_slug'     	=> 'date_time_app',
				'tab_slug'     	 	=> 'advanced',
			),
			'date_font_icon_color' 	=> array(
				'default'           => "#666666",
				'label'             => esc_html__('Date Input Icon Color', 'divi-form-builder'),
				'type'              => 'color-alpha',
				'description'       => esc_html__('Change the color of the icon that appears on the input', 'divi-form-builder'),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'date_time_app',
				'show_if'			=> array( 'field_type' => array('datepicker', 'datetimepicker') ),
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
			'_divilayoutcontent' => array(
                'type' => 'computed',
                'computed_callback' => array( 'DE_FB_FormField', 'get_divi_layout_content' ),
                'computed_depends_on' => array(
					'field_type',
					'html_content_type',
					'html_content_divi_layout'
                ),
            ),
            '_radio_images'              => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DE_FB_FormField', 'get_radio_images' ),
				'computed_depends_on' => array(
					'radio_checkbox_image_ids',
				),
			),
			'enable_autocomplete'              => array(
				'label'           => esc_html__( 'Autocomplete field?', 'divi-form-builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'default'         => 'on',
				'options'         => array(
					'on'  => et_builder_i18n( 'Yes' ),
					'off' => et_builder_i18n( 'No' ),
				),
				'description'     => esc_html__( 'Define whether the field should be autocompleted by the browser', 'divi-form-builder' ),
				'toggle_slug'     => 'field_options',
				'show_if'			=> array( 'field_type' => array('email','password','text','datepicker','datetimepicker','input')),


			),
		);

		$taxonomy_keys = array();

		foreach ( $registered_post_types as $key => $post_type ) {
			$post_obj = get_post_type_object($key);

			$taxonomies = get_object_taxonomies( $key, 'objects' );

			if ( !empty( $taxonomies ) ) {
				$fields[ $key . '_taxonomy_field'] = array(
					'label'				=> esc_html__( 'Taxonomy Mapping', 'divi-form-builder' ),
					'type'				=> 'select',
					'default'			=> 'none',
					'option_category'	=> 'basic_option',
					'options'			=> array(
						'none'			=> esc_html__( 'No Select', 'divi-form-builder' ),
					),
					'description'		=> esc_html__( 'Choose Taxonomy field to map with field', 'divi-form-builder' ),
					'depends_show_if'	=> $key . '_taxonomy',
					'toggle_slug'		=> 'field_mapping',
				);
				$taxonomy_keys[] = $key . '_taxonomy_field';
			} else {
				$fields[ $key . '_taxonomy_field'] = array(
					'label'				=> esc_html__( 'Taxonomy Mapping', 'divi-form-builder' ),
					'type'				=> 'select',
					'default'			=> 'none',
					'option_category'	=> 'basic_option',
					'options'			=> array(
						'none'			=> esc_html__( 'No Taxonomy available.', 'divi-form-builder' ),
					),
					'description'		=> esc_html__( 'Choose Taxonomy field to map with field', 'divi-form-builder' ),
					'depends_show_if'	=> $key . '_taxonomy',
					'toggle_slug'		=> 'field_mapping',
				);
				$taxonomy_keys[] = $key . '_taxonomy_field';
			}
			foreach ($taxonomies as $tax_key => $taxonomy) {
				$fields[ $key . '_taxonomy_field']['options'][$tax_key] = $taxonomy->labels->singular_name;
			}
		}

		return $fields;
	}

	public static function get_taxonomy_options( $args = array(), $conditional_tags = array(), $current_page = array() ) {

		$field_mapping_type = $args['field_mapping_type'];
		$field_name = $args[ $field_mapping_type . '_field' ];

		$checkbox_options = array();

		$terms = get_terms( array(
		    'taxonomy' => $field_name,
		    'hide_empty' => false,
		) );

		foreach ( $terms as $inx => $term ) {
			$term_data = new stdClass();
			$term_data->id =  $term->slug;
			$term_data->checked = false;
			$term_data->value =  $term->name;
			$term_data->link_url =  get_term_link($term->term_id);
			$term_data->link_text =  $term->name;
			$checkbox_options[] = $term_data;
		}

		return json_encode( $checkbox_options );
	}

	public static function get_divi_layout_content( $args = array(), $conditional_tags = array(), $current_page = array() ) {

		$field_type = $args['field_type'];
		$html_content_type = $args['html_content_type'];
		$html_content_divi_layout = $args['html_content_divi_layout'];
		$content = "";

		if ( $field_type == "html_content" && $html_content_type == "divi_library" && !empty( $html_content_divi_layout ) ) {
			$content = apply_filters('the_content', get_post_field('post_content', $html_content_divi_layout));
		}

		return $content;
	}

	public static function get_radio_images( $args = array(), $conditional_tags = array(), $current_page = array() ) {
		$attachments = array();

		if ( isset( $args['radio_checkbox_image_ids'] ) && !empty( $args['radio_checkbox_image_ids'] ) ) {
			$image_ids = $args['radio_checkbox_image_ids'];

			$attachments_args = array(
				'include'        => $image_ids,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => 'ASC',
				'orderby'        => 'post__in',
			);

			$_attachments = get_posts( $attachments_args );

			foreach ( $_attachments as $key => $val ) {
				$attachments[ $key ]                  = $_attachments[ $key ];
				$attachments[ $key ]->image_alt_text  = get_post_meta( $val->ID, '_wp_attachment_image_alt', true );
				$attachments[ $key ]->image_src_full  = wp_get_attachment_image_src( $val->ID, 'full' );
			}
		}

		return json_encode($attachments);
	}

	public function get_alignment( $device = 'desktop' ) {
		$is_desktop = 'desktop' === $device;
		$suffix     = ! $is_desktop ? "_{$device}" : '';
		$alignment  = $is_desktop && isset( $this->props['align'] ) ? $this->props['align'] : '';

		if ( ! $is_desktop && et_pb_responsive_options()->is_responsive_enabled( $this->props, 'align' ) ) {
			$alignment = et_pb_responsive_options()->get_any_value( $this->props, "align{$suffix}" );
		}

		return et_pb_get_alignment( $alignment );
	}

	public function render( $attrs, $content, $render_slug ) {

		global $de_fb_form_num;

		$field_id 					= str_replace(" ", "_", strtolower($this->props['field_id']));
		$add_field_prefix			= isset($this->props['add_field_prefix'])?$this->props['add_field_prefix']:'on';
		$field_title				= $this->props['field_title'];
		$field_type 				= $this->props['field_type'];
		$is_google_address 			= $this->props['is_google_address'];
		$html_content_type 			= $this->props['html_content_type'];
		$select_auto_detect			= $this->props['select_auto_detect'];
		$exclude_select_options		= $this->props['exclude_select_options'];
		$checkbox_auto_detect		= $this->props['checkbox_auto_detect'];
		$exclude_checkbox_options 	= $this->props['exclude_checkbox_options'];
		$checkbox_options 			= $this->props['checkbox_options'];
		$booleancheckbox_options 	= $this->props['booleancheckbox_options'];
		$radio_auto_detect			= $this->props['radio_auto_detect'];
		$exclude_radio_options 		= $this->props['exclude_radio_options'];
		$radio_options 				= $this->props['radio_options'];
		$select_options 			= $this->props['select_options'];
		$select_placeholder 			= $this->props['select_placeholder'];
		$select_placeholder_text 			= $this->props['select_placeholder_text'];
		$select_arrow_color			= $this->props['select_arrow_color'];
		$icon_top_position			= isset($this->props['icon_top_position'])?$this->props['icon_top_position']:'20px';

		$radio_checkbox_image 			= $this->props['radio_checkbox_image'];
		$radio_checkbox_image_ids 			= $this->props['radio_checkbox_image_ids'];
		$radio_checkbox_max_width 			= $this->props['radio_checkbox_max_width'];
		$radio_show_for_image				= $this->props['radio_show_for_image'];
		$radio_image_label_position			= $this->props['radio_image_label_position'];
		$radio_checkbox_same_height			= $this->props['radio_checkbox_same_height'];

		$go_next_step_on_change 		= $this->props['go_next_step_on_change'];

		$step_prev_text 			= $this->props['step_prev_text'];
		$step_next_text 			= $this->props['step_next_text'];
		$step_icon 					= $this->props['step_icon'];

		$min_number					= $this->props['min_number'];
		$max_number					= $this->props['max_number'];
		$number_increase_step		= $this->props['number_increase_step'];

		$min_length 				= $this->props['min_length'];
		$max_length 				= $this->props['max_length'];

		$email_message 				= $this->props['email_message'];
		$minlength_message			= $this->props['minlength_message'];
		$pattern_message			= $this->props['pattern_message'];

		$allowed_symbols 			= $this->props['allowed_symbols'];
		$required_mark 				= $this->props['required_mark'];
		$required_sign 				= ( $required_mark == "on" )?'*':'';

		$required_message			= ($this->props['required_message'] != '')?$this->props['required_message']:'This is a required field.';
		$required_message_position	= $this->props['required_message_position'];
		$field_mapping_type 		= $this->props['field_mapping_type'];
		$user_default_field			= $this->props['user_default_field'];
		$acf_field 					= $this->props['acf_field'];
		$custom_meta_field_name		= trim($this->props['custom_meta_field_name']);
		$user_field_name			= trim($this->props['user_field_name']);
		$custom_field_name 			= trim($this->props['custom_field_name']);
		$field_grid_column 			= $this->props['field_grid_column']?$this->props['field_grid_column']:'et_pb_column_4_4';
		$field_label_position 		= $this->props['field_label_position'];
		$field_placeholder			= ($this->props['field_placeholder'] != '')?$this->props['field_placeholder']:$field_title;
		$enable_placeholder 		= $this->props['enable_placeholder'];
		$date_time_picker_lang		= $this->props['date_time_picker_lang'];
		$date_format		= $this->props['date_format'];
		$date_time_format		= $this->props['date_time_format'];


		$hidden_value 			= $this->props['hidden_value'];
		$hidden_value_acf 			= $this->props['hidden_value_acf'];
		$hidden_value_custom 			= $this->props['hidden_value_custom'];

		$description_text 			= $this->props['description_text'];
		$description_text_location 			= $this->props['description_text_location'];
		$description_text_text 			= $this->props['description_text_text'];

		$use_icon 			= $this->props['use_icon'];
		$font_icon 			= $this->props['font_icon'];
		$icon_color 			= $this->props['icon_color'];
		$icon_font_size 			= $this->props['icon_font_size'];

		$password_font_icon 			= $this->props['password_font_icon'];
		$password_icon_color 			= $this->props['password_icon_color'];

		$field_label_width 			= $this->props['field_label_width'];
		$post_default_field			= $this->props['post_default_field'];
		$use_wysiwyg_editor			= $this->props['use_wysiwyg_editor'];
		$show_media_button 			= isset($this->props['show_media_button'])?$this->props['show_media_button']:'on';
		$textarea_rows				= $this->props['textarea_rows'];
		$textarea_limit 			= $this->props['textarea_limit'];
		$conditional_logic          = $this->props['conditional_logic']?$this->props['conditional_logic']:'';
        $conditional_logic_relation = $this->props['conditional_logic_relation']?$this->props['conditional_logic_relation']:'';
        $conditional_logic_rules    = $this->props['conditional_logic_rules']?$this->props['conditional_logic_rules']:array();

		$file_upload_alignment				= $this->props['file_upload_alignment'];
		$upload_icon				= $this->props['upload_icon'];
		$upload_icon_style				= $this->props['upload_icon_style'];
		$icon_upload				= $this->props['icon_upload'];

		$upload_progress_bar_color				= $this->props['upload_progress_bar_color'];
		$upload_progress_bar_height				= $this->props['upload_progress_bar_height'];
		$upload_progress_bar_width				= $this->props['upload_progress_bar_width'];
		$upload_progress_bar_style				= $this->props['upload_progress_bar_style'];

		$radio_checkbox_field_style        = $this->props['radio_checkbox_field_style'];

		$icon_image_width        = $this->props['icon_image_width'];
		$icon_image_height        = $this->props['icon_image_height'];

		$upload_padding        						 = $this->props['upload_padding'];
        $upload_padding_tablet                       = $this->props['upload_padding_tablet'];
        $upload_padding_phone                        = $this->props['upload_padding_phone'];
        $upload_padding_last_edited                  = $this->props['upload_padding' . '_last_edited'];
        $upload_padding_responsive_active            = et_pb_get_responsive_status($upload_padding_last_edited);
		$upload_alt_title                            = $this->props['upload_alt_title'];
		$enable_autocomplete                            = $this->props['enable_autocomplete'];

		if ('' !== $upload_padding && '|||' !== $upload_padding) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .dropzone',
                'declaration' => sprintf(
                'padding-top: %1$s; padding-right: %2$s; padding-bottom: %3$s; padding-left: %4$s;',
                esc_attr(et_pb_get_spacing($upload_padding, 'top', '80px')),
                esc_attr(et_pb_get_spacing($upload_padding, 'right', '0px')),
                esc_attr(et_pb_get_spacing($upload_padding, 'bottom', '80px')),
                esc_attr(et_pb_get_spacing($upload_padding, 'left', '0px'))
                ),
            ));
        }

        if ( '' !== $icon_top_position ) {
        	ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .et_pb_contact_field .dfb_input_icon:after, %%order_class%% .datepicker-wrapper::after, %%order_class%% .datetimepicker-wrapper::after ',
                'declaration' => sprintf(
                'top: %1$s!important;',
                esc_attr($icon_top_position)
                ),
            ));	
        }

		$preview_dis_vertical        = $this->props['preview_dis_vertical'];
		$preview_dis_horizontal        = $this->props['preview_dis_horizontal'];


		$hide_upload_image_preview        = $this->props['hide_upload_image_preview'];
		$hide_upload_prev_title        = $this->props['hide_upload_prev_title'];
		$hide_upload_prev_size        = $this->props['hide_upload_prev_size'];
		$hide_upload_prev_progressbar        = $this->props['hide_upload_prev_progressbar'];
		$max_upload_file_size        = $this->props['max_upload_file_size'];
		$accepted_file_types_image        = $this->props['accepted_file_types_image'];
		$accepted_file_types_file        = $this->props['accepted_file_types_file'];

		$max_upload_file_counts 		= $this->props['max_upload_file_counts'];

		$max_file_counts_error 				= $this->props['max_file_counts_error'];
		$max_upload_file_size_error        = $this->props['max_upload_file_size_error'];
		$accepted_file_types_image_error        = $this->props['accepted_file_types_image_error'];
		$upload_error_hide_delay_raw        = $this->props['upload_error_hide_delay'];
		$upload_error_hide_delay        = substr($upload_error_hide_delay_raw, 0, -2);
		
		$edit_button_text        = $this->props['edit_button_text'];
		$close_edit_button_text        = $this->props['close_edit_button_text'];
		$edit_image_instructions        = $this->props['edit_image_instructions'];
		$remove_file_from_media 		= isset($this->props['remove_file_from_media'])?$this->props['remove_file_from_media']:'off';
		$remove_file_confirm_message	= $this->props['remove_file_confirm_message'];		
		
		$preview_image_width        = $this->props['preview_image_width']?$this->props['preview_image_width']:'100px';
		$preview_image_height        = $this->props['preview_image_height']?$this->props['preview_image_height']:'100px';
		$preview_bg_color        = $this->props['preview_bg_color'];
		$preview_icon_remove        = $this->props['preview_icon_remove'];
		$preview_icon_remove_color        = $this->props['preview_icon_remove_color'];

		$preview_icon_remove_size        = $this->props['preview_icon_remove_size'];

		$edit_icon_remove 	  = $this->props['edit_icon_remove'];
		$edit_icon_remove_color			= $this->props['edit_icon_remove_color'];
		$edit_icon_remove_bg_color		= $this->props['edit_icon_remove_bg_color'];
		$edit_icon_remove_font_size		= $this->props['edit_icon_remove_font_size'];

		$upload_primary_color        = $this->props['upload_primary_color'];
		$upload_secondary_color        = $this->props['upload_secondary_color'];
		$upload_quarterly_color        = $this->props['upload_quarterly_color'];

		$radio_checkbox_checked_color				= $this->props['radio_checkbox_checked_color'];
		$checkbox_radio_inline				= $this->props['checkbox_radio_inline'];
		$checkbox_radio_inline_full				= $this->props['checkbox_radio_inline_full'];
		$checkbox_radio_grid_cols				= $this->props['checkbox_radio_grid_cols'];

		$checkbox_radio_inilne_gap				= $this->props['checkbox_radio_inilne_gap'] ?$this->props['checkbox_radio_inilne_gap']: '10px';

		$upload_desc_horizontal				= $this->props['upload_desc_horizontal'] ?$this->props['upload_desc_horizontal']: '0px';
		$upload_desc_vertical				= $this->props['upload_desc_vertical'] ?$this->props['upload_desc_vertical']: '20px';

		$signature_background 				= $this->props['signature_background']?$this->props['signature_background']:'#efefef';
		$signature_pencolor 				= $this->props['signature_pencolor']?$this->props['signature_pencolor']:'#000';
		$signature_clear 					= $this->props['signature_clear']?$this->props['signature_clear']:'on';
		$signature_clear_icon 				= $this->props['signature_clear_icon']?$this->props['signature_clear_icon']:'';
		$signature_clear_icon_color 		= $this->props['signature_clear_icon_color']?$this->props['signature_clear_icon_color']:'#000';
		$signature_clear_icon_size 			= $this->props['signature_clear_icon_size']?$this->props['signature_clear_icon_size']:'18px';
		$signature_clear_icon_top 			= $this->props['signature_clear_icon_top']?$this->props['signature_clear_icon_top']:'20px';


		$radio_checkbox_padding    	= $this->props['radio_checkbox_padding'];
        $radio_checkbox_padding_tablet		= $this->props['radio_checkbox_padding_tablet'];
        $radio_checkbox_padding_phone		= $this->props['radio_checkbox_padding_phone'];

		$radio_checkbox_margin     = $this->props['radio_checkbox_margin'];
        $radio_checkbox_margin_tablet		= $this->props['radio_checkbox_margin_tablet'];
        $radio_checkbox_margin_phone		= $this->props['radio_checkbox_margin_phone'];

		// Module classnames
		$this->add_classname(
			array(
				'clearfix',
				$this->get_text_orientation_classname(),
			)
		);

		// RADIO PADDING
		if ('' !== $radio_checkbox_padding && '|||' !== $radio_checkbox_padding) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .et_pb_contact_field .et_pb_contact_field_checkbox span.label_wrapper',
                'declaration' => sprintf(
                'padding-top: %1$s; padding-right: %2$s; padding-bottom: %3$s; padding-left: %4$s;',
                esc_attr(et_pb_get_spacing($radio_checkbox_padding, 'top', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_padding, 'right', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_padding, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_padding, 'left', '0px'))
                ),
            ));
        }
        if ('' !== $radio_checkbox_padding_tablet && '|||' !== $radio_checkbox_padding_tablet) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .et_pb_contact_field .et_pb_contact_field_checkbox span.label_wrapper',
                'declaration' => sprintf(
                'padding-top: %1$s; padding-right: %2$s; padding-bottom: %3$s; padding-left: %4$s;',
                esc_attr(et_pb_get_spacing($radio_checkbox_padding_tablet, 'top', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_padding_tablet, 'right', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_padding_tablet, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_padding_tablet, 'left', '0px'))
                ),
                'media_query' => ET_Builder_Element::get_media_query('max_width_980')
            ));
        }
        if ('' !== $radio_checkbox_padding_phone && '|||' !== $radio_checkbox_padding_phone) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .et_pb_contact_field .et_pb_contact_field_checkbox span.label_wrapper',
                'declaration' => sprintf(
                'padding-top: %1$s; padding-right: %2$s; padding-bottom: %3$s; padding-left: %4$s;',
                esc_attr(et_pb_get_spacing($radio_checkbox_padding_phone, 'top', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_padding_phone, 'right', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_padding_phone, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_padding_phone, 'left', '0px'))
                ),
                'media_query' => ET_Builder_Element::get_media_query('max_width_767')
            ));
        }

		// RADIO MARGIN
		if ('' !== $radio_checkbox_margin && '|||' !== $radio_checkbox_margin) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .et_pb_contact_field .et_pb_contact_field_checkbox span.label_wrapper',
                'declaration' => sprintf(
                'margin-top: %1$s; margin-right: %2$s; margin-bottom: %3$s; margin-left: %4$s;',
                esc_attr(et_pb_get_spacing($radio_checkbox_margin, 'top', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_margin, 'right', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_margin, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_margin, 'left', '0px'))
                ),
            ));
        }
        if ('' !== $radio_checkbox_margin_tablet && '|||' !== $radio_checkbox_margin_tablet) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .et_pb_contact_field .et_pb_contact_field_checkbox span.label_wrapper',
                'declaration' => sprintf(
                'margin-top: %1$s; margin-right: %2$s; margin-bottom: %3$s; margin-left: %4$s;',
                esc_attr(et_pb_get_spacing($radio_checkbox_margin_tablet, 'top', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_margin_tablet, 'right', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_margin_tablet, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_margin_tablet, 'left', '0px'))
                ),
                'media_query' => ET_Builder_Element::get_media_query('max_width_980')
            ));
        }
        if ('' !== $radio_checkbox_margin_phone && '|||' !== $radio_checkbox_margin_phone) {
            ET_Builder_Element::set_style($render_slug, array(
                'selector'    => '%%order_class%% .et_pb_contact_field .et_pb_contact_field_checkbox span.label_wrapper',
                'declaration' => sprintf(
                'margin-top: %1$s; margin-right: %2$s; margin-bottom: %3$s; margin-left: %4$s;',
                esc_attr(et_pb_get_spacing($radio_checkbox_margin_phone, 'top', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_margin_phone, 'right', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_margin_phone, 'bottom', '0px')),
                esc_attr(et_pb_get_spacing($radio_checkbox_margin_phone, 'left', '0px'))
                ),
                'media_query' => ET_Builder_Element::get_media_query('max_width_767')
            ));
        }

		$select2				= $this->props['select2'] ?: 'off';

		$upload_description        = $this->props['upload_description'];

		if ($radio_checkbox_image == 'on') {

			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => '%%order_class%% .radio_image',
				'declaration' => "
				max-width: {$radio_checkbox_max_width} !important;
				"
				)
			);

			$this->add_classname('radio_image_cont');

			if ( $radio_show_for_image == 'off' ) {
				$this->add_classname('hide_radio');
			}

			$this->add_classname( 'radio_label_' . $radio_image_label_position );
		}

		$render_count               = $this->render_count();

		$parent_module = self::get_parent_modules('page')['de_fb_form'];

		global $post;

		$unique_id = $parent_module->props['_unique_id'];
		$form_key = $post->ID . '-' . $parent_module->render_count();

		if ( $unique_id == '' && !empty( $post ) ) {
			$unique_id = $form_key;
		}

		$de_fb_settings = get_option( 'de_fb_settings', array() );

		$form_fields = $parent_module->get_child_fields();

		if ( $field_id == str_replace(" ", "_", strtolower($form_fields[0]['field_id'])) ) {
			$de_fb_settings[$unique_id]['fields'] = array();
			$de_fb_settings[$unique_id]['file_fields'] = array();
			update_option( 'de_fb_settings', $de_fb_settings );
		}

		$form_type = $parent_module->props['form_type'];
		$multistep_enabled = $parent_module->props['multistep_enabled'] == 'on' ;

		$ignore_field_prefix = $parent_module->props['ignore_field_prefix'];
		$is_user_edit_form = $parent_module->props['is_user_edit'];

		$upload_bg_drag = $this->props['upload_bg_drag'] ?: 'transparent';
		$upload_icon_drag_animation = $this->props['upload_icon_drag_animation'];

		$icon_alignment_horizontal = $this->props['icon_alignment_horizontal'];
		if ($icon_alignment_horizontal == "") {$icon_alignment_horizontal = "50%";}
		$icon_alignment_vertical = $this->props['icon_alignment_vertical'];
		if ($icon_alignment_vertical == "") {$icon_alignment_vertical = "30px";}

		$date_font_icon 			= $this->props['date_font_icon'];
		$date_font_icon_size 		= ($this->props['date_font_icon_size'])?$this->props['date_font_icon_size']:'18px';
		$date_font_icon_color        = $this->props['date_font_icon_color'];


		$label_padding                              = $this->props['label_padding'];
        $label_padding_tablet                       = $this->props['label_padding_tablet'];
        $label_padding_phone                        = $this->props['label_padding_phone'];
		
		$description_padding                              = $this->props['description_padding'];
        $description_padding_tablet                       = $this->props['description_padding_tablet'];
        $description_padding_phone                        = $this->props['description_padding_phone'];

        $origin_width 	= $this->props['width'];
        $origin_height 	= $this->props['height'];

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

		if ($use_icon == 'on') {

			$font_icon_arr = explode('||', $font_icon);
			$font_icon_font_family = ( !empty( $font_icon_arr[1] ) && $font_icon_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
			$font_icon_font_weight = ( !empty( $font_icon_arr[2] ))?$font_icon_arr[2]:'400';
			$font_icon_dis = preg_replace( '/(&amp;#x)|;/', '', et_pb_process_font_icon( $font_icon ) );
			$font_icon_dis = preg_replace( '/(&#x)|;/', '', $font_icon_dis );


			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .dfb_input_icon::after',
				'declaration' => sprintf(
					'
					position: absolute;
					right: 6px;
					top: 0px;
					content:"\%1s";
					font-size:%2s;
					color:%3s;
					font-family:%4$s!important;
					font-weight:%5$s;
					',$font_icon_dis,
					$icon_font_size,
					$icon_color,
					$font_icon_font_family,
					$font_icon_font_weight
				),
				));

				if ($field_type == 'password') {

					$password_font_icon_arr = explode('||', $password_font_icon);
					$password_font_icon_font_family = ( !empty( $password_font_icon_arr[1] ) && $password_font_icon_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
					$password_font_icon_font_weight = ( !empty( $password_font_icon_arr[2] ))?$password_font_icon_arr[2]:'400';
					$password_font_icon_dis = preg_replace( '/(&amp;#x)|;/', '', et_pb_process_font_icon( $password_font_icon ) );
					$password_font_icon_dis = preg_replace( '/(&#x)|;/', '', $password_font_icon_dis );



					ET_Builder_Element::set_style($render_slug, array(
						'selector'    => '%%order_class%% .input_password',
						'declaration' => 'cursor: pointer;'
					));

					ET_Builder_Element::set_style($render_slug, array(
						'selector'    => '%%order_class%% .dfb_input_icon.show_password::after',
						'declaration' => sprintf(
							'
							position: absolute;
							right: 6px;
							top: 0px;
							content:"\%1s";
							font-size:%2s;
							color:%3s;
							font-family:%4$s!important;
							font-weight:%5$s;
							',$password_font_icon_dis,
							$icon_font_size,
							$password_icon_color,
							$font_icon_font_family,
							$font_icon_font_weight
							),
							));


				}

		}

		$min_length      = intval( $min_length );
		$max_length      = intval( $max_length );
		$max_length_attr = '';
		$symbols_pattern = '.';
		$length_pattern  = '*';
		$pattern         = '';

		if ( $field_type != 'step' ) {
			$this->add_classname('grid_'. $field_grid_column . '_12');
			$this->add_classname('dfb_radio_'. $radio_checkbox_field_style);
		}

		if ($radio_checkbox_field_style == "button") {
			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%%.dfb_radio_button .et_pb_contact_field[data-type="radio"] label',
				'declaration' => sprintf(
					'font-size: 16px;'
				),
			));

			/* Radio/Checkbox Button Icon */
			$custom_radio_checkbox_button 		= $this->props['custom_radio_checkbox_button']; //Use Custom Style For Radio/Checkbox Button
			$radio_checkbox_button_use_icon 	= $this->props['radio_checkbox_button_use_icon']; // Show Radio/Checkbox Button Icon
			$radio_checkbox_button_icon 		= $this->props['radio_checkbox_button_icon']; //Icon
			$radio_checkbox_button_icon_color	= $this->props['radio_checkbox_button_icon_color']; //Icon
			$radio_checkbox_button_icon_placement	= $this->props['radio_checkbox_button_icon_placement'];
			$radio_checkbox_button_on_hover		= $this->props['radio_checkbox_button_on_hover'];


			if( $custom_radio_checkbox_button == 'on' && $radio_checkbox_button_use_icon !== 'off'){
				if( $radio_checkbox_button_icon !== '' ){

					$button_icon_arr = explode('||', $radio_checkbox_button_icon);

					$button_icon_font_family = ( !empty( $button_icon_arr[1] ) && $button_icon_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
					$button_icon_font_weight = ( !empty( $button_icon_arr[2] ))?$button_icon_arr[2]:'400';

					$iconContent = DE_FormBuilder::et_icon_css_content( esc_attr($radio_checkbox_button_icon) );
					$iconSelector = '';

					if ( $radio_checkbox_button_on_hover == 'on' ) {
						$iconSelector = '%%order_class%%.dfb_radio_button .et_pb_contact_field_checkbox label:hover';
					}else{
						$iconSelector = '%%order_class%%.dfb_radio_button .et_pb_contact_field_checkbox label';
					}
					if( $radio_checkbox_button_icon_placement == 'left' ){
						$iconSelector = $iconSelector . ':before';
					} else  {
						$iconSelector = $iconSelector . ':after';
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
							color: {$radio_checkbox_button_icon_color};
							display: inline-block;"
							)
						);
					}
				}
			}

			$custom_radio_checkbox_button_active 		= $this->props['custom_radio_checkbox_button_active']; //Use Custom Style For Radio/Checkbox Button
			$radio_checkbox_button_active_use_icon 	= $this->props['radio_checkbox_button_active_use_icon']; // Show Radio/Checkbox Button Icon
			$radio_checkbox_button_active_icon 		= $this->props['radio_checkbox_button_active_icon']; //Icon
			$radio_checkbox_button_active_icon_color	= $this->props['radio_checkbox_button_active_icon_color']; //Icon
			$radio_checkbox_button_active_icon_placement	= $this->props['radio_checkbox_button_active_icon_placement'];
			$radio_checkbox_button_active_on_hover		= $this->props['radio_checkbox_button_active_on_hover'];

			if( $custom_radio_checkbox_button_active == 'on' && $radio_checkbox_button_active_use_icon !== 'off'){

				if( $radio_checkbox_button_active_icon !== '' ){

					$button_icon_arr = explode('||', $radio_checkbox_button_active_icon);

					$button_icon_font_family = ( !empty( $button_icon_arr[1] ) && $button_icon_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
					$button_icon_font_weight = ( !empty( $button_icon_arr[2] ))?$button_icon_arr[2]:'400';

					$iconContent = DE_FormBuilder::et_icon_css_content( esc_attr($radio_checkbox_button_active_icon) );
					$iconSelector = '';

					if ( $radio_checkbox_button_active_on_hover == 'on' ) {
						$iconSelector = '%%order_class%%.dfb_radio_button .et_pb_contact_field_checkbox input:checked + label:hover';
					}else{
						$iconSelector = '%%order_class%%.dfb_radio_button .et_pb_contact_field_checkbox input:checked + label';
					}

					if( $radio_checkbox_button_active_icon_placement == 'left' ){
						$iconSelector = $iconSelector . ':before';
					} else {
						$iconSelector = $iconSelector . ':after';
					}
					if( !empty( $iconContent ) && !empty( $iconSelector ) ){
						ET_Builder_Element::set_style( $render_slug, array(
							'selector' => $iconSelector,
							'declaration' => "content: '{$iconContent}'!important;
							font-family:{$button_icon_font_family}!important;
							font-weight:{$button_icon_font_weight};
							display: inline-block;
							line-height: inherit;
							opacity:1!important;
							font-size: inherit!important;
							color: {$radio_checkbox_button_active_icon_color};
							display: inline-block;"
							)
						);
					}
				}
			}
		}

		$custom_edit_preview_button 		= $this->props['custom_edit_preview_button'];
		$edit_preview_button_use_icon 	= $this->props['edit_preview_button_use_icon'];
		$edit_preview_button_icon 		= $this->props['edit_preview_button_icon'];
		$edit_preview_button_icon_color	= $this->props['edit_preview_button_icon_color'];
		$edit_preview_button_icon_placement	= $this->props['edit_preview_button_icon_placement'];
		$edit_preview_button_on_hover		= $this->props['edit_preview_button_on_hover'];

		if( $custom_edit_preview_button == 'on' && $edit_preview_button_use_icon !== 'off'){

			if( $edit_preview_button_icon !== '' ){

				$button_icon_arr = explode('||', $edit_preview_button_icon);

				$button_icon_font_family = ( !empty( $button_icon_arr[1] ) && $button_icon_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
				$button_icon_font_weight = ( !empty( $button_icon_arr[2] ))?$button_icon_arr[2]:'400';

				$iconContent = DE_FormBuilder::et_icon_css_content( esc_attr($edit_preview_button_icon) );
				$iconSelector = '';

				if ( $edit_preview_button_on_hover == 'on' ) {
					$iconSelector = '%%order_class%%.dfb_radio_button .et_pb_contact_field_checkbox input:checked + label:hover';
				}else{
					$iconSelector = '%%order_class%%.dfb_radio_button .et_pb_contact_field_checkbox input:checked + label';
				}

				if( $edit_preview_button_icon_placement == 'left' ){
					$iconSelector = $iconSelector . ':before';
				} else {
					$iconSelector = $iconSelector . ':after';
				}
				if( !empty( $iconContent ) && !empty( $iconSelector ) ){
					ET_Builder_Element::set_style( $render_slug, array(
						'selector' => $iconSelector,
						'declaration' => "content: '{$iconContent}'!important;
						font-family:{$button_icon_font_family}!important;
						font-weight:{$button_icon_font_weight};
						display: inline-block;
						line-height: inherit;
						opacity:1!important;
						font-size: inherit!important;
						color: {$edit_preview_button_icon_color};
						display: inline-block;"
						)
					);
				}
			}
		}

		$accepted_file_types = '';


		if ( $field_type == 'file' || $field_type == 'image' ) {

			$preview_icon_remove_arr = explode('||', $preview_icon_remove);
			$preview_icon_remove_font_family = ( !empty( $preview_icon_remove_arr[1] ) && $preview_icon_remove_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
			$preview_icon_remove_font_weight = ( !empty( $preview_icon_remove_arr[2] ))?$preview_icon_remove_arr[2]:'400';
			$preview_icon_remove_dis = preg_replace( '/(&amp;#x)|;/', '', et_pb_process_font_icon( $preview_icon_remove ) );
			$preview_icon_remove_dis = preg_replace( '/(&#x)|;/', '', $preview_icon_remove_dis );

			$edit_icon_remove_arr = explode('||', $edit_icon_remove);
			$edit_icon_remove_font_family = ( !empty( $edit_icon_remove_arr[1] ) && $edit_icon_remove_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
			$edit_icon_remove_font_weight = ( !empty( $edit_icon_remove_arr[2] ))?$edit_icon_remove_arr[2]:'400';
			$edit_icon_remove_dis = preg_replace( '/(&amp;#x)|;/', '', et_pb_process_font_icon( $edit_icon_remove ) );
			$edit_icon_remove_dis = preg_replace( '/(&#x)|;/', '', $edit_icon_remove_dis );

			if ( $field_type == 'file' ) {
				$accepted_file_types = implode('|', explode(',', $accepted_file_types_file ) );
			} else {
				$accepted_file_types = implode('|', explode(',', $accepted_file_types_image ) );
			}



			if ($upload_progress_bar_width !== '') {
				ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .file_upload_item .progress',
					'declaration' => sprintf(
						'
						width:%1s;
						',$upload_progress_bar_width
						)
				));
			}

			if ($upload_progress_bar_height !== '') {
				ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .file_upload_item .progress',
					'declaration' => sprintf(
						'
						height:%2s;
						',$upload_progress_bar_height
						)
				));
			}

			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .file_upload_item .progress-bar',
				'declaration' => sprintf(
					'
					background-color:%1s;
					',$upload_progress_bar_color
				)
			));


			if ($preview_icon_remove_size == '') {
				$preview_icon_remove_size = '30px';
			}

			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .template-upload .cancel i:before, %%order_class%% .remove_upload:before',
				'declaration' => sprintf(
					'
					content:"\%1s";
					font-size:%2s;
					color:%3s;
					font-family:%4$s!important;
					font-weight:%5$s;
					',$preview_icon_remove_dis,
					$preview_icon_remove_size,
					$preview_icon_remove_color,
					$preview_icon_remove_font_family,
					$preview_icon_remove_font_weight
				),
			));

			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .file_preview_container .files, %%order_class%% .file_upload_item',
				'declaration' => sprintf(
					'background-color:%1s;',
					$preview_bg_color
				),
			));

			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .existing_image_preview .remove-file:before',
				'declaration' => sprintf(
					'
					content:"\%1s";
					font-size:%2s;
					color:%3s;
					font-family:%4$s!important;
					font-weight:%5$s;
					',$edit_icon_remove_dis,
					$edit_icon_remove_font_size,
					$edit_icon_remove_color,
					$preview_icon_remove_font_family,
					$preview_icon_remove_font_weight
				),
			));

			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .existing_image_preview .remove-file',
				'declaration' => sprintf(
					'
					background-color:%1s;
					',$edit_icon_remove_bg_color
				),
			));



			$this->add_classname('file_align_'. $file_upload_alignment);
			$transform = '';
			$transform_desc = '';
            if ($icon_image_width == "") {
                $icon_image_width = "50px";
			}

			if ($icon_alignment_horizontal == "50%") {
                $transform = "transform: translateX(-50%);";
			}


			if ($upload_desc_horizontal == "50%") {
                $transform_desc = "transform: translateX(-50%);";
			}

			if ($icon_image_height == "") {
                $icon_image_height = "50px";
            }

			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '
				%%order_class%% .field_wrapper .image_upload_result, 
				%%order_class%% .field_wrapper .file_upload_result
				',
				'declaration' => sprintf(
				  'width: %1$s;
				  height: %2$s;
					%3$s: %4$s;
					top: %5$s;
					%6$s',
				  	$icon_image_width,
				  	$icon_image_height,
					$file_upload_alignment,
					$icon_alignment_horizontal,
					$icon_alignment_vertical,
					$transform
				),
			  ));


				ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .drop-description',
					'declaration' => sprintf(
						'top: %1$s!important;
						%2$s: %3$s!important;
						%4$s',
						$upload_desc_vertical,
						$file_upload_alignment,
						$upload_desc_horizontal,
						$transform_desc
					),
					));


					ET_Builder_Element::set_style($render_slug, array(
						'selector'    => '%%order_class%% .field_wrapper.drag-over',
						'declaration' => sprintf(
							'background-color: %1$s;',
							$upload_bg_drag
						),
					));


			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .field_wrapper svg .primary',
				'declaration' => sprintf(
					'
					fill: '.$upload_primary_color.' !important;
					'
				),
			));

			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .field_wrapper svg .secondary',
				'declaration' => sprintf(
					'
					fill: '.$upload_secondary_color.' !important;
					'
				),
			));

			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .field_wrapper svg .quarterly',
				'declaration' => sprintf(
					'
					fill: '.$upload_quarterly_color.' !important;
					'
				),
			));

			if ($upload_icon_drag_animation == "wobble-hor-bottom") {
				ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .drag-over #file_upload',
					'declaration' => sprintf(
						'
						-webkit-animation: wobble-hor-bottom 0.8s both;
						animation: wobble-hor-bottom 0.8s both;
						'
					),
				));
			} else if ($upload_icon_drag_animation == "vibrate-1") {
				ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .drag-over #file_upload',
					'declaration' => sprintf(
						'
						-webkit-animation: vibrate-1 0.3s linear 4 both;
						animation: vibrate-1 0.3s linear 4 both;
						'
					),
				));
			} else if ($upload_icon_drag_animation == "shake-horizontal") {
				ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .drag-over #file_upload',
					'declaration' => sprintf(
						'
						-webkit-animation: shake-horizontal 0.8s cubic-bezier(0.455, 0.030, 0.515, 0.955) both;
						animation: shake-horizontal 0.8s cubic-bezier(0.455, 0.030, 0.515, 0.955) both;
						'
					),
				));
			} else if ($upload_icon_drag_animation == "jello-horizontal") {
				ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .drag-over #file_upload',
					'declaration' => sprintf(
						'
						-webkit-animation: jello-horizontal 0.9s both;
						animation: jello-horizontal 0.9s both;
						'
					),
				));
			} else if ($upload_icon_drag_animation == "heartbeat") {
				ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .drag-over #file_upload',
					'declaration' => sprintf(
						'
						-webkit-animation: heartbeat 1.5s ease-in-out both;
						animation: heartbeat 1.5s ease-in-out both;
						'
					),
				));
			}

			if ($preview_dis_vertical == '') {
				$preview_dis_vertical = '20px';
			}

			if ($preview_dis_horizontal == '') {
				$preview_dis_horizontal = '0';
			}

			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .field_wrapper.image_uploaded .image_upload_result',
				'declaration' => sprintf(
					'
					width: '.$preview_image_width.' !important;
					height: '.$preview_image_height.' !important;
					top: '.$preview_dis_vertical.' !important;
					left: '.$preview_dis_horizontal.' !important;
					'
				),
			));

			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => '%%order_class%% .file_preview_container .template-upload .preview canvas',
				'declaration' => sprintf(
					'
					width: '.$preview_image_width.' !important;
					height: '.$preview_image_height.' !important;
					'
				),
			));

			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-widget' );
			wp_enqueue_script( 'de_fb_tmpl' );
			wp_enqueue_script( 'de_fb_load_image' );
			wp_enqueue_script( 'de_fb_iframe_transport' );
			wp_enqueue_script( 'de_fb_file_upload' );
			wp_enqueue_script( 'de_fb_file_upload_process' );
			wp_enqueue_script( 'de_fb_file_upload_image' );
			wp_enqueue_script( 'de_fb_file_upload_validate' );
			wp_enqueue_script( 'de_fb_file_upload_ui' );
			wp_enqueue_style( 'de_fb_file_upload' );
			wp_localize_script( 'de_fb_js', 'ajax_object', array( 'ajax_url' => admin_url('admin-ajax.php') ) );
			wp_enqueue_style( 'de_fb_file_upload_ui' );
		}

		if ( $field_type == 'signature' ) {
			wp_enqueue_script( 'de_fb_signature' );
			wp_localize_script( 'de_fb_signature', 'fb_signature', array( 'signature_objs' => [] ) );
		}

		if ( $field_type == 'datepicker' || $field_type == 'datetimepicker') {
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
    		wp_enqueue_style( 'jquery-ui' );
			wp_localize_script( 'de_fb_js', 'datepicker_arg', array( 'img_url' => DE_FB_URL . '/images/calendar.png' ) );



			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => 'body .ui-widget-header',
				'declaration' => 'border: none;
					background: transparent;'
			));
			
			$date_font_icon_arr = explode('||', $date_font_icon);
			$date_font_icon_font_family = ( !empty( $date_font_icon_arr[1] ) && $date_font_icon_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
			$date_font_icon_font_weight = ( !empty( $date_font_icon_arr[2] ))?$date_font_icon_arr[2]:'400';
			$date_font_icon_dis = preg_replace( '/(&amp;#x)|;/', '', et_pb_process_font_icon( $date_font_icon ) );
			$date_font_icon_dis = preg_replace( '/(&#x)|;/', '', $date_font_icon_dis );


			ET_Builder_Element::set_style($render_slug, array(
				'selector'    => 'body %%order_class%% .datepicker-wrapper::after, body %%order_class%% .datetimepicker-wrapper::after',
				'declaration' => sprintf(
					'
					content:"\%1s";
					font-size:%2s!important;
					font-family:%3$s!important;
					font-weight:%4$s!important;
					color:%5$s!important;
					',$date_font_icon_dis,
					$date_font_icon_size,
					$date_font_icon_font_family,
					$date_font_icon_font_weight,
					$date_font_icon_color
				),
			));
		}

		if ( $field_type == 'datetimepicker' ) {
			wp_enqueue_script( 'jquery-ui-datetimepicker', DE_FB_URL . '/js/jquery.ui.datetimepicker/jquery-ui-timepicker-addon.js', 'jquery-ui-core' );
		}

		if ( $field_type == 'checkbox' || $field_type == 'radio') {
			if ($radio_checkbox_field_style == "default") {

				ET_Builder_Element::set_style($render_slug, array(
					'selector'    => 'form %%order_class%% p input[type=radio]:checked+label i:before,
					form %%order_class%% p input[type=checkbox]:checked+label i:before',
					'declaration' => 'color: '.$radio_checkbox_checked_color.' !important;'
				));

			} else {

				ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .et_pb_contact_field_checkbox i',
					'declaration' => 'display: none !important;'
				));

			}

			if ($checkbox_radio_inline == "on") {

				if ($checkbox_radio_inline_full == "on") {

					ET_Builder_Element::set_style($render_slug, array(
						'selector'    => '%%order_class%% .et_pb_contact_field_options_list',
						'declaration' => 'display:flex;flex-wrap:wrap;'
					));

				} else {

					ET_Builder_Element::set_style($render_slug, array(
						'selector'    => '%%order_class%% .et_pb_contact_field_options_list',
						'declaration' => 'display: grid; grid-template-columns: repeat(' . $checkbox_radio_grid_cols . ',minmax(0,1fr));grid-gap: '.$checkbox_radio_inilne_gap.';'
					));

				}

			}

		}

		ET_Builder_Element::set_style( $render_slug, array(
			'selector' => '%%order_class%% .select2-container--default .select2-selection--single .select2-selection__arrow b, %%order_class%% .et_pb_contact_field[data-type=select]:after',
			'declaration' => "border-top-color: " . $select_arrow_color . " !important;",
		) );

		if ( $field_type == 'select') {
			if ($select2 == "on") {
				
				
				

				ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .et_pb_contact_field',
					'declaration' => 'display: flex;flex-direction: column-reverse;'
				));

				ET_Builder_Element::set_style($render_slug, array(
					'selector'    => '%%order_class%% .et_pb_contact_field[data-type=select]:after',
					'declaration' => 'display: none !important;'
				));

			}

		}

		if ( !wp_script_is( 'de_fb_js', 'enqueued' ) ) {
			$fb_js_obj = array( 'ajax_url' => admin_url('admin-ajax.php') );
			wp_localize_script( 'de_fb_js', 'de_fb_ajax_obj', $fb_js_obj );
			wp_enqueue_script('de_fb_js');	
		}

		

		if ( in_array( $allowed_symbols, array( 'letters', 'numbers', 'alphanumeric' ) ) ) {
			switch ( $allowed_symbols ) {
				case 'letters':
					$symbols_pattern = '[A-Z|a-z|a-å|a-ö|w-я|\s-]';
					//$title           = __( 'Only letters allowed.', 'divi-form-builder' );
					break;
				case 'numbers':
					$symbols_pattern = '[0-9\s-]';
					//$title           = __( 'Only numbers allowed.', 'divi-form-builder' );
					break;
				case 'alphanumeric':
					$symbols_pattern = '[A-Z|a-z|a-å|a-ö|w-я|0-9|\s-]';
					//$title           = __( 'Only letters and numbers allowed.', 'divi-form-builder' );
					break;
			}
		}

		if ( 0 !== $min_length && 0 !== $max_length ) {
			$max_length = max( $min_length, $max_length );
			$min_length = min( $min_length, $max_length );

			if ( $max_length > 0 ) {
				$max_length_attr = sprintf(
					' maxlength="%1$d"',
					$max_length
				);
			}
		}

		if ( 0 !== $min_length || 0 !== $max_length ) {
			$length_pattern = '{';

			if ( 0 !== $min_length ) {
				$length_pattern .= $min_length;
				//$title          .= sprintf( __( 'Minimum length: %1$d characters. ', 'divi-form-builder' ), $min_length );
			}

			if ( 0 === $min_length ) {
				$length_pattern .= '0';
			}

			if ( 0 === $max_length ) {
				$length_pattern .= ',';
			}

			//$title = '';

			if ( 0 !== $max_length ) {
				$length_pattern .= ",{$max_length}";
				//$title          .= sprintf( __( 'Maximum length: %1$d characters.', 'divi-form-builder' ), $max_length );
			}

			$length_pattern .= '}';
		}

		if ( '.' !== $symbols_pattern ) {
			if ( $min_length === 0 ) {
				$pattern = sprintf(
					' pattern="%1$s%2$s"',
					esc_attr( $symbols_pattern ),
					esc_attr( $length_pattern )
				);	
			} else {
				$pattern = sprintf(
					' pattern="%1$s*"',
					esc_attr( $symbols_pattern )
				);
			}			
		}

		$conditional_logic_attr = '';



		/*if ( 'on' === $conditional_logic && ! empty( $conditional_logic_rules ) ) {
			$option_search           = array( '&#91;', '&#93;' );
			$option_replace          = array( '[', ']' );
			$conditional_logic_rules = str_replace( $option_search, $option_replace, $conditional_logic_rules );
			$condition_rows          = json_decode( $conditional_logic_rules );
			$ruleset                 = array();

			foreach ( $condition_rows as $condition_row ) {
				$condition_value = isset( $condition_row->value ) ? $condition_row->value : '';
				$condition_value = trim( $condition_value );

				if ( isset( $de_fb_settings[$unique_id]['fields']['de_fb_' . $condition_row->field] ) ) {
					$ruleset[] = array(
						'de_fb_' . $condition_row->field,
						$condition_row->condition,
						$condition_value,
					);
				} else if ( isset( $de_fb_settings[$unique_id]['fields'][$condition_row->field] ) ) {
					$ruleset[] = array(
						$condition_row->field,
						$condition_row->condition,
						$condition_value,
					);
				}
			}

			if ( ! empty( $ruleset ) ) {
				$json     = json_encode( $ruleset );
				$relation = $conditional_logic_relation === 'off' ? 'any' : 'all';

				$conditional_logic_attr = sprintf(
					' data-conditional-logic="%1$s" data-conditional-relation="%2$s"',
					esc_attr( $json ),
					$relation
				);
			}
		}*/

		$pid = !empty($_REQUEST['pid'])?$_REQUEST['pid']:'';

		$submit_result = get_query_var('df_submit_result');
		$submit_form_key = get_query_var( 'df_submit_formkey' );

		ob_start();

		$field_width = "et_pb_column_3_4";

		if ( $field_type != 'step' ) {
			$this->add_classname(
				array(
					$field_grid_column,
					'et_pb_column',
				)
			);

			if ($field_grid_column == "et_pb_column_4_4") {
				$field_grid_column_num = "100";
			} else if ($field_grid_column == "et_pb_column_3_4") {
				$field_grid_column_num = "75";
			} else if ($field_grid_column == "et_pb_column_2_3") {
				$field_grid_column_num = "60";
			} else if ($field_grid_column == "et_pb_column_1_2") {
				$field_grid_column_num = "50";
			} else if ($field_grid_column == "et_pb_column_1_3") {
				$field_grid_column_num = "40";
			} else if ($field_grid_column == "et_pb_column_1_4") {
				$field_grid_column_num = "25";
			} else {
				$field_grid_column_num = "100";
			}

			switch ( $field_label_width ) {
				case 'et_pb_column_3_4':
					$field_width = "et_pb_column_1_4";
					break;
				case 'et_pb_column_2_3':
					$field_width = "et_pb_column_1_3";
					break;
				case 'et_pb_column_1_2':
					$field_width = "et_pb_column_1_2";
					break;
				case 'et_pb_column_1_3':
					$field_width = "et_pb_column_2_3";
					break;
				case 'et_pb_column_1_4':
					$field_width = "et_pb_column_3_4";
					break;
				default:
					// code...
					break;
			}
		}

		$field_name = '';
		$field_value = '';

		$featured_image_field = false;

		if ( $field_mapping_type == 'acf' ) {
			if ( $acf_field != 'none' ) {
				$acf_field_object = get_field_object( $acf_field );
				if ( $acf_field_object ) {
					$field_name = $acf_field_object['name'];	
				}				
			}
		} else if ( $field_mapping_type == 'custom' ) {
			$field_name = $custom_field_name;
		} else {
			if ( $form_type == 'register' || $form_type == 'login' ) { // If form type is 
				if ( $field_mapping_type == 'user_meta' ) {
					$field_name = $user_field_name;
				} else if ( $field_mapping_type == 'user_default' ) {
					$field_name = $user_default_field;
				}
			} else {
				if ( $field_mapping_type == 'default' ) {
					$field_name = $post_default_field;

					if ( $field_type == 'image' && $post_default_field == 'post_thumbnail' ) {
						$featured_image_field = true;
					}
				} else if ( $field_mapping_type == 'custom_meta' ) {
					$field_name = $custom_meta_field_name;
				} else {
					$field_name = $this->props[ $field_mapping_type . '_field' ];
				}
			}
		}

		//SB: edit form - preload field_value

		$remove_field_name = '';
        $remove_field_type = 'post';

		if ( !empty( $pid ) ) {

			$post_object = get_post( $pid );

			//echo $field_name . '<br />';
			//echo $pid . '<br />';
			//echo $field_mapping_type . '<br />';

			if ( ($post_object instanceof WP_post) && $post_object->post_type == $form_type) {
				if ( $field_mapping_type == 'acf' && !empty( $field_name )) {

					$remove_field_name = $field_name;
					$field_value = get_post_meta( $pid, $field_name, true );
					$field_object = get_field_object( $acf_field, $pid );

					if ( $field_object['type'] == 'google_map' ) {
						$address_array = get_field( $field_name, $pid );
						$field_value = $address_array['address'];
					}

					if (!$field_value) {
						if ($field_object) {
							$field_value = [];
							if ( isset( $field_object['sub_fields'] ) ) {
								foreach ($field_object['sub_fields'] as $sub_field) {
									if ($sub_field['type'] == 'image') {
										$acf_val = get_field( $field_name . '_' . $sub_field['name'], $pid );

										if ( $sub_field['return_format'] == 'url' ) {
											$acf_val = get_post_meta( $pid, $field_name . '_' . $sub_field['name'], true );
										} else if ( $sub_field['return_format'] == 'array' ) {
											if (!empty( $acf_val ) && is_array( $acf_val) ) {
												$acf_val = $acf_val['ID'];
											}
										}

										if ( !empty( $acf_val ) ) {
											$field_value[] = $acf_val;											
										}
									}
								}
							}
						}
		      		}
				} else if ( $field_mapping_type != 'custom' ) {
					if ( $form_type != 'register' && $form_type != 'login' ) { // If form type is
						if ( $field_mapping_type == 'default' ) {
							//SB: get_post_field only returns data in the $post object. Add more field_name checks here based on other irregular items we might want to pull
							if ($field_name == 'post_thumbnail') {
								$field_value = get_post_thumbnail_id($pid);
								
								$remove_field_name = 'post_thumbnail';
							} else {
								$field_value = get_post_field( $field_name, $pid );
								$remove_field_name = $field_name;
							}
						} else if ( $field_mapping_type == 'custom_meta' ) {
							$field_value = get_post_meta( $pid, $field_name );
							$remove_field_name = $field_name;
						} else {
							$field_value = wp_get_post_terms( $pid, $field_name, array( 'fields' => 'slugs' ) );
						}
					}
				}

				if ( is_array( $field_value ) && count( $field_value ) == 1 ) {
					$field_value = $field_value[0];
				}
			}
		}

		$current_user = wp_get_current_user();

		if ( $form_type == 'register' && $is_user_edit_form == 'on' && 0 != $current_user->ID ) {
			$remove_field_type = 'user';
			$user_metadata = get_user_meta( $current_user->ID, '', true );
			if ( $field_mapping_type == 'user_default' && $field_type != 'password' ){
				$field_value = $current_user->$user_default_field;
			} else if ( $field_mapping_type == 'user_meta' || $field_mapping_type == 'acf' ) {
				$remove_field_name = $field_name;
				if ( !empty( $user_metadata[$field_name]) ) {
					$field_value = $user_metadata[ $field_name ][0];
					$field_value = maybe_unserialize( $field_value );
				}
			}
		}

		$conditional_logic_attr = '';
        $conditional_display = '';

        if ( 'on' === $conditional_logic && ! empty( $conditional_logic_rules ) ) {
            $option_search           = array( '&#91;', '&#93;' );
            $option_replace          = array( '[', ']' );
            $conditional_logic_rules = str_replace( $option_search, $option_replace, $conditional_logic_rules );
            $condition_rows          = json_decode( $conditional_logic_rules );
            $ruleset                 = array();

            foreach ( $condition_rows as $condition_row ) {
                $condition_value = isset( $condition_row->value ) ? $condition_row->value : '';
                $condition_value = trim( $condition_value );

                if ( isset( $de_fb_settings[$unique_id]['fields']['de_fb_' . $condition_row->field] ) ) {
					$ruleset[] = array(
						'de_fb_' . $condition_row->field,
						$condition_row->condition,
						$condition_value,
					);
				} else if ( isset( $de_fb_settings[$unique_id]['fields'][$condition_row->field] ) ) {
					$ruleset[] = array(
						$condition_row->field,
						$condition_row->condition,
						$condition_value,
					);
				}
            }

            if ( ! empty( $ruleset ) ) {
                $json     = json_encode( $ruleset );
                $relation = $conditional_logic_relation === 'off' ? 'any' : 'all';

                $conditional_logic_attr = sprintf(
                    ' data-conditional-logic="%1$s" data-conditional-relation="%2$s"',
                    esc_attr( $json ),
                    $relation
                );

				ET_Builder_Element::set_style( $render_slug, array(
					'selector' => '%%order_class%%',
					'declaration' => "
					display:none;
					"
				));

                $conditional_display = '';
            }
        }

		if ( $field_name == '' ) {
			$field_name = $field_id;
		}

		if ( !( $form_type == 'custom' && $ignore_field_prefix == 'on' ) ) {
			if ( $add_field_prefix != 'off' ) {
				$field_name = "de_fb_" . $field_name;
				$field_id = "de_fb_" . $field_id;
			}
		}

		if ( !empty( $submit_result ) && $submit_result == 'failed' && $submit_form_key == $form_key ) {
			if ( isset( $_POST[$field_name] ) && $_POST[$field_name] != '' ) {
				$field_value = $_POST[$field_name];
			}
		}

        if($multistep_enabled){
            $current_step = 1;
            if ( $field_type == 'step' ) {
                if ( !empty( $de_fb_settings[$unique_id]['fields'] ) ) {
                    foreach ( $de_fb_settings[$unique_id]['fields'] as $field_key => $field_val ) {
                    	if ( strpos( $field_key , 'df_step_' ) === 0 ){
                    		$step_render_count = intval(str_replace($field_key . '_', '', $field_val));
	                        if ( $step_render_count < $render_count ) {
	                            $current_step++;
	                        } else {
	                        	unset( $de_fb_settings[$unique_id]['fields'][$field_key] );
	                        }
                    	}
                    }
                }
                $field_id = 'df_step_' . $current_step;
                $field_name = $field_id . '_' . $render_count;
            }

            if ( $go_next_step_on_change == 'on' && ( $field_type == 'select' || $field_type == 'radio' ) ) {
	        	$this->add_classname( 'next_on_change' );
	        }
        }

		$de_fb_settings[$unique_id]['fields'][$field_id] = $field_name;

		if ( $field_type == "file" || $field_type == "image" ) {
			$de_fb_settings[$unique_id]['file_fields'][$field_id] = $field_name;
		}
		update_option( 'de_fb_settings', $de_fb_settings );
?>
<?php 
		if( $multistep_enabled ){
            if ( $field_type == 'step' ) {
                    $this->add_classname('empty_module');
?>
				</div>
			    </div>
<?php
				if ( $current_step != 1 ) {
?>
                <div class="step_button_wrapper">
                <?php if ( $current_step != 2 ) { ?>
                    <button class="et_pb_button df_step_button df_step_prev" data-step="<?php echo $current_step - 1;?>"><?php echo $step_prev_text;?></button>
                <?php } ?>
				<button class="et_pb_button df_step_button df_step_next" data-step="<?php echo $current_step + 1;?>"><?php echo $step_next_text;?></button>
                </div>
                </div>
<?php
				}
?>				
		        <div class="df_form_step df_step_<?php echo $current_step;?> <?php echo ($current_step == 1)?'active':'';?>" <?php echo ($current_step != 1)?'data-prev_text="' . $step_prev_text . '"':'';?> data-next_text="<?php echo $step_next_text;?>">
			    <div class="empty_module">
				<div class="empty_module_inner">
<?php
                $result = ob_get_clean();
                return $result;
	     	}
		} else if($field_type == 'step'){
			$this->add_classname('empty_module');
			ob_clean();
            return '';
        }


		$additional_classes = '';

		if ( $field_type == 'checkbox' || $field_type == 'radio' ) {
			if ( $radio_checkbox_image == 'on' && $radio_checkbox_same_height == 'on' ) {
				$additional_classes = 'equal_height';
		}
			}

		?>
		<div id="<?php echo $field_id . '_wrapper';?>" class="field_wrapper search_filter_cont <?php echo $additional_classes;?>" data-count="<?php echo $field_grid_column_num ?>" <?php echo $conditional_logic_attr;?>>
		<?php 

		if ($field_type == "html_content") {


			if ($html_content_type == "code") {

				$html_content_code = $this->props['html_content_code'];

				echo $html_content_code;

			} else if ($html_content_type == "text") {

				$this->add_classname('dfb_content_text_field');

				$html_content_editor = $this->props['html_content_editor'];
				// Un-autop converted GB block comments
				$html_content_editor = preg_replace( '/(<p>)?<!-- (\/)?divi:(.+?) (\/?)-->(<\/p>)?/', '<!-- $2divi:$3 $4-->', $html_content_editor );

				// Convert GB embeds to iframes
				$html_content_editor = preg_replace_callback(
					'/<!-- divi:core-embed\/youtube {"url":"([^"]+)"[\s\S]+?<!-- \/divi:core-embed\/youtube -->/',
						array( $this, 'convert_embeds' ),
						$html_content_editor
				);

				echo $html_content_editor;
			} else if ($html_content_type == "divi_library") {

				$html_content_divi_layout 	= $this->props['html_content_divi_layout'];

				$loop_layout = apply_filters('the_content', get_post_field('post_content', $html_content_divi_layout));
				echo $loop_layout;

			}

		} else {
            if ($field_label_position == 'left' || $field_label_position == 'right') {
                ?>
			<span class="field_row">
			<?php if ($field_label_position == "left") { ?>
				<label for="<?php echo $field_id;?>" class="et_pb_column field_label label_position_<?php echo $field_label_position;?> <?php echo $field_label_width;?>"><?php echo $field_title . '<span class="de_fb_required">'.$required_sign.'</span>';?></label>
			<?php } ?>
				<span class="et_pb_column <?php echo $field_width; ?>">
			<?php
            } else if ($field_label_position == 'top') {
                ?>
			<label for="<?php echo $field_id; ?>" class="field_label"><?php echo $field_title . '<span class="de_fb_required">'.$required_sign.'</span>'; ?></label>
			<?php
            } ?>
			<?php
			if ($description_text == 'on' && $description_text_location == 'above') {
				?>
				<span class="df_field_description_text"><?php echo esc_html($description_text_text) ?></span>
				<?php
			}
			?>
			<p class="et_pb_contact_field" data-type="<?php echo esc_html($field_type) ?>">
			<?php
                if ((!in_array($form_type, array('register', 'login', 'contact', 'custom')) && $field_mapping_type == 'acf') || $field_mapping_type == 'custom_meta') {
                    ?>
					<input type="hidden" name="meta_input[]" value="<?php echo $field_name; ?>">
					<?php
                } elseif (substr($field_mapping_type, -9) == '_taxonomy') {
                    ?>
					<input type="hidden" name="tax_input[]" value="<?php echo $field_name; ?>">
					<?php
                } elseif ($field_mapping_type == 'user_meta' || (in_array($form_type, array('register', 'login')) && $field_mapping_type == 'acf')) {
                    ?>
					<input type="hidden" name="user_meta[]" value="<?php echo $field_name; ?>">
					<?php
                }

            if ($form_type == 'contact' || $form_type == 'custom') {
                echo '<input type="hidden" name="field_title[]" value="' . $field_title . '"/>';
                echo '<input type="hidden" name="field_name[]" value="' . $field_name . '"/>';
                echo '<input type="hidden" name="field_id[]" value="' . $field_id . '"/>';
            }


			if ($hidden_value == 'page_name' || $hidden_value == '') {

				global $wp_query;
				$post = $wp_query->get_queried_object();
				$pagename = $post->post_name;
				$hiddenValue = $pagename;

			} else if ($hidden_value == 'page_url') {

				$hiddenValue = get_page_link();

			} else if ($hidden_value == 'acf') {

				$acf_field_object = get_field_object( $hidden_value_acf );
				$acf_hidden_value = $acf_field_object['value'];
				$hiddenValue = $acf_hidden_value;

			} else if ($hidden_value == 'custom') {

				$hiddenValue = $hidden_value_custom;

			} else {

			}

			if ($field_label_position == 'none') {
				$field_placholder = $field_placeholder . $required_sign;
			} else {
				$field_placholder = $field_placeholder;
			}

			if ($enable_placeholder == 'off') {
				$field_placholder = '';
			}

			if ($radio_checkbox_field_style == 'button') {
				$css_class_label = 'et_pb_button';
			} else {
				$css_class_label = '';
			}

            switch ($field_type) {
                    case 'input':
                    case 'email':
                    case 'password':
                    	if ( is_array( $field_value ) ) {
                    		$field_value = implode(',', $field_value );
                    	}
                        $input_type = ($field_type == 'input')?'text':$field_type;
                        $pattern = ($field_type == 'input' || $field_type == 'password')?$pattern:'';

                        $required_data = '';

		            	if ( 'on' == $required_mark ) {
		            		$required_data = ' data-msg-required="' . htmlentities($required_message) . '" data-required_position="' . $required_message_position . '"';
		            	}

		            	if ( 0 != $min_length ) {
		            		$required_data .= ' data-rule-minlength="' . $min_length . '" data-msg-minlength="' . $minlength_message . '"';
		            	}

		            	if ( $pattern != '' ) {
		            		$required_data .= ' data-msg-pattern="' . $pattern_message . '"';
		            	}

		            	if ( $field_type == 'email' ) {
		            		$required_data .= ' data-msg-email="' . $email_message . '"';
		            	}

		            	$additional_classes = '';
		            	if ( $field_type == 'input' && $is_google_address == 'on') {
		            		$additional_classes = 'de_fb_autocomplete';

		            		wp_dequeue_script('google-maps-api');
		                    add_filter( 'et_pb_enqueue_google_maps_script', '__return_false' );

		                    if ( wp_script_is( 'dmach_js_googlemaps_script') ) {
		                    	wp_dequeue_script('dmach_js_googlemaps_script');
		                    }

		                    wp_enqueue_script('de_fb_googlemaps_script');

		                    if ( !in_array($form_type, array('login', 'contact', 'custom')) && $field_mapping_type == 'acf' ) {
		                    	if ( $acf_field != 'none' ) {
		                    		$acf_field_object = get_field_object( $acf_field );
		                    		if ( $acf_field_object && $acf_field_object['type'] == 'google_map' ) {
?>
										<input type="hidden" name="<?php echo $field_name;?>[lat]" class="de_fb_lat"/>
										<input type="hidden" name="<?php echo $field_name;?>[lng]" class="de_fb_lng"/>
<?php		                    			
										$field_name = $field_name . "[address]";
		                    		}
		                    	}
		                    }
						}
						$readonly = '';
						if ( $form_type == 'register' && $is_user_edit_form == 'on' && 0 != $current_user->ID && $field_mapping_type == 'user_default' && $user_default_field == 'user_login' ) {
							$readonly = 'readonly';
						}
                        
                        ?>
							<input type="<?php echo $input_type;?>" name="<?php echo $field_name;?>" id="<?php echo $field_id;?>" <?php echo 'placeholder="'. $field_placholder .'"';?> <?php echo $pattern . ' ' . $max_length_attr;?> class="divi-form-builder-field input-field <?php echo $additional_classes;?>" <?php echo 'off' === $required_mark ? '' : 'required';?> value="<?php echo $field_value;?>" <?php echo $required_data;?> autocomplete="<?php echo $enable_autocomplete;?>" <?php echo $readonly;?>/>
						<?php
						if ($use_icon == 'on') {
							?> <span class="dfb_input_icon input_<?php echo $field_type ?>"></span> <?php
						}
                        break;
                    case 'number':
                    	?>
                    	<input type="number" name="<?php echo $field_name;?>" id="<?php echo $field_id;?>" <?php echo $max_length_attr;?>  <?php echo 'placeholder="'. $field_placholder .'"';?>class="divi-form-builder-field input-field" value="<?php echo $field_value;?>" <?php echo !empty($max_number)?'max="' . $max_number .'"':'';?> <?php echo !empty($min_number)?'min="' . $min_number .'"':'';?> <?php echo !empty($number_increase_step)?'step="' . $number_increase_step .'"':'';?>/>
                    	<?php
                    	break;
                    case 'hidden':
                        ?>
							<input type="hidden" name="<?php echo $field_name;?>" id="<?php echo $field_id;?>" <?php echo $max_length_attr;?>  class="divi-form-builder-field input-field" value="<?php echo $hiddenValue;?>"/>
						<?php
                        break;
                    case 'text':

                    	if ( is_array( $field_value ) ) {
                    		$field_value = implode(',', $field_value);
                    	}

                    	$required_data = '';

		            	if ( 'on' == $required_mark ) {
		            		$required_data = ' data-msg-required="' . htmlentities($required_message) . '" data-required_position="' . $required_message_position . '"';
		            	}
                    	
                        if ($use_wysiwyg_editor == 'on') {
                            if ( $textarea_limit !== '') {
                        ?>
                            	<span class="<?php echo $field_id;?>_limit" data-val="<?php echo $textarea_limit;?>"></span>
                        <?php
                            	add_filter('tiny_mce_before_init', function( $initArray, $field_id ){
                        			$initArray['setup'] = "function(ed) {ed.onKeyDown.add(function(ed, e) {if(tinyMCE.activeEditor.id=='{$field_id}') {var content = tinyMCE.activeEditor.getContent();var max = $('.{$field_id}_limit').data('val');var len = content.length;if (len >= max) {e.stopPropagation();e.preventDefault();}}});}";
									return $initArray;
                            	}, 10, 2);
                            }

												//////////////////////////////////////////////////////////////////////////////////////////
												//This section covers the tinymce load and init when wysiwyg is used

												// Define the editor settings
												$settings = [
													'textarea_name' => $field_name,
													'textarea_rows' => $textarea_rows,
													'tinymce'=>[
														'toolbar1'      => 'formatselect | forecolor,pastetext removeformat charmap | undo redo | bold,italic,underline,alignleft,aligncenter,alignright',
														'toolbar2'      => '',
														'toolbar3'      => '',
													],
													'media_buttons'	=> true
												];

												if ( $show_media_button == 'off' ) {
													$settings['media_buttons']	= false;
												}

												// Create the editor
	                      						ob_start();
												wp_editor($field_value, $field_id, $settings);

												//add these scripts to enqueue and load in tinymce
												_WP_Editors::enqueue_scripts();
												print_footer_scripts();
												_WP_Editors::editor_js();

	                      						$editor = ob_get_clean();

						            //really horrible I know but there is a form tag inside the editor code which we need to remove as it's already in a form and breaks the page
						            echo str_replace(['<form', '</form'], ['<aform', '</aform'], $editor);

												//////////////////////////////////////////////////////////////////////////////////////////

                        } else {
                        	$limit_attr = '';
                        	if ( $textarea_limit !== '') {
                        		$limit_attr = ' maxlength="' . $textarea_limit . '"';
                        	}

?>
							<textarea name="<?php echo $field_name; ?>" id="<?php echo $field_id; ?>" class="divi-form-builder-field textarea-field" <?php echo 'off' === $required_mark ? '' : 'required';?> <?php echo 'placeholder="'. $field_placholder .'"'; ?> rows="<?php echo $textarea_rows; ?>" <?php echo $limit_attr;?> <?php echo $required_data;?> autocomplete="<?php echo $enable_autocomplete;?>"><?php echo $field_value; ?></textarea>
							<?php
                        }
                        break;
                    case 'checkbox':
                        $input_field = '';

                        $required_data = ' data-required_message="' . htmlentities($required_message) . '" data-required_position="' . $required_message_position . '"';

                        if ($checkbox_auto_detect == 'off') {
							// MERGE - should this be commented out??
							
                            /*if (! $checkbox_options) {
                                $is_checked       = ! empty($checkbox_checked) && 'on' === $checkbox_checked;
                                $checkbox_options = sprintf(
                                    '[{"value":"%1$s","checked":%2$s}]',
                                    esc_attr($field_title),
                                    $is_checked ? 1 : 0
                                );
                                $field_title      = '';
                            }*/

                            $option_search    = array( '&#91;', '&#93;' );
                            $option_replace   = array( '[', ']' );
                            $checkbox_options = str_replace($option_search, $option_replace, $checkbox_options);
                            $checkbox_options = json_decode($checkbox_options);

                            $radio_checkbox_image_ids_array = array();
                            if ($radio_checkbox_image == 'on') {
                            	$radio_checkbox_image_ids_array = explode(',', $radio_checkbox_image_ids);
							}

                            foreach ($checkbox_options as $key => $option) {
							
                                $checkbox_options[$key]->id = $option->value;
                                if ( $radio_checkbox_image == 'on' ) {
                                	if ( isset( $radio_checkbox_image_ids_array[ $key ] ) && $radio_checkbox_image_ids_array[ $key ] != '' ) {
                                		$image = wp_get_attachment_image_src( $radio_checkbox_image_ids_array[ $key ], 'full' );
                                		$checkbox_options[$key]->image = $image[0];
                                	}
                                }
                            }

                        } else {
                            $checkbox_options = array();
                            if (strpos($field_mapping_type, '_taxonomy') !== false) {
                                $terms = get_terms(array(
                                    'taxonomy' => str_replace("de_fb_", "", $field_name),
                                    'hide_empty' => false,
                                ));

                                foreach ($terms as $inx => $term) {
                                    $term_data = new stdClass();
                                    $term_data->id =  $term->slug;
                                    $term_data->checked = false;
                                    $term_data->value =  $term->name;
                                    $term_data->link_url =  '';//get_term_link($term->term_id);
                                    $term_data->link_text =  '';//$term->name;
                                    array_push($checkbox_options, $term_data);
                                }
                            } elseif ($field_mapping_type == 'acf') {
                                if ($acf_field != 'none' && function_exists( 'get_field_object' ) ) {
                                    $acf_object = get_field_object($acf_field);
                                    $select_types = array('checkbox', 'radio', 'select');
                                    if ( !empty( $acf_object ) && isset( $acf_object['type'] ) && in_array($acf_object['type'], $select_types )) {
                                    	if ( is_array( $acf_object ) && isset( $acf_object['choices'] ) ) {
                                    		foreach ($acf_object["choices"] as $key => $choice) {
	                                            $choice_data = new stdClass();
	                                            $choice_data->id = $key;
	                                            $choice_data->checked = false;
	                                            $choice_data->value = $choice;
	                                            array_push( $checkbox_options, $choice_data);
	                                        }
                                    	}
                                    } else if ( $acf_object['type'] == 'post_object' ) {
                                    	$post_args = array(
                                    		'post_type'		=> $acf_object['post_type'],
                                    		'posts_per_page'	=> -1,
                                    		'post_status'		=> 'publish'
                                    	);

                                    	if ( !empty( $acf_object['taxonomy'] ) ) {
                                    		$tax_query = array(
                                    			'relation'	=> 'AND'
                                    		);
                                    		foreach ( $acf_object['taxonomy'] as $taxonomy ) {
                                    			list( $tax_name, $tax_slug ) = explode(':', $taxonomy );
                                    			$tax_query[] = array(
                                    				'taxonomy'	=> $tax_name,
                                    				'field'		=> 'slug',
                                    				'terms'		=> $tax_slug
                                    			);
                                    		}

                                    		$post_args['tax_query']	= $tax_query;
                                    	}

                                    	$post_objects = get_posts( $post_args );

                                    	foreach ($post_objects as $postObj) {
                                    		$choice_data = new stdClass();
                                    		$choice_data->id = $postObj->ID;
                                            $choice_data->checked = false;
                                            $choice_data->value = $postObj->post_title;
                                            array_push( $checkbox_options, $choice_data );
                                    	}
                                    }
                                }
                            } elseif ($field_mapping_type == 'user_default' && $field_name == 'de_fb_role') {
                                global $wp_roles;

                                $all_roles = $wp_roles->roles;
                                $editable_roles = apply_filters('editable_roles', $all_roles);

                                foreach ($editable_roles as $key => $role) {
                                    $term_data = new stdClass();
                                    $term_data->id =  $key;
                                    $term_data->checked = false;
                                    $term_data->value =  $role["name"];
                                    array_push($checkbox_options, $term_data);
                                }
                            } elseif ($field_mapping_type == 'default' && $field_name == 'de_fb_post_status') {
                                $post_status_objects = get_post_stati(array(), 'objects');

                                foreach ($post_status_objects as $name => $obj) {
                                    $term_data = new stdClass();
                                    $term_data->id = $name;
                                    $term_data->checked = false;
                                    $term_data->value = $obj->label;
                                    array_push($checkbox_options, $term_data);
                                }
                            }

                            $radio_checkbox_image_ids_array = array();
                            if ($radio_checkbox_image == 'on') {
                            	$radio_checkbox_image_ids_array = explode(',', $radio_checkbox_image_ids);
							}

                            foreach ($checkbox_options as $key => $option) {
                                if ( $radio_checkbox_image == 'on' ) {
                                	if ( isset( $radio_checkbox_image_ids_array[ $key ] ) && $radio_checkbox_image_ids_array[ $key ] != '' ) {
                                		$image = wp_get_attachment_image_src( $radio_checkbox_image_ids_array[ $key ], 'full' );
                                		$checkbox_options[$key]->image = $image[0];
                                	}
                                }
                            }
                        }

                        $exclude_options_array = array();

                        if ($checkbox_auto_detect == 'on' && $exclude_checkbox_options != '' ) {
                        	$exclude_options_array = explode(',', $exclude_checkbox_options );
                    	}
                
                        foreach ($checkbox_options as $index => $option) {

                        	if ( !empty($exclude_options_array) && in_array( $option->id, $exclude_options_array ) ) {
                    			continue;
                    		}

                        	$is_checked   = 1 === $option->checked ? true : false;
                            $option_value = !empty($option->id)?wp_strip_all_tags($option->id):$index;
                            $drag_id      = isset($option->dragID) ? $option->dragID : '';
                            $option_id    = isset($option->id) ? $option->id : $drag_id;
                            $option_id    = sprintf(' data-id="%1$s"', esc_attr($option_id));
                            $option_label = wp_strip_all_tags($option->value);

                            $option_link  = '';
                            $option_image = isset($option->image) ? '<img class="radio_image" src="'.$option->image.'">' : '';

                            if (!empty($field_value)) {
                                if (!is_array($field_value)) {
                                    $field_value = array($field_value);
                                }
                                if (in_array($option_value, $field_value)) {
                                    $is_checked = true;
                                } else {
                                    $is_checked = false;
                                }
                            }

                            if (! empty($option->link_url)) {
                                $link_text   = isset($option->link_text) ? $option->link_text : '';
                                $option_link = sprintf(' <a href="%1$s" target="_blank">%2$s</a>', esc_url($option->link_url), esc_html($link_text));
                            }

                            // The required field needs a value, use link information if the option value is empty
                            if ('off' !== $required_mark && empty($option_value) && ! empty($option_link)) {
                                $option_value = isset($option->link_text) && ! empty($option->link_text) ? esc_html($option->link_text) : esc_url($option->link_url);
                            }

                            $input_field .= sprintf(
								'<span class="et_pb_contact_field_checkbox">
									<input type="checkbox" id="et_pb_contact_%1$s_%5$s_%3$s" class="divi-form-builder-field checkbox-field" name="%8$s[]" value="%2$s"%4$s%6$s>
									<label class="%9$s" for="et_pb_contact_%1$s_%5$s_%3$s">
										<i></i>
										<span class="label_wrapper">
											<span>%7$s</span>
											%11$s%10$s
										</span>
									</label>
								</span>',
                                esc_attr($field_id),
                                esc_attr($option_value),
                                esc_attr($index),
                                $is_checked ? ' checked="checked"' : '',
                                esc_attr($render_count), // #5
                                $option_id,
                                $option_label, // #7
                                $field_name,
								$css_class_label,
								$option_link,
								$option_image
                            );
                        }

                        $input_field = sprintf(
                            '<span class="et_pb_contact_field_options_wrapper">
								<span class="et_pb_contact_field_options_list %2$s" %3$s>%1$s</span>
							</span>',
                            $input_field,
                            ($required_mark=="on")?'required':'',
                            $required_data
                        );

                        echo $input_field;

                        break;
                    case 'radio':
                        $input_field = '';

                        $required_data = ' data-required_message="' . htmlentities($required_message) . '" data-required_position="' . $required_message_position . '"';

                        if ($radio_auto_detect == 'off') {
                            /*if (! $radio_options) {

                                $radio_options = sprintf(
                                    '[{"value":"%1$s"}]',
                                    esc_attr($field_title),
                                    0
                                );
                                $field_title      = '';
                            }*/


                            $option_search    = array( '&#91;', '&#93;' );
                            $option_replace   = array( '[', ']' );
                            $radio_options = str_replace($option_search, $option_replace, $radio_options);
                            $radio_options = json_decode($radio_options);

                            $radio_checkbox_image_ids_array = array();
                            if ($radio_checkbox_image == 'on') {
                            	$radio_checkbox_image_ids_array = explode(',', $radio_checkbox_image_ids);
							}

                            foreach ($radio_options as $key => $option) {

                                $radio_options[$key]->id = $option->value;
                                if ( $radio_checkbox_image == 'on' ) {
                                	if ( isset( $radio_checkbox_image_ids_array[ $key ] ) && $radio_checkbox_image_ids_array[ $key ] != '' ) {
                                		$image = wp_get_attachment_image_src( $radio_checkbox_image_ids_array[ $key ], 'full' );
                                		$radio_options[$key]->image = $image[0];
                                	}
                                }
                            }


                        } else {
                            $radio_options = array();
                            if (strpos($field_mapping_type, '_taxonomy') !== false) {
                                $terms = get_terms(array(
                                    'taxonomy' => str_replace("de_fb_", "", $field_name),
                                    'hide_empty' => false,
                                ));

                                $term_data = array();

                                foreach ($terms as $inx => $term) {
                                    $term_data = new stdClass();
                                    $term_data->id =  $term->slug;
                                    $term_data->checked = false;
                                    $term_data->value =  $term->name;
                                    $term_data->link_url =  get_term_link($term->term_id);
                                    $term_data->link_text =  $term->name;
                                    array_push($radio_options, $term_data);
                                }
                            } elseif ($field_mapping_type == 'acf') {
                                if ($acf_field != 'none') {
                                    $acf_field_object = get_field_object($acf_field);
                                    if (in_array($acf_field_object['type'], array('checkbox', 'radio', 'select'))) {
                                        foreach ($acf_field_object['choices'] as $key => $choice) {
                                            $choice_data = new stdClass();
                                            $choice_data->id = $key;
                                            $choice_data->checked = false;
                                            $choice_data->value = $choice;
                                            array_push($radio_options, $choice_data);
                                        }
                                    }else if ( $acf_field_object['type'] == 'post_object' ) {
                                    	$post_args = array(
                                    		'post_type'		=> $acf_field_object['post_type'],
                                    		'posts_per_page'	=> -1,
                                    		'post_status'		=> 'publish'
                                    	);

                                    	if ( !empty( $acf_field_object['taxonomy'] ) ) {
                                    		$tax_query = array(
                                    			'relation'	=> 'AND'
                                    		);
                                    		foreach ( $acf_field_object['taxonomy'] as $taxonomy ) {
                                    			list( $tax_name, $tax_slug ) = explode(':', $taxonomy );
                                    			$tax_query[] = array(
                                    				'taxonomy'	=> $tax_name,
                                    				'field'		=> 'slug',
                                    				'terms'		=> $tax_slug
                                    			);
                                    		}

                                    		$post_args['tax_query']	= $tax_query;
                                    	}

                                    	$post_objects = get_posts( $post_args );

                                    	foreach ($post_objects as $postObj) {
                                    		$choice_data = new stdClass();
                                    		$choice_data->id = $postObj->ID;
                                            $choice_data->checked = false;
                                            $choice_data->value = $postObj->post_title;
                                            array_push( $radio_options, $choice_data );
                                    	}
                                    }
                                }
                            } elseif ($field_mapping_type == 'user_default' && $field_name == 'de_fb_role') {
                                global $wp_roles;

                                $all_roles = $wp_roles->roles;
                                $editable_roles = apply_filters('editable_roles', $all_roles);

                                foreach ($editable_roles as $key => $role) {
                                    $term_data = new stdClass();
                                    $term_data->id =  $key;
                                    $term_data->checked = false;
                                    $term_data->value =  $role['name'];
                                    array_push($radio_options, $term_data);
                                }
                            } elseif ($field_mapping_type == 'default' && $field_name == 'de_fb_post_status') {
                                $post_status_objects = get_post_stati(array(), 'objects');

                                foreach ($post_status_objects as $name => $obj) {
                                    $term_data = new stdClass();
                                    $term_data->id = $name;
                                    $term_data->checked = false;
                                    $term_data->value = $obj->label;
                                    array_push($radio_options, $term_data);
                                }
                            }

                            $radio_checkbox_image_ids_array = array();
                            if ($radio_checkbox_image == 'on') {
                            	$radio_checkbox_image_ids_array = explode(',', $radio_checkbox_image_ids);
							}

                            foreach ($radio_options as $key => $option) {
                                if ( $radio_checkbox_image == 'on' ) {
                                	if ( isset( $radio_checkbox_image_ids_array[ $key ] ) && $radio_checkbox_image_ids_array[ $key ] != '' ) {
                                		$image = wp_get_attachment_image_src( $radio_checkbox_image_ids_array[ $key ], 'full' );
                                		$radio_options[$key]->image = $image[0];
                                	}
                                }
                            }
                        }

                        $exclude_options_array = array();

                        if ($radio_auto_detect == 'on' && $exclude_radio_options != '' ) {
                        	$exclude_options_array = explode(',', $exclude_radio_options );
                    	}
                
                        foreach ($radio_options as $index => $option) {

                        	if ( !empty($exclude_options_array) && in_array( $option->id, $exclude_options_array ) ) {
                    			continue;
                    		}

                            $is_checked   = !empty($option->checked) && 1 === $option->checked ? true : false;
                            $option_value = !empty($option->id)?wp_strip_all_tags($option->id):$index;
                            $drag_id      = isset($option->dragID) ? $option->dragID : '';
                            $option_id    = isset($option->id) ? $option->id : $drag_id;
                            $option_id    = sprintf(' data-id="%1$s"', esc_attr($option_id));
                            $option_label = wp_strip_all_tags($option->value);
							$option_link  = '';
							$option_image = isset($option->image) ? '<img class="radio_image" src="'.$option->image.'">' : '';

                            if (!empty($field_value)) {
                                if (!is_array($field_value)) {
                                    $field_value = array($field_value);
                                }
                                if (in_array($option_value, $field_value)) {
                                    $is_checked = true;
                                } else {
                                    $is_checked = false;
                                }
                            }

                            if (! empty($option->link_url)) {
                                $link_text   = isset($option->link_text) ? $option->link_text : '';
                                $option_link = sprintf(' <a href="%1$s" target="_blank">%2$s</a>', esc_url($option->link_url), esc_html($link_text));
                            }

                            // The required field needs a value, use link information if the option value is empty
                            if ('off' !== $required_mark && empty($option_value) && ! empty($option_link)) {
                                $option_value = isset($option->link_text) && ! empty($option->link_text) ? esc_html($option->link_text) : esc_url($option->link_url);
                            }

                            $input_field .= sprintf(
								'<span class="et_pb_contact_field_checkbox">
									<input type="radio" id="et_pb_contact_%1$s_%5$s_%3$s" class="divi-form-builder-field radio-field" name="%8$s" value="%2$s"%4$s%6$s>
									<label class="%9$s" for="et_pb_contact_%1$s_%5$s_%3$s">
										<i></i>
										<span class="label_wrapper">
											<span>%7$s</span>
											%10$s
										</span>
									</label>
								</span>',
                                esc_attr($field_id),
                                esc_attr($option_value),
                                esc_attr($index),
                                $is_checked ? ' checked="checked"' : '',
                                esc_attr($render_count), // #5
                                $option_id,
                                $option_label, // #7
                                $field_name,
								$css_class_label,
								$option_image
                            );
                        }

                        $input_field = sprintf(
                            '<span class="et_pb_contact_field_options_wrapper">
								<span class="et_pb_contact_field_options_list %2$s" %3$s>%1$s</span>
							</span>',
                            $input_field,
                            ($required_mark=="on")?'required':'',
                            $required_data
                        );

                        echo $input_field;

                        break;
                    case 'select':
                        $select_field = '';

                        $is_multiple = false;

                        if ($select_auto_detect == 'off') {
                            if (! $select_options) {
                                $select_options = sprintf(
                                    '[{"value":"%1$s"}]',
                                    esc_attr($field_title),
                                    0
                                );
                                $field_title      = '';
                            }

                            $option_search    = array( '&#91;', '&#93;' );
                            $option_replace   = array( '[', ']' );
                            $select_options = str_replace($option_search, $option_replace, $select_options);
                            $select_options = json_decode($select_options);
                        } else {
                            $select_options = array();
                            if (strpos($field_mapping_type, '_taxonomy') !== false) {
                                $terms = get_terms(array(
                                    'taxonomy' => str_replace("de_fb_", "", $field_name),
                                    'hide_empty' => false,
                                ));

                                $term_data = array();

                                foreach ($terms as $inx => $term) {
                                    $term_data = new stdClass();
                                    $term_data->id =  $term->slug;
                                    $term_data->checked = false;
                                    $term_data->value =  $term->name;
                                    $term_data->link_url =  get_term_link($term->term_id);
                                    $term_data->link_text =  $term->name;
                                    array_push($select_options, $term_data);
                                }
                            } elseif ($field_mapping_type == 'acf') {
                                if ($acf_field != 'none') {
                                    $acf_field_object = get_field_object($acf_field);
                                    if (in_array($acf_field_object['type'], array('checkbox', 'radio', 'select'))) {
                                        foreach ($acf_field_object['choices'] as $key => $choice) {
                                            $choice_data = new stdClass();
                                            $choice_data->id = $key;
                                            $choice_data->checked = false;
                                            $choice_data->value = $choice;
                                            array_push($select_options, $choice_data);
                                        }
                                    } else if ( $acf_field_object['type'] == 'post_object' ) {
                                    	$post_args = array(
                                    		'post_type'		=> $acf_field_object['post_type'],
                                    		'posts_per_page'	=> -1,
                                    		'post_status'		=> 'publish'
                                    	);

                                    	if ( !empty( $acf_field_object['taxonomy'] ) ) {
                                    		$tax_query = array(
                                    			'relation'	=> 'AND'
                                    		);
                                    		foreach ( $acf_field_object['taxonomy'] as $taxonomy ) {
                                    			list( $tax_name, $tax_slug ) = explode(':', $taxonomy );
                                    			$tax_query[] = array(
                                    				'taxonomy'	=> $tax_name,
                                    				'field'		=> 'slug',
                                    				'terms'		=> $tax_slug
                                    			);
                                    		}

                                    		$post_args['tax_query']	= $tax_query;
                                    	}

                                    	$post_objects = get_posts( $post_args );

                                    	foreach ($post_objects as $postObj) {
                                    		$choice_data = new stdClass();
                                    		$choice_data->id = $postObj->ID;
                                            $choice_data->checked = false;
                                            $choice_data->value = $postObj->post_title;
                                            array_push($select_options, $choice_data);
                                    	}

                                    	if ( $acf_field_object['multiple'] == 1 ) {
                                    		$is_multiple = true;
                                    	}
                                    }
                                }
                            } elseif ($field_mapping_type == 'user_default' && $field_name == 'de_fb_role') {
                                global $wp_roles;

                                $all_roles = $wp_roles->roles;
                                $editable_roles = apply_filters('editable_roles', $all_roles);

                                foreach ($editable_roles as $key => $role) {
                                    $term_data = new stdClass();
                                    $term_data->id =  $key;
                                    $term_data->checked = false;
                                    $term_data->value =  $role['name'];
                                    array_push($select_options, $term_data);
                                }
                            } elseif ($field_mapping_type == 'default' && $field_name == 'de_fb_post_status') {
                                $post_status_objects = get_post_stati(array(), 'objects');

                                foreach ($post_status_objects as $name => $obj) {
                                    $term_data = new stdClass();
                                    $term_data->id = $name;
                                    $term_data->checked = false;
                                    $term_data->value = $obj->label;
                                    array_push($select_options, $term_data);
                                }
                            }
                        }

                        $exclude_options_array = array();

                        if ($select_auto_detect == 'on' && $exclude_select_options != '' ) {
                        	$exclude_options_array = explode(',', $exclude_select_options );
                    	}
                
                        foreach ($select_options as $index => $option) {

                        	if ( !empty($exclude_options_array) && in_array( $option->id, $exclude_options_array ) ) {
                    			continue;
                    		}
                
                            $is_checked   = (!empty($option->checked) && 1 === $option->checked) ? true : false;
                            $option_value = !empty($option->id)?wp_strip_all_tags($option->id):wp_strip_all_tags($option->value);
                            $drag_id      = isset($option->dragID) ? $option->dragID : '';
                            $option_id    = isset($option->id) ? $option->id : $drag_id;
                            $option_id    = sprintf(' data-id="%1$s"', esc_attr($option_id));
                            $option_label = wp_strip_all_tags($option->value);
                            $option_link  = '';

                            if (!empty($field_value)) {
                                if (!is_array($field_value)) {
                                    $field_value = array($field_value);
                                }
                                if (in_array($option_value, $field_value)) {
                                    $is_checked = true;
                                } else {
                                    $is_checked = false;
                                }
                            }

                            if (! empty($option->link_url)) {
                                $link_text   = isset($option->link_text) ? $option->link_text : '';
                                $option_link = sprintf(' <a href="%1$s" target="_blank">%2$s</a>', esc_url($option->link_url), esc_html($link_text));
                            }

                            // The required field needs a value, use link information if the option value is empty
                            if ('off' !== $required_mark && empty($option_value) && ! empty($option_link)) {
                                $option_value = isset($option->link_text) && ! empty($option->link_text) ? esc_html($option->link_text) : esc_url($option->link_url);
                            }

                            $selected = $is_checked ? 'selected' : '';

                            $select_field .= sprintf(
                                '<option value="%1$s" %3$s>%2$s</option>',
                                esc_attr($option_value),
                                esc_attr($option_label),
                                $selected
                            );
                        }

						if ($select_placeholder == 'on') {
							$select_placeholder_dis = '<option value="" disabled selected>'.$select_placeholder_text.'</option>';
						} else {
							$select_placeholder_dis = '';
						}

                        $select_field = sprintf(
                            '<select class="divi-form-builder-field select-field et_pb_contact_select %5$s" %6$s %8$s name="%3$s" data-field_type="%2$s" data-msg-required="%9$s" id="%1$s">
							%7$s
							%4$s
							</select>',
                            esc_attr($field_id),
                            esc_attr($field_type),
                            $field_name,
                            $select_field,
                            ($select2 == "on")?"select2":"",
                            ($required_mark=="on")?"required":"",
							$select_placeholder_dis,
							($is_multiple == true)?"multiple":"",
	                        ($required_mark=="on")? $required_message:""
                        );

                        echo $select_field;
                        break;
                    case 'image':
                    case 'file':

                    	$is_single = "on";
                    	if ( $field_mapping_type == 'acf' ) {
							if ( $acf_field != 'none' ) {
								$acf_field_object = get_field_object( $acf_field );
								if ( $acf_field_object ) {
									if ( $acf_field_object['type'] != 'file' && $acf_field_object['type'] != 'image' ) {
										$is_single = "off";
									}
								}
							}
						}

						if ( $form_type == 'contact' && $max_upload_file_counts != '' && $max_upload_file_counts != '1' ) {
							$is_single = 'off';
						}

                    	$required_data = "";
                    	if ( $required_mark == "on" ) {
                    		$required_data = ' data-required_message="' . htmlentities($required_message) . '" data-required_position="' . $required_message_position . '"';
                    	}

?>
				<span id="<?php echo $field_id;?>_fileupload" class="<?php echo ($required_mark=="on")?'required':'';?>" <?php echo $required_data;?> data-single="<?php echo $is_single;?>">
				<!--span id="<?php echo $field_id;?>_image_container" class="df-image-wrapper"-->
					<span class="dropzone fade well upload_field file_upload_wrapper" id="dropzone_<?php echo $field_id;?>">
						<span id="<?php echo $field_id;?>_result" class="image_upload_result">
							<span class="upload_icon">

						<?php
            if ($upload_icon == "on") {
                if ($upload_icon_style == "custom_upload") {
									echo '<img id="file_upload" src="' . esc_html($icon_upload) . '">';
                } else {
                    $path = '/icons/upload/'.$upload_icon_style.'.php';
                    include(DE_FB_PATH . $path);
                }
            }
						?>

							</span>
						</span>
						<span class="upload_desc drop-description"><?php echo $upload_description;?></span>
						<input name="file_<?php echo esc_attr($field_name);?>[]" id="<?php echo esc_attr($field_id);?>" type="file" class="upload_field df-image divi-form-builder-field" title="<?php echo esc_attr($upload_alt_title);?>" <?php echo ($is_single == 'off')?"multiple":'';?>/>
					</span>
					<input name="<?php echo esc_attr($field_name);?>" type="hidden" id="<?php echo esc_attr($field_id);?>_value" value="<?php echo (is_array($field_value)?implode(',', $field_value):$field_value);?>"/>
					<span class="file_preview_container files">
					</span>		          	
				</span>
				
					<script id="template-upload" type="text/x-tmpl">
				      	{% for (var i=0, file; file=o.files[i]; i++) { %}
				          	<div class="file_upload_item template-upload fade{%=o.options.loadImageFileTypes.test(file.type)?' image':''%}">
								<div class="file_upload_item_cont">
									<?php if ($hide_upload_image_preview !== "on") {?>
									<div class="preview_image_cont">
										<span class="preview preview_image"></span>
									</div>
									<?php } ?>
									<div class="preview_content">
										<?php if ($hide_upload_prev_title !== "on") { ?>
											<span class="name preview_name">{%=file.name%}</span>
										<?php } ?>
										<?php if ($hide_upload_prev_size !== "on") { ?>
											<span class="upload_size size">Processing...</span>
										<?php } ?>
										<strong class="error text-danger"></strong>
										<?php if ($hide_upload_prev_size !== "on") { ?>
											<span class="progress <?php echo $upload_progress_bar_style ?> active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><span class="progress-bar progress-bar-success" style="width:0%;"></span></span>
										<?php } ?>
									</div>
									<button class="btn btn-primary start" style="display:none;"></button>
									<a class="btn btn-warning cancel remove_upload">
										<i class=""></i>
									</a>
								</div>
							</div>
				      {% } %}
				    </script>
				    <!-- The template to display files available for download -->
<script>
jQuery(document).ready(function($){
	jQuery('#<?php echo $field_id;?>_fileupload').fileupload({
        url: '<?php echo admin_url('admin-ajax.php');?>',
        dataType: 'json',
        dropZone: jQuery('#dropzone_<?php echo $field_id;?>'),
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(<?php echo $accepted_file_types;?>)$/i,
        maxFileSize: <?php echo esc_attr($max_upload_file_size) ?>,
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
        previewMaxWidth: 100,
        previewMaxHeight: 100,
        imageMaxWidth: 1500,
        imageMaxHeight: 1500,
        sequentialUploads: true,
        singleFileUploads:true,
        previewCanvas: false,	
		<?php
		if ( $featured_image_field == true ) {
			echo 'maxNumberOfFiles:1,';
		} else if ( $max_upload_file_counts != "") {
			echo 'maxNumberOfFiles:' . $max_upload_file_counts . ',';
		}
		?>
        downloadTemplateId:'',
        messages: {
	        maxNumberOfFiles: '<?php echo addslashes($max_file_counts_error);?>',
	        acceptFileTypes: '<?php echo addslashes($accepted_file_types_image_error);?>',
	        maxFileSize: '<?php echo addslashes($max_upload_file_size_error);?>',
	        minFileSize: 'File is too small'
	    },
        previewCrop: true,
        formData: {'action':'de_fb_image_upload'},
    }).on('fileuploadadd', function (e, data) {

      	//jQuery(this).closest('.field_wrapper').find('.existing_image_preview').slideUp();

    	jQuery(this).parent().attr('data-uploaded', 'false');
    	var current_cnt = jQuery(this).parent().attr('data-filecnt');
    	if ( typeof current_cnt == 'undefined') {
    		current_cnt = 1;
    	} else {
    		current_cnt = parseInt(current_cnt) + 1;
    	}
    	jQuery(this).parent().attr( 'data-filecnt', current_cnt );

    	var _target = jQuery(e.target);
    	let form_obj = _target.closest('.de_fb_form');
    	if ( form_obj.find('form').hasClass('disable_submit_for_required') ) {
    		let required_fields= form_obj.find('.required');
	        let required_check = true;
	        let submit_btn = form_obj.find('.divi-form-submit');
	        if(required_fields.length > 0){
	            jQuery.each(required_fields,function (index,required_field){
	                if ( !$(this).closest('.de_fb_form_field').hasClass('condition-hide') ) {
	                    var field_type = jQuery(this).closest('.et_pb_contact_field').data('type');
	                    if ( ( field_type == 'checkbox' || field_type == 'radio' ) &&  jQuery(this).find('input:checked').length == 0 ) {
	                        required_check = false;
	                    }

	                    if ( ( field_type == 'file' || field_type == 'image' ) && (jQuery(this).parent().attr('data-filecnt') == "0" || jQuery(this).parent().attr('data-filecnt') == "") ) {
	                        required_check = false;
	                    }
	                }
	            });
	        }
	        if(required_check == true){
	            submit_btn.removeAttr('disabled');
	        }else{
	            submit_btn.attr('disabled',true);
	        }
    	}

    	jQuery(window).trigger('resize');
	
		// if setFormHeight exsists
		if ( typeof setFormHeight == 'function' ) {
			setFormHeight();
		}

	}).on('fileuploaddone', function (e, data) {
	
	// if setFormHeight exsists
	if ( typeof setFormHeight == 'function' ) {
		setFormHeight();
	}

    	var uploaded_cnt = jQuery(this).parent().attr('data-uploadedcnt');

    	var inserted_elem = jQuery(data.context);
    	var inserted_num = inserted_elem.find('canvas').attr('data-insertnum');
    	if ( jQuery(this).closest('.field_wrapper').find('.existing_image_preview').length > 0 ) {
    		jQuery(this).closest('.field_wrapper').find('.inserted_' + inserted_num).attr('data-id', data.result.files[0].attachment_id);
    	}
    	if ( typeof uploaded_cnt == 'undefined') {
    		uploaded_cnt = 1;
    	} else {
    		uploaded_cnt = parseInt(uploaded_cnt) + 1;
    	}
    	jQuery(this).parent().attr( 'data-uploadedcnt', uploaded_cnt );
    	var current_cnt = parseInt(jQuery(this).parent().attr('data-filecnt'));

    	if ( uploaded_cnt == current_cnt ) {
    		jQuery(this).parent().attr('data-uploaded', 'true');
    	}

    	var file_ids = [];
    	jQuery.each( data.result.files, function(inx, file) {
    		file_ids.push(file.attachment_id);
    	});

    	if ( jQuery(this).attr('data-single') == 'on' ) {
    		jQuery('#<?php echo $field_id;?>_value').val( file_ids.join(','));
    	} else {
    		if ( jQuery('#<?php echo $field_id;?>_value').val() == '' ) {
	    		jQuery('#<?php echo $field_id;?>_value').val( file_ids.join(','));
	    	} else {
	    		jQuery('#<?php echo $field_id;?>_value').val( jQuery('#<?php echo $field_id;?>_value').val() + ',' + file_ids.join(','));
	    	}

	    	if ( jQuery(this).closest('.field_wrapper').find('.existing_image_preview').length > 0 ) {
	    		var current_file_ids = [];
	    		jQuery(this).closest('.field_wrapper').find('.existing_image_preview a.existing_image_preview_link').each(function(){
	    			current_file_ids.push( jQuery(this).attr('data-id'));
	    		});
	    		if ( current_file_ids.length > 0 ) {
	    			jQuery('#<?php echo $field_id;?>_value').val( current_file_ids.join(','));
	    		}
	    	}
    	}

    	var all_uploaded = true;
    	jQuery(this).closest('form').find('.et_pb_contact_field[data-type="image"], .et_pb_contact_field[data-type="file"]').each(function(){
    		if ( jQuery(this).attr('data-uploaded') == "false" ){
    			all_uploaded = false;
    		}
    	});

    	if ( all_uploaded == true ){
    		if ( jQuery(this).closest('form').find('.divi-form-submit').hasClass('de_fb_ajax_submit')) {
            	de_fb_ajax_form_submit( jQuery(this).closest('form')[0] );
            	e.preventDefault();
            } else {
            	jQuery(this).closest('form').unbind('submit').submit();
            }
    	}
    	jQuery(window).trigger('resize');
		
    }).on('fileuploadprocessfail', function(e, data) {

    	var _target = jQuery(e.target);
    	if ( data.files.error == true ) {
    		var error_elem = _target.find('.template-upload.processing');
    		error_elem.addClass('error');
    		jQuery(this).parent().attr('data-uploaded', 'false');
	    	var current_cnt = jQuery(this).parent().attr('data-filecnt');
	    	current_cnt = parseInt(current_cnt) - 1;
	    	jQuery(this).parent().attr( 'data-filecnt', current_cnt );
    		setTimeout( function() {
    			error_elem.remove();
    			if ( _target.find('.files  .template-upload').length == 0 ) {
    				_target.closest('.et_pb_contact_field').removeAttr('data-uploaded');
    			}
    		}, <?php echo $upload_error_hide_delay;?>);
    	}
    	
    	jQuery(window).trigger('resize');
    }).on('fileuploadfail', function(e, data){
    	var _target = jQuery(e.target);
    	var _clicked_elem = jQuery(data.context);
    	let form_obj = _target.closest('.de_fb_form');
    	var current_cnt = jQuery(this).parent().attr('data-filecnt');
    	current_cnt = parseInt(current_cnt) - 1;
    	jQuery(this).parent().attr( 'data-filecnt', current_cnt );

    	if ( jQuery(this).closest('.field_wrapper').find('.existing_image_preview').length > 0 ) {
    		var insertedNum = _clicked_elem.find('canvas').attr('data-insertnum');
    		if ( jQuery(this).attr('data-single') == 'on' ) {
    			jQuery(this).closest('.field_wrapper').find('.image-preview-cont img').each(function(){
    				jQuery(this).attr('src', jQuery(this).attr('data-src'));
    			});
	    	} else {
	    		jQuery(this).closest('.field_wrapper').find('.existing_image_preview a.inserted_' + insertedNum).remove();
	    	}
    	}

    	if ( form_obj.find('form').hasClass('disable_submit_for_required') ) {
    		let required_fields= form_obj.find('.required');
	        let required_check = true;
	        let submit_btn = form_obj.find('.divi-form-submit');
	        if(required_fields.length > 0){
	            jQuery.each(required_fields,function (index,required_field){
	                if ( !$(this).closest('.de_fb_form_field').hasClass('condition-hide') ) {
	                    var field_type = jQuery(this).closest('.et_pb_contact_field').data('type');
	                    if ( ( field_type == 'checkbox' || field_type == 'radio' ) &&  jQuery(this).find('input:checked').length == 0 ) {
	                        required_check = false;
	                    }

	                    if ( ( field_type == 'file' || field_type == 'image' ) && (jQuery(this).parent().attr('data-filecnt') == "0" || jQuery(this).parent().attr('data-filecnt') == "") ) {
	                        required_check = false;
	                    }
	                }
	            });
	        }
	        if(required_check == true){
	            submit_btn.removeAttr('disabled');
	        }else{
	            submit_btn.attr('disabled',true);
	        }
    	}

    	jQuery(window).trigger('resize');
    }).on('fileuploadprocessalways', function( e, data) {
    	var _this = jQuery(this);
    	if ( jQuery(this).closest('.field_wrapper').find('.existing_image_preview').length > 0 ) {
    		if ( jQuery(this).attr('data-single') == 'on' ) {
    			if ( data.files[0].preview ) {
	    			var canvas = data.files[0].preview;
	    			var random_num = Math.floor(Math.random() * 100) + 1;
	    			jQuery(data.files[0].preview).attr('data-insertnum', random_num);
	    			var dataURL = canvas.toDataURL();
	    			jQuery(this).closest('.field_wrapper').find('.image-preview-cont img').each(function(){
	    				jQuery(this).attr('data-src', jQuery(this).attr('src'));
	    				jQuery(this).attr('src', dataURL);
	    			});
    			}    			
	    	} else {
	    		if ( data.files.length > 0 ) {
	    			jQuery.each( data.files, function( ind, file){
	    				if ( data.files[ind].preview ) {
			    			var canvas = data.files[ind].preview;
			    			if ( typeof jQuery(data.files[ind].preview).attr('data-insertnum') == 'undefined' ) {
			    				var random_num = Math.floor(Math.random() * 100) + 1;
				    			jQuery(data.files[ind].preview).attr('data-insertnum', random_num);
				    			var dataURL = canvas.toDataURL();
				    			var t_width = _this.closest('.field_wrapper').find('.image-preview-cont').find('img').eq(0).width();
				    			_this.closest('.field_wrapper').find('.image-preview-cont').append('<a class="existing_image_preview_link existing_file_link ui-sortable-handle inserted inserted_' + random_num + '" href="' + dataURL + '" target="_blank"><img decoding="async" class="no-lazy" style="max-width: 200px;width:' + t_width + 'px;" src="' + dataURL + '" title="Uploaded Image Preview"></a>');
			    			}
		    			}
	    			});
	    		}
	    	}
    	}

		// wait 1 second then setFormHeight();
		setTimeout( function() {
			// if setFormHeight exsists
			if ( typeof setFormHeight == 'function' ) {
				setFormHeight();
			}
		}, 1000 );

    });
});
</script>

<?php
	            if ($field_value) { //do we have something to show?

					if (!is_array($field_value)) {
						$field_value = [$field_value];
					}

					wp_enqueue_script( 'jquery-ui-sortable' );

					$remove_media_class = '';

					if ( $remove_file_from_media == 'on' ) {
						$remove_media_class = ' remove_from_media';
					}

	              	/*SB: show a preview of the image or file uploaded*/
		            echo '<div class="existing_image_preview' . $remove_media_class . '">';

		            if($field_type == 'image') { //image based uploads

						echo '<p class="upload-instructions hidethis" style="padding-bottom: 15px;">'.$edit_image_instructions.'</p>';
						echo '<a class="et_pb_button close-edit-image hidethis" href="">'.$close_edit_button_text.'</a>';

						echo '<div class="image-preview-cont" style="padding-top: 30px;">';

						
						foreach ($field_value as $field_val_id) {
							if ( $image_obj = wp_get_attachment_image_src( $field_val_id, 'thumbnail' ) ) {
								$large_image_obj = wp_get_attachment_image_src( $field_val_id, 'large' );
								$preview         = '<img style="max-width: 200px;" src="' . $image_obj[0] . '" title="Uploaded Image Preview" />'; 
								//@todo: SB: move css to file and add class
								//SB: @todo: add lightbox?
								echo '<a class="existing_image_preview_link existing_file_link" href="' . $large_image_obj[0] . '" target="_blank" data-id="' . $field_val_id . '">' . $preview . '<span class="remove-file"></span></a>';
							}
						}

						echo '</div>';
						echo '<div class="image-preview-edit-btn">';
						echo '<a class="et_pb_button edit-image" href="">'.$edit_button_text.'</a>';
						echo '
						<script>
						jQuery(document).ready(function ($) {
							$("#' . $field_id . '_wrapper .image-preview-cont").sortable({
								update: function( event, ui ) {
						    		var current_file_ids = [];
						    		jQuery(this).find("a.existing_image_preview_link").each(function(){
						    			current_file_ids.push( jQuery(this).attr("data-id"));
						    		});
						    		if ( current_file_ids.length > 0 ) {
						    			jQuery("#' . $field_id . '_value").val( current_file_ids.join(","));
						    		}
								}
							});
							$(".edit-image").each(function(i, obj) {
								$(this).closest(".de_fb_form_field").find(".et_pb_contact_field").addClass("hidethis");
							});
							$(document).on("click", "#' . $field_id . '_wrapper .edit-image", function (e) {
								e.preventDefault();
								e.stopPropagation();
								$(this).closest(".de_fb_form_field").find(".et_pb_contact_field").removeClass("hidethis");
								$(this).closest(".de_fb_form_field").find(".close-edit-image").removeClass("hidethis");
								$(this).closest(".de_fb_form_field").find(".upload-instructions").removeClass("hidethis");
								$(this).addClass("hidethis");
								var field_id = $(this).closest(".de_fb_form_field").find("input.upload_field").attr("id");
								$("#" + field_id + "_value").attr("data-origin", $("#" + field_id + "_value").val());
								$("#" + field_id + "_value").val("");
							});
							$(document).on("click", "#' . $field_id . '_wrapper .close-edit-image", function (e) {
								e.preventDefault();
								e.stopPropagation();
								$(this).closest(".de_fb_form_field").find(".et_pb_contact_field").addClass("hidethis");
								$(this).closest(".de_fb_form_field").find(".edit-image").removeClass("hidethis");
								$(this).closest(".de_fb_form_field").find(".upload-instructions").addClass("hidethis");
								var field_id = $(this).closest(".de_fb_form_field").find("input.upload_field").attr("id");
								$("#" + field_id + "_value").val($("#" + field_id + "_value").attr("data-origin"));
								$("#" + field_id + "_value").attr("data-origin", "");
								$(this).addClass("hidethis");
							});
						});
						</script>
						';
						echo '</div>';


		            } else if($field_type == 'file') { //file based uploads

		            	if ( !is_array( $field_value ) ) {
		            		$field_value = array($field_value);
		            	}

	            		foreach ($field_value as $key => $file_id) {
	            			$media_data = wp_get_attachment_url( $file_id );

				            //SB: @todo: @Peter this can be used to include a nice pre-icon to uploaded files so we show more than just the filename. Perhaps also adding the size would be good.
				            $icon_url = $icon = '';
				            /*
							  $media_path = get_attached_file( $field_value, true );
							  $media_mime = wp_check_filetype($media_path);
							  switch ($media_mime['ext']) {
								  case 'pdf':
									  $icon_url = ''; //pdf icon
							  }
						    */

							$media_path = get_attached_file( $file_id, true );
							$media_mime = wp_check_filetype($media_path);

				            if ($icon_url) {
					            $icon = '<img src="' . $icon_url . '" class="uploaded-file-type-icon" />';
				            }

				            $parsed_media_url = parse_url( $media_data );
				            $file_name = basename( $parsed_media_url[ 'path' ] );

				            echo '<div>
									<a class="existing_file_link" href="' . $media_data . '" target="_blank" title="' . $parsed_media_url['path'] . '" data-id="' . $file_id . '">' . $icon . $file_name . '<span class="remove-file"></span></a>
								</div>';
	            		}
		            }

		            echo '
		            <script>
		            jQuery(document).ready(function($){
		            	$(document).on("click", "#' . $field_id . '_wrapper .existing_image_preview .remove-file", function(e){
					        e.preventDefault();
					        e.stopPropagation();
					        var _this = $(this);
					        if (confirm("' . $remove_file_confirm_message . '") == true) {
								var file_id = _this.closest(".existing_file_link").data("id");
								var field_wrapper = $("#' . $field_id . '_wrapper");
								var form_type = _this.closest("form").find("input[name=form_type]").val();
								var form_type = _this.closest("form").find("input[name=form_type]").val();
								var remove_from_media = _this.closest(".existing_image_preview").hasClass("remove_from_media");
								
								var pid = _this.closest("form").find("input[name=ID]").val();

								$.ajax({
									url: de_fb_ajax_obj.ajax_url, // AJAX handler
									data: {
										action:"de_fb_remove_file_handler", 
										file_id : file_id, 
										pid : pid, 
										remove_field_type : "' . $remove_field_type. '", 
										remove_field_name : "' . $remove_field_name . '",
										remove_from_media : remove_from_media
									},
									type: "POST",
									success: function (data) {
										if ( data == "success" ) {
											_this.closest(".existing_file_link").remove();
											var field_value = $("#' . $field_id . '_value").val();
											var field_value_array = field_value.split(",");
											const index = field_value_array.indexOf( file_id.toString() );
											if (index > -1) {
												field_value_array.splice(index, 1);
											}
											$("#' . $field_id . '_value").val(field_value_array.join(","));

											if ( field_wrapper.find(".existing_file_link").length == 0 ) {
												field_wrapper.find(".hidethis").removeClass("hidethis");
												field_wrapper.find(".existing_image_preview").addClass("hidethis");
												$("#' . $field_id . '_value").val("");
											}
										}
									}
								});
							}
					    });
		            });
		            </script>
		            ';

		            echo '</div>';
	            }

                break;
            case 'datepicker':
            	if ( $date_time_picker_lang != '' ) {
            		wp_enqueue_script('jquery-ui-datepicker-lang-'.$date_time_picker_lang, DE_FB_URL . '/js/jquery.ui.datepicker/i18n/datepicker-' . $date_time_picker_lang . '.js', 'jquery-ui-core' );
            	}

            	$required_data = '';

            	if ( 'on' == $required_mark ) {
            		$required_data = ' data-msg-required="' . htmlentities($required_message) . '" data-required_position="' . $required_message_position . '"';
            	}
?>
				<span class="datepicker-wrapper">
					<input type="text" class="divi-form-builder-field datepicker-field" <?php echo 'off' === $required_mark ? '' : 'required';?> name="<?php echo $field_name;?>" id="<?php echo $field_id;?>" <?php echo 'placeholder="'. $field_placholder.'"';?> value="<?php echo $field_value;?>" <?php echo $required_data;?> autocomplete="<?php echo $enable_autocomplete;?>"/>
				</span>

				<script>
					jQuery(document).ready(function($){
						var datepicker_option = $.extend(
							$.datepicker.regional['<?php echo $date_time_picker_lang;?>'],
							{
								showOn: "both",
					            //buttonImage: datepicker_arg.img_url,
					            buttonImage: '<?php echo DE_FB_URL; ?>/images/calendar.png',
					            buttonImageOnly: true,
					            buttonText: "Select date",
					            dateFormat: "<?php echo $date_format;?>",
					            beforeShow: function(input, inst) {
									$('#ui-datepicker-div').addClass("date_picker_<?php echo $form_key;?>");
								},
								onClose: function(dateText, inst ) {
									$('#ui-datepicker-div').removeClass("date_picker_<?php echo $form_key;?>");
								}
							});
						$('#<?php echo $field_id;?>').datepicker( datepicker_option );
					});
				</script>
<?php
                break;
            case 'datetimepicker':
            	if ( $date_time_picker_lang != '' ) {
            		wp_enqueue_script('jquery-ui-datepicker-lang-'.$date_time_picker_lang, DE_FB_URL . '/js/jquery.ui.datepicker/i18n/datepicker-' . $date_time_picker_lang . '.js', 'jquery-ui-core' );
            		wp_enqueue_script('jquery-ui-datetimepicker-lang-'.$date_time_picker_lang, DE_FB_URL . '/js/jquery.ui.datetimepicker/i18n/jquery-ui-timepicker-' . $date_time_picker_lang . '.js', 'jquery-ui-core' );
            	}

            	$required_data = '';

            	if ( 'on' == $required_mark ) {
            		$required_data = ' data-msg-required="' . htmlentities($required_message) . '" data-required_position="' . $required_message_position . '"';
            	}
?>
				<span class="datetimepicker-wrapper">
					<input type="text" class="divi-form-builder-field datetimepicker-field" <?php echo 'off' === $required_mark ? '' : 'required';?> name="<?php echo $field_name;?>" id="<?php echo $field_id;?>" <?php echo 'placeholder="'. $field_placholder.'"';?> value="<?php echo $field_value;?>" <?php echo $required_data;?> autocomplete="<?php echo $enable_autocomplete;?>"/>
				</span>
				<script>
					jQuery(document).ready(function($){
						var timepicker_option = $.extend(
							$.timepicker.regional['<?php echo $date_time_picker_lang;?>'],
							{
								dateFormat: '<?php echo $date_format;?>',
				            	timeFormat: '<?php echo $date_time_format;?>',
				            	beforeShow: function(input, inst) {
									$('#ui-datepicker-div').addClass("date_picker_<?php echo $form_key;?>");
								},
								onClose: function(dateText, inst ) {
									$('#ui-datepicker-div').removeClass("date_picker_<?php echo $form_key;?>");
								}
							});
						$.datepicker.setDefaults( $.datepicker.regional[ '<?php echo $date_time_picker_lang;?>' ] );
						$('#<?php echo $field_id;?>').datetimepicker( timepicker_option );
					});
				</script>
<?php
                break;
            case 'signature':
?>
        		<span class="divi-form-builder-field signature-field" id="<?php echo $field_id;?>_wrapper">
					<canvas height="<?php echo $origin_height;?>"></canvas>
			<?php
				$signature_clear_icon_arr = explode('||', $signature_clear_icon);
				$signature_clear_icon_font_family = ( !empty( $signature_clear_icon_arr[1] ) && $signature_clear_icon_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
				$signature_clear_icon_font_weight = ( !empty( $signature_clear_icon_arr[2] ))?$signature_clear_icon_arr[2]:'400';
				$signature_clear_icon_dis = preg_replace( '/(&amp;#x)|;/', '', et_pb_process_font_icon( $signature_clear_icon ) );
				$signature_clear_icon_dis = preg_replace( '/(&#x)|;/', '', $signature_clear_icon_dis );
				if ( $signature_clear == 'on' && $signature_clear_icon != '' ) {
					ET_Builder_Element::set_style($render_slug, array(
						'selector'    => '%%order_class%% .signature_clear',
						'declaration' => sprintf(
							'
							position: absolute;
							right: 6px;
							top:%1$s;
							cursor:pointer;
							', $signature_clear_icon_top
						),
					));
					ET_Builder_Element::set_style($render_slug, array(
						'selector'    => '%%order_class%% .signature_clear::after',
						'declaration' => sprintf(
							'
							content:"\%1s";
							font-size:%2s;
							color:%3s;
							font-family:%4$s!important;
							font-weight:%5$s;
							',$signature_clear_icon_dis,
							$signature_clear_icon_size,
							$signature_clear_icon_color,
							$signature_clear_icon_font_family,
							$signature_clear_icon_font_weight
						),
					));
			?>
					<span class="signature_clear"></span>
			<?php
				}

				if ( $origin_width != "auto" ){
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'	=> '%%order_class%% .signature-field',
						'declaration' => 'width: ' . $origin_width  . ';'
					));
				}

				if ( $origin_height != "auto" ){
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'	=> '%%order_class%% .signature-field',
						'declaration' => 'height: ' . $origin_height . ';'
					));
				}
			?>
					<input type="hidden" name="<?php echo $field_name;?>" id="<?php echo $field_id;?>" value="">
			    </span>
			    <input type="hidden" name="signature[]" value="<?php echo $field_name;?>">
				<script>
					jQuery(document).ready(function($){

						function signature_apperance_<?php echo $field_id;?>() {
							var wrapper_<?php echo $field_id;?> = document.getElementById("<?php echo $field_id;?>_wrapper");
							var canvas_<?php echo $field_id;?> = wrapper_<?php echo $field_id;?>.querySelector("canvas");
							var parentWidth = $(canvas_<?php echo $field_id;?>).closest('.de_fb_form_field ').width();
							canvas_<?php echo $field_id;?>.setAttribute("width", parentWidth);
							fb_signature.signature_objs['signaturePad_<?php echo $field_id;?>'] = new SignaturePad(canvas_<?php echo $field_id;?>, {
								backgroundColor: '<?php echo $signature_background;?>',
								penColor: '<?php echo $signature_pencolor;?>'
							});
						}

						/*function resizeCanvas_<?php echo $field_id;?>() {
							// When zoomed out to less than 100%, for some very strange reason,
							// some browsers report devicePixelRatio as less than 1
							// and only part of the canvas is cleared then.
							var ratio =  Math.max(window.devicePixelRatio || 1, 1);
							var wrapper_<?php echo $field_id;?> = document.getElementById("<?php echo $field_id;?>_wrapper");
							var canvas_<?php echo $field_id;?> = wrapper_<?php echo $field_id;?>.querySelector("canvas");
							canvas_<?php echo $field_id;?>.width = canvas_<?php echo $field_id;?>.offsetWidth * ratio;
							canvas_<?php echo $field_id;?>.height = canvas_<?php echo $field_id;?>.offsetHeight * ratio;
							canvas_<?php echo $field_id;?>.getContext("2d").scale(ratio, ratio);
						}*/

						var init_<?php echo $field_id;?> = false;

						signature_apperance_<?php echo $field_id;?>();

						$(window).on('resize', function(){
							if ( init_<?php echo $field_id;?> == false ) {
								signature_apperance_<?php echo $field_id;?>();
								init_<?php echo $field_id;?> = true;
							}
							
							//resizeCanvas_<?php echo $field_id;?>();
						});

					});
				</script>
<?php
				break;
				case 'step':
					?>
                <?php if($multistep_enabled){ ?>
					STEP
					<?php } ?>
					<?php
					break;
		}
?>
			</p>

			<?php
			if ($description_text == 'on' && $description_text_location == 'below') {
				?>
				<span class="df_field_description_text"><?php echo esc_html($description_text_text) ?></span>
				<?php
			}
			?>
<?php
        if ($field_label_position == 'left' || $field_label_position == 'right') {
            ?>
					</span>
				<?php if ($field_label_position == "right") { ?>
					<label for="<?php echo $field_id;?>" class="et_pb_column field_label label_position_<?php echo $field_label_position;?> <?php echo $field_label_width;?>"><?php echo $field_title . '<span class="de_fb_required">'.$required_sign.'</span>';?></label>
				<?php } ?>
				</span>
<?php
        	}
        } 
?>
		</div>
<?php
		$result = ob_get_clean();

		return $result;
	}
}

new DE_FB_FormField;