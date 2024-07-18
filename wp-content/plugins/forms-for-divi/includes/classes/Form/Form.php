<?php

namespace WPT\DiviForms\Form;

use  Exception ;
/**
 * Form.
 */
class Form
{
    protected  $container ;
    protected  $name ;
    protected  $notification ;
    protected  $fields ;
    protected  $formatted_data ;
    protected  $success_message ;
    protected  $error_message ;
    /**
     * Constructor.
     */
    public function __construct( $container )
    {
        $this->container = $container;
    }
    
    public function set_fields( $fields )
    {
        $this->fields = $fields;
        $this->formatted_data = [];
        foreach ( $this->fields as $field ) {
            $this->formatted_data[$field->get_name()] = $field->get_formatted_value();
        }
        return $this;
    }
    
    public function get_fields()
    {
        return $this->fields;
    }
    
    public function get_formatted_data()
    {
        return $this->formatted_data;
    }
    
    /**
     * Set email notification
     */
    public function set_name( $name )
    {
        $this->name = $name;
    }
    
    /**
     * get form name
     */
    public function get_name()
    {
        return $this->name;
    }
    
    /**
     * Hydrate the form meta using encrypted meta
     */
    public function hydrate_meta( $encrypted_meta )
    {
        $meta = $this->container['crypt']->decrypt( $encrypted_meta );
        $meta = json_decode( $meta, true );
        if ( isset( $meta['name'] ) ) {
            $this->name = $meta['name'];
        }
        
        if ( isset( $meta['notification'] ) ) {
            $notification = $this->container['form_notification'];
            $notification->from_array( $meta['notification'] );
            $this->container['form']->set_notification( $notification );
        }
        
        if ( isset( $meta['messages'], $meta['messages']['success'] ) ) {
            $this->success_message = $meta['messages']['success'];
        }
        if ( isset( $meta['messages'], $meta['messages']['error'] ) ) {
            $this->error_message = $meta['messages']['error'];
        }
        
        if ( isset( $meta['spam_protection'] ) ) {
            $spam_protection = $this->container['spam_protection'];
            $spam_protection->set_is_enabled( true );
            $spam_protection->set_type( $meta['spam_protection']['type'] );
            $spam_protection->set_first_digit( $meta['spam_protection']['first_digit'] );
            $spam_protection->set_second_digit( $meta['spam_protection']['second_digit'] );
            $spam_protection->set_error_message( $meta['spam_protection']['message'] );
            $spam_protection->set_threshold( $meta['spam_protection']['threshold'] );
            $spam_protection->set_basic_captcha_validation_message( $meta['spam_protection']['basic_captcha_validation_message'] );
        }
    
    }
    
    /**
     * Get the form meta
     */
    public function form_meta()
    {
        $meta = [
            'name'     => $this->name,
            'messages' => [],
        ];
        if ( $this->notification ) {
            $meta['notification'] = $this->notification->to_array();
        }
        if ( $this->success_message ) {
            $meta['messages']['success'] = $this->success_message;
        }
        if ( $this->error_message ) {
            $meta['messages']['error'] = $this->error_message;
        }
        // spam_protection
        $spam_protection = $this->container['spam_protection'];
        if ( $spam_protection->is_enabled() ) {
            $meta['spam_protection'] = [
                'type'                             => $spam_protection->get_type(),
                'message'                          => $spam_protection->get_error_message(),
                'threshold'                        => $spam_protection->get_threshold(),
                'first_digit'                      => $spam_protection->get_first_digit(),
                'second_digit'                     => $spam_protection->get_second_digit(),
                'basic_captcha_validation_message' => $spam_protection->get_basic_captcha_validation_message(),
            ];
        }
        return $meta;
    }
    
    /**
     * Container form meta values
     */
    public function form_meta_encrypted()
    {
        $meta = $this->form_meta();
        return $this->container['crypt']->encrypt( wp_json_encode( $meta ) );
    }
    
    /**
     * Form email notification
     */
    public function set_notification( $notification )
    {
        $this->notification = $notification;
    }
    
    /**
     * Wrap the content with the form tags
     */
    public function wrap_content( $content )
    {
        $form = $this->container['form'];
        $spam_protection = $this->container['spam_protection'];
        $classes = [ 'wpt-divi-forms' ];
        $spam_protection_v3 = false;
        
        if ( $spam_protection->is_enabled() && $spam_protection->get_type() == 'v3' ) {
            $spam_protection_v3 = true;
            $classes[] = 'gr3';
        }
        
        $content = sprintf(
            '<form method="POST" enctype="multipart/form-data" novalidate onsubmit="return wptDiviForms.validate(this)" class="%s" data-gr3="%s">%s',
            implode( ' ', $classes ),
            $spam_protection->get_key(),
            $content
        );
        $csrf = $this->container['form_csrf'];
        $token = $csrf->token();
        $content = $content . sprintf( '<input type="hidden" value="%s" name="%s"/>', $token, $csrf->get_key() );
        $content = $content . sprintf( '<input type="hidden" value="%s" name="___fm"/>', $form->form_meta_encrypted() );
        $content = $content . '</form>';
        return $content;
    }
    
    /**
     * Process form submissions.
     */
    public function process()
    {
        $request = $this->container['request'];
        $spam_protection = $this->container['spam_protection'];
        $form = $this->container['form'];
        $request->init();
        
        if ( $request->valid() ) {
            // phpcs:ignore
            $_SESSION['wpt_form_data'] = $_POST;
            // phpcs:ignore
            $spam_protection_check = $request->spam_protection_check( $_POST );
            
            if ( !$spam_protection_check ) {
                $form->set_error_message( $spam_protection->get_error_message() );
                throw new Exception( "Spam Protection Error", 400 );
            }
            
            // phpcs:ignore
            $valid = $request->validate( $_POST );
            
            if ( !$valid ) {
                $_SESSION['wpt_form_errors'] = $request->get_errors();
                $this->container['session']->set_invalid_request( $form->get_error_message() );
                throw new Exception( "Validation Errors", 400 );
            }
            
            $this->set_fields( $request->get_form_fields() );
            // save form data
            $form_data = $this->get_formatted_data();
            $form_meta = [
                'form_name'     => $form->get_name(),
                'form_post_url' => $request->request_uri(),
            ];
            // send email.
            
            if ( $this->notification && $this->notification->can_send() ) {
                $this->notification->set_form_data( $this->get_formatted_data() );
                $this->notification->send();
            }
            
            // reset form data from session
            $this->container['session']->clear();
            $this->container['session']->set_valid_request( $form->get_success_message() );
            // redirect same page with success message
            throw new Exception( "Success", 200 );
        } else {
            $this->container['session']->clear();
            return false;
        }
    
    }
    
    /**
     * Set success message
     */
    public function set_success_message( $message )
    {
        $this->success_message = $message;
    }
    
    /**
     * Return success message
     */
    public function get_success_message()
    {
        return $this->success_message;
    }
    
    /**
     * Set error message
     */
    public function set_error_message( $message )
    {
        $this->error_message = $message;
    }
    
    /**
     * Return error message
     */
    public function get_error_message()
    {
        return $this->error_message;
    }

}