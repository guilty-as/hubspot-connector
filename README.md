# Note: Hotfix for HubSpot Outage 28.03.2019 - 29.03.2019

Here is a hotfix to make sure your sites don't break in production.


Add this repo to your composer.json file,
```
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/guilty-as/hubspot-connector"
        }
    ],
```

Then replace the version you are pulling in with this one:

```
"guilty/hubspot-connector": "dev-hotfix-hubspot-outage",
```

This will add a "timeout" setting in the hubspot connector settings page, set this to 1, if the request times out, all methods return an empty array, which you can take into account in your templates.



# HubSpot Connector plugin for Craft CMS 3.x

Expose Hubspot API features in Twig and pull in content from your HubSpot Portal.

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require guilty/hubspot-connector

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for HubSpot Connector.


## Configuring HubSpot Connector

1. Go the the admin panel
2. Go to the settings page
3. Click on "HubSpot Connector" under the "Plugins" section
4. Enter your [Hubspot API key](https://knowledge.hubspot.com/articles/kcs_article/integrations/how-do-i-get-my-hubspot-api-key) and click Save.
5. Done 

## Using HubSpot Connector

Here is a basic example, for more comprehensive examples and documentation check the [wiki](https://github.com/guilty-as/hubspot-connector/wiki/Introduction)

```twig
 {% for blog in craft.hubspot.blogs %}
    <a href="{{ blog.root_url }}">
        <h4>{{ blog.id }} - {{ blog.name }}</h4>
    </a>

    {% for post in  craft.hubspot.blogPosts(blog.id) %}
        <hr>
        <article>
            <h5>
                <a href="{{ post.published_url }}">
                    {{ post.html_title }}
                </a>
            </h5>

            {{ post.post_summary | striptags }}
        </article>
    {% endfor %}
{% endfor %}
```

Brought to you by [Guilty AS](https://guilty.no)

*The HubSpot logo and Trademark is the property of Hubspot Inc* 
