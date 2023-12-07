<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
