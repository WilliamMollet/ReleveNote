<?php

namespace App\Controller;

use App\Entity\Matiere;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Note;
use App\Form\CreateNoteType;
use Symfony\Contracts\Translation\TranslatorInterface;

class IndexController extends AbstractController

{
    public function __construct(private EntityManagerInterface $em, private TranslatorInterface $translator)
    {
    }

    #[Route('/index', name: 'app_index')]
    public function index(Request $request): Response
    {
        $newNote = new Note();
        $form = $this->createForm(CreateNoteType::class, $newNote);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $newNote->setCoefficient($newNote->getMatiere()->getCoefficient());
            $this->em->persist($newNote);
            $this->em->flush();
            $this->addFlash('success', $this->translator->trans('note').' '. $this->translator->trans('successCreate'));
            return $this->redirectToRoute('app_index');
        }
        $notes = $this->em->getRepository(Note::class)->findAll();
        $matieres = $this->em->getRepository(Matiere::class)->findAll();
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'form' => $form->createView(),
            'notes' => $notes,
            'matiere' => '',
            'total' => 0,
            'coef' => 0,
            'matieres' => $matieres,
        ]);
    }

    #[Route('/noteDel/{id}', name: 'app_note_delete')]
    public function delete(Note $note): Response
    {
        $this->em->remove($note);
        $this->em->flush();
        $this->addFlash('success', $this->translator->trans('note').' '. $this->translator->trans('successDelete'));
        return $this->redirectToRoute('app_index');
    }

    #[Route('/noteEdit/{id}', name: 'app_note_edit')]
    public function edit(Note $note, Request $request): Response
    {
        $form = $this->createForm(CreateNoteType::class, $note);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($note);
            $this->em->flush();
            $this->addFlash('success', $this->translator->trans('note').' '. $this->translator->trans('successEdit'));
            return $this->redirectToRoute('app_index');
        }
        return $this->render('index/edit.html.twig', [
            'form' => $form->createView(),
            'note' => $note,
            'entity' => 'note'
        ]);
    }
}
