<?php

namespace AppBundle\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

abstract class EnumType extends Type
{
    protected $name;
    protected $values = [];

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'ENUM(' . implode(
            ', ',
            array_map(
                function ($val) {
                    return '\'' . $val . '\'';
                },
                $this->values)) . ') COMMENT \'(DC2Type:' . $this->name . ')\'';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, $this->values, true)) {
            throw new \InvalidArgumentException('Invalid \'' . $this->name . '\' value.');
        }

        return $value;
    }

    public function getName()
    {
        return $this->name;
    }
}