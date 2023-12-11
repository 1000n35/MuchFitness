<?php

namespace App\Controller;

use App\Entity\ObjectifSeance;
use App\Form\ObjectifSeanceType;
use App\Repository\ObjectifSeanceRepository;
use App\Repository\SeanceTypeRepository;
use App\Repository\SemaineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/semaine/{semaineId}/objectif')]
class ObjectifSeanceController extends AbstractController
{
    #[Route('/new?seanceId={seanceId}', name: 'app_objectif_seance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, $semaineId, $seanceId, SemaineRepository $semaineRepository, SeanceTypeRepository $seanceTypeRepository, EntityManagerInterface $entityManager): Response
    {
        $objectifSeance = new ObjectifSeance();

        $user = $this->getUser();

        if (!$user) { // si aucun user connecté renvoie à la page de connexion
            return $this->redirectToRoute('app_login');
        }

        $seance = $seanceTypeRepository->findById($seanceId)[0];
        $semaine = $semaineRepository->findById($semaineId)[0];

        if ($user != $semaine->getUser()) {
            return $this->redirectToRoute('app_semaine_show', [
                'id' => $semaineId,
            ], Response::HTTP_SEE_OTHER);
        }

        $objectifSeance->setJourObjectif($seance->getJour());
        $objectifSeance->setSemaine($semaine);

        $form = $this->createForm(ObjectifSeanceType::class, $objectifSeance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($objectifSeance);
            $entityManager->flush();

            return $this->redirectToRoute('app_semaine_show', [
                'id' => $semaineId,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('objectif_seance/new.html.twig', [
            'objectif_seance' => $objectifSeance,
            'form' => $form,
            'semaine' => $semaine,
        ]);
    }

    #[Route('/{id}', name: 'app_objectif_seance_show', methods: ['GET'])]
    public function show(ObjectifSeance $objectifSeance, $semaineId, SemaineRepository $semaineRepository): Response
    {
        $user = $this->getUser();

        if (!$user) { // si aucun user connecté renvoie à la page de connexion
            return $this->redirectToRoute('app_login');
        }

        $semaine = $semaineRepository->findById($semaineId)[0];

        if ($user != $semaine->getUser() || $semaine != $objectifSeance->getSemaine()) {
            return $this->redirectToRoute('app_semaine_show', [
                'id' => $semaineId,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('objectif_seance/show.html.twig', [
            'objectif_seance' => $objectifSeance,
            'semaine' => $semaine,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_objectif_seance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ObjectifSeance $objectifSeance, $semaineId, SemaineRepository $semaineRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) { // si aucun user connecté renvoie à la page de connexion
            return $this->redirectToRoute('app_login');
        }

        $semaine = $semaineRepository->findById($semaineId)[0];

        if ($user != $semaine->getUser() || $semaine != $objectifSeance->getSemaine()) {
            return $this->redirectToRoute('app_objectif_seance_show', [
                'id' => $objectifSeance->getId(),
                'semaineId' => $semaineId,
            ], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(ObjectifSeanceType::class, $objectifSeance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_objectif_seance_show', [
                'id' => $objectifSeance->getId(),
                'semaineId' => $semaineId,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('objectif_seance/edit.html.twig', [
            'objectif_seance' => $objectifSeance,
            'form' => $form,
            'semaine' => $semaine,
        ]);
    }

    #[Route('/{id}', name: 'app_objectif_seance_delete', methods: ['POST'])]
    public function delete(Request $request, ObjectifSeance $objectifSeance, $semaineId, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$objectifSeance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($objectifSeance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_semaine_show', [
            'id' => $semaineId,
        ], Response::HTTP_SEE_OTHER);
    }
}
