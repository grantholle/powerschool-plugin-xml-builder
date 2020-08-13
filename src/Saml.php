<?php


namespace GrantHolle\PowerSchool\Plugin;

use GrantHolle\PowerSchool\Plugin\Exceptions\InvalidUserTypeException;

class Saml
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $entityId;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $metadataUrl;

    /**
     * @var array<Link>
     */
    protected $links = [];

    /**
     * @var array<array<SamlAttribute>>
     */
    protected $attributes = [];

    /**
     * @var array<Permission>
     */
    protected $permissions = [];

    public function __construct(string $name, string $entityId, string $baseUrl, string $metadataUrl = '')
    {
        $this->name = $name;
        $this->entityId = $entityId;
        $this->baseUrl = $baseUrl;
        $this->metadataUrl = $metadataUrl;
    }

    /**
     * Adds an attribute for SAML
     *
     * @param string $type
     * @param string $name
     * @param string $attributeName
     * @param string $attributeValue
     * @return $this
     * @throws InvalidUserTypeException
     */
    public function addAttribute(string $type, string $name, string $attributeName = '', string $attributeValue = ''): Saml
    {
        $cleanedType = strtolower($type);
        $types = [
            'admin',
            'teacher',
            'student',
            'guardian',
        ];

        if (!in_array($cleanedType, $types)) {
            throw new InvalidUserTypeException("User type {$cleanedType} is invalid. Must have value of admin, teacher, student, or guardian.");
        }

        if (!isset($this->attributes[$cleanedType])) {
            $this->attributes[$cleanedType] = [];
        }

        $this->attributes[$cleanedType][] = new SamlAttribute($name, $attributeName, $attributeValue);

        return $this;
    }

    /**
     * Adds a link for SAML SSO
     *
     * @param string $displayText
     * @param string $path
     * @param string $title
     * @param array|string $uiContexts
     * @return $this
     */
    public function addLink(string $displayText, string $path, string $title = '', $uiContexts = []): Saml
    {
        $this->links[] = new Link($displayText, $path, $title, $uiContexts);

        return $this;
    }

    public function addPermission(string $name, string $description, string $value): Saml
    {
        $this->permissions[] = new Permission($name, $description, $value);

        return $this;
    }

    public function toArray(): array
    {
        $array = [
            '_attributes' => [
                'name' => $this->name,
                'entity-id' => $this->entityId,
                'base-url' => $this->baseUrl,
                'metadata-url' => $this->metadataUrl,
            ],
            'links' => [
                'link' => array_map(function (Link $link) {
                    return $link->toArray();
                }, $this->links),
            ],
        ];

        if (!empty($this->attributes)) {
            $array['attributes'] = [
                'user' => [],
            ];

            foreach ($this->attributes as $type => $attributes) {
                $array['attributes']['user'][] = [
                    '_attributes' => [
                        'type' => $type,
                    ],
                    'attribute' => array_map(function (SamlAttribute $attribute) {
                        return $attribute->toArray();
                    }, $attributes),
                ];
            }
        }

        if (!empty($this->permissions)) {
            $array['permissions'] = [
                'permission' => array_map(function (Permission $permission) {
                    return $permission->toArray();
                }, $this->permissions),
            ];
        }

        return $array;
    }
}
