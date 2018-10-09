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

use Guilty\HubspotConnector\HubspotConnector;

use Craft;
use craft\base\Model;

/**
 * @author    Guilty AS
 * @package   HubspotConnector
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $apiKey = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['apiKey', 'string'],
            ['apiKey', 'required'],
        ];
    }
}
