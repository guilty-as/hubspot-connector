<?php

namespace Guilty\HubspotConnector\controllers;

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
        $email = Craft::$app->getRequest()->getParam('email', null);

        if (!$email) {
            return $this->asErrorJson("email not defined");
        }

        $service = HubspotConnector::getInstance()->hubspot;

        $contactExists = $service->hasContactByEmail($email);

        if ($contactExists == false) {
            $service->createContact([
                [
                    "property" => "email",
                    "value" => $email,
                ],
            ]);
        }

        return $service->subscribeToBlogNewsletter($email)
            ? $this->asJson(["success" => true, "message" => "You're now subscribed"])
            : $this->asJson(["success" => false, "message" => "Could not subscribe your email"]);
    }
}