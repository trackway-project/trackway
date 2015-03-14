<?php

namespace AppBundle\Type;

class LocaleEnumType extends EnumType
{
    protected $name = 'localeEnum';
    protected $values = ['de', 'en'];
}