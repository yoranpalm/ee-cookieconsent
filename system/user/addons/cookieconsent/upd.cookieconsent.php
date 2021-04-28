<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD . 'cookieconsent/config.php';

/*
|--------------------------------------------------------------------------
| Module Update File
|--------------------------------------------------------------------------
|
| The Update file for a module includes a class with a name that is a
| combination of the package’s name with a _upd suffix. The first letter
| and only the first letter of the class name should be capitalized.
| There is only one required class variable is $version,
| which should indicate the current version of this module.
|
*/

class Cookieconsent_upd
{
  /**
   * Module Name
   *
   * @var String
   */
  public $name = 'Cookieconsent';

  /**
   * Module Version
   *
   * @var Float
   */
  public $version = COOKIECONSENT_VERSION;

  /**
   * The Main Site ID
   *
   * @var Integer
   */
  public $site_id;

  /**
   * Constructor function
   *
   * Set dynamic variables
   * @return void
   */
  public function __construct()
  {
    ee()->load->dbforge();

    /* Define global variables */
    $this->site_id = ee()->config->item('site_id');
  }

  /**
   * Installation Method
   *
   * @return  boolean
   */
  public function install()
  {
    $data = [
      'module_name'        => $this->name,
      'module_version'     => $this->version,
      'has_cp_backend'     => 'y',
      'has_publish_fields' => 'y'
    ];

    ee()->db->insert('modules', $data);

    $fields = array(
      'id' => array(
        'type'          => 'int',
        'constraint'    => 10,
        'unsigned'      => TRUE,
        'null'          => FALSE,
        'auto_increment' => TRUE
      ),
      'site_id' => array(
        'type'          => 'int',
        'constraint'    => 3,
        'null'          => FALSE,
        'default'       => 1
      ),
      'tracking_id' => array(
        'type'          => 'varchar',
        'constraint'    => 100,
        'null'          => TRUE,
        'required'      => FALSE
      ),
      'anonymize_ip' => array(
        'type'          => 'varchar',
        'constraint'    => 3,
        'null'          => FALSE
      ),
      'show_gtag' => array(
        'type'          => 'varchar',
        'constraint'    => 3,
        'null'          => FALSE
      ),
      'banner_title' => array(
        'type'          => 'varchar',
        'constraint'    => 200,
        'null'          => FALSE,
      ),
      'banner_text' => array(
        'type'          => 'varchar',
        'constraint'    => 200,
        'null'          => FALSE
      ),
      'cookiepolicy_path' => array(
        'type'          => 'varchar',
        'constraint'    => 200,
        'null'          => FALSE
      )
    );

    ee()->dbforge->add_key('id', true);
    ee()->dbforge->add_field($fields);
    ee()->dbforge->create_table('cookieconsent_settings');

    // Implement your install logic here
    $data = array(
      'id'                => '1',
      'site_id'           => '1',
      'anonymize_ip'      => 'n',
      'show_gtag'         => 'y',
      'banner_title'      => lang('banner_title'),
      'banner_text'       => lang('banner_text'),
      'cookiepolicy_path' => '/'
    );

    ee()->db->insert('cookieconsent_settings', $data);

    return TRUE;
  }

  /**
   * Uninstall
   *
   * @return  boolean
   */
  public function uninstall()
  {
    // Remove Module info
    $module = ee('Model')->get('Module')->filter('module_name', 'Cookieconsent')->first();
    $module->delete();
    ee()->dbforge->drop_table('cookieconsent_settings');
    // Implement additional uninstall logic here
    return TRUE;
  }

  /**
   * Module Updater
   *
   * @return  boolean
   */
  function update($current = '')
  {
    if (version_compare($current, $this->version, '=')) {
      return FALSE;
    }

    if (version_compare($current, $this->version, '<')) {
      // Do your update code here
    }

    return TRUE;
  }
}

/* End of file upd.cookieconsent.php */
/* Location: /system/user/addons/cookieconsent/upd.cookieconsent.php */