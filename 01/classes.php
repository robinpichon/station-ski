<?php

class Group
{
  private $name;

  function __construct(string $name)
  {
    $this->name = $name;
  }
}

class User
{
  private $name;
  private $group;

  function __construct(string $name, Group $group) {
    $this->name = $name;
    $this->group = $group;
  }
}

class Station
{
  private $name;
  private $dept;

  function __construct(string $name, int $dept)
  {
    $this->name = $name;
    $this->dept = $dept;
  }

  function getStationName() {
    return $this->name;
  }

  function getStationDept() {
    return $this->dept;
  }
}

class Review extends Station
{
  private $station;
  private $notation;

  function __construct(Station $station, int $notation)
  {
    $this->station = $station;
    $this->notation = $notation;
  }

  function getStation() {
    return $this->station;
  }

  function getStationNote() {
    return $this->notation;
  }
}

$reviewList = [
  new Review(new Station("Les 2 Alpes", 74), 5),
  new Review(new Station("Aussois", 73), 1),
  new Review(new Station("Alpe d'Huez", 38), 3)
];

foreach ($reviewList as $review) {
  echo 'Station: '.$review->getStation()->getStationName().' - Département: '.$review->getStation()->getStationDept().' - Note: '.$review->getStationNote().'/5';
  echo "\n";
}

?>
