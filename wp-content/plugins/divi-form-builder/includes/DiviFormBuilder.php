<?php

class DE_FormBuilder extends DiviExtension {

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $gettext_domain = 'divi-form-builder';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $name = 'divi-form-builder';

	/**
	 * The extension's version
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = DE_FB_VERSION;

	/**
	 * DE_FormBuilder constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */

	public static $divi_layouts = array();

	public function __construct( $name = 'divi-form-builder', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

		//add_action( 'rest_api_init', array( $this, 'de_setup_rest_api' ) );

		parent::__construct( $name, $args );

		$this->init_hooks();
	}

	// render ET font icons content css property
	public static function et_icon_css_content( $font_icon ){
		$icon = preg_replace( '/(&amp;#x)|;/', '', et_pb_process_font_icon( $font_icon ) );
		$icon = preg_replace( '/(&amp#x)|;/', '', $icon );
		$icon = preg_replace( '/(&#x)|;/', '', $icon );

		return '\\' . $icon;
	}

	public static function get_divi_layouts(  ){

		if ( empty( self::$divi_layouts ) ) {
			$layout_query = array(
				'post_type'=>'et_pb_layout'
				, 'posts_per_page'=>-1
				, 'meta_query' => array(
						array(
								'key' => '_et_pb_predefined_layout',
								'compare' => 'NOT EXISTS',
						),
				)
			);

			self::$divi_layouts['none'] = 'No Layout (please choose one)';
			if ($layouts = get_posts($layout_query)) {
				foreach ($layouts as $layout) {
					self::$divi_layouts[$layout->ID] = $layout->post_title;
				}
			}
		}
		return self::$divi_layouts;		
	}

	public function init_hooks() {
		add_action( 'wp', array( $this, 'check_divi_form_submit'), 20);
		add_action( 'wp_enqueue_scripts', array( $this, 'register_divi_form_scripts') );
		add_action( 'wp_dashboard_setup', array( $this, 'dfb_check_validation') );

		add_action('wp_ajax_de_fb_image_upload', array( $this, 'do_image_upload') );
		add_action('wp_ajax_nopriv_de_fb_image_upload', array( $this, 'do_image_upload') );
	}

    function do_image_upload(){
        $options = [
	        'param_name' => key($_FILES)
	    ];
    	require('de_fb_file_upload.php');
    	$upload_handler = new DE_FB_FILE_UPLOAD($options);
    	exit;
    }

	public function check_divi_form_submit() {
		global $wp_query;
		if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
			include( ABSPATH . 'wp-admin/includes/image.php' );
		}
		//$valid = $this->check_validation();
		
		if ( isset( $_POST['divi-form-submit'] ) && $_POST['divi-form-submit'] == 'yes' ) {
			$form_type = $_POST['form_type'];
			$form_id = $_POST['form_id'];

			$registered_post_types = et_get_registered_post_type_options( false, false );
			unset($registered_post_types['attachment']);

			/*$post_array = $_POST;
			unset( $post_array['form_type'] );
			unset( $post_array['divi-form-submit'] );
			unset( $post_array['form_id']);
			unset( $post_array['redirect_url_after_submission'] );
			unset( $post_array['redirect_url_after_failed'] );*/

			$post_array = array();

			foreach( $_POST as $key => $p_value ) {
				if ( !in_array( $key, array( 'form_type', 'divi-form-submit', 'form_id', 'redirect_url_after_failed', 'redirect_url_after_submission', 'simple_captcha', 's_nonce', 'form_key', 'unique_id', 'recaptcha_token'))) {
					if ( is_array($p_value) ) {
						foreach ($p_value as $p_key => $value) {
							$p_value[$p_key] = wp_kses_post( $value );
						}
					} else {
						$p_value = wp_kses_post( $p_value );
					}
					$post_array[ str_replace("de_fb_", "", $key) ] = $p_value;
				}
			}

			$submit_result = '';
			set_query_var( 'submit_result', $submit_result );

			$de_fb_settings = get_option( 'de_fb_settings', array() );
			$form_settings = $de_fb_settings[$_POST['unique_id']];
			$form_key = $_POST['form_key'];

			$captcha_ok = true;

			if ( isset($_POST['simple_captcha']) ) {
				$captcha_result = $_POST['simple_captcha'];
				$captcha_original = $_POST['s_nonce'];
				if ( md5( $captcha_result ) != $captcha_original ) {
					$captcha_ok = false;
				}
			}

			if ( empty( $_POST['form_key'] ) ) {
				$captcha_ok = false;
			} else {

				if ( empty( $form_settings ) ) {
					$captcha_ok = false;
				} else {
					if ( $form_settings['google_recaptcha'] == 'on' && in_array( $form_settings['recaptcha_sitekey_type'], array( 'recaptcha_2_in', 'recaptcha_3') ) ) {
						if ( empty( $_POST['recaptcha_token'] ) ) {
							$captcha_ok = false;
						} else {
							$secret_key = '';
							$score = 0;
							if ( $form_settings['recaptcha_sitekey_type'] == 'recaptcha_2_in' ) {
								$secret_key = $form_settings['recaptcha_seckey_in'];
								$score = $form_settings['recaptcha_score_in'];
							} else if ( $form_settings['recaptcha_sitekey_type'] == 'recaptcha_3' ) {
								$secret_key = $form_settings['recaptcha_seckey_v3'];
								$score = $form_settings['recaptcha_score_v3'];
							}

							$url = 'https://www.google.com/recaptcha/api/siteverify';
						    $data = array(
						        'secret' => $secret_key,
						        'response' => $_POST['recaptcha_token']
						    );

						  	$response = wp_remote_post( $url, array(
									'method'  => 'POST',
									'body'        => $data,
								)
							);

							if ( is_wp_error( $response ) ) {
								$captcha_ok = false;
							} else {
								$responseKeys = json_decode( wp_remote_retrieve_body( $response ), TRUE );

								if ( $responseKeys['success'] != true || $responseKeys['score'] < $score ) {
									$captcha_ok = false;
								}
							}
						}
					}
				}
			}

			if ( isset($_POST['form_type_confirm']) && $_POST['form_type_confirm'] !== "") {
				$captcha_ok = false;
			}

			if ( $captcha_ok == true ) {
				/*unset( $post_array['simple_captcha'] );
				unset( $post_array['s_nonce'] );*/

				do_action( 'df_before_process', $form_id, $post_array, $form_type );

				$post_type_keys = array_keys( $registered_post_types );

				$wp_upload_dir = wp_upload_dir();
				$upload_dir = $wp_upload_dir['basedir'] . '/de_fb_uploads/';
				$upload_url = $wp_upload_dir['baseurl'] . '/de_fb_uploads/';

				if (!file_exists($wp_upload_dir['basedir'] . '/de_fb_uploads')) {
		            mkdir($wp_upload_dir['basedir'] . '/de_fb_uploads', 0777, true);
		        }

				$uploaded_files = array();

				foreach( $form_settings['file_fields'] as $file_key => $file_field ) {
					if ( isset( $_POST[$file_field] ) && $_POST[$file_field] != '' ) {
						$uploaded_files[] = $_POST[$file_field];
					}
				}

				if ( !empty( $_POST['signature'] ) ) {
					foreach ($_POST['signature'] as $sg_field ) {
						$img_data = $_POST[$sg_field];
						$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img_data));
						$target_file = $upload_dir . 'signature_' . date('YmdHis') . '.png';

						if ( file_exists( $target_file ) ) {
							$target_file = $upload_dir . 'signature_' . date('YmdHis') . rand() . '.png';
						}

						file_put_contents( $target_file, $data);

						$attachment = array(
							'post_mime_type' => 'image/png',  // file type
							'post_title' => 'signature_' . date('YmdHis') . '.png',  // sanitize and use image name as file name
							'post_content' => '',  // could use the image description here as the content
							'post_status' => 'inherit'
						);

						$attachmentId = wp_insert_attachment( $attachment, $target_file );

						wp_update_attachment_metadata(
			                $attachmentId,
			                wp_generate_attachment_metadata( $attachmentId, $target_file )
			            );

						$_POST[$sg_field] = $attachmentId;
						$post_array[str_replace("de_fb_", "", $sg_field)] = $attachmentId;
					}
				}

