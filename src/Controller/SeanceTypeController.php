<?php

namespace App\Controller;

use App\Entity\SeanceType;
use App\Entity\Programme;
use App\Form\SeanceTypeType;
use App\Repository\ProgrammeRepository;
use App\Repository\SeanceTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/seance/type')]
class SeanceTypeController extends AbstractController
{
    #[Route('/', name: 'app_seance_type_index', methods: ['GET'])]
    public function index(SeanceTypeRepository $seanceTypeRepository): Response
    {
        return $this->render('seance_type/index.html.twig', [
            'seance_types' => $seanceTypeRepository->findAll(),
        ]);
    }

    #[Route('/new/{programmeid}/{jour}', name: 'app_seance_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $programmeid, $jour, ProgrammeRepository $programmeRepository): Response
    {

        $nv_jour = (int)$jour; 
        $programme = $programmeRepository->findById($programmeid);

        $seanceType = new SeanceType();
        $seanceType->setCreateur($this->getUser());
        $seanceType->setProgramme($programme[0]);
        $seanceType->setJour($nv_jour);
        $form = $this->createForm(SeanceTypeType::class, $seanceType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($seanceType);
            $entityManager->flush();

            return $this->redirectToRoute('app_seance_type_new', [
                'programmeid' => $programmeid,
                'jour' => $nv_jour+1
        ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('seance_type/new.html.twig', [
            'seance_type' => $seanceType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_seance_type_show', methods: ['GET'])]
    public function show(SeanceType $seanceType): Response
    {
        return $this->render('seance_type/show.html.twig', [
            'seance_type' => $seanceType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_seance_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SeanceType $seanceType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SeanceTypeType::class, $seanceType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_seance_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('seance_type/edit.html.twig', [
            'seance_type' => $seanceType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_seance_type_delete', methods: ['POST'])]
    public function delete(Request $request, SeanceType $seanceType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seanceType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($seanceType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_seance_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
