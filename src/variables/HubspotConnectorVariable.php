<?php
/**
 * HubSpot Connector plugin for Craft CMS 3.x
 *
 * Expose Hubspot api features in Twig and pull in content from your HubSpot Portal.
 *
 * @link      https://guilty.no
 * @copyright Copyright (c) 2018 Guilty AS
 */

namespace Guilty\HubspotConnector\variables;

use Guilty\HubspotConnector\HubspotConnector;

/**
 * @author    Guilty AS
 * @package   HubspotConnector
 * @since     1.0.0
 */
class HubspotConnectorVariable
{
    public function blogs($params = [])
    {
        return HubspotConnector::getInstance()->hubspot->getBlogs($params)->objects;
    }

    public function blogPosts($blogId, $params = [])
    {
        return HubspotConnector::getInstance()->hubspot->getBlogPosts($blogId, $params)->objects;
    }

    public function blogPost($blogPostId)
    {
        return HubspotConnector::getInstance()->hubspot->getBlogPost($blogPostId);
    }

    public function blogTopics($params = [])
    {
        return HubspotConnector::getInstance()->hubspot->getBlogTopics($params)->objects;
    }

    public function totalBlogPosts($blogId, $params = [])
    {
        return HubspotConnector::getInstance()->hubspot->getBlogPosts($blogId, $params)->total;
    }
}
