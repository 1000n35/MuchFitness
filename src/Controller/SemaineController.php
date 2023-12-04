<?php

namespace App\Controller;

use App\Entity\Semaine;
use App\Form\SemaineType;
use App\Repository\SemaineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/semaine')]
class SemaineController extends AbstractController
{
    #[Route('/', name: 'app_semaine_index', methods: ['GET'])]
    public function index(SemaineRepository $semaineRepository): Response
    {
        return $this->render('semaine/index.html.twig', [
            'semaines' => $semaineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_semaine_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $semaine = new Semaine();
        $form = $this->createForm(SemaineType::class, $semaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($semaine);
            $entityManager->flush();

            return $this->redirectToRoute('app_semaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('semaine/new.html.twig', [
            'semaine' => $semaine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_semaine_show', methods: ['GET'])]
    public function show(Semaine $semaine): Response
    {
        return $this->render('semaine/show.html.twig', [
            'semaine' => $semaine,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_semaine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Semaine $semaine, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SemaineType::class, $semaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_semaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('semaine/edit.html.twig', [
            'semaine' => $semaine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_semaine_delete', methods: ['POST'])]
    public function delete(Request $request, Semaine $semaine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$semaine->getId(), $request->request->get('_token'))) {
            $entityManager->remove($semaine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_semaine_index', [], Response::HTTP_SEE_OTHER);
    }
}
