<?php

namespace AppBundle\Type;

class InvitationStatusEnumType extends EnumType
{
    protected $name = 'invitationStatusEnum';
    protected $values = ['open', 'cancelled', 'accepted', 'rejected'];
}