<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use EllisLab\ExpressionEngine\Library\CP\Table;

class Cookieconsent_lib
{
    public $values = array();

    public function __construct()
    {
        /* Needful model classes */
        ee()->load->model('Cookieconsent_model', 'cookieConsentModel');
    }

    function handleGeneralSettings($vars)
    {
        if (count($this->values) == 0) {
            $this->values = ee()->cookieConsentModel->getGeneralSettings();
        }

        $vars['sections'] = array(
            'google_analytics_settings' => array(
                array(
                    'title'     => 'Google Analytics: Tracking-ID',
                    'desc'      => 'Set up the Analytics global site tag: <a href="https://support.google.com/analytics/answer/1008080" target="_blank">https://support.google.com/analytics/answer/1008080</a>',
                    'fields'    => array(
                        'tracking_id' => array(
                            'type'      => 'text',
                            'value'     => (isset($this->values['tracking_id'])) ? $this->values['tracking_id'] : '',
                            'required'  => FALSE
                        )
                    )
                ),

                array(
                    'title'     => 'Google Analytics: IP Anonymization (or IP masking)',
                    'desc'      => 'IP Anonymization (or IP masking) in Analytics: <a href="https://support.google.com/analytics/answer/2763052?hl=en" target="_blank">https://support.google.com/analytics/answer/2763052</a>',
                    'fields'    => array(
                        'anonymize_ip' => array(
                            'type'      => 'yes_no',
                            'value'     => (isset($this->values['anonymize_ip'])) ? $this->values['anonymize_ip'] : 'n',
                            'required'  => FALSE
                        )
                    )
                ),

                array(
                    'title'     => 'Global site tag flush with {exp:cookieconsent:head}',
                    'desc'      => 'Load the global site tag (gtag.js) flush with {exp:cookieconsent:head}',
                    'fields'    => array(
                        'show_gtag' => array(
                            'type'      => 'yes_no',
                            'value'     => (isset($this->values['show_gtag'])) ? $this->values['show_gtag'] : 'y',
                            'required'  => FALSE
                        )
                    )
                ),
            ),

            'cookie_banner_settings' => array(
                array(
                    'title'     => 'Cookie banner title',
                    'desc'      => 'Title displayed on the cookie banner',
                    'fields'    => array(
                        'banner_title' => array(
                            'type'      => 'text',
                            'value'     => (isset($this->values['banner_title'])) ? $this->values['banner_title'] : lang('banner_title'),
                            'required'  => FALSE
                        )
                    )
                ),

                array(
                    'title'     => 'Cookie banner text',
                    'desc'      => 'Text displayed on the cookie banner',
                    'fields'    => array(
                        'banner_text' => array(
                            'type'      => 'textarea',
                            'value'     => (isset($this->values['banner_text'])) ? $this->values['banner_text'] : lang('banner_text'),
                            'required'  => FALSE
                        )
                    )
                ),

                array(
                    'title'     => 'Cookie Policy path',
                    'desc'      => 'Path to the cookie policy',
                    'fields'    => array(
                        'cookiepolicy_path' => array(
                            'type'      => 'text',
                            'value'     => (isset($this->values['cookiepolicy_path'])) ? $this->values['cookiepolicy_path'] : '/',
                            'required'  => FALSE
                        )
                    )
                )
            )
        );

        $vars += array(
            'base_url'              => ee('CP/URL')->make('addons/settings/cookieconsent'),
            'cp_page_title'         => lang('cookieconsent_settings'),
            'save_btn_text'         => lang('save'),
            'save_btn_text_working' => lang('saving'),
        );

        return $vars;
    }

    function handleGeneralSettingsPost()
    {
        $values = array();

        foreach ($_POST as $key => $value) {
            $values[$key] = ee()->input->post($key, true);
        }

        $validator = ee('Validation')->make();
        // $validator->setRules(array(
        //     'banner_theme' => 'required|enum[dark,light]'
        // ));

        $result = $validator->validate($values);

        if (!$result->isValid()) {
            return $result;
        }

        /* DB QUERIES to save data */
        ee()->cookieConsentModel->handleGeneralSettingsPost($values);
        return TRUE;
    }
}
