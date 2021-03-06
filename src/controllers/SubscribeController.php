<?php

namespace Guilty\HubspotConnector\controllers;

use Craft;
use craft\web\Controller;
use Guilty\HubspotConnector\HubspotConnector;

class SubscribeController extends Controller
{
    protected $allowAnonymous = true;

    public function actionIndex()
    {
        $this->requirePostRequest();

        $plugin = HubspotConnector::getInstance();

        if ($plugin->getSettings()->enableBlogSubscriptionEndpoint == false) {
            return $this->asErrorJson("Blog subscription endpoint is disabled");
        }

        $email = Craft::$app->getRequest()->getParam("email");

        if (!$email) {
            return $this->asErrorJson("email not defined");
        }

        $service = $plugin->hubspot;

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