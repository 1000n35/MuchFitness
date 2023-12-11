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

use function PHPUnit\Framework\isEmpty;

#[Route('/semaine')]
class SemaineController extends AbstractController
{
    #[Route('/', name: 'app_semaine_index', methods: ['GET'])]
    public function index(SemaineRepository $semaineRepository): Response
    {
        $dateNow = new \DateTime();
        $user = $this->getUser();

        if (!$user) { // si aucun user connecté renvoie à la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('semaine/index.html.twig', [
            'semaines' => $semaineRepository->findByUser($user->getId()),
            'dateNow' => $dateNow,
        ]);
    }



    #[Route('/new', name: 'app_semaine_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SemaineRepository $semaineRepository, EntityManagerInterface $entityManager): Response
    {
        $semaine = new Semaine();

        $user = $this->getUser();

        if (!$user) { // si aucun user connecté renvoie à la page de connexion
            return $this->redirectToRoute('app_login');
        }

        if(!$user->getProgSuivi()) { // si aucun prog suivi renvoie à l'index
            return $this->redirectToRoute('app_programme_index', [], Response::HTTP_SEE_OTHER);
        }

        $dateNow = new \DateTime();


        $semaineListe = $semaineRepository->findByUser($user);
        if(!isEmpty($semaineListe)) {
            $lastSemaine = $semaineListe[0];
            if($dateNow < $lastSemaine->getDateDebut()->modify('+' . count($lastSemaine->getProgramme()->getSeanceTypes()) . 'days')) {
                return $this->redirectToRoute('app_semaine_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        $semaine->setProgramme($user->getProgSuivi());
        $semaine->setUser($user);

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
        $user = $this->getUser();

        if (!$user) { // si aucun user connecté renvoie à la page de connexion
            return $this->redirectToRoute('app_login');
        }

        if ($user != $semaine->getUser()) {
            return $this->redirectToRoute('app_semaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('semaine/show.html.twig', [
            'semaine' => $semaine,
        ]);
    }



    #[Route('/{id}/edit', name: 'app_semaine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Semaine $semaine, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) { // si aucun user connecté renvoie à la page de connexion
            return $this->redirectToRoute('app_login');
        }

        if ($user != $semaine->getUser()) {
            return $this->redirectToRoute('app_semaine_show', [
                'id' => $semaine->getId(),
            ], Response::HTTP_SEE_OTHER);
        }
        
        $form = $this->createForm(SemaineType::class, $semaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_semaine_show', [
                'id' => $semaine->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('semaine/edit.html.twig', [
            'semaine' => $semaine,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_semaine_delete', methods: ['POST'])]
    public function delete(Request $request, Semaine $semaine, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($semaine);
        $entityManager->flush();

        return $this->redirectToRoute('app_semaine_index', [], Response::HTTP_SEE_OTHER);
    }
}
