<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PropertySearch {

    /**
     * @var string
     * @Assert\LessThanOrEqual(5)
     */
    private $title;

    /**
     * @Assert\Range(
     *      min = 45,
     *      max = 450,
     *      minMessage = "surface must be greater than 45 m²",
     *      maxMessage = "surface must be less than 450 m²"
     * )
     * @var integer
     */
    private $minSurface;

    /**
     * @Assert\Range(
     *      min = 25000,
     *      max = 400000,
     *      minMessage = "Price must be greater than 25000 $",
     *      maxMessage = "Price must be less than 400000 $"
     * )
     * @var integer
     */
    private $maxPrice;


    /**
     * Get the value of title
     *
     * @return  string
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param  string  $title
     *
     * @return  self
     */ 
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return  integer
     */ 
    public function getMinSurface()
    {
        return $this->minSurface;
    }

    /**
     * @param  integer  $minSurface  )
     *
     * @return  self
     */ 
    public function setMinSurface($minSurface)
    {
        $this->minSurface = $minSurface;

        return $this;
    }

    /**
     * @return  integer
     */ 
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * @param  integer  $maxPrice  )
     *
     * @return  self
     */ 
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }
}