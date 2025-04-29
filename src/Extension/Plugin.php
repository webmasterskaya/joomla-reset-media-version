<?php

/**
 * @package     Joomla\Plugin\Quickicon\ResetMediaVersion\Extension
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Joomla\Plugin\Quickicon\ResetMediaVersion\Extension;

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;

/**
 * @method \Joomla\CMS\Application\CMSWebApplicationInterface getApplication()
 *
 * @since 2.0
 */
class Plugin extends CMSPlugin implements SubscriberInterface
{
    /**
     * Affects constructor behavior. If true, language files will be loaded automatically.
     *
     * @var    boolean
     * @since  1.0.0
     */
    protected $autoloadLanguage = true;

    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return  array
     *
     * @since   2.0.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onGetIcons'              => 'getIcons',
            'onAjaxResetmediaversion' => 'resetMediaVersion',
        ];
    }

    /**
     * This method is called when the Quick Icons module is constructing its set
     * of icons. You can return an array which defines a single icon and it will
     * be rendered right after the stock Quick Icons.
     *
     * @param   Event  $event  The event object
     *
     * @return void
     *
     * @since   1.0.0
     */
    public function getIcons(Event $event): void
    {
        $context = $event->getArgument('context');

        if (!$this->getApplication()->getIdentity()->authorise('core.manage', 'com_config')
            || $context !== $this->params->get('context', 'mod_quickicon')) {
            return;
        }

        $request = Uri::buildQuery(
            [
                'option'                => 'com_ajax',
                'plugin'                => 'resetmediaversion',
                'group'                 => 'quickicon',
                'format'                => 'json',
                Session::getFormToken() => 1,
            ]
        );

        $icon = [
            [
                'link'   => Route::_('index.php?' . $request),
                'image'  => 'fas fa-sync-alt',
                'icon'   => '',
                'text'   => Text::_('PLG_QUICKICON_RESETMEDIAVERSION_RESET'),
                'id'     => 'plg_quickicon_resetmediaversion',
                'group'  => 'MOD_QUICKICON_MAINTENANCE',
                'access' => ['core.manage', 'com_config'],
            ],
        ];

        $document = $this->getApplication()->getDocument();
        $wa       = $document->getWebAssetManager();

        $wa->registerAndUseScript(
            'plg_quickicon_resetmediaversion.resetmediaversion',
            'plg_quickicon_resetmediaversion/resetmediaversion.min.js',
            [],
            ['defer' => true],
            ['core']
        );

        $document->addScriptOptions('js-reset-media-version', ['j4_compatible' => true]);

        $result = $event->getArgument('result', []);

        $result[] = $icon;

        $event->setArgument('result', $result);
    }

    /**
     * Method to ajax functions.
     *
     * @throws \Exception
     *
     * @return void
     * @since   1.0
     */
    public function resetMediaVersion(): void
    {
        if (!Session::checkToken('get')) {
            $this->getApplication()->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
            $this->getApplication()->close();
        }

        if (!$this->getApplication()->getIdentity()->authorise('core.manage', 'com_config')) {
            $this->getApplication()->enqueueMessage(Text::_('JGLOBAL_AUTH_ACCESS_DENIED'), 'error');
            $this->getApplication()->close();
        }

        $task = $this->getApplication()->input->get('task', 'reset');

        switch ($task):
            case 'reset':
                (new Version())->refreshMediaVersion();
                echo new JsonResponse(
                    true,
                    Text::_('PLG_QUICKICON_RESETMEDIAVERSION_COMPLETE'),
                    false
                );
                break;
            default:
                echo new JsonResponse(
                    false,
                    Text::_('PLG_QUICKICON_RESETMEDIAVERSION_COMPLETE'),
                    false
                );
        endswitch;
        $this->getApplication()->close();
    }
}
