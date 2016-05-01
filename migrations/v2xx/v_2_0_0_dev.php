<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v2xx;

class v_2_0_0_dev extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '2.0.0-dev', '>=');
	}

	public static function depends_on()
	{
		return array('\boardtools\quickreply\migrations\v1xx\v_1_0_2');
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'update_bbcode_post'))),

			// Update existing configs
			array('config.update', array('qr_version', '2.0.0-dev')),

			// Remove configs
			array('config.remove', array('qr_source_post')),
			array('config.remove', array('qr_quickquote_link')),
		);
	}

	public function update_bbcode_post()
	{
		// Load the acp_bbcode class
		$bbcode_tool = $this->load_class();

		$bbcode_data = $this->get_bbcode_data();

		foreach ($bbcode_data as $bbcode_name => $bbcode_array)
		{
			// Build the BBCodes
			$data = $bbcode_tool->build_regexp($bbcode_array['bbcode_match'], $bbcode_array['bbcode_tpl']);
			$bbcode_array += $this->build_bbcode_array($data);

			$row_exists = $this->exist_bbcode($bbcode_name, $bbcode_array);
			if ($row_exists)
			{
				// Update existing BBCode
				$this->update_bbcode($row_exists['bbcode_id'], $bbcode_array);
			}
		}
	}

	private function load_class()
	{
		if (!class_exists('acp_bbcodes'))
		{
			include($this->phpbb_root_path . 'includes/acp/acp_bbcodes.' . $this->php_ext);
		}
		return new \acp_bbcodes();
	}

	private function get_bbcode_data()
	{
		return array(
			'post'	=> array(
				'bbcode_helpline'	=> 'BBCode for QuickReply extension',
				'bbcode_match'		=> '[post]{NUMBER}[/post]',
				'bbcode_tpl'		=> '<a href="./viewtopic.php?p={NUMBER}#p{NUMBER}" title="{L_QR_BBPOST}"><i class="icon fa-external-link-square fa-fw icon-lightgray icon-md"></i></a>',
				'display_on_posting'=> 0,
			),
		);
	}

	private function build_bbcode_array($data)
	{
		return array(
				'bbcode_tag'			=> $data['bbcode_tag'],
				'first_pass_match'		=> $data['first_pass_match'],
				'first_pass_replace'	=> $data['first_pass_replace'],
				'second_pass_match'		=> $data['second_pass_match'],
				'second_pass_replace'	=> $data['second_pass_replace']
			);
	}

	private function exist_bbcode($bbcode_name, $bbcode_array)
	{
		$sql = 'SELECT bbcode_id
				FROM ' . $this->table_prefix . "bbcodes
				WHERE LOWER(bbcode_tag) = '" . strtolower($bbcode_name) . "'
				OR LOWER(bbcode_tag) = '" . strtolower($bbcode_array['bbcode_tag']) . "'";
		$result = $this->db->sql_query($sql);
		$row_exists = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $row_exists;
	}

	private function update_bbcode($bbcode_id, $bbcode_array)
	{
		$sql = 'UPDATE ' . $this->table_prefix . 'bbcodes
			SET ' . $this->db->sql_build_array('UPDATE', $bbcode_array) . '
			WHERE bbcode_id = ' . $bbcode_id;
		$this->db->sql_query($sql);
	}
}
