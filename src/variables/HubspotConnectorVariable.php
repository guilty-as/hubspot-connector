<?php

namespace Guilty\HubspotConnector\variables;

use Guilty\HubspotConnector\HubspotConnector;

class HubspotConnectorVariable
{
    public function blogs($params = [])
    {
        return HubspotConnector::getInstance()->hubspot->getBlogs($params);
    }

    public function blogPosts($blogId, $params = [])
    {
        return HubspotConnector::getInstance()->hubspot->getBlogPosts($blogId, $params);
    }

    public function blogPostByTopics($blogId, $topicIds, $params = [])
    {
        // In case you pass a single value
        if (!is_array($topicIds)) {
            $topicIds = [$topicIds];
        }

        $blogPosts = HubspotConnector::getInstance()->hubspot->getBlogPosts($blogId, $params);

        return array_filter($blogPosts, function ($blogPost) use ($topicIds) {
            return count(array_intersect($blogPost->topics, $topicIds));
        });
    }

    public function blogPost($blogPostId)
    {
        return HubspotConnector::getInstance()->hubspot->getBlogPost($blogPostId);
    }

    public function blogTopics($params = [])
    {
        return HubspotConnector::getInstance()->hubspot->getBlogTopics($params);
    }

    public function totalBlogPosts($blogId, $params = [])
    {
        return HubspotConnector::getInstance()->hubspot->getTotalBlogPosts($blogId, $params);
    }
}