				if ( in_array( $form_type, $post_type_keys ) ) {
					//Create Post Form
					$post_array['post_type'] = $form_type;

					$post_array['tax_input'] = array();
					$post_array['meta_input'] = array();
					if ( empty($post_array['post_status']) ) {
						$post_array['post_status'] = 'draft';
					}

					$acf_fields = array();

					if ( function_exists("acf_get_field_groups") ) {
						$acf_groups = acf_get_field_groups( array('post_type' => $form_type ) );
					
						if ( !empty( $acf_groups ) ) {
							foreach ( $acf_groups as $acf_group ) {
								$fields = acf_get_fields( $acf_group['key'] );
								foreach ( $fields as $field ) {
									if ( $field['type'] == 'group' ) {
										foreach ( $field['sub_fields'] as $sub_field ) {
											$acf_fields[$field['name'] . '_' . $sub_field['name']] = $sub_field;
										}
									}
									$acf_fields[$field['name']] = $field;
								}
							}
						}
					}

					if ( !empty( $_POST['tax_input'] ) && count($_POST['tax_input']) > 0 ) {
						foreach( $_POST['tax_input'] as $key => $tax_input ) {
							$tax_input_rname = str_replace( "de_fb_", "", $tax_input );
							$post_array['tax_input'][$tax_input_rname] = array();
							if ( isset($post_array[$tax_input_rname])) {
								if ( !is_array( $post_array[$tax_input_rname] ) ) {
									$post_array[$tax_input_rname] = explode(',', $post_array[$tax_input_rname] );
								}
								foreach( $post_array[$tax_input_rname] as $term_key => $term_slug ) {
									$term = get_term_by( 'slug', $term_slug, $tax_input_rname );
									if ( $term ) {
										$post_array['tax_input'][$tax_input_rname][] = $term->term_id;	
									} else {
										if ( $term_slug != '' ) {
											$terms = explode( ',', $term_slug );
											if ( !empty( $terms ) && count( $terms ) > 0 ) {
												foreach ( $terms as $term_name ) {
													$term = wp_insert_term( $term_name, $tax_input_rname );
													if ( !is_wp_error( $term ) ) {
														$post_array['tax_input'][$tax_input_rname][] = $term['term_id'];
													}	
												}
											}										
										}
									}							
								}
							}
							
							unset( $post_array[$tax_input_rname] );
						}
					}

					$meta_repeater_fields = array();

					if ( !empty( $_POST['meta_input'] ) && count($_POST['meta_input']) > 0 ) {
						foreach( $_POST['meta_input'] as $key => $meta_input ) {
							$meta_input_rname = str_replace( "de_fb_", "", $meta_input );
							$post_array['meta_input'][$meta_input_rname] = array();
							if ( !empty( $post_array[$meta_input_rname] ) ) {
								if ( !empty( $acf_fields[$meta_input_rname] ) 
									&& ( $acf_fields[$meta_input_rname]['type'] == 'group' 
										|| $acf_fields[$meta_input_rname]['type'] == 'repeater' 
										|| $acf_fields[$meta_input_rname]['type'] == 'gallery' ) ) {

									if ( $acf_fields[$meta_input_rname]['type'] == 'gallery' ) {
										$value_array = explode(',' , $post_array[$meta_input_rname] );
										$post_array['meta_input'][$meta_input_rname] = $value_array;
										$post_array['meta_input']['_' . $meta_input_rname] = $acf_fields[$meta_input_rname]['key'];
									} else {
										$value_array = explode(',' , $post_array[$meta_input_rname] );
										$sub_fields = $acf_fields[$meta_input_rname]['sub_fields'];	
										$file_cnt = count( $value_array );
										if ( $acf_fields[$meta_input_rname]['type'] == 'group' ) {
											$ind = 0;
											foreach ( $sub_fields as $sub_field ) {
												if ( $sub_field['type'] == 'image' || $sub_field['type'] == 'file' ) {
													if ( $file_cnt > $ind ) {
														$post_array['meta_input'][$acf_fields[$meta_input_rname]['name'] . '_' . $sub_field['name']] = $value_array[$ind];
													} else {
														$post_array['meta_input'][$acf_fields[$meta_input_rname]['name'] . '_' . $sub_field['name']] = '';
													}
													$post_array['meta_input']['_' . $acf_fields[$meta_input_rname]['name'] . '_' . $sub_field['name']] = $sub_field['key'];													
													$ind++;
													
												}
											}
										} else {
											$meta_repeater_fields[$meta_input_rname] = $post_array[$meta_input_rname];
										}
									}
								} else {
									$post_array['meta_input'][$meta_input_rname] = $post_array[$meta_input_rname];

									if ( !empty( $acf_fields[$meta_input_rname] ) ) {
										if ( $acf_fields[$meta_input_rname]['type'] == 'google_map' ) {
											// If only address is set by pasted address and have no latitude and longitude
											if ( !empty( $post_array[$meta_input_rname]['address'] ) && $post_array[$meta_input_rname]['lat'] == '' && $post_array[$meta_input_rname]['lng'] == '' ) {
												
												$et_google_api_settings = get_option( 'et_google_api_settings' );
	    										if ( isset( $et_google_api_settings['api_key'] ) ) {
	    											$temp_address = str_replace(" ", "+", $post_array[$meta_input_rname]['address']);
													$json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=" . $temp_address . "&key=" . $et_google_api_settings['api_key']);

													$json = json_decode( $json );

													if ( count( $json->{'results'} ) > 0 ) {
														$address_lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
														$address_lng = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
														$post_array['meta_input'][$meta_input_rname]['lat'] = strval($address_lat);
														$post_array['meta_input'][$meta_input_rname]['lng'] = strval($address_lng);
													}
	    										}
												
											}
										}
										$post_array['meta_input'][ '_' . $meta_input_rname] = $acf_fields[$meta_input_rname]['key'];
									}
								}
								unset( $post_array[$meta_input_rname] );
							}
						}
					}

					if ( !empty( $post_array['ID'] ) ) {
						$post_obj = get_post( $post_array['ID'] );
						$post_array['post_date'] = $post_obj->post_date;
					}

					do_action( 'df_before_insert_post', $form_id, $post_array );

					$post_id = wp_insert_post( $post_array );
					$enable_assign_terms = $form_settings['enable_assign_terms'];

					if ( !is_wp_error($post_id) ) {
						if ( !empty( $post_array['post_thumbnail'] ) ) {
							set_post_thumbnail($post_id, $post_array['post_thumbnail']);
						}

						if ( $enable_assign_terms == 'on' ) {
							if ( ! empty( $post_array['tax_input'] ) ) {
								foreach ( $post_array['tax_input'] as $taxonomy => $tags ) {
									$taxonomy_obj = get_taxonomy( $taxonomy );

									if ( ! $taxonomy_obj ) {
										/* translators: %s: Taxonomy name. */
										_doing_it_wrong( __FUNCTION__, sprintf( __( 'Invalid taxonomy: %s.' ), $taxonomy ), '4.4.0' );
										continue;
									}

									if ( is_array( $tags ) ) {
										$tags = array_filter( $tags );
									}
									wp_set_post_terms( $post_id, $tags, $taxonomy );
								}
							}
						}

						if ( !empty( $meta_repeater_fields ) ) {
							foreach ($meta_repeater_fields as $repeater_key => $repeater_field ) {
								$sub_fields = $acf_fields[$repeater_key]['sub_fields'];	
								$value_array = explode(',' , $repeater_field );
								$file_cnt = count( $value_array );
								foreach ( $sub_fields as $sub_field ) {
									if ( $sub_field['type'] == 'image' || $sub_field['type'] == 'file' ) {
										for ( $ind = 0; $ind < $file_cnt; $ind++ ) {
											$row = array(
												$sub_field['name']	=> $value_array[$ind]
											);
											add_row( $acf_fields[$repeater_key]['name'], $row, $post_id );
										}													
										break;
									}
								}
							}
						}
						$submit_result = 'success';
						set_query_var( 'df_submit_result', 'success' );
						set_query_var( 'df_submit_formkey', $form_key );
						do_action( 'df_after_insert_post', $form_id, $post_id, $post_array );
					} else {
						$submit_result = 'failed';
						$err_message = $post_id->get_error_message();

						set_query_var( 'df_submit_result', 'failed' );
						set_query_var( 'df_submit_formkey', $form_key );
						set_query_var( 'df_submit_message', $err_message );
					}
				} else if ( $form_type == 'register' ) {
					// User Register form
					do_action( 'df_before_insert_user', $form_id, $post_array );

					if ( !empty( $post_array['ID'] ) ) {
						if ( !empty($post_array['user_pass']) && !empty($post_array['pass_repeat']) && $post_array['user_pass'] == '' && $post_array['pass_repeat'] == '' ) {
							unset( $post_array['user_pass']);
							unset( $post_array['pass_repeat']);
						} else {
							if ( !empty( $post_array['user_pass'] )){
								$post_array['user_pass'] = wp_hash_password( $post_array['user_pass'] );	
							}							
						}

						$user_obj = get_user_by( 'ID', $post_array['ID'] );

						if ( !isset( $post_array['user_login'] ) ) {
							$post_array['user_login'] = $user_obj->user_login;
						}
					}

					if ( !isset( $post_array['user_login'] ) && isset( $post_array['user_email'] ) ) { 
						$post_array['user_login'] = $post_array['user_email']; 
					}

					$user_id = wp_insert_user( $post_array );

					if ( ! is_wp_error( $user_id ) ) {
						$user_obj = get_user_by( 'ID', $user_id );

						$acf_fields = array();

						if ( function_exists("acf_get_field_groups") ) {
							$acf_groups = acf_get_field_groups( );
						
							if ( !empty( $acf_groups ) ) {
								foreach ( $acf_groups as $acf_group ) {
									$fields = acf_get_fields( $acf_group['key'] );
									foreach ( $fields as $field ) {
										$acf_fields[$field['name']] = $field;
									}
								}
							}
						}

						if ( !empty( $post_array['user_meta'] ) && count($post_array['user_meta']) > 0 ) {
							foreach( $post_array['user_meta'] as $key => $user_meta ) {
								$user_meta_rname = str_replace("de_fb_" , "", $user_meta );

								if ( !empty( $post_array[$user_meta_rname] ) ) {
									if ( !empty( $acf_fields[$user_meta_rname] ) 
										&& ( $acf_fields[$user_meta_rname]['type'] == 'group' 
											|| $acf_fields[$user_meta_rname]['type'] == 'repeater' 
											|| $acf_fields[$user_meta_rname]['type'] == 'gallery' ) ) {

										if ( $acf_fields[$user_meta_rname]['type'] == 'gallery' ) {
											$value_array = explode(',' , $post_array[$user_meta_rname] );

											update_user_meta( $user_id, $user_meta_rname, $value_array );
											update_user_meta( $user_id, '_' . $user_meta_rname, $acf_fields[$user_meta_rname]['key'] );
										} else {
											$value_array = explode(',' , $post_array[$user_meta_rname] );
											$sub_fields = $acf_fields[$user_meta_rname]['sub_fields'];	
											$file_cnt = count( $value_array );
											if ( $acf_fields[$user_meta_rname]['type'] == 'group' ) {
												$ind = 0;
												foreach ( $sub_fields as $sub_field ) {
													if ( $sub_field['type'] == 'image' || $sub_field['type'] == 'file' ) {

														if ( $file_cnt > $ind ) {
															update_user_meta( $user_id, $acf_fields[$user_meta_rname]['name'] . '_' . $sub_field['name'], $value_array[$ind] );
														} else {
															delete_user_meta( $user_id, $acf_fields[$user_meta_rname]['name'] . '_' . $sub_field['name'] );
														}
														
														update_user_meta( $user_id, '_' . $acf_fields[$user_meta_rname]['name'] . '_' . $sub_field['name'], $sub_field['key'] );
														$ind++;
														
													}
												}
											}
										}
									} else {
										if ( !empty( $uploaded_files[$user_meta] ) ) {
											update_user_meta( $user_id, $user_meta_rname, $uploaded_files[$user_meta] );
										} else if ( !empty( $post_array[$user_meta_rname] ) ) {
											if ( $acf_fields[$user_meta_rname]['type'] == 'google_map' ) {
												// If only address is set by pasted address and have no latitude and longitude
												if ( !empty( $post_array[$user_meta_rname]['address'] ) && $post_array[$user_meta_rname]['lat'] == '' && $post_array[$user_meta_rname]['lng'] == '' ) {
													
													$et_google_api_settings = get_option( 'et_google_api_settings' );
		    										if ( isset( $et_google_api_settings['api_key'] ) ) {
		    											$temp_address = str_replace(" ", "+", $post_array[$user_meta_rname]['address']);
														$json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=" . $temp_address . "&key=" . $et_google_api_settings['api_key']);

														$json = json_decode( $json );

														if ( count( $json->{'results'} ) > 0 ) {
															$address_lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
															$address_lng = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
															$post_array[$user_meta_rname]['lat'] = strval($address_lat);
															$post_array[$user_meta_rname]['lng'] = strval($address_lng);
														}
		    										}													
												}
											}
											update_user_meta( $user_id, $user_meta_rname, $post_array[$user_meta_rname] );
											update_user_meta( $user_id, '_' . $user_meta_rname, $acf_fields[$user_meta_rname]['key'] );
										}
									}
									unset( $post_array[$user_meta_rname] );
								}
							}
						}

						if ( $form_settings['auto_login'] == 'on' ) {
							wp_set_auth_cookie( $user_id );
						}

						$submit_result = 'success';
						set_query_var( 'df_submit_result', 'success' );
						set_query_var( 'df_submit_formkey', $form_key );
						do_action( 'df_after_insert_user', $form_id, $user_id, $post_array );
					} else {
						$submit_result = 'failed';
						set_query_var( 'df_submit_result', 'failed' );
						set_query_var( 'df_submit_formkey', $form_key );
						$err_message = $user_id->get_error_message();
						set_query_var( 'df_submit_message', $err_message );
					}
				} else if ( $form_type == 'login' ) {
					$creds = array();
					$creds['user_login'] = isset($post_array['user_login'])?$post_array['user_login']:(isset($post_array['user_email'])?$post_array['user_email']:'');
					$creds['user_password'] = isset($post_array['user_pass'])?$post_array['user_pass']:'';
					$creds['remember'] = isset( $post_array['rememberme'] ) ? true : false;
					$user = wp_signon( $creds, false );
	 				if ( !(is_wp_error( $user ) ) ) {
	 					$submit_result = 'success';
						set_query_var( 'df_submit_result', 'success' );
						set_query_var( 'df_submit_formkey', $form_key );
						wp_set_auth_cookie( $user->id );
					} else {
						$submit_result = 'failed';
						$err_message = $user->get_error_message();
						set_query_var( 'df_submit_message', $err_message );
						set_query_var( 'df_submit_result', 'failed' );
						set_query_var( 'df_submit_formkey', $form_key );
					}
				} else if ( $form_type == 'contact') {
					
				}

