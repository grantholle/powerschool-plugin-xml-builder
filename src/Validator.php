<?php

namespace GrantHolle\PowerSchool\Plugin;

use GrantHolle\PowerSchool\Plugin\Exceptions\XmlValidationError;
use XmlValidator\XmlValidator;

class Validator extends XmlValidator
{
    public function __construct(string $xml)
    {
        $this->validate($xml, __DIR__ . '/plugin.xsd');
    }

    public function invalid()
    {
        return !empty($this->errors);
    }

    public function throwError()
    {
        $error = $this->errors[0];

        throw new XmlValidationError("Invalid XML: {$error->message} on line {$error->line}:{$error->column}");
    }
}