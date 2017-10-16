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

    public function getMap($stationId)
    {
        $key = 'AIzaSyDXqQN8HdrYioKBXsvvbdoQMGTGcIB9H0E';
        $stationRepository = $this->em->getRepository(Station::class);
        $place_id = $stationRepository->find($stationId)->getPlaceId();

        // Request to Google Map Embed API
        $map = '<iframe
                  width="600"
                  height="450"
                  frameborder="0" style="border:0"
                  src="https://www.google.com/maps/embed/v1/place?key='.$key.'&q=place_id:'.$place_id.'" allowfullscreen>
                </iframe>';

        return $map;
    }
}