				if ( !empty( $post_array['bloom_subscribe'] ) ) {
					$bloom_array = explode( '_', $post_array['bloom_subscribe'] );
					if ( count( $bloom_array ) > 2 
						&& ( 
							( $bloom_array[0] == 'campaign' && $bloom_array[1] == 'monitor' ) 
							|| ( $bloom_array[0] == 'constant' && $bloom_array[1] == 'contact' ) 
						) ) {
						$email_service = $bloom_array[0] . '_' . $bloom_array[1];
						unset( $bloom_array[0] );
						unset( $bloom_array[1] );
					} else {
						$email_service = $bloom_array[0];
						unset($bloom_array[0]);
					}

					$email_id = implode('_', $bloom_array);
					$bloom_email_list = array();
					$email_list_detail = array();
					$account_name = '';
					$bloom_name_field_id = $form_settings['bloom_name_field'];
					$bloom_lastname_field_id = $form_settings['bloom_lastname_field'];
					$bloom_email_field_id = $form_settings['bloom_email_field'];

					$bloom_name = ($bloom_name_field_id != "" && isset($_POST[$form_settings['fields']['de_fb_' . $bloom_name_field_id]]))?$_POST[$form_settings['fields']['de_fb_' . $bloom_name_field_id]]:'';

					if ( $bloom_name == '' ) {
						$bloom_name = ($bloom_name_field_id != "" && isset($_POST[$form_settings['fields'][$bloom_name_field_id]]))?$_POST[$form_settings['fields'][$bloom_name_field_id]]:'';
					}
					$bloom_lastname = ($bloom_lastname_field_id != "" && isset($_POST[$form_settings['fields']['de_fb_' . $bloom_lastname_field_id]]))?$_POST[$form_settings['fields']['de_fb_' . $bloom_lastname_field_id]]:'';
					if ( $bloom_lastname == '' ) {
						$bloom_lastname = ($bloom_lastname_field_id != "" && isset($_POST[$form_settings['fields'][$bloom_lastname_field_id]]))?$_POST[$form_settings['fields'][$bloom_lastname_field_id]]:'';
					}
					$bloom_email = ($bloom_email_field_id != "" && isset($_POST[$form_settings['fields']['de_fb_' . $bloom_email_field_id]]))?$_POST[$form_settings['fields']['de_fb_' . $bloom_email_field_id]]:'';

					if ( $bloom_email == '' ) {
						$bloom_email = ($bloom_email_field_id != "" && isset($_POST[$form_settings['fields'][$bloom_email_field_id]]))?$_POST[$form_settings['fields'][$bloom_email_field_id]]:'';
					}

					if ( class_exists('ET_Bloom') ) {
						$bloom_obj = ET_Bloom::get_this();
						if ( $bloom_obj ) {
							$all_accounts = $bloom_obj->providers->accounts();
							if ( !empty( $all_accounts ) ) {
								$bloom_email_list = $all_accounts[$email_service];
								if ( !empty( $bloom_email_list ) ) {
									foreach( $bloom_email_list as $name => $details ) {
										if ( !empty( $details['lists'][$email_id] ) ) {
											$account_name = $name;
											$email_list_detail = $details['lists'][$email_id];
											break;
										}
									}
								}
							}
							if ( !empty( $email_list_detail ) ) {
								$provider = $bloom_obj->providers->get( $email_service, $account_name, 'bloom' );
								$result = $provider->subscribe( array(
									'service'       => $email_service,
									'account_name'  => $account_name,
									'list_id'       => $email_id,
									'email'         => $bloom_email,
									'name'          => $bloom_name,
									'last_name'     => $bloom_lastname,
									// This is for SendInBlue integration
									/*'custom_fields' => array( 
										'NOM'		=> $bloom_name, 
										'PRENOM'	=> $bloom_lastname, 
									)*/
								) );
							}
						}
					}
				}

