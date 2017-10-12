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
    * @ORM\ManyToOne(targetEntity= "Station", inversedBy="reviews")
    * @ORM\JoinColumn(name="station_id", referencedColumnName="id")
    */
    private $station;

    /**
    * @ORM\ManyToOne(targetEntity= "User", inversedBy="reviews")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(name="notation", type="integer")
     */
    private $notation;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
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

    public function setStation(Station $station)
    {
        $this->station = $station;
        return $this;
    }

    public function getStation()
    {
        return $this->station;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
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
