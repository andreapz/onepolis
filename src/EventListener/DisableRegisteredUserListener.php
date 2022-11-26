<?php

namespace App\EventListener;

use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;

class DisableRegisteredUserListener
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param \FOS\UserBundle\Event\GetResponseUserEvent $event
     */
    public function disableUser(GetResponseUserEvent $event)
    {
        $user = $event->getUser();
        /** @var \AppBundle\Entity\User $user */
        $user->setEnabled(false);
    }

    /**
     * @param \FOS\UserBundle\Event\FilterUserResponseEvent $event
     */
    public function eventNewUser(FilterUserResponseEvent $event)
    {
        $user = $event->getUser();

        //$this->mailer->getTransport()->setStreamOptions(array('ssl' => array('allow_self_signed' => true, 'verify_peer' => false)));

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('mister.tzu@gmail.com')
            ->setTo('mister.tzu@gmail.com')
            /*->setBody(
                $this->twig->render(
                    // templates/email/registration.html.twig
                    'email/registration.html.twig',
                    ['name' => $user->getUsername(), 'email' => $user->getEmail()]
                ),
                'text/html'
            )*/
            /*
            * If you also want to include a plaintext version of the message*/
            ->addPart(
                "Nuova registrazione

                " . $user->getUsername() ." si è appena registrato con la mail " . $user->getUsername() . ".
                
                Ciao
                ",
                'text/plain'
            )
        ;
        
        $this->mailer->send($message);
    }
    
}
