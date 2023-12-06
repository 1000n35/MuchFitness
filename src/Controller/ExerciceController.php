<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Form\ExerciceType;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/exercice')]
class ExerciceController extends AbstractController
{
    #[Route('/', name: 'app_exercice_index', methods: ['GET'])]
    public function index(ExerciceRepository $exerciceRepository): Response
    {
        return $this->render('exercice/index.html.twig', [
            'exercices' => $exerciceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_exercice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        $exercice = new Exercice();

        $user = $this->getUser();


        if ($user) {
            
            if (!$user->isIsCoach()){  //Comprend pas l'erreur mais ça marche :/
                return $this->redirectToRoute('home'); // Redirection vers la page de connexion
            }


            // Assigner l'ID de l'utilisateur connecté à createurId
            $exercice->setCreateur($user);

            // Créer le formulaire
            $form = $this->createForm(ExerciceType::class, $exercice);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($exercice);
                $entityManager->flush();

                return $this->redirectToRoute('app_exercice_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('exercice/new.html.twig', [
                'exercice' => $exercice,
                'form' => $form,
            ]);
        } else {
            // Gérer le cas où aucun utilisateur n'est connecté
            // Redirection vers une page d'authentification, par exemple
            return $this->redirectToRoute('app_login'); // Redirection vers la page de connexion
        }
    }

    #[Route('/{id}', name: 'app_exercice_show', methods: ['GET'])]
    public function show(Exercice $exercice): Response
    {
        return $this->render('exercice/show.html.twig', [
            'exercice' => $exercice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_exercice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Exercice $exercice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_exercice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('exercice/edit.html.twig', [
            'exercice' => $exercice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_exercice_delete', methods: ['POST'])]
    public function delete(Request $request, Exercice $exercice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exercice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($exercice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_exercice_index', [], Response::HTTP_SEE_OTHER);
    }
}
