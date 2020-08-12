<?php

namespace GrantHolle\PowerSchool\Plugin;

class SamlAttribute
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $attributeName;

    /**
     * @var string
     */
    protected $attributeValue;

    public function __construct(string $name, string $attributeName = '', string $attributeValue = '')
    {
        $this->name = $name;
        $this->attributeName = $attributeName;
        $this->attributeValue = $attributeValue;
    }

    public function toArray()
    {
        $attributes = [
            'name' => $this->name,
        ];

        if ($this->attributeName) {
            $attributes['attribute-name'] = $this->attributeName;
        }

        if ($this->attributeValue) {
            $attributes['attribute-value'] = $this->attributeValue;
        }

        return [
            '_attributes' => $attributes,
        ];
    }
}
