<?php

namespace App\Controller;

use App\Entity\ObjectifSeance;
use App\Form\ObjectifSeanceType;
use App\Repository\ObjectifSeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/objectif/seance')]
class ObjectifSeanceController extends AbstractController
{
    #[Route('/', name: 'app_objectif_seance_index', methods: ['GET'])]
    public function index(ObjectifSeanceRepository $objectifSeanceRepository): Response
    {
        return $this->render('objectif_seance/index.html.twig', [
            'objectif_seances' => $objectifSeanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_objectif_seance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objectifSeance = new ObjectifSeance();
        $form = $this->createForm(ObjectifSeanceType::class, $objectifSeance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($objectifSeance);
            $entityManager->flush();

            return $this->redirectToRoute('app_objectif_seance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('objectif_seance/new.html.twig', [
            'objectif_seance' => $objectifSeance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_objectif_seance_show', methods: ['GET'])]
    public function show(ObjectifSeance $objectifSeance): Response
    {
        return $this->render('objectif_seance/show.html.twig', [
            'objectif_seance' => $objectifSeance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_objectif_seance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ObjectifSeance $objectifSeance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ObjectifSeanceType::class, $objectifSeance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_objectif_seance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('objectif_seance/edit.html.twig', [
            'objectif_seance' => $objectifSeance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_objectif_seance_delete', methods: ['POST'])]
    public function delete(Request $request, ObjectifSeance $objectifSeance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$objectifSeance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($objectifSeance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_objectif_seance_index', [], Response::HTTP_SEE_OTHER);
    }
}
