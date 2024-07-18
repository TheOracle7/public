<?php

class DE_FB_EditPostButton extends ET_Builder_Module {

	public $slug       = 'de_fb_edit_post_button';
	public $vb_support = 'on';

    public $folder_name = '';
    public $fields_defaults = array();
    public $text_shadow = '';
    public $margin_padding = '';
    public $_additional_fields_options = array();

	protected $module_credits = array(
		'module_uri' => 'https://diviengine.com',
		'author'     => 'Divi Engine',
		'author_uri' => 'https://diviengine.com',
	);

	public function init() {
		$this->name = esc_html__( 'Edit/Delete Post - Divi Form Builder', 'divi-form-builder' );
        $this->folder_name = 'divi_form_builder';

		$this->settings_modal_toggles = array(
            'general' => array(
                'toggles' => array(),
            ),
        );

        $this->main_css_element = '%%order_class%%';
        $this->fields_defaults = array();

        $this->advanced_fields = array(
            'fonts' => array(
                'text' => array(
                    'label'    => esc_html__( 'Button', 'divi-form-builder' ),
                    'css'      => array(
                        'main' => "%%order_class%% .et_pb_module_inner",
                        'important' => 'plugin_only',
                    ),
                    'font_size' => array(
                        'default' => '14px',
                    ),
                    'line_height' => array(
                        'default' => '1em',
                    ),
                ),
            ),
            'button' => array(
                'button' => array(
                    'label' => esc_html__( 'Button', 'divi-form-builder' ),
                    'css' => array(
                        'main' => "{$this->main_css_element} .et_pb_button",
                        'important' => 'all',
                    ),
                    'box_shadow'  => array(
                        'css' => array(
                            'main' => "{$this->main_css_element} .et_pb_button",
                            'important' => 'all',
                        ),
                    ),
                    'margin_padding' => array(
                        'css'           => array(
                            'main' => "{$this->main_css_element} .et_pb_button",
                            'important' => 'all',
                        ),
                    ),
                ),
            ),
            'background' => array(
                'settings' => array(
                    'color' => 'alpha',
                ),
            ),
            'border' => array(),
            'custom_margin_padding' => array(
                'css' => array(
                    'important' => 'all',
                ),
            ),
        );

        $this->custom_css_fields = array();
	}

