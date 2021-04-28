<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cookieconsent_model extends CI_Model
{
    public $site_id;

    public function __construct()
    {
        /* Define global variables */
        $this->site_id = ee()->config->item('site_id');
    }

    function handleGeneralSettingsPost($values)
    {
        $this->db->select('id');
        $this->db->from('cookieconsent_settings');
        $this->db->where('site_id', $this->site_id);
        $get = $this->db->get();
        $values['site_id'] = $this->site_id;

        if ($get->num_rows == 0) {
            $this->db->insert('cookieconsent_settings', $values);
            return true;
        }

        $id = $get->row('id');
        $this->db->where('id', $id);
        $this->db->update('cookieconsent_settings', $values);
        return true;
    }

    function getGeneralSettings()
    {
        $this->db->select('*');
        $this->db->from('cookieconsent_settings');
        $this->db->where('site_id', $this->site_id);
        $get = $this->db->get();

        if ($get->num_rows == 0) {
            return false;
        }

        $result = $get->result_array();
        return $result[0];
    }
}
