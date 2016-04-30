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
			// Update existing configs
			array('config.update', array('qr_version', '2.0.0-dev')),

			// Remove configs
			array('config.remove', array('qr_source_post')),
			array('config.remove', array('qr_quickquote_link')),
		);
	}
}
