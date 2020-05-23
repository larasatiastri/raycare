<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
     function __construct($config = array())
     {
          parent::__construct($config);
     }
 
    /**
     * Error Array
     * Returns the error messages as an array
     * @return  array
     */
    function error_array()
    {
        $error_data = array();
        $field_data = $this->field_data();

        foreach ($field_data as $field => $data) {
            $error_data[$field] = $data['error'];
        }

        return $error_data;
    }    

    function post_data()
    {
        $post_data = array();
        $field_data = $this->field_data();

        foreach ($field_data as $field => $data) {
            $post_data[$field] = $data['postdata'];
        }

        return $post_data;
    }

    function field_data()
    {
        return $this->_field_data;
    }

    function get_flashdata(){

        $form_error = $this->error_array();
        $form_data = $this->post_data();

        $flashdata = array(
            'form_error' => $form_error,
            'form_data'  => $form_data
        );

        return $flashdata;
    }
}