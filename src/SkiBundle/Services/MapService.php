<?php

namespace SkiBundle\Services;

use SkiBundle\Entity\Station;
use Doctrine\ORM\EntityManager;

class MapService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getMapUrl($stationId)
    {
        $key = 'AIzaSyDXqQN8HdrYioKBXsvvbdoQMGTGcIB9H0E';
        $stationRepository = $this->em->getRepository(Station::class);
        $place_id = $stationRepository->find($stationId)->getPlaceId();
        $url = 'https://www.google.com/maps/embed/v1/place?key='.$key.'&q=place_id:'.$place_id;
        return $url;
    }
}
