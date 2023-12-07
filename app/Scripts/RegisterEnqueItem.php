<?php
namespace WTOP\Scripts;

use WTOP\Localization;

class RegisterEnqueItem
{
    protected $plugin_prefix;
    protected $plugin_version;
    protected $files_list;

    public function __construct( $parameters, $locale )
    {
        $this->files_list = $parameters;

        $this->plugin_prefix = $locale;
        $this->plugin_version = '1.0';
        if (is_admin()) {
            add_action('admin_enqueue_scripts', array($this, 'add_script_fn'));
        } else {
            add_action('wp_enqueue_scripts', array($this, 'add_script_fn'));
        }

    }

    /**
     * add_script_fn
     *
     * @return void
     */
    public function add_script_fn()
    {
        //wp_enqueue_media();

        foreach ($this->files_list as $key => $value) {
            if ($key == 'common') {
                foreach ($value as $single_line) {
                    $this->process_enq_line($single_line);
                }
            }
            if ($key == 'admin' && is_admin()) {
                foreach ($value as $single_line) {
                    $this->process_enq_line($single_line);
                }
            }
            if ($key == 'front' && !is_admin()) {
                foreach ($value as $single_line) {
                    $this->process_enq_line($single_line);
                }
            }
        }
    }

    /**
     * process_enq_line
     *
     * @param mixed $line
     * @return void
     */
    public function process_enq_line($line)
    {

        $default_array = [
            'type' => '',
            'url' => '',
            'enq' => '',
            'localization' => '',
        ];
        $line = array_merge($default_array, $line);

        $custom_id = rand(1000, 9999) . basename($line['url']);
        if ($line['type'] == 'style') {
            wp_enqueue_style($this->plugin_prefix . $custom_id, $line['url']);
        }
        if ($line['type'] == 'script') {

            $rand_prefix = rand(1000, 9999);
            if (isset($line['id'])) {
                $script_prefix = $line['id'];
            } else {
                $script_prefix = $this->plugin_prefix . $custom_id . $rand_prefix;
            }

            wp_register_script($script_prefix, $line['url'], $line['enq']);
            if ($line['localization']) {
                wp_localize_script($script_prefix, $this->plugin_prefix . '_local_data', $line['localization']);
            }
            wp_enqueue_script($script_prefix);
        }
    }
}