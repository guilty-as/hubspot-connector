<?php


namespace Guilty\HubspotConnector\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\FieldInterface;
use Guilty\HubspotConnector\HubspotConnector;

class HubspotBlogField extends Field implements FieldInterface
{
    public static function displayName(): string
    {
        return Craft::t('hubspot-connector', 'Hubspot Blog');
    }

    public function getInputHtml($value, ElementInterface $element = null): string
    {
        return Craft::$app->getView()->renderTemplate('_includes/forms/select', [
            'name' => $this->handle,
            'value' => $value,
            'options' => $this->getHubspotBlogs(),
        ]);
    }

    protected function optionsSettingLabel(): string
    {
        return Craft::t('app', 'Dropdown Options');
    }

    private function getHubspotBlogs()
    {
        $blogs = HubspotConnector::getInstance()->hubspot->getBlogs()->objects;

        if (!$blogs) {
            return [
                "label" => "- No blogs available",
                "value" => null,
            ];
        }

        return array_map(function ($blog) {
            return [
                "label" => $blog->name . " ({$blog->id})",
                "value" => $blog->id,
            ];
        }, $blogs);
    }
}
