<?php

namespace WTOP\MetaBox;
class CreateMetaBox
{
    private $metabox_parameters = [];
    private $meta_box_fields = [];

    public function __construct($meta_box_settings)
    {
        $this->metabox_parameters = $meta_box_settings;

        add_action('add_meta_boxes', array($this, 'add_custom_box'));
        add_action('save_post', array($this, 'save_postdata'));
    }

    public function add_custom_box()
    {

        add_meta_box(
            'custom_meta_editor_' . rand(100, 999),
            $this->metabox_parameters['title'],
            array($this, 'custom_meta_editor'),
            $this->metabox_parameters['post_type'],
            $this->metabox_parameters['position'],
            $this->metabox_parameters['place']
        );
    }


    public function custom_meta_editor()
    {
        global $post;
        $out = '
			<div class="tw-bs4">
				<div class="form-horizontal ">';

        foreach ($this->metabox_parameters['parameters'] as $key => $element_setting) {

            $interface_element_value = '';
            if ($element_setting['type'] == 'select') {
                $interface_element_value = [];
            }
            if (isset($element_setting['name'])) {
                $current_value = get_post_meta( $post->ID, $element_setting['name'], true  ) ;
                if (isset( $current_value )) {
                    $interface_element_value = $current_value;
                }
            }

            $object = new \WTOP\FormElements\FormItemOutputGenerator($element_setting, $interface_element_value);
            $out .= $object->GetCode();
        }

        $out .= '</div>	
		</div>';
        echo $out;
    }

    function save_postdata($post_id)
    {
        global $current_user;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        if (isset($_POST['post_type'])) {
            if ($_POST['post_type'] == 'page') {
                if (!current_user_can('edit_page', $post_id))
                    return;
            } else {
                if (!current_user_can('edit_post', $post_id))
                    return;
            }
        }

        /// User editotions
        if (get_post_type($post_id) == $this->metabox_parameters['post_type']) {

            foreach ($this->metabox_parameters['parameters'] as $single_parameter) {



                if (isset($_POST[$single_parameter['name']])) {
                    if (is_array($_POST[$single_parameter['name']])) {
                        $filtered_array = array_map('sanitize_text_field', $_POST[$single_parameter['name']]);
                        update_post_meta($post_id, $single_parameter['name'], $filtered_array );
                    } else {
                        update_post_meta($post_id, $single_parameter['name'], sanitize_text_field($_POST[$single_parameter['name']]));
                    }

                }

            }

        }

    }
}