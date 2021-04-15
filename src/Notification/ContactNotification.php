<?php

namespace App\Notification;

use App\Entity\Contact;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

class ContactNotification
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $renderer;
    public function __construct(Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
    }
    public function notify(Contact $contact)
    {
        $email = (new Swift_Message('Agence : ' . $contact->getProperty()->getTitle()))
            ->setFrom('noreply@mon-agence.local')
            ->setTo('contact@mon-agence.local')
            ->setReplyTo($contact->getEmail())
            ->setBody($this->renderer->render('emails/contact.html.twig', [
                'contact' => $contact
            ]), 'text/html');
        $this->mailer->send($email);
    }
}
