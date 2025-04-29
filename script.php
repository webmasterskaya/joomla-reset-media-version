<?php
/*
 * @package     Joomla - Reset media version
 * @version     1.1.0
 * @author      Artem Vasilev - webmasterskaya.xyz
 * @copyright   Copyright (c) 2018 - 2021 Webmasterskaya. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://webmasterskaya.xyz/
 */

use Joomla\CMS\Application\AdministratorApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\Database\DatabaseDriver;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

\defined('_JEXEC') or die;

return new class () implements ServiceProviderInterface {

    public function register(Container $container)
    {
        $container->set(InstallerScriptInterface::class,
            new class ($container->get(AdministratorApplication::class)) implements InstallerScriptInterface {
                /**
                 * The application object
                 *
                 * @var  AdministratorApplication
                 *
                 * @since  2.0.0
                 */
                protected AdministratorApplication $app;

                /**
                 * The Database object.
                 *
                 * @var   DatabaseDriver
                 *
                 * @since  2.0.0
                 */
                protected DatabaseDriver $db;

                /**
                 * Constructor.
                 *
                 * @param   AdministratorApplication  $app  The application object.
                 *
                 * @since 2.0.0
                 */
                public function __construct(AdministratorApplication $app)
                {
                    $this->app = $app;
                    $this->db  = Factory::getContainer()->get('DatabaseDriver');
                }

                /**
                 * Function called after the extension is installed.
                 *
                 * @param   InstallerAdapter  $adapter  The adapter calling this method
                 *
                 * @return  boolean  True on success
                 *
                 * @since   2.0.0
                 */
                public function install(InstallerAdapter $adapter): bool
                {
                    $this->enablePlugin($adapter);

                    return true;
                }

                /**
                 * Function called after the extension is updated.
                 *
                 * @param   InstallerAdapter  $adapter  The adapter calling this method
                 *
                 * @return  boolean  True on success
                 *
                 * @since   2.0.0
                 */
                public function update(InstallerAdapter $adapter): bool
                {
                    return true;
                }

                /**
                 * Function called after the extension is uninstalled.
                 *
                 * @param   InstallerAdapter  $adapter  The adapter calling this method
                 *
                 * @return  boolean  True on success
                 *
                 * @since   2.0.0
                 */
                public function uninstall(InstallerAdapter $adapter): bool
                {
                    return true;
                }

                /**
                 * Function called before extension installation/update/removal procedure commences.
                 *
                 * @param   string            $type     The type of change (install or discover_install, update, uninstall)
                 * @param   InstallerAdapter  $adapter  The adapter calling this method
                 *
                 * @return  boolean  True on success
                 *
                 * @since   2.0.0
                 */
                public function preflight(string $type, InstallerAdapter $adapter): bool
                {
                    return true;
                }

                /**
                 * Function called after extension installation/update/removal procedure commences.
                 *
                 * @param   string            $type     The type of change (install or discover_install, update, uninstall)
                 * @param   InstallerAdapter  $adapter  The adapter calling this method
                 *
                 * @return  boolean  True on success
                 *
                 * @since   2.0.0
                 */
                public function postflight(string $type, InstallerAdapter $adapter): bool
                {
                    return true;
                }

                /**
                 * Enable plugin after installation.
                 *
                 * @param   InstallerAdapter  $adapter  Parent object calling object.
                 *
                 * @since  2.0.0
                 */
                protected function enablePlugin(InstallerAdapter $adapter): void
                {
                    // Prepare plugin object
                    $plugin          = new \stdClass();
                    $plugin->type    = 'plugin';
                    $plugin->element = $adapter->getElement();
                    $plugin->folder  = (string)$adapter->getParent()->manifest->attributes()['group'];
                    $plugin->enabled = 1;

                    // Update record
                    $this->db->updateObject('#__extensions', $plugin, ['type', 'element', 'folder']);
                }
            });
    }
};
