<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

namespace boardtools\quickreply\acp;

class quickreply_qn_module extends \boardtools\quickreply\functions\acp_module_helper
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var string */
	protected $form_key;

	/** @var bool */
	protected $submit;

	/** @var array */
	protected $error;

	/** @var array */
	protected $display_vars;

	/** @var string */
	public $u_action;

	/** @var string */
	public $page_title;

	/** @var string */
	public $tpl_name;

	/** @var array */
	public $new_config = array();

	public function main($id, $mode)
	{
		global $config, $template, $user, $request;

		$this->config = $config;
		$this->new_config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;

		$this->tpl_name = 'acp_quickreply_qn';
		$this->form_key = 'config_quickreply_qn';
		add_form_key($this->form_key);

		$this->generate_display_vars();

		$this->submit = $this->request->is_set_post('submit');
		$this->submit_form();

		// Output relevant page
		$this->output_page();
	}

	/**
	 * Generates the array of display_vars
	 *
	 * @return array
	 */
	private function generate_display_vars()
	{
		$this->display_vars = array(
			'title' => 'ACP_QUICKREPLY_QN',
			'vars'  => array(
				'legend1'              => '',
				'qr_quickquote'        => array('lang' => 'ACP_QR_QUICKQUOTE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_source_post'       => array('lang' => 'ACP_QR_SOURCE_POST', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_quickquote_link'   => array('lang' => 'ACP_QR_QUICKQUOTE_LINK', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_full_quote'        => array('lang' => 'ACP_QR_FULL_QUOTE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),

				'legend2'              => '',
				'qr_quicknick'         => array('lang' => 'ACP_QR_QUICKNICK', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_quicknick_string'  => array('lang' => 'ACP_QR_QUICKNICK_STRING', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_quicknick_ref'     => array('lang' => 'ACP_QR_QUICKNICK_REF', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_comma'             => array('lang' => 'ACP_QR_COMMA', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_color_nickname'    => array('lang' => 'ACP_QR_COLOUR_NICKNAME', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_quicknick_pm'      => array('lang' => 'ACP_QR_QUICKNICK_PM', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),

				'legend3' => 'ACP_SUBMIT_CHANGES',
			),
		);
	}
}
