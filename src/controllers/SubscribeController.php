<?php

namespace Guilty\HubspotConnector\Controllers;

use Craft;
use craft\web\Controller;
use Guilty\HubspotConnector\HubspotConnector;

class SubscribeController extends Controller
{
    protected $allowAnonymous = true;

    protected $blogSubscriptionFrequencies = [
        'instant',
        'daily',
        'weekly',
        'monthly',
    ];



    public function actionIndex()
    {
        die("Hello world");

        $request = Craft::$app->getRequest();

        $blogId = $request->getParam('blogId', null);
        $email = $request->getParam('email', null);

        if (!$blogId || !$email) {
            return false;
        }

        $success = HubspotConnector::getInstance()->hubspot->subscribeTo($email, $blogId);

        return true;
    }
}