<?php

namespace App\Services;

use App\Entity\Post;
use App\Entity\Room;
use App\Entity\Visitor;
use App\Repository\VisitorRepository;

class VisitorService
{
    private VisitorRepository $visitorRepository;
    public function __construct(VisitorRepository $visitorRepository)
    {
        $this->visitorRepository = $visitorRepository;
    }

    public function createVisitor(string $ip): Visitor
    {
        $visitor = new Visitor();
        $visitor->setIp($ip);
        $this->visitorRepository->add($visitor);
        return $visitor;
    }

    public function getVisitorByIp(string $ip): Visitor
    {
        $visitor = $this->visitorRepository->findOneBy(["ip" => $ip]);
        if(!$visitor){
            $visitor = $this->createVisitor($ip);
        }
        return $visitor;
    }

    public function isVisitedRoom(Room $room, $ip): void
    {
        $visitor = $this->getVisitorByIp($ip);
        if(!array_search($room, $visitor->getRooms()->toArray())){
            $visitor->addRoom($room);
            $this->visitorRepository->add($visitor, true);
        }
    }

    public function isVisitedPost(Post $post, $ip): void
    {
        $visitor = $this->getVisitorByIp($ip);
        if(!array_search($post, $visitor->getPosts()->toArray())){
            $visitor->addPost($post);
            $this->visitorRepository->add($visitor, true);
        }
    }

}