<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/posts", name="posts")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $posts = $entityManager->getRepository(Post::class)->findAll();
        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }
    
    private function createPostForm() {
        $post = new Post();
        return $this->createForm("App\Form\PostType", $post);
    }

    /**
     * @Route("/posts/delete/{id}", name="posts_delete")
     */
    public function delete(int $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $tagRepo = $entityManager->getRepository(Post::class);
        $post = $tagRepo->find($id);
        if ($post !== null) {
            $entityManager->remove($post);
            $entityManager->flush();
        }
        return $this->redirectToRoute('posts');
    }

    /**
     * @Route("/posts/create", name="posts_create")
     */
    public function create(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $postForm = $this->createPostForm();

        $postForm->handleRequest($request);

        if($postForm->isSubmitted() && $postForm->isValid()) {
            $post = $postForm->getData();
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute("posts");
        }

        return $this->render("post/create.html.twig", [
            "form" => $postForm->createView()
        ]);
    }

    /**
     * @Route("/posts/{id}", name="posts_show")
     */
    public function show(int $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $tagRepo = $entityManager->getRepository(Post::class);
        $post = $tagRepo->find($id);
        return $this->render("post/show.html.twig", [
            'post' => $post
        ]);
    }
}
