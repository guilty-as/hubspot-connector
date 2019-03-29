<?php

namespace Guilty\HubspotConnector\services;

use craft\base\Component;
use Guilty\HubspotConnector\HubspotConnector;
use GuzzleHttp\Exception\ConnectException;
use SevenShores\Hubspot\Exceptions\BadRequest;
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
            'timeout' => $this->settings->connectTimeout,
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
        try {
            return $this->blogs->all($params)->getData()->objects;
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function getBlog($blogId)
    {
        try {
            return $this->blogs->getById($blogId)->getData()->objects;
        } catch (\Exception $exception) {
            return [];
        }
    }

    // Blog Posts
    // =========================================================================
    public function getBlogPosts($blogId = false, $params = [])
    {
        try {
            return $this->blogPosts->all(array_merge($params, [
                "content_group_id" => $blogId,
            ]))->getData()->objects;
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function getBlogPost($blogPostId)
    {
        try {
            return $this->blogPosts->getById($blogPostId)->getData()->objects;
        } catch (\Exception $exception) {
            return [];
        }
    }

    // Blog Topics
    // =========================================================================
    public function getBlogTopics($params = [])
    {
        try {
            return $this->blogTopics->all($params)->getData()->objects;
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function getBlogTopic($blogTopicId)
    {
        try {
            return $this->blogTopics->getById($blogTopicId)->getData()->objects;
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function searchBlogTopics($query, $params)
    {
        try {
            return $this->blogTopics->search($query, $params)->getData()->objects;
        } catch (\Exception $exception) {
            return [];
        }
    }

    // Contacts
    // =========================================================================
    public function getContactByEmail($email)
    {
        try {
            return $this->contacts->getByEmail($email)->getData()->objects;
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function getContact($contactId)
    {
        try {
            return $this->contacts->getById($contactId)->getData()->objects;
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function getContacts($params = [])
    {
        try {
            return $this->contacts->all($params)->getData()->objects;
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function createContact($properties)
    {
        try {
            return $this->contacts->create($properties)->getData()->objects;
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function hasContactByEmail($email)
    {
        $response = $this->getContactByEmail($email);

        if ($response == [] || isset($response->status) && $response->status === "error") {
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
     * @return array|bool true if the contact was updated, false if not
     */
    public function subscribeToBlogNewsletter($email)
    {
        try {
            return $this->contacts->updateByEmail($email, [
                    [
                        "property" => $this->settings->blogSubscriptionProperty,
                        "value" => $this->settings->defaultBlogSubscriptionFrequency,
                    ],
                ])->getStatusCode() === 204;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function getContactPropetyGroupDetails()
    {
        try {
            return array_filter($this->contactProperties->all()->getData(), function ($property) {
                return $property->groupName === "contactinformation";
            });
        } catch (\Exception $exception) {
            return [];
        }
    }


    /**
     * Get all properties that are Hubspot defined (aka, default properties)
     * that are in the "contactinformation" group
     *
     * @return array
     */
    public function getHubspotDefinedContactInformationProperties()
    {
        try {
            return array_filter($this->contactProperties->all()->getData(), function ($property) {
                return $property->groupName === "contactinformation" && $property->hubspotDefined === true;
            });
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * Get all properties that are in the "contactinformation" group
     *
     * @return array
     */
    public function getContactInformationProperties()
    {
        try {
            return array_filter($this->contactProperties->all()->getData(), function ($property) {
                return $property->groupName === "contactinformation";
            });
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function getBlogSubscriptionFrequencies()
    {
        // TODO(10 des 2018) ~ Helge: Implement
    }
}
