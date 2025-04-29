<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\Quickicon\ResetMediaVersion\Extension\Plugin;

\defined('_JEXEC') or die;

return new class implements ServiceProviderInterface {
    public function register(Container $container)
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $plugin = new Plugin(
                    $container->get(DispatcherInterface::class),
                    (array)PluginHelper::getPlugin('quickicon', 'resetmediaversion')
                );
                $plugin->setApplication(Factory::getApplication());

                return $plugin;
            }
        );
    }
};