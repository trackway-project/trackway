<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Membership;
use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TeamVoter implements VoterInterface
{
    const VIEW = 'VIEW';
    const EDIT = 'EDIT';

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, [self::VIEW, self::EDIT], false);
    }

    public function supportsClass($class)
    {
        $supportedClass = 'AppBundle\Entity\Team';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @param TokenInterface $token
     * @param Team $team
     * @param array $attributes
     *
     * @return int
     */
    public function vote(TokenInterface $token, $team, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass(get_class($team))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // check if the voter is used correct, only allow one attribute
        // this isn't a requirement, it's just one easy way for you to
        // design your voter
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException('Only one attribute is allowed for VIEW or EDIT');
        }

        // set the attribute to check against
        $attribute = $attributes[0];

        // check if the given attribute is covered by this voter
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        /** @var User $user */
        $user = $token->getUser();

        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }

        switch ($attribute) {
            case self::VIEW:
                /** @var Membership $membership */
                foreach ($user->getMemberships() as $membership) {
                    if ($team === $membership->getTeam()) {
                        return VoterInterface::ACCESS_GRANTED;
                    }
                }
                break;

            case self::EDIT:
                /** @var Membership $membership */
                foreach ($user->getMemberships() as $membership) {
                    if ($team === $membership->getTeam() && in_array('ROLE_ADMIN', $membership->getGroup()->getRoles(), false)
                    ) {
                        return VoterInterface::ACCESS_GRANTED;
                    }
                }
                break;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}