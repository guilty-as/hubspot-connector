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


    public function rules()
    {
        return [
            ['apiKey', 'string'],
            ['apiKey', 'required'],
            ['defaultBlogSubscriptionFrequency', 'string'],
            ['blogSubscriptionProperty', 'string'],
        ];
    }
}
