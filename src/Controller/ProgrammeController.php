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
    public function index(Request $request, ProgrammeRepository $programmeRepository): Response
    {
        $user = $this->getUser();

        // Récupérer tous les programmes par défaut
        $programmes = $programmeRepository->findAll();

        // Vérifier si l'utilisateur est connecté et a cliqué sur le bouton "Programmes favoris"
        if ($user && $request->query->get('favoris')) {
            // Récupérer les programmes favoris de l'utilisateur connecté
            $programmes = $programmeRepository->findFavoritesByUser($user->getId());
        }

        return $this->render('programme/index.html.twig', [
            'programmes' => $programmes,
        ]);
    }

    #[Route('/new', name: 'app_programme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $programme = new Programme();

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        if (!$user->isCoach()){  //Comprend pas l'erreur mais ça marche :/
            return $this->redirectToRoute('app_programme_index'); // Redirection vers la page de connexion
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
                'jour' => 1
        ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('programme/new.html.twig', [
            'programme' => $programme,
            'form' => $form,
        ]);
    }



    #[Route('/show/{id}/follow/{action}_{from}', name: 'app_programme_suivreProgramme', methods: ['GET','POST'])]
    public function suivreProg(Programme $programme, $action, $from, EntityManagerInterface $entityManager): Response
    {
        $user=$this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        switch ($action) {
            case 'join':
                $user->setProgSuivi($programme);
                break;

            case 'drop':
                $user->setProgSuivi(null);
                break;  
                   
        }

        $entityManager->flush();

        switch ($from) {
            case 'show':
                return $this->redirectToRoute('app_programme_show', [
                    'id' => $programme->getId()
                ]); 

            case 'index':
                return $this->redirectToRoute('app_programme_index');
                   
        }
               
    }



    #[Route('/show/{id}/favorites/{action}_{from}', name: 'app_programme_enFavoris', methods: ['GET','POST'])]
    public function enFavori(Programme $programme, $action, $from, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        switch ($action) {
            case 'add':
                $programme->addEstFavori($user);
                break;

            case 'remove':
                $programme->removeEstFavori($user);
                break;  
                   
        }

        $entityManager->persist($programme);
        $entityManager->flush();

        switch ($from) {
            case 'show':
                return $this->redirectToRoute('app_programme_show', [
                    'id' => $programme->getId()
                ]);

            case 'index':
                return $this->redirectToRoute('app_programme_index');
                   
        }
        
    }
    
    

    #[Route('/{id}', name: 'app_programme_show', methods: ['GET'])]
    public function show(Programme $programme): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('programme/show.html.twig', [
            'programme' => $programme,
        ]);        
    }

    #[Route('/{id}/edit', name: 'app_programme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Programme $programme, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (!$user->isCoach()) {
            return $this->redirectToRoute('app_programme_show', [
                'id' => $programme->getId()
            ]);
        }

        if ($user != $programme->getCreateur()) {
            return $this->redirectToRoute('app_programme_show', [
                'id' => $programme->getId()
            ]);
        }

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
