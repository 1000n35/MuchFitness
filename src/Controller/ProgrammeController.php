<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Form\ProgrammeType;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/programme')]
class ProgrammeController extends AbstractController
{
    #[Route('/', name: 'app_programme_index', methods: ['GET'])]
    public function index(ProgrammeRepository $programmeRepository): Response
    {
        $user = $this->getUser();
        
        if ($user) {
            return $this->render('programme/index.html.twig', [
                'programmes' => $programmeRepository->findAll(),
            ]);
        } else {
            // Gérer le cas où aucun utilisateur n'est connecté
            // Redirection vers une page d'authentification, par exemple
            return $this->redirectToRoute('app_login'); // Redirection vers la page de connexion
        }
    }

    #[Route('/new', name: 'app_programme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $programme = new Programme();

        $user = $this->getUser();


        if ($user) {
            
            if (!$user->isCoach()){  //Comprend pas l'erreur mais ça marche :/
                return $this->redirectToRoute('home'); // Redirection vers la page de connexion
            }


            // Assigner l'ID de l'utilisateur connecté à createurId
            $programme->setCreateur($user);

            // Créer le formulaire
            $form = $this->createForm(ProgrammeType::class, $programme);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($programme);
                $entityManager->flush();

                return $this->redirectToRoute('app_seance_type_new', [
                    'programmeid' => $programme->getId(),
                    'jour' => 0
            ], Response::HTTP_SEE_OTHER);
            }

            return $this->render('programme/new.html.twig', [
                'programme' => $programme,
                'form' => $form,
            ]);
        } else {
            // Gérer le cas où aucun utilisateur n'est connecté
            // Redirection vers une page d'authentification, par exemple
            return $this->redirectToRoute('app_login'); // Redirection vers la page de connexion
        }
    }

    #[Route('/show/{id}', name: 'app_programme_suivreProgramme', methods: ['GET','POST'])]
    public function suivreProg($id, ProgrammeRepository $programmeRepository,EntityManagerInterface $entityManager): Response
    {
        $programme=$programmeRepository->findById($id);
        $user=$this->getUser();
        $user->setProgSuivi($programme[0]);
        $entityManager->flush();
        return $this->redirectToRoute('app_programme_index');
        
    }
    

    #[Route('/{id}', name: 'app_programme_show', methods: ['GET'])]
    public function show(Programme $programme): Response
    {
        return $this->render('programme/show.html.twig', [
            'programme' => $programme,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_programme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Programme $programme, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_programme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('programme/edit.html.twig', [
            'programme' => $programme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_programme_delete', methods: ['POST'])]
    public function delete(Request $request, Programme $programme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$programme->getId(), $request->request->get('_token'))) {
            $entityManager->remove($programme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_programme_index', [], Response::HTTP_SEE_OTHER);
    }
}
