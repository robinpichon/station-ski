<?php

namespace SkiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Review
 *
 * @ORM\Table(name="review")
 * @ORM\Entity(repositoryClass="SkiBundle\Repository\ReviewRepository")
 */
class Review
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="station_id", type="integer")
     */
    private $stationId;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="notation", type="integer")
     */
    private $notation;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255)
     */
    private $comment;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set station id
     *
     * @param integer $stationId
     *
     * @return Review
     */
    public function setStationId($stationId)
    {
        $this->stationId = $stationId;

        return $this;
    }

    /**
     * Get station id
     *
     * @return int
     */
    public function getStationId()
    {
        return $this->stationId;
    }

    /**
     * Set user id
     *
     * @param integer $userId
     *
     * @return Review
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get user id
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set notation
     *
     * @param integer $notation
     *
     * @return Review
     */
    public function setNotation($notation)
    {
        $this->notation = $notation;

        return $this;
    }

    /**
     * Get notation
     *
     * @return int
     */
    public function getNotation()
    {
        return $this->notation;
    }

    /**
     * Set review
     *
     * @param string $review
     *
     * @return Review
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get review
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
}