	public function get_fields() {

		$divi_layouts = DE_FormBuilder::get_divi_layouts();

        $pages = get_pages();

        $pages_option = array();

        $pages_option['none'] = esc_html__( 'Select Page', 'divi-form-builder');

        if ( !empty( $pages) ) {
            foreach ( $pages as $page_ind => $page ) {
                $pages_option[$page->ID] = $page->post_title;
            }
        }

        $et_accent_color = et_builder_accent_color();

        $fields = array(
            'edit_or_delete' => array(
                'toggle_slug'       => 'main_content',
                'label' => esc_html__( 'Edit or Delete?', 'divi-form-builder' ),
                'type' => 'select',
                'option_category' => 'basic_option',
                'options' => array(
                    'edit' => esc_html__( 'Edit Post', 'divi-form-builder' ),
                    'delete' => esc_html__( 'Delete Post', 'divi-form-builder' ),
                ),
                'default' => 'edit',
                'description' => esc_html__( 'Choose what you want the button to do - edit or delete the post.', 'divi-form-builder' )
            ),
            'title' => array(
                'label'           => esc_html__( 'Button Text', 'divi-form-builder' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'default'         => 'Edit Post',
                'toggle_slug'       => 'main_content',
                'description'     => esc_html__( 'Input your desired button text.', 'divi-form-builder' ),
            ),
            'fullwidth_btn' => array(
                'toggle_slug'       => 'main_content',
                'label' => esc_html__( 'Full Width Button?', 'divi-form-builder' ),
                'type' => 'yes_no_button',
                'option_category' => 'basic_option',
                'options' => array(
                    'on' => esc_html__( 'Yes', 'divi-form-builder' ),
                    'off' => esc_html__( 'No', 'divi-form-builder' ),
                ),
                'default' => 'on',
                'description' => esc_html__( 'If you want to make your button fullwdith of the available space, enable this.', 'divi-form-builder' )
            ),
            'show_author_only' => array(
                'toggle_slug'       => 'main_content',
                'label' => esc_html__( 'Show for Author Only?', 'divi-form-builder' ),
                'type' => 'yes_no_button',
                'option_category' => 'basic_option',
                'options' => array(
                    'on' => esc_html__( 'Yes', 'divi-form-builder' ),
                    'off' => esc_html__( 'No', 'divi-form-builder' ),
                ),
                'show_if' => array('edit_or_delete' => 'edit'),
                'default' => 'off',
                'description' => esc_html__( 'Enable this if you want to show button for posts current user posted.', 'divi-form-builder' )
            ),
            'showin_modal' => array(
                'toggle_slug'       => 'main_content',
                'label' => esc_html__( 'Show Post in Modal?', 'divi-form-builder' ),
                'type' => 'yes_no_button',
                'option_category' => 'basic_option',
                'options' => array(
                    'on' => esc_html__( 'Yes', 'divi-form-builder' ),
                    'off' => esc_html__( 'No', 'divi-form-builder' ),
                ),
                'default' => 'off',
                'show_if' => array('edit_or_delete' => 'edit'),
                'affects'         => array(
                    'form_page_id',
                    'new_tab',
                    'modal_layout',
                    'modal_overlay_color',
                    'modal_close_icon',
                    'modal_close_icon_color',
                    'modal_close_icon_size',
                    'modal_style',
                    'loading_animation_color'
                ),
                'description' => esc_html__( 'If you want to show post in modal instead to go post page, enable this.', 'divi-form-builder' )
            ),
            'form_page_id' => array(
                'toggle_slug'       => 'main_content',
                'label' => esc_html__( 'Edit Page', 'divi-form-builder' ),
                'type' => 'select',
                'option_category' => 'basic_option',
                /*'show_if' => array('edit_or_delete' => 'edit'),*/
                'options' => $pages_option,
                'default' => 'none',
                'depends_show_if' => 'off',
                'description' => esc_html__( 'Select Page that contains form to edit.', 'divi-form-builder' )
            ),
            'new_tab' => array(
                'toggle_slug'       => 'main_content',
                'label' => esc_html__( 'Open in New Tab?', 'divi-form-builder' ),
                'type' => 'yes_no_button',
                'option_category' => 'basic_option',
                /*'show_if' => array('edit_or_delete' => 'edit'),*/
                'options' => array(
                    'on' => esc_html__( 'Yes', 'divi-form-builder' ),
                    'off' => esc_html__( 'No', 'divi-form-builder' ),
                ),
                'default' => 'off',
                'depends_show_if' => 'off',
                'description' => esc_html__( 'Enable this if you want to open in a new tab.', 'divi-form-builder' )
            ),
            'modal_layout' => array(
                'toggle_slug'       => 'main_content',
                'label' => esc_html__( 'Modal Form layout', 'divi-form-builder' ),
                'type' => 'select',
                'option_category' => 'basic_option',
                /*'show_if' => array('edit_or_delete' => 'edit'),*/
                'options' => $divi_layouts,
                'default' => 'none',
                'depends_show_if' => 'on',
                'description' => esc_html__( 'Select the Divi Library Layout that has the form to edit the post - this will be shown in the modal.', 'divi-form-builder' )
            ),
            'modal_style' => array(
                'toggle_slug'       => 'main_content',
                'label' => esc_html__( 'Modal Style', 'divi-form-builder' ),
                'type' => 'select',
                /*'show_if' => array('edit_or_delete' => 'edit'),*/
                'option_category' => 'basic_option',
                'options' => array(
                    'center-modal'       => esc_html__( 'Center', 'divi-form-builder' ),
                    'side-modal'       => esc_html__( 'Side', 'divi-form-builder' ),
                ),
                'default' => 'center-modal',
                'depends_show_if' => 'on',
                'description' => esc_html__( 'Select the modal style.', 'divi-form-builder' )
            ),
            'modal_overlay_color' => array(
                'toggle_slug'       => 'main_content',
                'label' => esc_html__( 'Modal Overlay Background', 'divi-form-builder' ),
                'type' => 'color-alpha',
                'option_category' => 'basic_option',
                /*'show_if' => array('edit_or_delete' => 'edit'),*/
                'default' => 'rgba(0,0,0,0.5)',
                'depends_show_if' => 'on',
                'description' => esc_html__( 'Select background color of modal overlay.', 'divi-form-builder' )
            ),
            'modal_close_icon' => array(
                'toggle_slug'       => 'main_content',
                'label' => esc_html__( 'Modal Close Icon', 'divi-form-builder' ),
                'type' => 'select_icon',
                /*'show_if' => array('edit_or_delete' => 'edit'),*/
                'option_category' => 'basic_option',
                'class' => array('et-pb-font-icon'),
                'mobile_options'      => true,
                'default'           => '%%44%%',
                'depends_show_if' => 'on',
                'description' => esc_html__( 'Choose an icon for modal close icon.', 'divi-form-builder' )
            ),
            'modal_close_icon_color' => array(
                'default'           => $et_accent_color,
                'label'             => esc_html__('Modal Close Icon Color', 'divi-form-builder'),
                'type'              => 'color-alpha',
                /*'show_if' => array('edit_or_delete' => 'edit'),*/
                'description'       => esc_html__('Here you can define a custom color for modal close icon.', 'divi-form-builder'),
                'depends_show_if'   => 'on',
                'toggle_slug'       => 'main_content',
                'option_category'   => 'basic_option',
                'mobile_options'    => true,
            ),
            'modal_close_icon_size' => array(
                'label'            => esc_html__( 'Modal Close Icon Size', 'divi-form-builder' ),
                'description'      => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'divi-form-builder' ),
                'type'             => 'range',
                'option_category'  => 'basic_option',
                /*'show_if' => array('edit_or_delete' => 'edit'),*/
                'toggle_slug'      => 'main_content',
                'default'          => '46px',
                'default_unit'     => 'px',
                'default_on_front' => '',
                'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
                'range_settings'   => array(
                    'min'  => '1',
                    'max'  => '500',
                    'step' => '1',
                ),
                'depends_show_if'  => 'on',
                'responsive'       => true,
                'hover'            => 'tabs',
            ),
            'loading_animation_color' => array(
              'label'             => esc_html__( 'Loading Post Animation Color', 'et_builder' ),
              'description'       => esc_html__( 'Define the color of the animation.', 'et_builder' ),
              'type'              => 'color-alpha',
              /*'show_if' => array('edit_or_delete' => 'edit'),*/
              'custom_color'      => true,
              'option_category'   => 'configuration',
              'toggle_slug'       => 'main_content',
              'depends_show_if' => 'on',
            ),
            'button_alignment' => array(
                'label'            => esc_html__( 'Button Alignment', 'divi-form-builder' ),
                'description'      => esc_html__( 'Align your button to the left, right or center of the module.', 'divi-form-builder' ),
                'type'             => 'text_align',
                'option_category'  => 'configuration',
                'options'          => et_builder_get_text_orientation_options( array( 'justified' ) ),
                'tab_slug'         => 'advanced',
                'toggle_slug'      => 'alignment',
            ),
        );

        return $fields;
	}

