<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Security;


class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'app_user')]
    public function index(User $user): Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if($this->getUser() !== $user) {
            return $this->redirectToRoute('home');
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user', name: 'aff_user')]
    public function aff(User $user): Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if($this->getUser() !== $user) {
            return $this->redirectToRoute('home');
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/edit/{id}', name: 'edit_user', methods: ['GET', 'POST'])]
    public function edit(User $user, Request $request, PersistenceManagerRegistry $doctrine): Response 
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if($this->getUser() !== $user) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            // Charger l'objet File à partir du chemin stocké
            $imagePath = $user->getImageFile();
            $imageFile = new File($imagePath);



            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Modification(s) effectuée(s)!");

            return $this->redirectToRoute('app_user', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


}
