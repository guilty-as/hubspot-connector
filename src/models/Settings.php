<?php

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

    /**
     * @var string
     */
    public $defaultBlogSubscriptionFrequency = null;

    /**
     * @var string
     */
    public $blogSubscriptionProperty = null;

    /** @var int  */
    public $connectTimeout = 30;

    public function rules()
    {
        return [
            ['apiKey', 'string'],
            ['apiKey', 'string'],
            ['connectTimeout', 'number'],
            ['defaultBlogSubscriptionFrequency', 'string'],
            ['blogSubscriptionProperty', 'string'],
        ];
    }
}
