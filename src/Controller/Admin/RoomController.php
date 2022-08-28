<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use App\Services\ImagesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/room')]
class RoomController extends AbstractController
{
    const DIRECTORY = 'rooms_directory';

    #[Route('/', name: 'app_room_index', methods: ['GET'])]
    public function index(RoomRepository $roomRepository): Response
    {
        return $this->render('room/index.html.twig', [
            'rooms' => $roomRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_room_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RoomRepository $roomRepository, ImagesService $imagesService): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedImages = $form->get('images')->getData();
            foreach ($uploadedImages as $file) {
                $imageUrl = $imagesService->uploadImage($file, self::DIRECTORY);
                $image = new Image();
                $image->setLink($imageUrl);
                $room->addImage($image);
            }
            $room->setCreatedAt(new \DateTime('now'));
            $roomRepository->add($room, true);

            $this->addFlash('success', 'appartement créé');
            return $this->redirectToRoute('app_room_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('room/new.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_room_show', methods: ['GET'])]
    public function show(Room $room): Response
    {
        return $this->render('room/show.html.twig', [
            'room' => $room,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_room_edit', methods: ['GET', 'POST'])]
    public function edit(ImagesService $imagesService, Request $request, Room $room, RoomRepository $roomRepository): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedImages = $form->get('images')->getData();
            foreach ($uploadedImages as $file) {
                $imageUrl = $imagesService->uploadImage($file, self::DIRECTORY);
                $image = new Image();
                $image->setLink($imageUrl);
                $room->addImage($image);
            }
            $room->setUpdatedAt(new \DateTime('now'));
            $roomRepository->add($room, true);

            $this->addFlash('info', 'appartement modifié');
            return $this->redirectToRoute('app_room_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('room/edit.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_room_delete', methods: ['POST'])]
    public function delete(ImagesService $imagesService, Request $request, Room $room, RoomRepository $roomRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $roomRepository->remove($room, true);
        }
        foreach ($room->getImages() as $file) {
            $imageUrl = $imagesService->removeImage($file->getLink());
        }
        $this->addFlash('danger', 'appartement supprimé');
        return $this->redirectToRoute('app_room_index', [], Response::HTTP_SEE_OTHER);
    }
}
