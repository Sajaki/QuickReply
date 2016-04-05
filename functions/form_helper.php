<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\functions;

class form_helper
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\plupload\plupload */
	protected $plupload;

	/** @var \phpbb\mimetype\guesser */
	protected $mimetype_guesser;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth         $auth
	 * @param \phpbb\config\config     $config
	 * @param \phpbb\template\template $template
	 * @param \phpbb\user              $user
	 * @param \phpbb\cache\service     $cache
	 * @param \phpbb\plupload\plupload $plupload
	 * @param \phpbb\mimetype\guesser  $mimetype_guesser
	 * @param string                   $phpbb_root_path Root path
	 * @param string                   $php_ext
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\cache\service $cache, \phpbb\plupload\plupload $plupload, \phpbb\mimetype\guesser $mimetype_guesser, $phpbb_root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->cache = $cache;
		$this->plupload = $plupload;
		$this->mimetype_guesser = $mimetype_guesser;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	/**
	 * Define BBCode and smilies status, handle attachments
	 *
	 * @param int $forum_id Forum ID
	 * @param int $topic_id Topic ID
	 */
	public function prepare_qr_form($forum_id, $topic_id)
	{
		// BBCode, Smilies and URLs
		$bbcode_status = $this->handle_bbcodes($forum_id);
		$smilies_status = $this->handle_smilies($forum_id);
		$this->template->assign_var('S_LINKS_ALLOWED', $this->config['allow_post_links']);

		// Show attachment box for adding attachments
		$show_attach_box = $this->qr_attachments_allowed($forum_id);

		if ($bbcode_status || $smilies_status || $show_attach_box)
		{
			$this->user->add_lang('posting');
		}

		if ($show_attach_box)
		{
			$this->handle_attachments($forum_id, $topic_id, $show_attach_box);
		}
	}

	/**
	 * Checks whether attachments are allowed in quick reply in the specified forum
	 *
	 * @param int $forum_id Forum ID
	 * @return bool
	 */
	public function qr_attachments_allowed($forum_id)
	{
		return (
			@ini_get('file_uploads') != '0' &&
			strtolower(@ini_get('file_uploads')) != 'off' &&
			$this->config['allow_attachments'] &&
			$this->auth->acl_get('u_attach') &&
			$this->auth->acl_get('f_attach', $forum_id) &&
			$this->config['qr_attach']
		);
	}

	/**
	 * Display BBCodes in the specified forum
	 *
	 * @param int $forum_id Forum ID
	 * @return bool Whether BBCode is enabled for quick reply
	 */
	protected function handle_bbcodes($forum_id)
	{
		$bbcode_status = (
			$this->config['allow_bbcode'] &&
			$this->auth->acl_get('f_bbcode', $forum_id)
		);
		$img_status = $flash_status = false;
		$quote_status = true;

		if ($bbcode_status)
		{
			$img_status = $this->auth->acl_get('f_img', $forum_id);
			$flash_status = (
				$this->auth->acl_get('f_flash', $forum_id) &&
				$this->config['allow_post_flash']
			);

			// Build custom bbcodes array
			display_custom_bbcodes();
		}

		$this->template->assign_vars(array(
			'S_BBCODE_ALLOWED' => ($bbcode_status) ? 1 : 0,
			'S_BBCODE_BUTTONS' => $bbcode_status && $this->config['qr_bbcode'],
			'S_BBCODE_IMG'     => $img_status,
			'S_BBCODE_FLASH'   => $flash_status,
			'S_BBCODE_QUOTE'   => $quote_status,
		));

		return $bbcode_status;
	}

	/**
	 * Display smilies in the specified forum
	 *
	 * @param int $forum_id Forum ID
	 * @return bool Whether smilies are enabled for quick reply
	 */
	protected function handle_smilies($forum_id)
	{
		$smilies_status = (
			$this->config['allow_smilies'] &&
			$this->config['qr_smilies'] &&
			$this->auth->acl_get('f_smilies', $forum_id)
		);

		// Generate smiley listing
		if ($smilies_status)
		{
			if (!function_exists('generate_smilies'))
			{
				include($this->phpbb_root_path . 'includes/functions_posting.' . $this->php_ext);
			}
			generate_smilies('inline', $forum_id);
		}

		$this->template->assign_var('S_SMILIES_ALLOWED', $smilies_status);

		return $smilies_status;
	}

	/**
	 * Parse and display attachments
	 *
	 * @param int  $forum_id        Forum ID
	 * @param int  $topic_id        Topic ID
	 * @param bool $show_attach_box Whether we need to display the attachment box
	 */
	public function handle_attachments($forum_id, $topic_id, $show_attach_box)
	{
		if (!class_exists('parse_message'))
		{
			include($this->phpbb_root_path . 'includes/message_parser.' . $this->php_ext);
		}
		$message_parser = new \parse_message();
		$message_parser->set_plupload($this->plupload);

		$message_parser->get_submitted_attachment_data($this->user->data['user_id']);

		$attachment_data = $message_parser->attachment_data;
		$filename_data = $message_parser->filename_data;

		posting_gen_inline_attachments($attachment_data);

		$max_files = ($this->auth->acl_get('a_') || $this->auth->acl_get('m_', $forum_id)) ? 0 : (int) $this->config['max_attachments'];
		$s_action = append_sid("{$this->phpbb_root_path}posting.$this->php_ext", "mode=reply&amp;f=$forum_id&amp;t=$topic_id");
		$this->plupload->configure($this->cache, $this->template, $s_action, $forum_id, $max_files);

		posting_gen_attachment_entry($attachment_data, $filename_data, $show_attach_box);

		$this->template->assign_vars(array(
			// Upload attachments
			'S_QR_SHOW_ATTACH_BOX' => $show_attach_box,
			'S_ATTACH_DATA'        => ($attachment_data) ? json_encode($attachment_data) : '[]',
		));
	}
}
