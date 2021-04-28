<?php

/**
 * Cookieconsent Class
 *
 * @package     ExpressionEngine
 * @category    Module
 * @author      Yoran Palm <contact@yoranpalm.nl>
 * @copyright   Copyright (c) 2020 Yoran Palm
 * @link        https://github.com/yoranpalm/ee-cookieconsent
 */

use YoranPalm\Cookieconsent\Services\ModuleService;

require_once PATH_THIRD . 'cookieconsent/config.php';

class Cookieconsent
{
  /**
   * Initalize module
   */
  public function __construct()
  {
    /* Needful model classes */
    ee()->load->model('Cookieconsent_model', 'cookieConsentModel');
  }

  /**
   * Module Method
   * {exp:cookieconsent:get_settings}
   * 
   * @return
   */
  private function get_settings()
  {
    $query = ee()->db->get('cookieconsent_settings');

    $settings = array();

    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        $settings[] = $row;
      }
    }

    return $settings;
  }

  /**
   * Module Method
   * {exp:cookieconsent:head}
   * 
   * @return
   */
  public function head()
  {
    $settings = ee()->cookieConsentModel->getGeneralSettings();

    if ($settings['show_gtag'] === 'y') {
      $gtag = $this->gtag();
    }

    $head_vars = [
      'gtag' => $gtag,
      'css_path' => COOKIECONSENT_CSS_PATH,
      'js_path' => COOKIECONSENT_JS_PATH,
    ];

    $head = ee('View')->make('cookieconsent:head')->render($head_vars);

    return $head;
  }

  /**
   * Module Method
   * {exp:cookieconsent:banner}
   * 
   * @return
   */
  public function banner()
  {
    $settings = ee()->cookieConsentModel->getGeneralSettings();

    $banner_vars = [
      'settings' => $settings,
    ];

    $banner = ee('View')->make('cookieconsent:banner')->render($banner_vars);

    return $banner;
  }

  /**
   * Module Method
   * {exp:cookieconsent:gtag}
   * 
   * @return
   */
  public function gtag()
  {
    $settings = ee()->cookieConsentModel->getGeneralSettings();
    $cookies_targeting = ee('Consent')->hasGranted('ee:cookies_targeting');

    if ($settings['tracking_id'] == '') {
      $gtag = '<!-- Tracking-ID has not been specified -->';
    } elseif ($settings['anonymize_ip'] == 'n' && $cookies_targeting == 1) {
      $gtag =
        "
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src='https://www.googletagmanager.com/gtag/js?id=" . $settings['tracking_id'] . "'></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', '" . $settings['tracking_id'] . "');
        </script>
      ";
    } elseif ($settings['anonymize_ip'] == 'y' || $cookies_targeting == 0) {
      $gtag =
        "
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src='https://www.googletagmanager.com/gtag/js?id=" . $settings['tracking_id'] . "'></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', '" . $settings['tracking_id'] . "', { 'anonymize_ip': true });
        </script>
      ";
    }

    return $gtag;
  }

  /**
   * Module Method
   * {exp:cookieconsent:method}
   * 
   * @return void
   */
  public function method()
  {
    // Implement your module logic and methods
  }
}

/* End of file mod.cookieconsent.php */
/* Location: ./system/user/addons/cookieconsent/mod.cookieconsent.php */
