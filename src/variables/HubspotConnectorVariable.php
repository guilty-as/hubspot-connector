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

use Craft;
use Guilty\HubspotConnector\HubspotConnector;
use Guilty\HubspotConnector\services\Hubspot;

/**
 * @author    Guilty AS
 * @package   HubspotConnector
 * @since     1.0.0
 */
class HubspotConnectorVariable
{
    // Public Methods
    // =========================================================================

    /**
     * @param null $optional
     * @return string
     */
    public function blogs()
    {
        return HubspotConnector::getInstance()->hubspot->getBlogs()->objects;
    }

    public function blogPosts($blogId)
    {
        return HubspotConnector::getInstance()->hubspot->getBlogPosts($blogId)->objects;
    }

    public function blogPost($blogPostId)
    {
        return HubspotConnector::getInstance()->hubspot->getBlogPost($blogPostId);
    }
}
