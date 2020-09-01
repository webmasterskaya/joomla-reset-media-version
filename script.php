<?php
/*
 * @package     Joomla - Reset media version
 * @version     1.0.0
 * @author      Artem Vasilev - webmasterskaya.xyz
 * @copyright   Copyright (c) 2018 - 2020 Webmasterskaya. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://webmasterskaya.xyz/
 */

defined('_JEXEC') or die;

class PlgQuickiconResetMediaVersionInstallerScript
{
	/**
	 * The minimal compatible version of PHP
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $minPHPVersion = '5.6.0';

	/**
	 * The minimal compatible version of Joomla!
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $minJVersion = '3.9.0';

	/**
	 * Runs right before any installation action.
	 *
	 * @param   string  $type  Type of PreFlight action.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @throws  Exception
	 *
	 * @since  1.0.0
	 */
	function preflight($type)
	{
		// use Factory class
		if (class_exists(\Joomla\CMS\Factory::class))
		{
			$factory = \Joomla\CMS\Factory::class;
		}
		else
		{
			$factory = JFactory::class;
		}

		// use Text class
		if (class_exists(\Joomla\CMS\Language\Text::class))
		{
			$text = \Joomla\CMS\Language\Text::class;
		}
		else
		{
			$text = JText::class;
		}

		// Check compatible PHP version
		if (!(version_compare(PHP_VERSION, $this->minPHPVersion) >= 0))
		{
			$factory::getApplication()->enqueueMessage(
				$text::sprintf(
					'PLG_QUICKICON_RESETMEDIAVERSION_WRONG_PHP',
					$this->minPHPVersion
				),
				'error'
			);

			return false;
		}

		// use Version class
		if (class_exists(\Joomla\CMS\Version::class))
		{
			$version = new \Joomla\CMS\Version();
		}
		else
		{
			jimport('joomla.version');
			$version = new JVersion();
		}

		// Check compatible Joomla version
		if (!$version->isCompatible($this->minJVersion))
		{
			$factory::getApplication()->enqueueMessage(
				$text::sprintf(
					'PLG_QUICKICON_RESETMEDIAVERSION_WRONG_JOOMLA',
					$this->minJVersion
				),
				'error'
			);

			return false;
		}

		return true;
	}

	/**
	 * Runs right after any installation action.
	 *
	 * @param   string  $type  Type of PostFlight action.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @throws  Exception
	 *
	 * @since  1.0.0
	 */
	function postflight($type)
	{
		// We will publish the plugin if this is the installation
		if ($type != 'update')
		{
			$db = \Joomla\CMS\Factory::getDbo();
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__extensions'))
				->set(
					$db->quoteName('enabled').' = 1'
				)
				->where(
					$db->quoteName('name').' = '.$db->quote(
						'plg_quickicon_resetmediaversion'
					)
				);
			$db->setQuery($query)->execute();
		}

		return true;
	}
}
