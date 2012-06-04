<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cit\UserBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Form\Model\CheckPassword;
use FOS\UserBundle\Form\Handler\ProfileFormHandler as BaseHandler;

class ProfileFormHandler extends BaseHandler
{
    public function process(UserInterface $user)
    {
        $this->form->setData(new CheckPassword($user));
        
        if ('POST' === $this->request->getMethod()) {
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) 
            {
                if (30 < strlen($user->getUsername()))
                {
                    return 1;
                }

                if (20 < strlen($user->getMobilephone()))
                {
                    return 2;
                }

                if (100 < strlen($user->getNom()))
                {
                    return 3;
                }

                if (100 < strlen($user->getPrenom()))
                {
                    return 4;
                }

                $this->onSuccess($user);
                return 0;
            }

            // Reloads the user to reset its username. This is needed when the
            // username or password have been changed to avoid issues with the
            // security layer.
            $this->userManager->reloadUser($user);
        }

        return 10;
    }
}