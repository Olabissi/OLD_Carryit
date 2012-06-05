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
use FOS\UserBundle\Form\Model\ChangePassword;
use FOS\UserBundle\Form\Handler\ChangePasswordFormHandler as BaseHandler;

class ChangePasswordFormHandler extends BaseHandler
{
    public function process(UserInterface $user)
    {
        $this->form->setData(new ChangePassword($user));

        if ('POST' === $this->request->getMethod()) {
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) 
            {
                if (5 > strlen($this->getNewPassword()))
                {
                    return 1;
                }

                $this->onSuccess($user);
                return 0;
            }
        }

        return 5;
    }

    protected function onSuccess(UserInterface $user)
    {
        $user->setPlainPassword($this->getNewPassword());
        $this->userManager->updateUser($user);
    }
}
