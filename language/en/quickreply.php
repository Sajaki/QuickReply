<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'QR_INSERT_TEXT'                   => 'Insert quote in the Quick Reply form',
	'QR_PROFILE'                       => 'Go to profile',
	'QR_QUICKNICK'                     => 'Refer by username',
	'QR_QUICKNICK_TITLE'               => 'Insert username in the Quick Reply form',
	'QR_REPLY_IN_PM'                   => 'Reply in PM',
	//begin mod Translit
	'QR_TRANSLIT_TEXT'                 => 'Translit:',
	'QR_TRANSLIT_TEXT_TO_RU'           => 'to russian',
	'QR_TRANSLIT_TEXT_TOOLTIP'         => 'For instant view in Russian click the button',
	'QR_FORAIN_LANG'                   => array('je','jo','ayu','ay','aj','oju','oje','oja','oj','uj','yi','ya','ja','ju','yu','ja','y','zh','i\'','shch','sch','ch','sh','ea','a','b','v','w','g','d','e','z','i','k','l','m','n','o','p','r','s','t','u','f','x','c','\'e','\'','`','j','h'),
	'QR_THIS_LANG'                     => array('э','ё','aю','ай','ай','ою','ое','оя','ой','уй','ый','я','я','ю','ю','я','ы','ж','й','щ','щ','ч','ш','э','а','б','в','в','г','д','е','з','и','к','л','м','н','о','п','р','с','т','у','ф','х','ц','э','ь','ъ','й','х'),
	'QR_FORAIN_LANG_CAP'               => array('Yo','Jo','Ey','Je','Ay','Oy','Oj','Uy','Uj','Ya','Ja','Ju','Yu','Ja','Y','Zh','I\'','Sch','Ch','Sh','Ea','Tz','A','B','V','W','G','D','E','Z','I','K','L','M','N','O','P','R','S','T','U','F','X','C','EA','J','H'),
	'QR_THIS_LANG_CAP'                 => array('Ё','Ё','Ей','Э','Ай','Ой','Ой','Уй','Уй','Я','Я','Ю','Ю','Я','Ы','Ж','Й','Щ','Ч','Ш','Э','Ц','А','Б','В','В','Г','Д','Е','З','И','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Э','Й','Х'),
	//end mod Translit
	//begin mod CapsLock Transform
	'QR_TRANSFORM_TEXT'                => 'Change Text Case:',
	'QR_TRANSFORM_TEXT_TOOLTIP'        => 'Press a button to change the case of the highlighted text',
	'QR_TRANSFORM_TEXT_LOWER'          => '&#9660; abc',
	'QR_TRANSFORM_TEXT_UPPER'          => '&#9650; ABC',
	'QR_TRANSFORM_TEXT_INVERS'         => '&#9660;&#9650; aBC',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'  => 'lower case',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'  => 'UPPER CASE',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP' => 'iNVERT cASE',
	//end mod CapsLock Transform
));
