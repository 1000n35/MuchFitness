<?php

namespace App\Controller;

use App\Entity\SuiviSeance;
use App\Form\SuiviSeanceType;
use App\Repository\SuiviSeanceRepository;
use App\Repository\SeanceTypeRepository;
use App\Repository\SemaineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/semaine/{semaineId}/suivi')]
class SuiviSeanceController extends AbstractController
{
    #[Route('/new?seanceId={seanceId}', name: 'app_suivi_seance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, $semaineId, $seanceId, SemaineRepository $semaineRepository, SeanceTypeRepository $seanceTypeRepository, EntityManagerInterface $entityManager): Response
    {
        $suiviSeance = new SuiviSeance();

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

        $suiviSeance->setJourSeance($seance->getJour());
        $suiviSeance->setSemaine($semaine);

        $form = $this->createForm(SuiviSeanceType::class, $suiviSeance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($suiviSeance);
            $entityManager->flush();

            return $this->redirectToRoute('app_semaine_show', [
                'id' => $semaineId,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suivi_seance/new.html.twig', [
            'suivi_seance' => $suiviSeance,
            'form' => $form,
            'semaine' => $semaine,
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_seance_show', methods: ['GET'])]
    public function show(SuiviSeance $suiviSeance, $semaineId, SemaineRepository $semaineRepository): Response
    {
        $user = $this->getUser();

        if (!$user) { // si aucun user connecté renvoie à la page de connexion
            return $this->redirectToRoute('app_login');
        }

        $semaine = $semaineRepository->findById($semaineId)[0];

        if ($user != $semaine->getUser() || $semaine != $suiviSeance->getSemaine()) {
            return $this->redirectToRoute('app_semaine_show', [
                'id' => $semaineId,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suivi_seance/show.html.twig', [
            'suivi_seance' => $suiviSeance,
            'semaine' => $semaine,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_suivi_seance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SuiviSeance $suiviSeance, $semaineId, SemaineRepository $semaineRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) { // si aucun user connecté renvoie à la page de connexion
            return $this->redirectToRoute('app_login');
        }

        $semaine = $semaineRepository->findById($semaineId)[0];

        if ($user != $semaine->getUser() || $semaine != $suiviSeance->getSemaine()) {
            return $this->redirectToRoute('app_suivi_seance_show', [
                'id' => $suiviSeance->getId(),
                'semaineId' => $semaineId,
            ], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(SuiviSeanceType::class, $suiviSeance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_seance_show', [
                'id' => $suiviSeance->getId(),
                'semaineId' => $semaineId,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suivi_seance/edit.html.twig', [
            'suivi_seance' => $suiviSeance,
            'form' => $form,
            'semaine' => $semaine,
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_seance_delete', methods: ['POST'])]
    public function delete(Request $request, SuiviSeance $suiviSeance, $semaineId, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suiviSeance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($suiviSeance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_semaine_show', [
            'id' => $semaineId,
        ], Response::HTTP_SEE_OTHER);
    }
}
