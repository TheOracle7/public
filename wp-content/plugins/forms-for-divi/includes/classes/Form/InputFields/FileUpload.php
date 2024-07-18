<?php
namespace WPT\DiviForms\Form\InputFields;

/**
 * FileUpload.
 */
class FileUpload extends InputField
{
    protected $options;
    protected $has_upload_limit;
    protected $upload_limit;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->type                    = 'file_upload';
        $this->options                 = [];
        $this->has_upload_limit        = false;
        $this->allowed_file_extensions = '';
    }

    /**
     * Allowed file extensions
     */
    public function set_allowed_file_extensions($allowed_file_extensions)
    {
        $extensions = [];

        if ($allowed_file_extensions) {
            $extensions = explode(',', $allowed_file_extensions);
            $extensions = array_map(function ($item) {
                return '.' . trim($item);
            }, $extensions);
        }

        $this->allowed_file_extensions = implode(',', $extensions);
        return $this;
    }

    /**
     * Set upload limit
     */
    public function set_upload_limit($upload_limit)
    {
        $this->has_upload_limit = true;
        $this->upload_limit     = $upload_limit;
    }

    /**
     * Get the public file url
     */
    public function get_file_url($file_name)
    {
        $uploads = wp_upload_dir();
        return esc_url_raw($uploads['baseurl'] . '/wpt_divi_forms_uploads/' . $file_name);
    }

    /**
     * Add a unique prefix to file name.
     */
    public function filename_add_prefix($file_name)
    {
        $prefix = $this->container['str']->random(13);

        return sprintf('%s-%s', $prefix, $file_name);
    }

    /**
     * Set value
     */
    public function set_value($value)
    {
        if (isset($value['error']) && ($value['error'] === 0)) {
            $upload_dir = $this->container['bootstrap']->get_upload_dir();

            $file_name = $this->filename_add_prefix($value['name']);

            $destination = sprintf('%s/%s', $upload_dir, $file_name);

            if (move_uploaded_file($value['tmp_name'], $destination)) {
                $value = $this->get_file_url($file_name);
            } else {
                // error
                $value = '';
            }

        } else {
            $value = '';
        }

        $this->value = $value;

        return $this;
    }

    /**
     * Get input field html
     */
    public function input_html($config = [])
    {

        return $this->field_wrapper(sprintf(
            '<div class="mdc-file-upload-field-container" >
                <div class="mdc-form-field mdc-file">
                    <input type="file" name="%s" %s/>
                    <button type="button" class="mdc-button mdc-button--raised">
                        <span class="mdc-button__label">%s</span>
                    </button>
                </div>
                <label class="file-upload-output"></label>
                <label class="mdc-text-field mdc-text-field--no-label">
                    <span class="mdc-text-field__ripple"></span>
                    <input class="mdc-text-field__input" type="text" style="border:none; padding:0;background: transparent;" %s>
                    <span class="mdc-line-ripple"></span>
                </label>

                <div class="mdc-text-field-helper-line">
                %s
                %s
                </div>
            </div>

            <div>

            </div>',
            $this->name,
            $this->allowed_file_extensions ? sprintf('accept="%s"', $this->allowed_file_extensions) : '',
            $this->label,
            implode(' ', $this->validation_attributes()),
            $this->error_html(),
            $this->has_helper_text() ? $this->get_helper_text_mdc() : ''
        ));

    }
}
