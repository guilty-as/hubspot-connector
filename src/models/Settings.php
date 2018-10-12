<?php
/**
 * HubSpot Connector plugin for Craft CMS 3.x
 *
 * Expose Hubspot api features in Twig and pull in content from your HubSpot Portal.
 *
 * @link      https://guilty.no
 * @copyright Copyright (c) 2018 Guilty AS
 */

namespace Guilty\HubspotConnector\models;

use craft\base\Model;

class Settings extends Model
{
    /**
     * @var string
     */
    public $apiKey = '';

    /**
     * @var bool
     */
    public $enableBlogSubscriptionEndpoint = false;

    public function rules()
    {
        return [
            ['apiKey', 'string'],
            ['apiKey', 'required'],
            ['enableBlogSubscriptionEndpoint', 'bool'],
        ];
    }
}
