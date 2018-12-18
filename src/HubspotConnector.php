<?php

namespace Guilty\HubspotConnector;

use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\services\Fields;
use craft\web\UrlManager;
use Guilty\HubspotConnector\fields\HubspotBlogField;
use Guilty\HubspotConnector\fields\HubspotBlogTopicField;
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
 * @property  HubspotService $hubspot
 */
class HubspotConnector extends Plugin
{
    /**
     * @var HubspotConnector
     */
    public static $plugin;

    /**
     * @var string
     */
    public $schemaVersion = '1.0.3';

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                $event->sender->set('hubspot', HubspotConnectorVariable::class);
            });

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['hubspot/subscribe'] = 'hubspot-connector/subscribe';
            }
        );

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = HubspotBlogField::class;
                $event->types[] = HubspotBlogTopicField::class;
            });

    }

    protected function createSettingsModel()
    {
        return new Settings();
    }

    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'hubspot-connector/settings',
            [
                'settings' => $this->getSettings(),
                'isConnected' => $this->hubspot->hasApiKey(),
                'blogSubscriptionFrequencies' => $this->getFrequencyList(),
                'contactProperties' => $this->getContactInformationPropertiesList(),
            ]
        );
    }

    protected function getFrequencyList()
    {
        if ($this->hubspot->hasApiKey() === false) {
            return [];
        }

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


        if ($this->hubspot->hasApiKey() === false) {
            return [];
        }

        return array_map(function ($item) {
            return [
                "label" => $item->label,
                "value" => $item->name,
            ];
        }, $this->hubspot->getHubspotDefinedContactInformationProperties());
    }
}
