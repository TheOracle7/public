<?php
namespace WPT\DiviForms\Form;

/**
 * Notification.
 */
class Notification
{
    protected $container;
    protected $to;
    protected $reply_to;
    protected $bcc;
    protected $subject;
    protected $message;
    protected $send;
    protected $data;
    protected $data_keys;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->send      = false;
    }

    public function set_form_data($data)
    {
        $this->data = $data;

        $this->data['_admin_email'] = get_option('admin_email');
        $this->data['_site_title']  = get_option('blogname');

        $this->data_keys = array_keys($this->data);
    }

    /**
     * Send out the email.
     */
    public function send()
    {
        $headers = [];

        $from_email_header = sprintf('From: %s <%s>', $this->data['_site_title'], $this->data['_admin_email']);
        $from_email_header = apply_filters('divi_forms_from_email_header', $from_email_header);

        $headers[] = $from_email_header;

        if ($this->reply_to) {
            $headers[] = sprintf('Reply-To: %s', $this->transform_text($this->reply_to));
        }

        if ($this->bcc) {
            $headers[] = sprintf('Bcc: %s', $this->transform_text($this->bcc));
        }

        add_filter(
            'wp_mail_content_type',
            function () {
                return 'text/html';
            }
        );

        wp_mail(
            $this->transform_text($this->to),
            $this->transform_text($this->subject),
            $this->transform_text($this->message),
            $headers
        );
    }

    /**
     * Transform the text by replacing {...} key with data
     */
    public function transform_text($text)
    {

        if (is_string($text)) {
            foreach ($this->data_keys as $key) {

                $value = $this->data[$key];
                if (is_array($value)) {
                    $value = array_map('stripslashes', $value);
                    $value = implode(', ', $value);
                } else {
                    $value = stripslashes($value);
                }
                $text = str_replace('%%' . trim($key) . '%%', $value, $text);
            }
        }
        return $text;
    }

    /**
     * Set send flag
     */
    public function set_send($send)
    {
        $this->send = $send;
        return $this;
    }

    /**
     * check if notification needs to be send.
     */
    public function can_send()
    {
        return $this->send;
    }

    public function set_to($to)
    {
        $this->to = $to;
        return $this;
    }

    public function set_reply_to($reply_to)
    {
        $this->reply_to = $reply_to;
        return $this;
    }

    public function set_bcc($bcc)
    {
        $this->bcc = $bcc;
        return $this;
    }

    public function set_subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function set_message($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * To array
     */
    public function to_array()
    {
        $data = [
            'to'       => $this->to,
            'reply_to' => $this->reply_to,
            'bcc'      => $this->bcc,
            'subject'  => $this->subject,
            'message'  => $this->message,
            'send'     => $this->send,
        ];

        return $data;
    }

    /**
     * Re-hydrate notification object using the data
     */
    public function from_array($data)
    {
        $this->to       = $data['to'];
        $this->reply_to = $data['reply_to'];
        $this->bcc      = $data['bcc'];
        $this->subject  = $data['subject'];
        $this->message  = $data['message'];
        $this->send     = $data['send'];
    }

    /**
     * Get String Representation
     */
    public function to_string()
    {
        return wp_json_encode($this->to_array());
    }

}
