<?php

namespace GrantHolle\PowerSchool\Plugin;

class Permission
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $value;

    public function __construct(string $name, string $description, string $value)
    {
        $this->name = $name;
        $this->description = $description;
        $this->value = $value;
    }

    public function toArray(): array
    {
        return [
            '_attributes' => [
                'name' => $this->name,
                'description' => $this->description,
                'value' => $this->value,
            ],
        ];
    }
}