				if ( !empty( $uploaded_files ) && count( $uploaded_files) > 0 ) {
					do_action( 'df_process_uploaded_files', $form_id,  $uploaded_files, $form_type );
				}

				do_action( 'df_after_process', $form_id, $post_array, $form_type );
			} else {
				$submit_result = 'failed';
				set_query_var( 'df_submit_result', 'failed' );
				set_query_var( 'df_submit_formkey', $form_key );
				$err_message = esc_html__('Captcha Error', 'divi-form-builder');
				set_query_var( 'df_submit_message', $err_message );
				do_action( 'df_captcha_failed', $form_id, $post_array, $form_type );
			}

			if ( $form_type != 'contact' && $submit_result == 'success' ) {
				$enable_submission_notification = $form_settings['enable_submission_notification'];
				if ( $enable_submission_notification == 'on') {
					$use_custom_email 				= $form_settings['use_custom_email'];
					$acf_field_type 				= $form_settings['acf_field_type'];
					$acf_email_field_linked 		= $form_settings['acf_email_field_linked'];
					$acf_email_field 				= $form_settings['acf_email_field'];
					$custom_contact_email 			= $form_settings['custom_contact_email'];
					$from_name_field				= $form_settings['from_name_field'];
					$from_email_field				= $form_settings['from_email_field'];
					$from_name 						= $form_settings['from_name'];
					$from_email 					= $form_settings['from_email'];
					$replyto_name 					= $form_settings['replyto_name'];
					$replyto_email					= $form_settings['replyto_email'];
					$custom_from_name				= $form_settings['custom_from_name'];
					$custom_from_email				= $form_settings['custom_from_email'];
					$email_cc 						= $form_settings['email_cc'];
					$email_bcc 						= $form_settings['email_bcc'];
					$email_title 					= $form_settings['email_title'];
					$email_template 				= $form_settings['email_template'];
					$send_copy_to_sender 			= $form_settings['send_copy_to_sender'];
					$sender_setting 				= $form_settings['sender_setting'];
					$sender_name_field				= str_replace(" ", "_", strtolower($form_settings['sender_name_field']));
					$sender_email_field				= str_replace(" ", "_", strtolower($form_settings['sender_email_field']));
					$reply_from_name 				= $form_settings['reply_from_name'];
					$reply_custom_from_name 		= $form_settings['reply_custom_from_name'];
					$reply_from_email 				= $form_settings['reply_from_email'];
					$reply_custom_from_email 		= $form_settings['reply_custom_from_email'];
					$reply_email_title				= $form_settings['reply_email_title'];
					$reply_email_template 			= $form_settings['reply_email_template'];
					$reply_to_name 					= $form_settings['reply_to_name'];
					$reply_to_email					= $form_settings['reply_to_email'];

					$email_template_html			= $form_settings['email_template_html'];
					$reply_email_template_html		= $form_settings['reply_email_template_html'];

					$email = get_bloginfo('admin_email');

					$header = array( 'Content-Type: text/html; charset=UTF-8' );
					$reply_header = array( 'Content-Type: text/html; charset=UTF-8' );

					$from_text = 'From: ';
					$reply_from_text = $from_text;

					if ( $from_name == 'default' ) {
						$from_text .= get_bloginfo( 'name' );
					} elseif ($from_name == 'sender' ) {
						if ( isset( $form_settings['fields']['de_fb_' . $from_name_field ] ) ) {
							$temp_key = $form_settings['fields']['de_fb_' . $from_name_field ];
						} else if ( isset( $form_settings['fields'][$from_name_field ] ) ) {
							$temp_key = $form_settings['fields'][$from_name_field ];
						}
						if ( isset( $_POST[ $temp_key ] ) ){
							$from_text .= ' ' . $_POST[ $temp_key ];
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
						$temp_key = '';
						if ( isset( $form_settings['fields']['de_fb_' . $from_email_field ] ) ) {
							$temp_key = $form_settings['fields']['de_fb_' . $from_email_field ];
						} else if ( isset( $form_settings['fields'][$from_email_field ] ) ) {
							$temp_key = $form_settings['fields'][$from_email_field ];
						}

						if ( isset( $_POST[ $temp_key ] ) ){
							$from_text .= ' <' . $_POST[ $temp_key ] . '>';
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
						$temp_key = '';
						if ( isset($form_settings['fields']['de_fb_' . $replyto_email ] ) ){
							$temp_key = $form_settings['fields']['de_fb_' . $replyto_email ];
						} else if ( isset($form_settings['fields']['de_fb_' . $replyto_email ] ) ) {
							$temp_key = $form_settings['fields'][$replyto_email ];
						}
						if ( isset( $_POST[ $temp_key ] ) ) {
							if ( $replyto_name != '' ) {
								if ( isset( $_POST[ $temp_key ] ) ) {
									$header[] = 'Reply-To: ' . $_POST[ $temp_key ] . ' <' . $_POST[ $temp_key ] . '>';
								} else {
									$header[] = 'Reply-To: ' . $_POST[ $temp_key ];
								}
							}
						}/* else {
							if ( $replyto_name != '' ) {
								$header[] = 'Reply-To: '. $replyto_name . ' <' . $replyto_email . '>';
							} else {
								$header[] = 'Reply-To: ' . $replyto_email;
							}
						}*/
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

					$title = esc_html__( 'New Message Arrived', 'divi-form-builder' );

					if ( !empty( $email_title ) ) {
						$title = htmlspecialchars_decode($email_title);
					}

					$reply_title = esc_html__( 'We received your message', 'divi-form-builder' );
					if ( !empty( $reply_email_title ) ) {
						$reply_title = $reply_email_title;
					}

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

					foreach ($form_settings['fields'] as $field_key => $field_name ) {
						$field_val = '';
						$field_id = str_replace( "de_fb_", "", $field_key );

						if ( !empty( $_POST[$field_name] ) ) {

							if ( $field_name == 'de_fb_post_thumbnail' ) {
								$field_val = '<img src="' . wp_get_attachment_image_url( $post_array['post_thumbnail'] ) . '" width="300" height="150" style="max-width:100%;height:auto;">';
							} else {
								if ( !empty($_POST['signature']) && in_array( $field_name, $_POST['signature'] ) ) {
									$field_val = '<img src="' . wp_get_attachment_image_url( $_POST[$field_name] ) . '" width="300" height="150" style="max-width:100%;height:auto;">';
								} else {
									if ( isset( $form_settings['file_fields'][$field_key] ) && $form_settings['file_fields'][$field_key] == $field_name ) {
										$field_val = "";
										$field_val_arr = explode( ",", $_POST[$field_name] );
										if ( !is_array( $field_val_arr) ) {
											$field_val_arr = array( $field_val_arr );
										}
										if ( stripos( $body, "%%{$field_id}%%") !== false || $email_template == "" ) {
											foreach ( $field_val_arr as $key => $val ) {
												$mail_attachs[] = get_attached_file( $val );	
											}											
										}
										if ( stripos( $reply_body, "%%{$field_id}%%") !== false || $reply_email_template == "") {
											foreach ( $field_val_arr as $key => $val ) {
												$reply_mail_attachs[] = get_attached_file( $val );
											}											
										}
									} else if ( is_array( $_POST[$field_name] ) ) {
										$field_val = implode( ',', $_POST[$field_name] );	
									} else {
										$field_val = $_POST[$field_name];	
									}
								}
							}

							if ( is_array( $_POST[$field_name] ) ) {
								$mail_field_value = '';

								if ( isset( $_POST['tax_input'] ) && in_array( $field_name, $_POST['tax_input'] ) ) {
									$terms_array = array();
									foreach ( $_POST[$field_name] as $tax_term ) {
										$taxonomy_slug = str_replace( "de_fb_", "", $field_name );
										$term_obj = get_term_by('slug', $tax_term, $taxonomy_slug );
										$terms_array[] = $term_obj->name; 
									}

									$mail_field_value = implode(',', $terms_array );
								} else if ( ( isset( $_POST['meta_input'] ) && in_array( $field_name, $_POST['meta_input'] ) )
									|| ( isset( $_POST['user_meta'] ) && in_array( $field_name, $_POST['user_meta'] ) ) ) {
									$meta_array = array();
									$meta_input_rname = str_replace( "de_fb_", "", $field_name );
									if ( !empty( $acf_fields[$meta_input_rname] ) 
										&& ( $acf_fields[$meta_input_rname]['type'] == 'select' 
											|| $acf_fields[$meta_input_rname]['type'] == 'checkbox' 
											|| $acf_fields[$meta_input_rname]['type'] == 'radio' ) ) {
										$choices = $acf_fields[$meta_input_rname]['choices'];
										foreach ( $_POST[$field_name] as $meta_index ) {
											$meta_array[] = $choices[$meta_idnex];
										}

										$mail_field_value = implode( ',', $meta_array );
									} else {
										$mail_field_value = implode( ',', $_POST[$field_name] );
									}
								} else {
									$mail_field_value = implode( ',', $_POST[$field_name] );
								}

								if ( !empty( $email_template ) ) {
									$body = str_ireplace( "%%{$field_id}%%", wp_strip_all_tags( $mail_field_value ), $body );
								} else {
									$body .= '<p><label>' . $field_id . ':</label>' . $mail_field_value . '</p>';
								}

								if ( !empty( $reply_email_template ) ) {
									$reply_body = str_ireplace( "%%{$field_id}%%", wp_strip_all_tags( $mail_field_value ), $reply_body );
								} else {
									$reply_body .= '<p><label>' . $field_id . ':</label>' . $mail_field_value . '</p>';
								}

								$title = str_ireplace( "%%{$field_id}%%", $mail_field_value, $title);
								$reply_title = str_ireplace("%%{$field_id}%%", $mail_field_value, $reply_title);
							} else {

								$mail_field_value = '';

								if ( isset( $_POST['tax_input'] ) && in_array( $field_name, $_POST['tax_input'] ) ) {
									$taxonomy_slug = str_replace( "de_fb_", "", $field_name );
									$term_obj = get_term_by('slug', $_POST[$field_name], $taxonomy_slug );

									$mail_field_value = $term_obj->name;
								} else if ( ( isset( $_POST['meta_input'] ) && in_array( $field_name, $_POST['meta_input'] ) )
									|| ( isset( $_POST['user_meta'] ) && in_array( $field_name, $_POST['user_meta'] ) ) ) {
									$meta_input_rname = str_replace( "de_fb_", "", $field_name );
									if ( !empty( $acf_fields[$meta_input_rname] ) 
										&& ( $acf_fields[$meta_input_rname]['type'] == 'select' 
											|| $acf_fields[$meta_input_rname]['type'] == 'checkbox' 
											|| $acf_fields[$meta_input_rname]['type'] == 'radio' ) ) {
										$choices = $acf_fields[$meta_input_rname]['choices'];
										
										$mail_field_value = $choices[$_POST[$field_name]];
									} else {
										$mail_field_value = $_POST[$field_name];
									}
								} else {
									$mail_field_value = $_POST[$field_name];
								}

								if ( !empty( $email_template ) ) {
									$body = str_ireplace( "%%{$field_id}%%", $mail_field_value, $body );
								} else {
									if ( !( $form_type == 'register' && ( str_replace( "de_fb_", "", $field_name ) == 'user_pass' || str_replace( "de_fb_", "", $field_name ) == 'pass_repeat' ) ) ) {
										$body .= '<p><label>' . $field_id . ':</label>' . $mail_field_value . '</p>';	
									}									
								}

								if ( !empty( $reply_email_template ) ) {
									$reply_body = str_ireplace( "%%{$field_id}%%", $mail_field_value, $reply_body );
								} else {
									if ( !( $form_type == 'register' && ( str_replace( "de_fb_", "", $field_name ) == 'user_pass' || str_replace( "de_fb_", "", $field_name ) == 'pass_repeat' ) ) ) {
										$reply_body .= '<p><label>' . $field_id . ':</label>' . $mail_field_value . '</p>';
									}
								}

								$title = str_ireplace( "%%{$field_id}%%", $field_val, $title);
								$reply_title = str_ireplace("%%{$field_id}%%", $field_val, $reply_title);
							}
							
						} else if ( !empty( $uploaded_files[$field_name] ) ) {
							$file_name = basename( $uploaded_files[$field_name] );
							if ( !empty( $email_template ) ) {
								$body = str_ireplace( "%%{$field_id}%%", '<a href="' . $uploaded_files[$field_name] . '" target="_blank">' . $file_name. '</a>', $body );
							} else {
								$body .= '<p><label>' . $field_id . ':</label><a href="' . $uploaded_files[$field_name] . '" target="_blank">' . $file_name. '</a></p>';	
							}

							if ( !empty( $reply_email_template ) ) {
								$reply_body = str_ireplace( "%%{$field_id}%%", '<a href="' . $uploaded_files[$field_name] . '" target="_blank">' . $file_name. '</a>', $reply_body );
							} else {
								$reply_body .= '<p><label>' . $field_id . ':</label><a href="' . $uploaded_files[$field_name] . '" target="_blank">' . $file_name. '</a></p>';	
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
					}

					$login_user = wp_get_current_user();

					if ( $login_user->ID != 0 ) {
						$body = str_ireplace( "%%login_user%%", $login_user->display_name, $body );
						$reply_body = str_ireplace( "%%login_user%%", $login_user->display_name, $reply_body );	
					} else {
						$body = str_ireplace( "%%login_user%%", '', $body );
						$reply_body = str_ireplace( "%%login_user%%", '', $reply_body );
					}
					

					if ( $email_template_html == 'on' ) {
						$body = str_replace( "\r\n", "", $body);
						$body = str_replace( "\n", "", $body);
					} else {
						$body = str_replace( "\r\n", "<br/>", $body);
						$body = str_replace( "\n", "<br/>", $body);
					}					

					$body = stripslashes( $body );

					$body = apply_filters( 'df_notification_body', $body, $post_array );
					$title = stripslashes( $title );

					$email = apply_filters( 'df_notifcation_recipient', $email, $form_id, $post_array );
					$mail_result = wp_mail( $email, $title, $body, $header, $mail_attachs );

					if ( $mail_result ) {
						if ( $send_copy_to_sender == 'on' ){
							if ( $sender_setting == 'sender' ) {
								$temp_key = '';
								if ( isset( $form_settings['fields']['de_fb_' . $sender_email_field] ) ) {
									$temp_key = $form_settings['fields']['de_fb_' . $sender_email_field];
								} else if ( isset( $form_settings['fields'][$sender_email_field] ) ) {
									$temp_key = $form_settings['fields'][$sender_email_field];
								}
								$sender_email = $_POST[$temp_key];
							} else if ( $sender_setting == 'login_user') {
								$sender_email = ($login_user->ID != 0)?$login_user->user_email:'';
							}

							if ( $sender_email != '' ) {

								if ( $reply_email_template_html == 'on' ) {
									$reply_body = str_replace( "\r\n", "", $reply_body);
									$reply_body = str_replace( "\n", "", $reply_body);
								} else {
									$reply_body = str_replace( "\r\n", "<br/>", $reply_body);
									$reply_body = str_replace( "\n", "<br/>", $reply_body);	
								}
								
								$reply_body = stripslashes( $reply_body );
								$reply_body = apply_filters( 'df_confirmation_body', $reply_body, $post_array );

								$reply_title = stripslashes( $reply_title );
								$send_mail_result = wp_mail( $sender_email, $reply_title, $reply_body, $reply_header, $reply_mail_attachs );
							}
						}
					}
				}
			}

			$redirect_url_after_submission = !empty( $_POST['redirect_url_after_submission'] )?$_POST['redirect_url_after_submission']:'';
			$redirect_url_after_failed = !empty( $_POST['redirect_url_after_failed'] )?$_POST['redirect_url_after_failed']:'';

			if ( $form_type != 'contact' && $submit_result == 'success' && $redirect_url_after_submission != '' ) {
				do_action( 'df_before_redirect', $form_id, $submit_result, $redirect_url_after_submission );
				wp_redirect( $redirect_url_after_submission  ) ;
			}

			if ( $form_type != 'contact' && $submit_result == 'failed' && $redirect_url_after_failed != '' ) {
				do_action( 'df_before_redirect', $form_id, $submit_result, $redirect_url_after_failed );
				wp_redirect( $redirect_url_after_failed  ) ;
			}
		}
	}

	public function register_divi_form_scripts() {
		wp_register_script( 'de_fb_load_image', DE_FB_URL . '/js/jquery.fileupload/load-image.all.min.js', array('jquery'), DE_FB_VERSION, true );
		wp_register_script( 'de_fb_tmpl', DE_FB_URL . '/js/jquery.fileupload/tmpl.min.js', array('jquery'), DE_FB_VERSION, true );
		wp_register_script( 'de_fb_iframe_transport', DE_FB_URL . '/js/jquery.fileupload/jquery.iframe-transport.js', array('jquery'), DE_FB_VERSION, true );
		wp_register_script( 'de_fb_file_upload', DE_FB_URL . '/js/jquery.fileupload/jquery.fileupload.js', array('jquery', 'jquery-ui-core'), DE_FB_VERSION, true );
		wp_register_script( 'de_fb_file_upload_image', DE_FB_URL . '/js/jquery.fileupload/jquery.fileupload-image.js', array('de_fb_file_upload'), DE_FB_VERSION, true );
		wp_register_script( 'de_fb_file_upload_process', DE_FB_URL . '/js/jquery.fileupload/jquery.fileupload-process.js', array('de_fb_file_upload'), DE_FB_VERSION, true );
		wp_register_script( 'de_fb_file_upload_ui', DE_FB_URL . '/js/jquery.fileupload/jquery.fileupload-ui.js', array('de_fb_file_upload'), DE_FB_VERSION, true );
		wp_register_script( 'de_fb_file_upload_validate', DE_FB_URL . '/js/jquery.fileupload/jquery.fileupload-validate.js', array('de_fb_file_upload'), DE_FB_VERSION, true );
		wp_register_style( 'de_fb_file_upload', DE_FB_URL . '/css/jquery.fileupload.min.css', array(), DE_FB_VERSION );
		wp_register_style( 'de_fb_file_upload_ui', DE_FB_URL . '/css/jquery.fileupload-ui.min.css', array(), DE_FB_VERSION );
		wp_register_script( 'de_fb_js', DE_FB_URL . '/js/divi-form-builder.min.js', array(), DE_FB_VERSION, true );
		wp_register_script( 'de_fb_signature', DE_FB_URL . '/js/signature_pad.min.js', array(), DE_FB_VERSION, true );
		wp_register_script( 'de_fb_validate', DE_FB_URL . '/js/jquery.validation/jquery.validate.min.js', array(), DE_FB_VERSION, true );
		wp_register_script( 'de_fb_validate_additional', DE_FB_URL . '/js/jquery.validation/additional-methods.min.js', array(), DE_FB_VERSION, true );

		$et_google_api_settings = get_option( 'et_google_api_settings' );
	    if ( isset( $et_google_api_settings['api_key'] ) ) {
	        wp_register_script('de_fb_googlemaps_script',  'https://maps.googleapis.com/maps/api/js?key='.$et_google_api_settings['api_key'].'&libraries=places', array()); // with Google Maps API fix
	    }
	}


	public function dfb_check_validation() {

		if (defined('DOING_AJAX') && DOING_AJAX) {
			return;
		}

		$a_result = '';

		$de_su = 'https://diviengine.com/';

		$de_su_json = $de_su . 'wp-json/de_plugins/products';

		$site_url = get_option( 'siteurl' );
		$site_url = str_replace( 'https://', '', $site_url );
		$site_url = str_replace( 'http://', '', $site_url );
		$site_url = rtrim( $site_url, '/' );

		$aj_gaket = get_option( 'et_automatic_updates_options' );
		$aj_gaket_val = $aj_gaket['api_key'];
		$code_l = get_option('divi_fb_license');
		$code_d = "Y";

		if ( isset( $code_l['key'] ) && $code_l['key'] !== '' ) {
			$code_d = $code_l['key'];
		}

		$product_id = '58499';
		$et_status = 'N';

		if ( DE_FB_P == 'm_a' && $aj_gaket_val != '' ) {
			$json = file_get_contents('https://www.elegantthemes.com/marketplace/index.php/wp-json/api/v1/check_subscription/product_id/'.$product_id.'/api_key/'.$aj_gaket_val);
	        $data = json_decode($json);
	        $code_m = $data->code;
	        if ( $code_m != 'no_billing_records') {
				$et_status = 'Y';
	        }
		}

		$secure_string = $site_url . '|' . 'de_fb' . '|' . DE_FB_P . '|' . $code_d . '|' . $et_status;

		$file = $this->plugin_dir . '/key.rem';

		$de_keys = get_option( 'de_keys', array() );
		if ( !file_exists( $file ) ) {
			if ( !empty( $de_keys['de_fb'] ) ) {
				$keypair = $de_keys['de_fb'];
				file_put_contents($file, $keypair);
			} else {
				$keypair = md5( $site_url );
				file_put_contents($file, $keypair);
				$de_keys['de_fb'] = $keypair;
				update_option( 'de_keys', $de_keys );
			}
		} else {
			$keypair = file_get_contents( $file );
			if ( $keypair == '' ) {
				$keypair = md5( $site_url );
			}
			file_put_contents($file, $keypair);
			$de_keys['de_fb'] = $keypair;
			update_option( 'de_keys', $de_keys );
		}

		$keyFile = $this->plugin_dir . '/.key';
		$key = '';

		if ( $et_status != 'Y' && file_exists( $keyFile ) ) {
			$key = file_get_contents( $keyFile );

			if ( $key == '' ) {
				$key = 'pw/ety9aX2o=';
			}
		}


		$body = array(
			'keypair'		=> $keypair,
			'key'			=> $key,
			'secure_str'	=> base64_encode( $secure_string )
		);

		$args = array(
			'body'        => $body,
		);

		$response = wp_remote_post( $de_su_json, $args );
		$a_result = str_replace('"', '', wp_remote_retrieve_body( $response ));

		if ( $a_result == 'msg_ok' ) {
			return true;
		} else {
			return false;
		}
	}
}

new DE_FormBuilder;