	public function render( $attrs, $content, $render_slug ) {

        $edit_or_delete           = $this->props['edit_or_delete'];
        $title_get                  = $this->props['title'];
        $button_use_icon            = $this->props['button_use_icon'];
        $custom_icon                = $this->props['button_icon'];
        $button_bg_color            = $this->props['button_bg_color'];
        $fullwidth_btn              = $this->props['fullwidth_btn'];
        $showin_modal               = $this->props['showin_modal'];
        $modal_layout               = $this->props['modal_layout'];
        $modal_overlay_color        = $this->props['modal_overlay_color'];
        $modal_close_icon           = $this->props['modal_close_icon'];
        $modal_close_icon_color     = $this->props['modal_close_icon_color'];
        $modal_close_icon_size      = $this->props['modal_close_icon_size'];
        $page_id                    = $this->props['form_page_id'];
        $show_author_only           = $this->props['show_author_only'];
        $loading_animation_color      = $this->props['loading_animation_color'];
        $new_tab      = $this->props['new_tab'];
        $modal_style      = $this->props['modal_style'];
        $button_alignment  = $this->props['button_alignment'];

		// Module classnames
		$this->add_classname(
			array(
				'clearfix',
				$this->get_text_orientation_classname(),
			)
		);

        $this->add_classname( 'de-fb-btn-align-' . $button_alignment );

        if ($fullwidth_btn == 'on') {
            $this->add_classname('fullwidth-btn');
        }

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
        
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
        wp_enqueue_style( 'jquery-ui' );

        wp_localize_script( 'de_fb_js', 'de_fb_obj', array( 'ajax_url' => admin_url('admin-ajax.php') ) );
        wp_enqueue_script('de_fb_js');

        wp_enqueue_script( 'de_fb_signature' );
        wp_localize_script( 'de_fb_signature', 'fb_signature', array( 'signature_objs' => [] ) );

        wp_enqueue_style( 'de_fb_file_upload' );
        wp_enqueue_style( 'de_fb_file_upload_ui' );

        wp_localize_script( 'de_fb_js', 'datepicker_arg', array( 'img_url' => DE_FB_URL . '/images/calendar.png' ) );

        do_action( 'wpml_register_single_string', 'divi-form-builder', 'Edit Post Button Title Text', $title_get );
        $title = apply_filters( 'wpml_translate_single_string', $title_get, 'divi-form-builder', 'Edit Post Button Title Text' );
        
        //////////////////////////////////////////////////////////////////////

        $post_id = get_the_ID();

        if ( $show_author_only == 'on' ) {
            $current_user = wp_get_current_user();

            if ( ! ( $current_user instanceof WP_User ) ) {
                return '';
            } else {
                if ( $current_user->ID != get_the_author_meta( 'ID' ) ) {
                    return '';
                }
            }
        }

        ob_start();
        $symbols = array( '21', '22', '23', '24', '25', '26', '27', '28', '29', '2a', '2b', '2c', '2d', '2e', '2f', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '3a', '3b', '3c', '3d', '3e', '3f', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '4a', '4b', '4c', '4d', '4e', '4f', '50', '51', '52', '53', '54', '55', '56', '57', '58', '59', '5a', '5b', '5c', '5d', '5e', '5f', '60', '61', '62', '63', '64', '65', '66', '67', '68', '69', '6a', '6b', '6c', '6d', '6e', '6f', '70', '71', '72', '73', '74', '75', '76', '77', '78', '79', '7a', '7b', '7c', '7d', '7e', 'e000', 'e001', 'e002', 'e003', 'e004', 'e005', 'e006', 'e007', 'e009', 'e00a', 'e00b', 'e00c', 'e00d', 'e00e', 'e00f', 'e010', 'e011', 'e012', 'e013', 'e014', 'e015', 'e016', 'e017', 'e018', 'e019', 'e01a', 'e01b', 'e01c', 'e01d', 'e01e', 'e01f', 'e020', 'e021', 'e022', 'e023', 'e024', 'e025', 'e026', 'e027', 'e028', 'e029', 'e02a', 'e02b', 'e02c', 'e02d', 'e02e', 'e02f', 'e030', 'e103', 'e0ee', 'e0ef', 'e0e8', 'e0ea', 'e101', 'e107', 'e108', 'e102', 'e106', 'e0eb', 'e010', 'e105', 'e0ed', 'e100', 'e104', 'e0e9', 'e109', 'e0ec', 'e0fe', 'e0f6', 'e0fb', 'e0e2', 'e0e3', 'e0f5', 'e0e1', 'e0ff', 'e031', 'e032', 'e033', 'e034', 'e035', 'e036', 'e037', 'e038', 'e039', 'e03a', 'e03b', 'e03c', 'e03d', 'e03e', 'e03f', 'e040', 'e041', 'e042', 'e043', 'e044', 'e045', 'e046', 'e047', 'e048', 'e049', 'e04a', 'e04b', 'e04c', 'e04d', 'e04e', 'e04f', 'e050', 'e051', 'e052', 'e053', 'e054', 'e055', 'e056', 'e057', 'e058', 'e059', 'e05a', 'e05b', 'e05c', 'e05d', 'e05e', 'e05f', 'e060', 'e061', 'e062', 'e063', 'e064', 'e065', 'e066', 'e067', 'e068', 'e069', 'e06a', 'e06b', 'e06c', 'e06d', 'e06e', 'e06f', 'e070', 'e071', 'e072', 'e073', 'e074', 'e075', 'e076', 'e077', 'e078', 'e079', 'e07a', 'e07b', 'e07c', 'e07d', 'e07e', 'e07f', 'e080', 'e081', 'e082', 'e083', 'e084', 'e085', 'e086', 'e087', 'e088', 'e089', 'e08a', 'e08b', 'e08c', 'e08d', 'e08e', 'e08f', 'e090', 'e091', 'e092', 'e0f8', 'e0fa', 'e0e7', 'e0fd', 'e0e4', 'e0e5', 'e0f7', 'e0e0', 'e0fc', 'e0f9', 'e0dd', 'e0f1', 'e0dc', 'e0f3', 'e0d8', 'e0db', 'e0f0', 'e0df', 'e0f2', 'e0f4', 'e0d9', 'e0da', 'e0de', 'e0e6', 'e093', 'e094', 'e095', 'e096', 'e097', 'e098', 'e099', 'e09a', 'e09b', 'e09c', 'e09d', 'e09e', 'e09f', 'e0a0', 'e0a1', 'e0a2', 'e0a3', 'e0a4', 'e0a5', 'e0a6', 'e0a7', 'e0a8', 'e0a9', 'e0aa', 'e0ab', 'e0ac', 'e0ad', 'e0ae', 'e0af', 'e0b0', 'e0b1', 'e0b2', 'e0b3', 'e0b4', 'e0b5', 'e0b6', 'e0b7', 'e0b8', 'e0b9', 'e0ba', 'e0bb', 'e0bc', 'e0bd', 'e0be', 'e0bf', 'e0c0', 'e0c1', 'e0c2', 'e0c3', 'e0c4', 'e0c5', 'e0c6', 'e0c7', 'e0c8', 'e0c9', 'e0ca', 'e0cb', 'e0cc', 'e0cd', 'e0ce', 'e0cf', 'e0d0', 'e0d1', 'e0d2', 'e0d3', 'e0d4', 'e0d5', 'e0d6', 'e0d7', 'e600', 'e601', 'e602', 'e603', 'e604', 'e605', 'e606', 'e607', 'e608', 'e609', 'e60a', 'e60b', 'e60c', 'e60d', 'e60e', 'e60f', 'e610', 'e611', 'e612', 'e008', );

        $close_icon_index   = (int) str_replace( '%', '', $modal_close_icon );
        $close_icon_rendered =  sprintf(
            '\%1$s',
            $symbols[$close_icon_index]
        );

        if ($edit_or_delete == 'edit') {
            
            if ( $showin_modal == 'on' ){
	            ET_Builder_Element::set_style( $render_slug, array(
		            'selector'    => 'body #page-container .et_pb_column:has(%%order_class%%)',
		            'declaration' => "z-index:3;"
	            ) );
                wp_enqueue_script( 'de_fb_validate' );
                wp_enqueue_script( 'de_fb_validate_additional' );
                wp_enqueue_style('divi-form-builder-select2-css');
                wp_enqueue_script('divi-form-builder-select2-js');
                wp_enqueue_script('de_fb_googlemaps_script');
                ?>
                <style>
                   .de-fb-popup .modal-close:before {
                       content: "<?php echo $close_icon_rendered ?>";
                       font-size: <?php echo $modal_close_icon_size;?>;
                       color: <?php echo $modal_close_icon_color;?>;
                   }
                   .de-fb-popup{
                       background-color: <?php echo $modal_overlay_color;?>!important;
                   }
                </style>
                <a class="et_pb_button edit_form_modal" data-modal-style="<?php echo $modal_style ?>"
                data-modal-layout="<?php echo $modal_layout;?>" data-id="<?php echo get_the_ID();?>" loading_animation_color="<?php echo esc_attr($loading_animation_color) ?>" href="#"><?php echo esc_html__( $title, 'divi-form-builder' ); ?></a>
                <div id="de-fb-modal-wrapper-<?php echo get_the_ID();?>"></div>
                <?php
            } else {
                if ( $page_id !== 'none' ) {
                    $page_link = get_the_permalink( $page_id );
                if ($new_tab == 'on') {
                    $newtab = 'target="_blank"';
                } else {
                    $newtab = '';
                }
                ?>
                <a class="et_pb_button" href="<?php echo $page_link . '?pid=' . $post_id; ?>" <?php echo $newtab ?>><?php echo esc_html__( $title, 'divi-form-builder' ); ?> </a>
                <?php
                }
            }
        } else {
            global $post;
            $url = get_bloginfo('url');
            if (current_user_can('edit_post', $post->ID)){
            ?>
            <a class="et_pb_button" href="<?php echo esc_url( get_delete_post_link( $post->ID ) ); ?>"><?php echo esc_html__( $title, 'divi-form-builder' ); ?> </a>
            <?php
            }
        }


        if( $button_use_icon == 'on' && $custom_icon != '' ){
            $button_icon_arr = explode('||', $custom_icon);

            $button_icon_font_family = ( !empty( $button_icon_arr[1] ) && $button_icon_arr[1] == 'fa' )?'FontAwesome':'ETmodules';
            $button_icon_font_weight = ( !empty( $button_icon_arr[2] ))?$button_icon_arr[2]:'400';

            $custom_icon = 'data-icon="'. esc_attr( et_pb_process_font_icon( $custom_icon ) ) .'"';
            ET_Builder_Element::set_style( $render_slug, array(
                'selector'    => 'body #page-container %%order_class%% .et_pb_button:after',
                'declaration' => "content: attr(data-icon);
                    font-family: {$button_icon_font_family}!important;
                    font-weight:{$button_icon_font_weight}",
            ) );
        }else{
            ET_Builder_Element::set_style( $render_slug, array(
                'selector'    => 'body #page-container %%order_class%% .et_pb_button:hover',
                'declaration' => "padding: .3em 1em;",
            ) );
        }

        if( !empty( $button_bg_color ) ){
            ET_Builder_Element::set_style( $render_slug, array(
                'selector'    => 'body #page-container %%order_class%% .et_pb_button',
                'declaration' => "background-color:". esc_attr( $button_bg_color ) ."!important;",
            ) );
        }

        $data = ob_get_clean();
        //////////////////////////////////////////////////////////////////////

        $data = str_replace(
            'class="et_pb_button"',
            'class="et_pb_button"' . $custom_icon
            , $data
        );

        $data = str_replace(
            'class="et_pb_button edit_form_modal"',
            'class="et_pb_button edit_form_modal"' . $custom_icon
            , $data
        );

        return $data;
	}
}

new DE_FB_EditPostButton;