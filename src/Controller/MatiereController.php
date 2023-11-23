<?php

namespace App\Controller;

use App\Entity\Matiere;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Note;
use App\Form\CreateNoteMatiereType;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

class MatiereController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em, private TranslatorInterface $translator) {
    }

    #[Route('/matiere/{nom}', name: 'app_matiere')]
    public function index(Matiere $matiere = null, Request $request,): Response
    {
        $newNote = new Note();
        $form = $this->createForm(CreateNoteMatiereType::class, $newNote,);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $newNote->setCreatedAt(new \DateTime());
            $newNote->setCoefficient($newNote->getMatiere()->getCoefficient());
            $newNote->setMatiere($matiere);
            $this->em->persist($newNote);
            $this->em->flush();
            $this->addFlash('success', $this->translator->trans('note').' '. $this->translator->trans('successCreate'));
            return $this->redirectToRoute('app_index');
        }
        $notes = $matiere->getNotes();
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'form' => $form->createView(),
            'notes' => $notes,
            'matiere' => $matiere,
            'total' => 0,
            'coef' => 0,
            'matieres' => $this->em->getRepository(Matiere::class)->findAll(),
        ]);
    }
}
