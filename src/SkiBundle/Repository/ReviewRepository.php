<?php

namespace SkiBundle\Repository;

/**
 * ReviewRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReviewRepository extends \Doctrine\ORM\EntityRepository
{
    public function getReviews($stationId)
    {
        return $this->createQueryBuilder('review')
                    ->andWhere('review.stationId = :stationid')
                    ->setParameter('stationid', $stationId)
                    ->getQuery()
                    ->execute();
    }

    public function getAverageNotation($stationId)
    {
        $result = $this->createQueryBuilder('review')
                    ->select("AVG(review.notation) AS notation_avg")
                    ->where('review.stationId = :stationId')
                    ->groupBy('review.stationId')
                    ->setParameter('stationId', $stationId)
                    ->getQuery()
                    ->execute();
        return round($result[0]['notation_avg'], 1);
    }
}
