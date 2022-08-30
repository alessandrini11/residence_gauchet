<?php
namespace App\Controller\Admin;

use App\Repository\PostRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use App\Repository\VisitorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin")]
class Dashboard extends AbstractController
{
    #[Route("/", name: "admin")]
    public function index(
        UserRepository $userRepository, 
        RoomRepository $roomRepository, 
        PostRepository $postRepository, 
        VisitorRepository $visitorRepository
    ): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'users' => $userRepository->findAll(),
            'rooms' => $roomRepository->findAll(),
            'posts' => $postRepository->findAll(),
            'visitors' => $visitorRepository->findAll()
        ]);
    }
}