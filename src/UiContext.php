<?php


namespace GrantHolle\PowerSchool\Plugin;

use GrantHolle\PowerSchool\Plugin\Exceptions\InvalidContextIdException;

class UiContext
{
    /**
     * @var string
     */
    protected $id;

    const ADMIN_LEFT_NAV = 'admin.left_nav';
    const ADMIN_HEADER = 'admin.header';
    const TEACHER_HEADER = 'teacher.header';
    const GUARDIAN_HEADER = 'guardian.header';
    const STUDENT_HEADER = 'student.header';

    public function __construct(string $id)
    {
        $contexts = [
            'admin.left_nav',
            'admin.header',
            'teacher.header',
            'guardian.header',
            'student.header',
        ];

        if (!in_array($id, $contexts)) {
            throw new InvalidContextIdException("The given context ID '{$id}' is not a valid PowerSchool UI Context ID");
        }

        $this->id = $id;
    }

    public function toArray()
    {
        return [
            '_attributes' => [
                'id' => $this->id,
            ]
        ];
    }
}
