<?php
namespace App\Controller\Admin;

use App\Repository\PostRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use App\Repository\VisitorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route("/admin")]
class Dashboard extends AbstractController
{
    #[Route("/", name: "admin")]
    public function index(
        UserRepository $userRepository, 
        RoomRepository $roomRepository, 
        PostRepository $postRepository, 
        VisitorRepository $visitorRepository,
        ChartBuilderInterface $chartBuilder
    ): Response
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                ],
            ],
        ]);
        return $this->render('dashboard/index.html.twig', [
            'users' => $userRepository->findAll(),
            'rooms' => $roomRepository->findAll(),
            'posts' => $postRepository->findAll(),
            'visitors' => $visitorRepository->findAll(),
            'chart' => $chart,
        ]);
    }
}