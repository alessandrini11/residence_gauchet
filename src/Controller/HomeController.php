<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Room;
use App\Form\SearchPostType;
use App\models\SearchPost;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\RoomRepository;
use App\Services\VisitorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RoomRepository $roomRepository, PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy(["isApproved" => true], ['createdAt' => 'DESC'], 3);
        $rooms = $roomRepository->findBy([], ['createdAt' => 'DESC'], 2);
        return $this->render('home/index.html.twig', [
            'rooms' => $rooms,
            'posts' => $posts
        ]);
    }

    #[Route('/apropos', name: 'app_apropos')]
    public function apropos(): Response
    {
        return $this->render('home/apropos.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig');
    }

    #[Route('/appartements', name: 'app_appartements')]
    public function appartements(RoomRepository $roomRepository): Response
    {
        return $this->render('home/appartement/index.html.twig', [
            'rooms' => $roomRepository->findAll()
        ]);
    }

    #[Route('/gallerie', name: 'app_gallerie')]
    public function gallery(): Response
    {
        return $this->render('home/gallery/index.html.twig');
    }

    #[Route('/appartements/{id}', name: 'app_appartements_detail')]
    public function appartementsDetail(Room $room, Request $request, VisitorService $visitorService): Response
    {

        $ip = $request->getClientIp();
        $visitorService->isVisitedRoom($room, $ip);
        return $this->render('home/appartement/detail.html.twig', [
            'room' => $room
        ]);
    }

    #[Route('/articles', name: 'app_blog', methods: ['GET'])]
    public function blog(PostRepository $postRepository, Request $request, CategoryRepository $categoryRepository): Response
    {
        $searchPost = new SearchPost();
        $form = $this->createForm(SearchPostType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchPost->title = $form->get('title')->getData();
            $posts = $postRepository->searchByName($searchPost);
            return $this->render('home/articles/index.html.twig', [
                'posts' => $posts,
                'categories' => $categoryRepository->findAll(),
                'searchForm' => $form->createView()
            ]);
        }
        return $this->render('home/articles/index.html.twig', [
            'posts' => $postRepository->findBy(["isApproved" => true]),
            'categories' => $categoryRepository->findAll(),
            'searchForm' => $form->createView()
        ]);
    }

    #[Route('/articles/{id}', name: 'app_blog_detail')]
    public function blogDetails(CategoryRepository $categoryRepository, Post $post, Request $request, VisitorService $visitorService): Response
    {
        $ip = $request->getClientIp();
        $visitorService->isVisitedPost($post, $ip);
        return $this->render('home/articles/detail.html.twig', [
            'post' => $post,
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/articles/categorie/{id}', name: 'app_category_detail')]
    public function getPostsByCategory(Category $category, CategoryRepository $categoryRepository): Response
    {
        return $this->render('home/category/index.html.twig', [
            'category' => $category,
            'categories' => $categoryRepository->findAll()
        ]);
    }
}
