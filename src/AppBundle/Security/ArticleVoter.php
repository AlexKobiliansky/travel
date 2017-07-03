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
    // these strings are just invented: you can use anything
   // const VIEW = 'view';
    const EDIT = 'edit';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::EDIT,))) {
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

       // if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
         //   return true;
       // }

        $article = $subject;

        switch ($attribute) {
         //   case self::VIEW:
       //         return $this->canView($article, $user);
            case self::EDIT:
                if (($this->decisionManager->decide($token, ['ROLE_ADMIN'])) ||
                    $article->getUsers()->contains($user)) {
                    return true;
                }
               // return $this->canEdit($article, $user);
        }

        return false;

       // throw new \LogicException('This code should not be reached!');
    }
/*
    private function canView(Article $article, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($article, $user)) {
            return true;
        }
        return false;
    }

    private function canEdit(Article $article, User $user)
    {
        dump($user); die();
        if ($article->getUsers()->contains($user))
        {
          dump($user); die('=)');
            return true;
        }
        dump($user); die('fff');
        return false;
    }*/
}
