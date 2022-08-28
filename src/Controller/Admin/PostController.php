<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Services\ImagesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post')]
class PostController extends AbstractController
{
    const DIRECTORY = 'posts_directory';
    
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostRepository $postRepository, ImagesService $imagesService): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedImageFile = $form->get('image')->getData();
            $imageUrl = $imagesService->uploadImage($uploadedImageFile, self::DIRECTORY);
            $post->setImage($imageUrl);
            $post->setIsApproved(false);
            $postRepository->add($post, true);

            $this->addFlash('success','article créé');
            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(ImagesService $imagesService, Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            if($form->get('image')){
                $imagesService->removeImage($post->getImage());
                $uploadedImageFile = $form->get('image')->getData();
                $imageUrl = $imagesService->uploadImage($uploadedImageFile, self::DIRECTORY);
                $post->setImage($imageUrl);
            }
            $postRepository->add($post, true);
            $this->addFlash('info', 'article modifié');
            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(ImagesService $imagesService, Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post, true);
        }
        $imagesService->removeImage($post->getImage());
        $this->addFlash('success', 'article supprimé');
        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
