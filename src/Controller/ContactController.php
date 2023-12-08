<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Programme;
use App\Form\ContactType;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $contact = new Contact();

        if($this->getUser()) {
            $contact->setNom($this->getUser()->getNom());
            $contact->setPrenom($this->getUser()->getPrenom());
            $contact->setEmail("admin@gmail.com");
        }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $em = $doctrine->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash("success", "Mail envoyé!");

            return $this->redirect("contact");
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/contact/{id}', name: 'contact_coach')]
    public function contactCoach(Programme $programme, Request $request, PersistenceManagerRegistry $doctrine, Security $security): Response
    {
        $contact = new Contact();
        $user = $security->getUser();

        if($this->getUser()) {
            $contact->setNom($user->getNom());
            $contact->setPrenom($user->getPrenom());
            $contact->setEmail($programme->getCreateur()->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $em = $doctrine->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash("success", "Mail envoyé!");

            return $this->redirect("contact");
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
