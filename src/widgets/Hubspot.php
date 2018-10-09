<?php
/**
 * HubSpot Connector plugin for Craft CMS 3.x
 *
 * Expose Hubspot api features in Twig and pull in content from your HubSpot Portal.
 *
 * @link      https://guilty.no
 * @copyright Copyright (c) 2018 Guilty AS
 */

namespace Guilty\HubspotConnector\widgets;

use Guilty\HubspotConnector\HubspotConnector;
use Guilty\HubspotConnector\assetbundles\hubspotwidget\HubspotWidgetAsset;

use Craft;
use craft\base\Widget;

/**
 * HubSpot Connector Widget
 *
 * @author    Guilty AS
 * @package   HubspotConnector
 * @since     1.0.0
 */
class Hubspot extends Widget
{

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $message = 'Hello, world.';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('hub-spot-connector', 'Hubspot');
    }

    /**
     * @inheritdoc
     */
    public static function iconPath()
    {
        return Craft::getAlias("@guilty/hubspotconnector/assetbundles/hubspotwidget/dist/img/Hubspot-icon.svg");
    }

    /**
     * @inheritdoc
     */
    public static function maxColspan()
    {
        return null;
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge(
            $rules,
            [
                ['message', 'string'],
                ['message', 'default', 'value' => 'Hello, world.'],
            ]
        );
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'hub-spot-connector/_components/widgets/Hubspot_settings',
            [
                'widget' => $this
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getBodyHtml()
    {
        Craft::$app->getView()->registerAssetBundle(HubspotWidgetAsset::class);

        return Craft::$app->getView()->renderTemplate(
            'hub-spot-connector/_components/widgets/Hubspot_body',
            [
                'message' => $this->message
            ]
        );
    }
}
