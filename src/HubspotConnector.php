<?php

namespace Guilty\HubspotConnector;

use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
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


        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['hubspot/subscribe'] = 'hubspot-connector/subscribe';
            }
        );
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
                'settings' => $this->getSettings(),
                'blogSubscriptionFrequencies' => $this->getFrequencyList(),
                'contactProperties' => $this->getContactInformationPropertiesList(),
            ]
        );
    }

    protected function getFrequencyList()
    {
        return [
            [
                "label" => "Instant",
                "value" => "instant",
            ],
            [
                "label" => "Daily",
                "value" => "daily",
            ],
            [
                "label" => "Weekly",
                "value" => "weekly",
            ],
            [
                "label" => "Monthly",
                "value" => "monthly",
            ],
        ];
    }

    protected function getContactInformationPropertiesList()
    {
        return array_map(function ($item) {
            return [
                "label" => $item->label,
                "value" => $item->name,
            ];
        }, $this->hubspot->getHubspotDefinedContactInformationProperties());
    }
}
