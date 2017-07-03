<?php
namespace AppBundle\Security;

use AppBundle\Entity\Article;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    const EDIT   = 'edit';
    const CREATE = 'create';
    const DELETE = 'delete';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::CREATE, self::EDIT, self::DELETE))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Article) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return true;
        }

        $article = $subject;

        switch ($attribute) {
            case self::CREATE:
                if ($this->decisionManager->decide($token, ['ROLE_AUTHOR'])) {
                    return true;
                }
                    break;
            case self::EDIT || self::DELETE:
                if ($article->getUsers()->contains($user)) {
                    return true;
                }
                    break;
        }

        return false;
    }
}
