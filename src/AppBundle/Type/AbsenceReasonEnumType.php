<?php

namespace AppBundle\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class AbsenceReasonEnumType extends EnumType
{
    protected $name = 'absenceReasonEnum';
    protected $values = array('illness', 'vacation', 'holiday', 'other');
}