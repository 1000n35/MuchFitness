<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Form\ExerciceType;
use App\Repository\ExerciceRepository;
use App\Repository\SeanceTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $exercice = new Exercice();

        $user = $this->getUser();


        if ($user) {
            
            if (!$user->isCoach()){  //Comprend pas l'erreur mais ça marche :/
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

    #[Route('/{programmeid}/{jour}/{seancetypeid}', name: 'app_exercice_creer_exo', methods: ['GET','POST'])]
    public function ajoutExo(Request $request, $programmeid, $jour, $seancetypeid,SeanceTypeRepository $seanceTypeRepository,ExerciceRepository $exerciceRepository,EntityManagerInterface $entityManager): Response
    {
        $exercice = new Exercice();

        $user = $this->getUser();

        $seanceType = $seanceTypeRepository->findById($seancetypeid);
        $exercicePresents = $seanceType[0]->getExercices();

        if ($user) {
            
            if (!$user->isCoach()){  //Comprend pas l'erreur mais ça marche :/
                return $this->redirectToRoute('home'); // Redirection vers la page de connexion
            }


            // Assigner l'ID de l'utilisateur connecté à createurId
            $exercice->setCreateur($user);

            // Créer le formulaire
            $form = $this->createForm(ExerciceType::class, $exercice);
            $form->handleRequest($request);

            $exercice->addContient($seanceType[0]);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($exercice);
                $entityManager->flush();
                return $this->render('exercice/choix.html.twig', [
                    'exercices' => $exerciceRepository->findAll(),
                    'programmeid' => $programmeid,
                    'jour' => $jour,
                    'seancetype' => $seanceType[0],
                    'seancetypeid' => $seancetypeid,
                    'exercicePresents' => $exercicePresents
                ]);
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

    #[Route('/{programmeid}/{jour}/{seancetypeid}', name: 'app_exercice_choix', methods: ['GET','POST'])]
    public function choix(ExerciceRepository $exerciceRepository, $programmeid, $jour, $seancetypeid,SeanceTypeRepository $seanceTypeRepository): Response
    {
        $seanceType = $seanceTypeRepository->findById($seancetypeid);
        $exercicePresents = $seanceType[0]->getExercices();


        return $this->render('exercice/choix.html.twig', [
            'exercices' => $exerciceRepository->findAll(),
            'programmeid' => $programmeid,
            'jour' => $jour,
            'seancetypeid' => $seancetypeid,
            'seancetype' => $seanceType[0],
            'exercicePresents' => $exercicePresents
        ]);
    }

    #[Route('/{programmeid}/{jour}/{seancetypeid}/{exerciceid}', name: 'app_exercice_ajout', methods: ['GET','POST'])]
    public function ajout($programmeid, $jour, $seancetypeid,$exerciceid,ExerciceRepository $exerciceRepository, SeanceTypeRepository $seanceTypeRepository,EntityManagerInterface $entityManager,): Response
    {
        $exercice = $exerciceRepository->findById((int) $exerciceid);
        $seanceType = $seanceTypeRepository->findById((int) $seancetypeid);
        $exercice[0]->addContient($seanceType[0]);
        $exercicePresents = $seanceType[0]->getExercices();

        $entityManager->persist($exercice[0]);
        $entityManager->flush();

        return $this->render('exercice/choix.html.twig', [
            'exercices' => $exerciceRepository->findAll(),
            'programmeid' => $programmeid,
            'jour' => $jour,
            'seancetype' => $seanceType[0],
            'seancetypeid' => $seancetypeid,
            'exerciceid' => $exerciceid,
            'exercicePresents' => $exercicePresents
        ]);
        
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
