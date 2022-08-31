<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\PostRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encodedPassword = $passwordEncoder->hashPassword(
                $user,
                $user->getPlainPassword()
            );
            $user->setPassword($encodedPassword);
            $userRepository->add($user, true);

            $this->addFlash('success', 'utilisateur créé');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordEncoder): Response
    {
        if($this->getUser() !== $user){
            throw new AccessDeniedException('Vous n\'êtes pas authorisé(e) a modifier cet utilisateur ');
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('plainPassword') !== null){
                $encodedPassword = $passwordEncoder->hashPassword(
                    $user,
                    $user->getPlainPassword()
                );
                $user->setPassword($encodedPassword);
            }
            $userRepository->add($user, true);

            $this->addFlash('info', 'utilisateur modifié');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, PostRepository $postRepository, RoomRepository $roomRepository, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            foreach ($user->getRooms() as $room) {
                $room->setUser(null);
                $roomRepository->add($room, true);
            }
            foreach ($user->getPosts() as $post) {
                $post->setUser(null);
                $postRepository->add($post, true);
            }
            $userRepository->remove($user, true);
        }

        $this->addFlash('danger', 'utilisateur supprimé');
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/approved/{id}', name: 'app_user_approved', methods: ['PATCH'])]
    #[IsGranted("ROLE_ADMIN")]
    public function approvePost(User $user, UserRepository $userRepository): JsonResponse
    {
        $user->setIsBlocked(!$user->isIsBlocked());
        $userRepository->add($user, true);
        return new JsonResponse(["message" => "updated"]);
    }
}
