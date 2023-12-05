<?php

namespace App\Controller;

use App\Entity\SuiviSeance;
use App\Form\SuiviSeanceType;
use App\Repository\SuiviSeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/suivi/seance')]
class SuiviSeanceController extends AbstractController
{
    #[Route('/', name: 'app_suivi_seance_index', methods: ['GET'])]
    public function index(SuiviSeanceRepository $suiviSeanceRepository): Response
    {
        return $this->render('suivi_seance/index.html.twig', [
            'suivi_seances' => $suiviSeanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_suivi_seance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $suiviSeance = new SuiviSeance();
        $form = $this->createForm(SuiviSeanceType::class, $suiviSeance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($suiviSeance);
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_seance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suivi_seance/new.html.twig', [
            'suivi_seance' => $suiviSeance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_seance_show', methods: ['GET'])]
    public function show(SuiviSeance $suiviSeance): Response
    {
        return $this->render('suivi_seance/show.html.twig', [
            'suivi_seance' => $suiviSeance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_suivi_seance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SuiviSeance $suiviSeance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviSeanceType::class, $suiviSeance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_seance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suivi_seance/edit.html.twig', [
            'suivi_seance' => $suiviSeance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_seance_delete', methods: ['POST'])]
    public function delete(Request $request, SuiviSeance $suiviSeance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suiviSeance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($suiviSeance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_suivi_seance_index', [], Response::HTTP_SEE_OTHER);
    }
}
