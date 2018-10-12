<?php

namespace Guilty\HubspotConnector\services;

use craft\base\Component;
use Guilty\HubspotConnector\HubspotConnector;
use SevenShores\Hubspot\Http\Client;
use SevenShores\Hubspot\Resources\BlogPosts;
use SevenShores\Hubspot\Resources\Blogs;
use SevenShores\Hubspot\Resources\BlogTopics;
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
}
