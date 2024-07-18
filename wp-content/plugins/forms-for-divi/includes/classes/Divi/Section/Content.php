<?php

namespace WPT\DiviForms\Divi\Section;

/**
 * Content.
 */
class Content
{
    protected  $container ;
    /**
     * Constructor.
     */
    public function __construct( $container )
    {
        $this->container = $container;
    }
    
    /**
     * Wrap form elements around the section.
     */
    public function et_pb_module_content(
        $content,
        $props,
        $attrs,
        $render_slug,
        $_address,
        $global_content
    )
    {
        $fields = $this->container['divi_section_fields'];
        \ET_Builder_Element::set_style( $render_slug, [
            'selector'    => $fields->get_selector( 'label_floating' ),
            'declaration' => 'line-height: 1 !important;',
        ] );
        
        if ( isset( $props['wpt_enable_form'] ) && $props['wpt_enable_form'] == 'on' ) {
            $form = $this->container['form'];
            $spam_protection = $this->container['spam_protection'];
            $spam_protection_type = $fields->get_prop_value( 'wpt_form_spam_protection_provider', $props );
            $threshold = $fields->get_prop_value( 'wpt_form_recaptcha_v3_threshold', $props );
            if ( $spam_protection_type ) {
                // spam_protection
                $spam_protection->set_is_enabled( true )->set_type( $spam_protection_type )->set_threshold( $threshold )->set_error_message( $fields->get_prop_value( 'wpt_form_spam_protection_error_message', $props ) );
            }
            $fields = $this->container['divi_section_fields'];
            $form_submit_status = $this->container['session']->get_form_status();
            // set form name, messages and notification
            $form_name = ( isset( $props['wpt_form_name'] ) ? $props['wpt_form_name'] : 'Untitled Form' );
            $form->set_name( $form_name );
            $form->set_success_message( $fields->get_prop_value( 'wpt_form_success_message', $props ) );
            $send_notification = $fields->get_prop_value( 'wpt_form_notification_send', $props ) == 'on';
            $captcha_validation_message = $fields->get_prop_value( 'wpt_form_basic_captcha_validation_error_message', $props );
            $captcha_label_prefix = $fields->get_prop_value( 'wpt_form_basic_captcha_label_prefix', $props );
            $this->container['spam_protection']->set_basic_captcha_validation_message( $captcha_validation_message );
            $this->container['spam_protection']->set_basic_captcha_label_prefix( $captcha_label_prefix );
            
            if ( $send_notification ) {
                $notification = $this->container['form_notification'];
                $notification->from_array( [
                    'to'       => $fields->get_prop_value( 'wpt_form_notification_to', $props ),
                    'reply_to' => $fields->get_prop_value( 'wpt_form_notification_reply_to', $props ),
                    'bcc'      => $fields->get_prop_value( 'wpt_form_notification_bcc', $props ),
                    'subject'  => $fields->get_prop_value( 'wpt_form_notification_subject', $props ),
                    'message'  => $fields->get_prop_value( 'wpt_form_notification_message', $props ),
                    'send'     => true,
                ] );
                $this->container['form']->set_notification( $notification );
            }
            
            $content = $form->wrap_content( $content );
            // show form when there is no valid request or validation error
            
            if ( in_array( $form_submit_status, [ 301, 400 ] ) ) {
                
                if ( $form_submit_status == 400 ) {
                    $message_timeout = $fields->get_prop_value( 'wpt_form_error_message_timeout', $props );
                    $content .= sprintf( '<aside class="mdc-snackbar form-submit-error" data-timeout=%s>
  <div class="mdc-snackbar__surface" role="status" aria-relevant="additions">
    <div class="mdc-snackbar__label" aria-atomic="false">
      %s
    </div>
    <div class="mdc-snackbar__actions" aria-atomic="true">
      <button type="button" class="mdc-button mdc-snackbar__action">
        <div class="mdc-button__ripple"></div>
        <span class="mdc-button__label">✖</span>
      </button>
    </div>
</aside>', $message_timeout, $form->get_error_message() );
                }
            
            } else {
                $message_timeout = $fields->get_prop_value( 'wpt_form_success_message_timeout', $props );
                $content .= sprintf( '<aside class="mdc-snackbar form-submit-success" data-timeout=%s>
  <div class="mdc-snackbar__surface" role="status" aria-relevant="additions">
    <div class="mdc-snackbar__label" aria-atomic="false">
      %s
    </div>
    <div class="mdc-snackbar__actions" aria-atomic="true">
      <button type="button" class="mdc-button mdc-snackbar__action">
        <div class="mdc-button__ripple"></div>
        <span class="mdc-button__label">✖</span>
      </button>
    </div>
</aside>', $message_timeout, $form->get_success_message() );
            }
            
            wp_register_style( 'divi-forms-css-vars-inline-css', false );
            wp_enqueue_style( 'divi-forms-css-vars-inline-css' );
            $this->set_styles( $render_slug, $props );
        }
        
        return $content;
    }
    
    /**
     * Add custom class to the section
     */
    public function modify_props(
        $props,
        $attrs,
        $render_slug,
        $_address,
        $content
    )
    {
        
        if ( $render_slug == 'et_pb_section' ) {
            if ( !isset( $props['module_class'] ) ) {
                $props['module_class'] = '';
            }
            $props['module_class'] = trim( sprintf( '%s wpt-divi-forms', $props['module_class'] ) );
            if ( isset( $props['wpt_enable_form'] ) && $props['wpt_enable_form'] == 'on' ) {
                // enqueue scripts on divi form availability
                $this->container['bootstrap']->enqueue_scripts();
            }
        }
        
        return $props;
    }
    
    public function set_styles( $render_slug, $props )
    {
        $inline_css = '';
        $responsive = \ET_Builder_Module_Helper_ResponsiveOptions::instance();
        $fields = $this->container['divi_section_fields'];
        $order_class = \ET_Builder_Element::get_module_order_class( $render_slug );
        $selector = 'div.wpt-divi-forms.et_pb_section.' . $order_class;
        $set_row_bottom_zero = $this->prop_value( $props, 'wpt_form_section_row_padding_bottom_set_zero' );
        
        if ( $set_row_bottom_zero == 'on' ) {
            $row_padding_bottom = '0';
        } else {
            $row_padding_bottom = 'none';
        }
        
        $inline_css .= sprintf(
            "%s{padding-bottom: %s; padding-top:%s;}",
            $fields->get_selector_pure( 'rows', $selector ),
            $row_padding_bottom,
            $row_padding_bottom
        );
        \ET_Builder_Element::set_style( $render_slug, [
            'selector'    => $fields->get_selector( 'rows' ),
            'declaration' => sprintf( 'padding-bottom: %s;padding-top: %s;', $row_padding_bottom, $row_padding_bottom ),
        ] );
        wp_add_inline_style( 'divi-forms-css-vars-inline-css', $inline_css );
    }
    
    /**
     * Get prop value else return default value.
     */
    public function prop_value( $props, $key )
    {
        $fields = $this->container['divi_section_fields'];
        return ( isset( $props[$key] ) ? $props[$key] : $fields->get_default( $key ) );
    }

}