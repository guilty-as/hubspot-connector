<?php
/**
 * HubSpot Connector plugin for Craft CMS 3.x
 *
 * Expose Hubspot api features in Twig and pull in content from your HubSpot Portal.
 *
 * @link      https://guilty.no
 * @copyright Copyright (c) 2018 Guilty AS
 */

namespace Guilty\HubspotConnector\services;

use craft\helpers\App;
use Guilty\HubspotConnector\HubspotConnector;

use Craft;
use craft\base\Component;
use SevenShores\Hubspot\Factory;
use SevenShores\Hubspot\Http\Client;
use SevenShores\Hubspot\Resources\BlogPosts;
use SevenShores\Hubspot\Resources\Blogs;
use SevenShores\Hubspot\Resources\BlogTopics;
use SevenShores\Hubspot\Resources\Contacts;

/**
 * @author    Guilty AS
 * @package   HubspotConnector
 * @since     1.0.0
 */
class Hubspot extends Component
{

    // Public Methods
    // =========================================================================
    /** @var Contacts */
    protected $contacts;

    /** @var Blogs */
    protected $blogs;

    /** @var BlogPosts */
    protected $blogPosts;

    /** @var BlogTopics */
    protected $blogTopics;

    public function init()
    {
        parent::init();

        $apiKey = HubspotConnector::$plugin->getSettings()->apiKey;

        $client = new Client(['key' => $apiKey]);
        $this->contacts = new Contacts($client);
        $this->blogs = new Blogs($client);
        $this->blogPosts = new BlogPosts($client);
        $this->blogTopics = new BlogTopics($client);

    }

    public function getBlogs($params = [])
    {
        return $this->blogs->all($params)->getData();
    }

    public function getBlogPosts($blogId, $params = [])
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
