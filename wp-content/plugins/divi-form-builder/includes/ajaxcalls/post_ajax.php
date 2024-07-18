<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function de_fb_get_edit_form_ajax_handler() {
	$modal_layout = $_POST['modal_layout'];

	ob_start();

	echo do_shortcode( get_post_field('post_content', $modal_layout ) );

	// retrieve the styles for the modules
    $internal_style = ET_Builder_Element::get_style();
    // reset all the attributes after we retrieved styles
    ET_Builder_Element::clean_internal_modules_styles( false );
    $et_pb_rendering_column_content = false;

?>
	<div class="df-inner-styles">
<?php

	printf(
            '<style type="text/css" class="df_ajax_inner_styles">
              %1$s
            </style>',
            et_core_esc_previously( $internal_style )
        );

?>
	</div>
<?php

	$result['content'] = ob_get_clean();

	wp_send_json($result);
	wp_die();
}

add_action('wp_ajax_de_fb_get_edit_form_ajax_handler', 'de_fb_get_edit_form_ajax_handler' );
add_action('wp_ajax_nopriv_de_fb_get_edit_form_ajax_handler', 'de_fb_get_edit_form_ajax_handler' );

function de_fb_ajax_submit_ajax_handler() {


	global $wpdb;

	ob_start();

	$post_array = array();
	
	$form_type = $_POST['form_type'];
	$form_id = $_POST['form_id'];

	$registered_post_types = et_get_registered_post_type_options( false, false );
	unset($registered_post_types['attachment']);
	unset($registered_post_types['project']);

	$de_fb_settings = get_option( 'de_fb_settings', array() );
	$form_settings = $de_fb_settings[$_POST['unique_id']];
	$login_user = wp_get_current_user();

	$form_key = $_POST['form_key'];
	$form_key_arr = explode('-', $form_key);

	$post_id = $form_key_arr[0];
	$de_fb_form_num = $form_key_arr[1];

	$unique_id = $_POST['unique_id'];

	$enable_submission_notification = $form_settings['enable_submission_notification'];
	$use_custom_email 				= $form_settings['use_custom_email'];
	$acf_field_type 				= $form_settings['acf_field_type'];
	$acf_email_field_linked 		= $form_settings['acf_email_field_linked'];
	$acf_email_field 				= $form_settings['acf_email_field'];
	$custom_contact_email 			= $form_settings['custom_contact_email'];
	$from_name_field				= $form_settings['from_name_field'];
	$from_email_field				= $form_settings['from_email_field'];
	$from_name 						= $form_settings['from_name'];
	$from_email 					= $form_settings['from_email'];
	$custom_from_name				= $form_settings['custom_from_name'];
	$custom_from_email				= $form_settings['custom_from_email'];
	$email_cc 						= $form_settings['email_cc'];
	$email_bcc 						= $form_settings['email_bcc'];
	$email_title 					= $form_settings['email_title'];
	$email_template 				= $form_settings['email_template'];
	$send_copy_to_sender 			= $form_settings['send_copy_to_sender'];
	$sender_setting 				= $form_settings['sender_setting'];
	$sender_name_field				= $form_settings['sender_name_field'];
	$sender_email_field				= $form_settings['sender_email_field'];
	$reply_from_name 				= $form_settings['reply_from_name'];
	$reply_custom_from_name 		= $form_settings['reply_custom_from_name'];
	$reply_from_email 				= $form_settings['reply_from_email'];
	$reply_custom_from_email 		= $form_settings['reply_custom_from_email'];
	$reply_email_title				= $form_settings['reply_email_title'];
	$reply_email_template 			= $form_settings['reply_email_template'];
	$replyto_email 						= $form_settings['replyto_email'];
	$replyto_name 						= $form_settings['replyto_name'];
	$reply_to_email 					= $form_settings['reply_to_email'];

	$form_title 					= $form_settings['form_title'];
	$save_to_database				= $form_settings['save_to_database'];

	$messages 						= $form_settings['messages'];
	$message_position				= $form_settings['message_position'];

	if ( $_POST['form_type'] == 'contact' ) {
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

		$captcha_ok = true;

		if ( isset( $_POST['simple_captcha'] ) ) {
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
			do_action( 'df_before_process', $form_id, $post_array, $form_type );

			$post_type_keys = array_keys( $registered_post_types );

			$wp_upload_dir = wp_upload_dir();
			$upload_dir = $wp_upload_dir['basedir'] . '/de_fb_uploads/';
			$upload_url = $wp_upload_dir['baseurl'] . '/de_fb_uploads/';

			if (!file_exists($wp_upload_dir['basedir'] . '/de_fb_uploads')) {
	            mkdir($wp_upload_dir['basedir'] . '/de_fb_uploads', 0777, true);
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
				}
			}

			$uploaded_files = array();

			foreach( $form_settings['file_fields'] as $file_key => $file_field ) {
				if ( isset( $_POST[$file_field] ) && $_POST[$file_field] != '' ) {
					$uploaded_files[] = $_POST[$file_field];
				}
			}

			/*if ( !empty( $_FILES ) ) {
				foreach ( $_FILES as $key => $file ) {
					if ( !empty( $file['name'] ) ) {
						$target_file = $upload_dir . basename( $file["name"] );
						$target_url = $upload_url . basename( $file["name"] );
						if (file_exists($target_file)) {
							$file_info = pathinfo($target_file);
							$current_time = date('YmdHis');
							$target_file = $file_info['dirname'] . '/' . $file_info['filename'] . $current_time . '.' . $file_info['extension'];
							$target_url = $upload_url . $file_info['filename'] . $current_time . '.' . $file_info['extension'];
						}
						move_uploaded_file( $_FILES[$key]["tmp_name"], $target_file );
						$uploaded_files[$key] = $target_url;
					}
				}
			}*/

			if ( !empty( $uploaded_files ) && count( $uploaded_files) > 0 ) {
				do_action( 'df_process_uploaded_files', $form_id,  $uploaded_files, $form_type );
			}

			$field_title_array = $post_array['field_title'];
			$field_names_array = array_unique( array_merge( $post_array['field_name'], array_values( $form_settings['fields'] ) ) );
			$field_ids_array = array_unique( array_merge( $post_array['field_id'], array_keys( $form_settings['fields'] ) ) );

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
					$sender_field_temp = str_replace( "de_fb_", "", $field_names_array[ $field_key ]);
					$from_text .= ' ' . $post_array[ $sender_field_temp ];
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
					$sender_field_temp = str_replace( "de_fb_", "", $field_names_array[ $field_key ]);
					$from_text .= ' <' . $post_array[ $sender_field_temp ] . '>';
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
			$mail_attachs = array();
			$reply_mail_attachs = array();

			if ( $replyto_email != '' ) {
				$field_key = array_search( "de_fb_" . $replyto_email, $field_ids_array );
				if ( $field_key === FALSE ) {
					$field_key = array_search( $replyto_email, $field_ids_array );
				}
				if ( $field_key !== FALSE ) {
					$sender_field_email = str_replace( "de_fb_", "", $field_names_array[ $field_key ]);
					if ( $replyto_name != '' ) {
						$field_name_key = array_search( "de_fb_" . $replyto_name, $field_ids_array );
						if ( $field_name_key === FALSE ) {
							$field_name_key = array_search( $replyto_name, $field_ids_array );
						}
						if ( $field_name_key !== FALSE ) {
							$sender_field_name = str_replace( "de_fb_", "", $field_names_array[ $field_name_key ]);
							$header[] = 'Reply-To: ' . $post_array[ $sender_field_name ] . ' <' . $post_array[ $sender_field_email ] . '>';
						} else {
							$header[] = 'Reply-To: ' . $post_array[ $sender_field_email ];
						}
					}
				}
			}

			/*if ( $replyto_email != '' ) {
				if ( $replyto_name != '' ) {
					$header[] = 'Reply-To: '. $replyto_name . ' <' . $replyto_email . '>';
				} else {
					$header[] = 'Reply-To: ' . $replyto_email;
				}
			}*/

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

			if ( $use_custom_email == 'off' && !empty( $custom_contact_email ) ) {
				$email = $custom_contact_email;
			}

			$title = esc_html__( 'New Message Arrived', 'divi-form-builder' );

			if ( !empty( $email_title ) ) {
				$title = htmlspecialchars_decode($email_title);
			}

			$reply_title = esc_html__( 'We received your message', 'divi-form-builder' );
			if ( !empty( $reply_email_title ) ) {
				$reply_title = $reply_email_title;
			}

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

				if ( $unique_id != '' && $unique_id != $form_key ){
					$form_row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_form_name} WHERE form_no = %s", $unique_id ) );
				} else {
					$form_row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_form_name} WHERE post_id = %d AND form_no = %s", $post_id, $unique_id ) );

					if ( empty( $form_row ) ) {
						$form_row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_form_name} WHERE post_id = %d AND form_no = %s", $post_id, $de_fb_form_num ) );	
					}
				}

				$save_form_title = $form_title;
				if ( $save_form_title == "" ) {
					$save_form_title = 'Contact Form #' . $unique_id;
				}

				$de_form_arr = array(
					'post_id' 	=> $post_id,
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
				$origin_field_name = $field_name;
				$field_id = str_replace( "de_fb_", "", $field_id );
				$field_name = str_replace( "de_fb_", "", $field_name );


				if ( !empty( $post_array[$field_name] ) ) {

					if ( isset( $_POST['signature'] ) && in_array( $origin_field_name, $_POST['signature'] ) ) {
						$field_val = '<img src="' . wp_get_attachment_image_url( $_POST[ $origin_field_name ] ) . '" width="300" height="150" style="max-width:100%;height:auto;">';
					} else {
						if ( isset( $form_settings['file_fields'][$field_key] ) && $form_settings['file_fields'][$field_key] == $origin_field_name ) {
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
							if ( stripos( $reply_body, "%%{$field_id}%%") !== false || $reply_email_template == ""  ) {
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

					if ( !( isset( $_POST['signature'] ) && in_array( $origin_field_name, $_POST['signature'] ) ) && isset( $post_array[$field_name] ) && is_array( $post_array[$field_name] ) ) {
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
					
				} else if ( !empty( $uploaded_files[ $origin_field_name] ) ) {
					$file_name = basename( $uploaded_files[$origin_field_name] );
					if ( !empty( $email_template ) ) {
						$body = str_ireplace( "%%{$field_id}%%", '<a href="' . $uploaded_files[$origin_field_name] . '" target="_blank">' . $file_name. '</a>', $body );
					} else {
						$body .= '<p><label>' . $field_title_array[$key] . ':</label><a href="' . $uploaded_files[$origin_field_name] . '" target="_blank">' . $file_name. '</a></p>';	
					}

					if ( !empty( $reply_email_template ) ) {
						$reply_body = str_ireplace( "%%{$field_id}%%", '<a href="' . $uploaded_files[$origin_field_name] . '" target="_blank">' . $file_name. '</a>', $reply_body );
					} else {
						$reply_body .= '<p><label>' . $field_title_array[$key] . ':</label><a href="' . $uploaded_files[$origin_field_name] . '" target="_blank">' . $file_name. '</a></p>';	
					}

					$field_val = '<a href="' . $uploaded_files[$origin_field_name] . '" target="_blank">' . $file_name. '</a>';
				} else {
					if ( !empty( $email_template ) ) {
						$body = str_ireplace( "%%{$field_id}%%", '', $body );
					}
					if ( !empty( $reply_email_template ) ) {
						$reply_body = str_ireplace( "%%{$field_id}%%", '', $reply_body );
					}
				}

				if ( !empty( $post_array[$field_name] ) && isset( $form_settings['file_fields'][$field_key] ) && $form_settings['file_fields'][$field_key] == $origin_field_name ) {
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

			$body = str_replace( "\r\n", "<br/>", $body);
			//$body = str_replace( "\n", "<br/>", $body);
			$body = stripslashes( $body );

			$body = apply_filters( 'df_contact_body', $body, $post_array );

			$title = preg_replace( sprintf( "/%s.*?%s/", preg_quote( '%%' ), preg_quote( '%%' ) ), '', $title);
			$title = stripslashes( $title );

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

			$email = apply_filters( 'df_contact_recipient', $email, $form_id, $post_array );

			$mail_result = wp_mail( $email, $title, $body, $header, $mail_attachs );
			
			$submit_result = '';
			$err_message = '';

			if ( $mail_result ) {
				$submit_result = 'success';
				if ( $send_copy_to_sender == 'on' ){
					if ( $sender_setting == 'sender' ) {

						if ( isset( $form_settings['fields']['de_fb_' . $sender_name_field] ) ) {
							$sender_name = $post_array[ str_replace("de_fb_", "", $form_settings['fields']['de_fb_' . $sender_name_field] )] ;	
						} else if ( isset( $form_settings['fields'][$sender_name_field] ) ) {
							$sender_name = $post_array[ str_replace("de_fb_", "", $form_settings['fields'][$sender_name_field] )] ;
						}

						if ( isset( $form_settings['fields']['de_fb_' . $sender_email_field] ) ) {
							$sender_email = $post_array[ str_replace("de_fb_", "", $form_settings['fields']['de_fb_' . $sender_email_field] ) ];	
						} else if ( isset( $form_settings['fields'][$sender_email_field] ) )  {
							$sender_email = $post_array[ str_replace("de_fb_", "", $form_settings['fields'][$sender_email_field] ) ];	
						}

					} else if ( $sender_setting == 'login_user') {
						$sender_name = ($login_user->ID != 0)?$login_user->display_name:'';
						$sender_email = ($login_user->ID != 0)?$login_user->user_email:'';
					}

					if ( $sender_email != '' ) {
						$reply_body = str_replace( "\r\n", "<br/>", $reply_body);
						$reply_body = str_replace( "\n", "<br/>", $reply_body);
						$reply_body = stripslashes( $reply_body );

						$reply_body = apply_filters( 'df_confirmation_body', $reply_body, $post_array );
						$reply_title = stripslashes( $reply_title );
						$reply_title = preg_replace( "/" . preg_quote( '%%' ) . ".*?" . preg_quote( '%%' ) . "/", '', $reply_title);
						$send_mail_result = wp_mail( $sender_email, $reply_title, $reply_body, $reply_header, $reply_mail_attachs );

						if ( !$send_mail_result ) {
							$submit_result = 'failed';
							$err_message = esc_html__( 'Error occured during sending an email.', 'divi-form-builder' );
						}
					} else {
						$submit_result = 'failed';
						$err_message = esc_html__( 'There was a problem with sending the form, no recipient email address.', 'divi-form-builder' );
					}
				}
			} else {
				$submit_result = 'failed';
				$err_message = esc_html__( 'Error occured during sending an email.', 'divi-form-builder' );
			}

			do_action( 'df_after_process', $form_id, $post_array, $form_type );
		} else {
			$submit_result = 'failed';
			$err_message = esc_html__( 'Captcha Error', 'divi-form-builder' );
			do_action( 'df_captcha_failed', $form_id, $post_array, $form_type );
		}

	}

	$redirect = '';

	if ( $submit_result == 'success' && !empty( $_POST['redirect_url_after_submission'] ) ) {
		$redirect = $_POST['redirect_url_after_submission'];
	}

	if ( $submit_result == 'failed' && !empty( $_POST['redirect_url_after_failed'] ) ) {
		$redirect = $_POST['redirect_url_after_failed'];
	}

	$message_content = '';

	if ( $redirect == '' ) {
		$message_array = $messages[$submit_result];
		if ( $message_array['type'] == 'text' ) {
    		$message_content = str_ireplace( "%%message%%", $err_message, $message_array['text'] );
    		$message_content = '<div class="message_wrapper"><div class="message message_' . $submit_result . '">' . $message_content . '</div></div>';
    	} else if ( $message_array['type'] == 'layout' && !empty( $message_array['layout'] ) ) {
    		$message_content = apply_filters( 'the_content', get_post_field( 'post_content', $message_array['layout'] ) );
    		$message_content = str_ireplace( "%%message%%", $err_message, $message_content );

    		$message_content = preg_replace('/et_pb_([a-z|_]+)_(\d+)_tb_body/', 'et_pb_fb_ajax_${1}_${2}_tb_body', $message_content);
	        $message_content = preg_replace('/et_pb_([a-z|_]+)_(\d+)( |")/', 'et_pb_fb_ajax_${1}_${2}${3}', $message_content);

	        $message_content = et_core_esc_previously($message_content);

    		ob_start();

    		$internal_style = ET_Builder_Element::get_style();
		    // reset all the attributes after we retrieved styles
		    ET_Builder_Element::clean_internal_modules_styles(false);
		    
		    // append styles
		    if ($internal_style) {

		    	$internal_style = preg_replace('/et_pb_([a-z|_]+)_(\d+)_tb_body/', 'et_pb_fb_ajax_${1}_${2}_tb_body', $internal_style);
        		$internal_style = preg_replace('/et_pb_([a-z|_]+)_(\d+)( |"|\.|,|:)/', 'et_pb_fb_ajax_${1}_${2}${3}', $internal_style);
		?>
		    <div class="fb-inner-styles">
		<?php

		      printf('<style type="text/css" class="de_fb_ajax_inner_styles">
		              %1$s
		            </style>', et_core_esc_previously($internal_style));
		?>
		    </div>
		<?php
		    }

		    $css_output = ob_get_clean();

		    $message_content = $message_content . $css_output;
    	}
	}

	$result = array( 'result' => $submit_result, 'redirect' => $redirect, 'message' => $message_content, 'message_position' => $message_position );

	wp_send_json( $result );
}

add_action('wp_ajax_de_fb_ajax_submit_ajax_handler', 'de_fb_ajax_submit_ajax_handler' );
add_action('wp_ajax_nopriv_de_fb_ajax_submit_ajax_handler', 'de_fb_ajax_submit_ajax_handler' );

function de_fb_ajax_remove_file_ajax_handler() {
	$file_id = $_POST['file_id'];
	$remove_field_name = $_POST['remove_field_name'];
	$remove_field_type = $_POST['remove_field_type'];
	$remove_from_media = $_POST['remove_from_media'];

	$result = true;

	if ( $remove_from_media == "true" ) {
		$result = wp_delete_attachment( $file_id );	
	}
	
	$pid = $_POST['pid'];

	if ( !empty($remove_field_type) && $remove_field_type == 'post' ) {
		if ( !empty( $remove_field_name ) ) {
			if ( $remove_field_name == 'post_thumbnail' ) {
				delete_post_thumbnail( $pid );
			} else {
				$original_value = get_post_meta( $pid, $remove_field_name, true );

				if ( !is_array( $original_value ) ) {
					$original_value_array = explode(',', $original_value );
				} else {
					$original_value_array = $original_value;
				}

				$file_ind = array_search( $file_id, $original_value_array );
				if ( $file_ind !== false ) {
					unset( $original_value_array[$file_ind] );
					if ( !empty( $original_value_array ) ) {
						update_post_meta( $pid, $remove_field_name, $original_value_array );
					} else {
						delete_post_meta( $pid, $remove_field_name );
					}
				}
			}
		}
	} else if ( $remove_field_type == 'user' ) {
		$original_value = get_user_meta( $pid, $remove_field_name, true );

		if ( !is_array( $original_value ) ) {
			$original_value_array = explode(',', $original_value );
		} else {
			$original_value_array = $original_value;
		}

		$file_ind = array_search( $file_id, $original_value_array );
		if ( $file_ind !== false ) {
			unset( $original_value_array[$file_ind] );
			if ( !empty( $original_value_array ) ) {
				update_user_meta( $pid, $remove_field_name, $original_value_array );
			} else {
				delete_user_meta( $pid, $remove_field_name );
			}
		}
	}

	if ( $result ) {
		echo "success";
	} else {
		echo "failed";
	}

	exit;
}
add_action('wp_ajax_de_fb_remove_file_handler', 'de_fb_ajax_remove_file_ajax_handler');
add_action('wp_ajax_nopriv_de_fb_remove_file_handler', 'de_fb_ajax_remove_file_ajax_handler');

if ( !function_exists('de_fb_load_actions_ajax') ) {
	function de_fb_load_actions_ajax( $actions ) {
    
	    if ( !in_array( 'de_fb_get_edit_form_ajax_handler', $actions ) )
			$actions[] = 'de_fb_get_edit_form_ajax_handler';

		if ( !in_array( 'de_fb_ajax_submit_ajax_handler', $actions ) )
			$actions[] = 'de_fb_ajax_submit_ajax_handler';

	    return $actions;
	}

	add_filter( 'et_builder_load_actions', 'de_fb_load_actions_ajax'  );
}