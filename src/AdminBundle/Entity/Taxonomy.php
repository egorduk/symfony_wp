<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\TaxonomyRepository")
 * @ORM\Table(name="taxonomy")
 */
class Taxonomy
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="taxonomy")
     */
    private $posts;


    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return integer
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param integer $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function __toString()
    {
        return (string)$this->getId();
    }

    /**
     * Add post
     *
     * @param \AdminBundle\Entity\Post $post
     *
     * @return Taxonomy
     */
    public function addPost(\AdminBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \AdminBundle\Entity\Post $post
     */
    public function removePost(\AdminBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
