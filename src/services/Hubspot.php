<?php

namespace Guilty\HubspotConnector\services;

use craft\base\Component;
use Guilty\HubspotConnector\HubspotConnector;
use SevenShores\Hubspot\Http\Client;
use SevenShores\Hubspot\Resources\BlogPosts;
use SevenShores\Hubspot\Resources\Blogs;
use SevenShores\Hubspot\Resources\BlogTopics;
use SevenShores\Hubspot\Resources\ContactProperties;
use SevenShores\Hubspot\Resources\Contacts;

class Hubspot extends Component
{
    /** @var Contacts */
    protected $contacts;

    /** @var Blogs */
    protected $blogs;

    /** @var BlogPosts */
    protected $blogPosts;

    /** @var BlogTopics */
    protected $blogTopics;

    /** @var ContactProperties */
    protected $contactProperties;

    /** @var string */
    protected $apiKey;

    /**
     * @var \Guilty\HubspotConnector\models\Settings
     */
    protected $settings;

    // Setup
    // =========================================================================
    public function init()
    {
        parent::init();

        $settings = HubspotConnector::$plugin->getSettings();

        $this->settings = $settings;
        $this->apiKey = $settings->apiKey;

        if ($this->hasApiKey()) {
            $this->setupClients();
        }
    }

    protected function setupClients()
    {
        $client = new Client([
            'key' => $this->apiKey,
        ], new \GuzzleHttp\Client([
            'http_errors' => false,
        ]));

        $this->contacts = new Contacts($client);
        $this->blogs = new Blogs($client);
        $this->blogPosts = new BlogPosts($client);
        $this->blogTopics = new BlogTopics($client);
        $this->contactProperties = new ContactProperties($client);
    }

    public function hasApiKey()
    {
        return empty($this->apiKey) === false;
    }


    // Blogs
    // =========================================================================
    public function getBlogs($params = [])
    {
        return $this->blogs->all($params)->getData();
    }

    public function getBlog($blogId)
    {
        return $this->blogs->getById($blogId)->getData();
    }

    // Blog Posts
    // =========================================================================
    public function getBlogPosts($blogId = false, $params = [])
    {
        return $this->blogPosts->all(array_merge($params, [
            "content_group_id" => $blogId,
        ]))->getData();
    }

    public function getBlogPost($blogPostId)
    {
        return $this->blogPosts->getById($blogPostId)->getData();
    }

    // Blog Topics
    // =========================================================================
    public function getBlogTopics($params = [])
    {
        return $this->blogTopics->all($params)->getData();
    }

    public function getBlogTopic($blogTopicId)
    {
        return $this->blogTopics->getById($blogTopicId)->getData();
    }

    public function searchBlogTopics($query, $params)
    {
        return $this->blogTopics->search($query, $params)->getData();
    }

    // Contacts
    // =========================================================================
    public function getContactByEmail($email)
    {
        return $this->contacts->getByEmail($email)->getData();
    }

    public function getContact($contactId)
    {
        return $this->contacts->getById($contactId)->getData();
    }

    public function getContacts($params = [])
    {
        return $this->contacts->all($params)->getData();
    }

    public function createContact($properties)
    {
        return $this->contacts->create($properties)->getData();
    }

    public function hasContactByEmail($email)
    {
        $response = $this->getContactByEmail($email);

        if (isset($response->status) && $response->status === "error") {
            return false;
        }

        return true;
    }


    // Convenience Methods
    // =========================================================================

    /**
     * This will "subscribe" a contact/email to the seleted blog by magically
     * setting the blogSubscriptionProperty on the contact object to a chosen
     * "blogSubscriptionFrequency" (usually  daily, weekly, biweekly or monthly)
     *
     * @param string $email
     * @return bool true if the contact was updated, false if not
     */
    public function subscribeToBlogNewsletter($email)
    {
        return $this->contacts->updateByEmail($email, [
                [
                    "property" => $this->settings->blogSubscriptionProperty,
                    "value" => $this->settings->defaultBlogSubscriptionFrequency,
                ],
            ])->getStatusCode() === 204;
    }

    public function getContactPropetyGroupDetails()
    {
        return array_filter($this->contactProperties->all()->getData(), function ($property) {
            return $property->groupName === "contactinformation";
        });
    }


    /**
     * Get all properties that are Hubspot defined (aka, default properties)
     * that are in the "contactinformation" group
     *
     * @return array
     */
    public function getHubspotDefinedContactInformationProperties()
    {
        return array_filter($this->contactProperties->all()->getData(), function ($property) {
            return $property->groupName === "contactinformation" && $property->hubspotDefined === true;
        });
    }

    /**
     * Get all properties that are in the "contactinformation" group
     *
     * @return array
     */
    public function getContactInformationProperties()
    {
        return array_filter($this->contactProperties->all()->getData(), function ($property) {
            return $property->groupName === "contactinformation";
        });
    }

    public function getBlogSubscriptionFrequencies()
    {
        // TODO(10 des 2018) ~ Helge: Implement
    }


}
