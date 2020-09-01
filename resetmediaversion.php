<?php
/*
 * @package     Joomla - Reset media version
 * @version     __DEPLOY_VERSION__
 * @author      Artem Vasilev - webmasterskaya.xyz
 * @copyright   Copyright (c) 2018 - 2020 Webmasterskaya. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://webmasterskaya.xyz/
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

class PlgQuickiconResetMediaVersion extends CMSPlugin
{
	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * This method is called when the Quick Icons module is constructing its set
	 * of icons. You can return an array which defines a single icon and it will
	 * be rendered right after the stock Quick Icons.
	 *
	 * @param   string  $context  The calling context
	 *
	 * @return  array  A list of icon definition associative arrays, consisting of the
	 *                 keys link, image, text and access.
	 *
	 * @since   1.0
	 */
	public function onGetIcons($context)
	{
		if ($context !== $this->params->get('context', 'mod_quickicon')
			|| !Factory::getUser()->authorise('core.manage', 'com_config'))
		{
			return;
		}

		$token = Session::getFormToken().'='. 1;
		$link  = Uri::base()
			.'index.php?option=com_ajax&plugin=resetmediaversion&group=quickicon&format=json&task=reset&'
			.$token;
		HTMLHelper::_(
			'script',
			'plg_quickicon_resetmediaversion/resetmediaversion.js',
			array('version' => 'auto', 'relative' => true)
		);

		return array(
			array(
				'link'   => $link,
				'image'  => 'loop',
				'icon'   => 'header/icon-48-purge.png',
				'text'   => Text::_('PLG_QUICKICON_RESETMEDIAVERSION_RESET'),
				'id'     => 'plg_quickicon_resetmediaversion',
				'group'  => 'MOD_QUICKICON_MAINTENANCE',
				'access' => array('core.manage', 'com_config'),
			),
		);
	}

	/**
	 * Method to ajax functions.
	 *
	 * @throws Exception
	 *
	 * @since   1.0
	 */
	public function onAjaxResetMediaVersion()
	{
		if (!Session::checkToken('get'))
		{
			echo new JsonResponse(
				new Exception(Text::_('JINVALID_TOKEN')), '', true
			);
			Factory::getApplication()->close();
		}

		if (!Factory::getUser()->authorise('core.manage', 'com_config'))
		{
			echo new JsonResponse(
				new Exception(Text::_('JGLOBAL_AUTH_ACCESS_DENIED')), '', true
			);
			Factory::getApplication()->close();
		}

		$task = Factory::getApplication()->input->get('task', 'reset');

		switch ($task):
			case 'reset':
				(new Version())->refreshMediaVersion();
				echo new JsonResponse(true, Text::_('PLG_QUICKICON_RESETMEDIAVERSION_COMPLETE'), false);
				Factory::getApplication()->close();
				break;
			default:
				echo new JsonResponse(false, Text::_('PLG_QUICKICON_RESETMEDIAVERSION_COMPLETE'), false);
				Factory::getApplication()->close();
		endswitch;
	}
}
