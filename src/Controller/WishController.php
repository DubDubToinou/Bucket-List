<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use App\Util\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wish", name="wish_")
 */
class WishController extends AbstractController
{
    /**
     * @Route("", name="list")
     */
    public function list(WishRepository $wishRepository): Response
    {
        $souhaits = $wishRepository->findAll();

        return $this->render('wish/list.html.twig', [
            'souhaits' => $souhaits,
        ]);
    }

    /**
     * @Route("/Details/{id}", name="details")
     */
    public function details($id, WishRepository $wishRepository): Response
    {
        $souhait = $wishRepository->find($id);

        if (!$souhait) {
            throw $this->createNotFoundException('Il n\'y a pas de souhait ici :) ');
        }

        return $this->render('wish/details.html.twig', [
            'souhait' => $souhait,
        ]);
    }

    /**
     * @Route("/user/AjouterSerie", name="ajout")
     */
    public function ajout(EntityManagerInterface $entityManager, Request $request, Censurator $censurator): Response
    {
        $souhait = new Wish();
        $souhait->setDateCreated(new \DateTime());
        $utilisateurEnCours = $this->getUser()->getUserIdentifier();
        $souhait->setAuthor($utilisateurEnCours);

        $souhaitForm = $this->createForm(WishType::class, $souhait);
        $souhaitForm->handleRequest($request);

        if ($souhaitForm->isSubmitted() && $souhaitForm->isValid()) {

            $souhait->setDescription($censurator->purify($souhait->getDescription()));

            $entityManager->persist($souhait);
            $entityManager->flush();

            $this->addFlash('success', 'Souhait ajouté !');

            return $this->redirectToRoute('wish_list');
        }

        return $this->render('wish/ajout.html.twig', [
            'souhaitForm' => $souhaitForm->createView(),
        ]);
    }
}
