<?php
/**
 * HubSpot Connector plugin for Craft CMS 3.x
 *
 * Expose Hubspot api features in Twig and pull in content from your HubSpot Portal.
 *
 * @link      https://guilty.no
 * @copyright Copyright (c) 2018 Guilty AS
 */

namespace Guilty\HubspotConnector;

use Guilty\HubspotConnector\services\Hubspot as HubspotService;
use Guilty\HubspotConnector\variables\HubspotConnectorVariable;
use Guilty\HubspotConnector\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

/**
 * Class HubspotConnector
 *
 * @author    Guilty AS
 * @package   HubspotConnector
 * @since     1.0.0
 *
 * @property  HubspotService $hubspot
 */
class HubspotConnector extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var HubspotConnector
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function (Event $event) {
            /** @var CraftVariable $variable */
            $variable = $event->sender;
            $variable->set('hubspot', HubspotConnectorVariable::class);
        });
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'hubspot-connector/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
