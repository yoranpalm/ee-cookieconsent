<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD . 'cookieconsent/config.php';

/**
 * Cookieconsent Class
 *
 * @package     ExpressionEngine
 * @category    Module
 * @author      Yoran Palm <contact@yoranpalm.nl>
 * @copyright   Copyright (c) 2020 Yoran Palm
 * @link        https://github.com/yoranpalm/ee-cookieconsent
 */

class Cookieconsent_mcp
{

	public function __construct()
	{
		$this->base_url = ee('CP/URL', 'addons/settings/cookieconsent');

		/* Define global variables */
		$this->site_id = ee()->config->item("site_id");

		ee()->load->library('Cookieconsent_lib', null, 'cookieConsent');
	}

	public function index()
	{
		/* Basic dependancy of backend forms */
		$this->_startupForm();

		$sidebar = ee('CP/Sidebar')->make();

		$cookieconsent = $sidebar->addHeader(lang('cookieconsent_module_name'));
		// ->withButton(lang('new'), ee('CP/URL', 'addons/settings/cookieconsent/create'));

		$cookieconsent_list = $cookieconsent->addBasicList();
		$cookieconsent_list->addItem(lang('cookieconsent_settings'), ee('CP/URL', 'addons/settings/cookieconsent'));
		$cookieconsent_list->addItem(lang('consent_requests'), ee('CP/URL', ' settings/consents'));
		$cookieconsent_list->addItem(lang('documentation'), COOKIECONSENT_DOCS_URL);

		$this->vars = array();
		$this->vars = ee()->cookieConsent->handleGeneralSettings($this->vars);

		if (isset($_POST) && count($_POST)) {
			// $this->vars = array();
			// $this->vars = ee()->cookieConsent->handleGeneralSettings($this->vars);

			$ret = ee()->cookieConsent->handleGeneralSettingsPost();
			if ($ret === true) {
				ee('CP/Alert')->makeInline('shared-form')
					->asSuccess()
					->withTitle(lang('save_success'))
					->addToBody(lang('save_success_desc'))
					->defer();
				ee()->functions->redirect(ee('CP/URL')->make('addons/settings/cookieconsent'));
			} else {
				ee('CP/Alert')->makeInline('shared-form')
					->asIssue()
					->withTitle(lang('save_error'))
					->addToBody(lang('save_error_desc'))
					->now();
				ee()->functions->redirect(ee('CP/URL')->make('addons/settings/cookieconsent'));
			}
		}

		return array(
			'body'       => "<div class='" . ((version_compare(APP_VER, '4.0.0', '<')) ? 'box' : '') . "'>" . ee('View')->make('ee:_shared/form')->render($this->vars) . "</div>",
			'breadcrumb' => array(
				ee('CP/URL')->make('addons/settings/cookieconsent')->compile() => lang('cookieconsent_module_name')
			),
			'heading' => lang('cookieconsent_settings')
		);
	}

	function _startupForm()
	{
		$this->vars = array();

		/* CSRF and XID is same after EE version 2.8.0. For previous versions (Backword compatability) */
		if (version_compare(APP_VER, '2.8.0', '<')) {
			$this->vars['csrf_token']   = ee()->security->get_csrf_hash();
			$this->vars['xid']          = ee()->functions->add_form_security_hash('{XID_HASH}');
		} else {
			$this->vars['csrf_token']   = XID_SECURE_HASH;
			$this->vars['xid']          = XID_SECURE_HASH;
		}
	}
}

/* End of file mcp.cookieconsent.php */
/* Location: ./system/user/addons/cookieconsent/mcp.cookieconsent.php */
