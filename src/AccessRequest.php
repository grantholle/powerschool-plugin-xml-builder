<?php

namespace GrantHolle\PowerSchool\Plugin;

class AccessRequest
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $field;

    /**
     * @var string
     */
    protected $access;

    public function __construct(string $table, string $field, bool $fullAccess = false)
    {
        $this->table = $table;
        $this->field = $field;
        $this->access = $fullAccess
            ? 'FullAccess'
            : 'ViewOnly';
    }

    public function toArray(): array
    {
        return [
            '_attributes' => [
                'table' => $this->table,
                'field' => $this->field,
                'access' => $this->access,
            ],
        ];
    }
}
