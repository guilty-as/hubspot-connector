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


    public function init()
    {
        parent::init();

        $settings = HubspotConnector::$plugin->getSettings();

        $this->apiKey = $settings->apiKey;

        if ($this->hasApiKey()) {
            $this->setupClients();
        }
    }

    protected function setupClients()
    {
        $client = new Client(['key' => $this->apiKey]);

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

    public function getBlogs($params = [])
    {
        return $this->blogs->all($params)->getData();
    }

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

    public function getBlogTopics($params = [])
    {
        return $this->blogTopics->all($params)->getData();
    }

    public function subscribeTo($email, $blogId)
    {

        $this->contacts->updateByEmail($email, [

        ]);
    }

    public function getContactPropetyGroupDetails()
    {
        return array_filter($this->contactProperties->all()->getData(), function ($property) {
            return $property->groupName === "contactinformation";
        });
    }


    public function getHubspotDefinedContactInformationProperties()
    {
        return array_filter($this->contactProperties->all()->getData(), function ($property) {
            return $property->groupName === "contactinformation" && $property->hubspotDefined === true;
        });
    }


    public function getContactInformationProperties()
    {
        return array_filter($this->contactProperties->all()->getData(), function ($property) {
            return $property->groupName === "contactinformation";
        });
    }

    public function getBlogSubscriptionFrequencies()
    {

    }
}
