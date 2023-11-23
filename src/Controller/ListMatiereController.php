<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Matiere;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CreateMatiereType;
use Symfony\Contracts\Translation\TranslatorInterface;

class ListMatiereController extends AbstractController
{
    
    public function __construct(private EntityManagerInterface $em, private TranslatorInterface $translator)
    {
    }
    
    #[Route('/list/matiere', name: 'app_list_matiere')]
    public function index(Request $request): Response
    {
        $newMatiere = new Matiere();
        $form = $this->createForm(CreateMatiereType::class, $newMatiere);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($newMatiere);
            $this->em->flush();
            $this->addFlash('success', $this->translator->trans('matière').' '. $this->translator->trans('successCreate'));
            return $this->redirectToRoute('app_list_matiere');
        }
        $matieres = $this->em->getRepository(Matiere::class)->findAll();
        return $this->render('list_matiere/index.html.twig', [
            'controller_name' => 'ListMatiereController',
            'matieres' => $matieres,
            'form' => $form->createView()
        ]);
    }

    #[Route('/matiereDel/{id}', name: 'app_matiere_delete')]
    public function delete(Matiere $matiere): Response
    {
        $this->em->remove($matiere);
        $this->em->flush();
        $this->addFlash('success', $this->translator->trans('matière').' '. $this->translator->trans('successDelete'));
        return $this->redirectToRoute('app_list_matiere');
    }

    #[Route('/matiereEdit/{id}', name: 'app_matiere_edit')]
    public function edit(Matiere $matiere, Request $request): Response
    {
        $form = $this->createForm(CreateMatiereType::class, $matiere);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($matiere);
            $this->em->flush();
            $this->addFlash('success', $this->translator->trans('matière').' '. $this->translator->trans('successEdit'));
            return $this->redirectToRoute('app_list_matiere');
        }
        return $this->render('index/edit.html.twig', [
            'controller_name' => 'ListMatiereController',
            'form' => $form->createView(),
            'entity' => "matière"
        ]);
    }
}
