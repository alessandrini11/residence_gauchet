<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Services\ImagesService;
use App\Services\VisitorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/admin/post')]
class PostController extends AbstractController
{
    const DIRECTORY = 'posts_directory';

    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
            'current_menu' => 'post'
        ]);
    }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostRepository $postRepository, ImagesService $imagesService): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedImageFile = $form->get('file')->getData();
            $imageUrl = $imagesService->uploadImage($uploadedImageFile, self::DIRECTORY);
            $post->setImage($imageUrl);
            $post->setIsApproved(false);
            $post->setUser($this->getUser());
            $postRepository->add($post, true);

            $this->addFlash('success', 'article créé');
            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post, VisitorService $visitorService, Request $request): Response
    {
        $ip = $request->getClientIp();
        $visitorService->isVisitedPost($post, $ip);
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(ImagesService $imagesService, Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->getUser() !== $post->getUser()) {
            throw new AccessDeniedException('Vous ne pouvez pas modifier cet article');
        }
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        $oldImage = $post->getImage();
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setImage($oldImage);
            $uploadedImageFile = $form->get('file')->getData();
            if ($uploadedImageFile) {
                $imagesService->removeImage($oldImage);
                $imageUrl = $imagesService->uploadImage($uploadedImageFile, self::DIRECTORY);
                $post->setImage($imageUrl);
            }
            $post->setIsApproved(false);
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
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post, true);
        }
        $imagesService->removeImage($post->getImage());
        $this->addFlash('danger', 'article supprimé');
        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/approved/{id}', name: 'app_post_approved', methods: ['PATCH'])]
    #[IsGranted("ROLE_ADMIN")]
    public function approvePost(Post $post, PostRepository $postRepository): JsonResponse
    {
        $post->setIsApproved(!$post->isIsApproved());
        $postRepository->add($post, true);
        return new JsonResponse(["message" => "updated"]);
    }
}
