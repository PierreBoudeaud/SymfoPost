<?php

namespace App\Controller;

use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TagController
 * @package App\Controller
 */
class TagController extends AbstractController
{
    /**
     * @Route("/tags", name="tags")
     */
    public function index(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $tagForm = $this->createTagForm();

        $tagForm->handleRequest($request);

        if($tagForm->isSubmitted() && $tagForm->isValid()) {
            $tag = $tagForm->getData();
            $entityManager->persist($tag);
            $entityManager->flush();
            unset($tagForm);
            $tagForm = $this->createTagForm();
        }

        $tags = $entityManager->getRepository(Tag::class)->findAll();

        return $this->render('tag/index.html.twig', [
            'controller_name' => 'TagController',
            'tags' => $tags,
            'form' => $tagForm->createView()
        ]);
    }

    private function createTagForm() {
        $tag = new Tag();
        return $this->createForm("App\Form\TagType", $tag);
    }

    /**
     * @Route("/tags/delete/{id}", name="tags_delete")
     */
    public function delete(int $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $tagRepo = $entityManager->getRepository(Tag::class);
        $tag = $tagRepo->find($id);
        if ($tag !== null) {
            $entityManager->remove($tag);
            $entityManager->flush();
        }
        return $this->redirectToRoute('tags');
    }
}
