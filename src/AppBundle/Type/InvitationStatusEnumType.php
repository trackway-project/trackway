<?php

namespace AppBundle\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class InvitationStatusEnumType extends EnumType
{
    protected $name = 'invitationStatusEnum';
    protected $values = array('open', 'cancelled', 'accepted', 'rejected');
}