<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
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
            "souhaits" => $souhaits
        ]);
    }

    /**
     * @Route("/Details/{id}", name="details")
     */
    public function details($id, WishRepository $wishRepository): Response{

        $souhait = $wishRepository->find($id);

        return $this->render('wish/details.html.twig', [
            "souhait" => $souhait
        ]);
    }

    /**
     * @Route("/AjouterSerie", name="ajout")
     */
    public function ajout(EntityManagerInterface $entityManager, Request $request): Response{

        $souhait = new Wish();
        $souhait->setDateCreated(new \DateTime());

        $souhaitForm = $this->createForm(WishType::class, $souhait);
        $souhaitForm->handleRequest($request);

        if($souhaitForm->isSubmitted() && $souhaitForm->isValid()){

            $entityManager->persist($souhait);
            $entityManager->flush();

            $this->addFlash('success', 'Souhait ajouté !');
            return $this->redirectToRoute('wish_list');

        }

        return $this->render('wish/ajout.html.twig', [
            'souhaitForm' => $souhaitForm->createView()
        ]);
    }


}
