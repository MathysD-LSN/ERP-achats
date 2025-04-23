<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Form\AchatType;
use App\Repository\AchatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AchatController extends AbstractController
{
    private $entityManager;
    

    // Injection de l'EntityManagerInterface via le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/achat', name: 'app_achat_index')]
    public function index(AchatRepository $achatRepository): Response
    {
        $achats = $achatRepository->findAll();

        return $this->render('achat/index.html.twig', [
            'achats' => $achats,
        ]);
    }
    

    #[Route('/achat/new', name: 'app_achat_new')]
    public function new(Request $request): Response
    {
        $achat = new Achat();
        $form = $this->createForm(AchatType::class, $achat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $idStock = $form->get('id_stock')->getData();
            var_dump($idStock);
            $achat->setIdStock($idStock);

            $this->entityManager->persist($achat); // Persister l'entité
            $this->entityManager->flush(); // Sauvegarder dans la base

            return $this->redirectToRoute('app_achat_index');
        }

        return $this->render('achat/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/achat/{id}', name: 'app_achat_show')]
    public function show(Achat $achat): Response
    {
        return $this->render('achat/show.html.twig', [
            'achat' => $achat,
        ]);
    }

    #[Route('/achat/{id}/edit', name: 'app_achat_edit')]
    public function edit(Request $request, Achat $achat): Response
    {
        $form = $this->createForm(AchatType::class, $achat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush(); // Enregistrer les modifications

            return $this->redirectToRoute('app_achat_index');
        }

        return $this->render('achat/edit.html.twig', [
            'form' => $form->createView(),
            'achat' => $achat,
        ]);
    }

    #[Route('/achat/{id}/delete', name: 'app_achat_delete')]
    public function delete(Achat $achat): Response
    {
        $this->entityManager->remove($achat);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_achat_index');
    }
    
}
