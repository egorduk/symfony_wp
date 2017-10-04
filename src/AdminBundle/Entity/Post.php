<?php

namespace AdminBundle\Entity;

use AuthBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\PostRepository")
 * @ORM\Table(name="post")
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $excerpt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $postDate;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="PostType", inversedBy="posts")
     * @ORM\JoinColumn(name="post_type_id", referencedColumnName="id")
     */
    private $postType;

    /**
     * @ORM\ManyToOne(targetEntity="Taxonomy", inversedBy="posts")
     * @ORM\JoinColumn(name="taxonomy_id", referencedColumnName="id")
     */
    private $taxonomy;

    /**
     * @ORM\ManyToOne(targetEntity="PostStatus", inversedBy="posts")
     * @ORM\JoinColumn(name="post_status_id", referencedColumnName="id")
     */
    private $postStatus;


    public function __construct()
    {
        $this->postDate = new \DateTime();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Post
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set excerpt
     *
     * @param string $excerpt
     *
     * @return Post
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * Get excerpt
     *
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * Set author
     *
     * @param User $author
     *
     * @return Post
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set postDate
     *
     * @param \DateTime $postDate
     *
     * @return Post
     */
    public function setPostDate($postDate)
    {
        $this->postDate = $postDate;

        return $this;
    }

    /**
     * Get postDate
     *
     * @return \DateTime
     */
    public function getPostDate()
    {
        return $this->postDate;
    }

    /**
     * Set postType
     *
     * @param PostType $postType
     *
     * @return Post
     */
    public function setPostType($postType)
    {
        $this->postType = $postType;

        return $this;
    }

    /**
     * Get postType
     *
     * @return PostType
     */
    public function getPostType()
    {
        return $this->postType;
    }

    /**
     * Set taxonomy
     *
     * @param Taxonomy $taxonomy
     *
     * @return Post
     */
    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;

        return $this;
    }

    /**
     * Get taxonomy
     *
     * @return Taxonomy
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    /**
     * @return PostStatus
     */
    public function getPostStatus()
    {
        return $this->postStatus;
    }

    /**
     * @param PostStatus $postStatus
     */
    public function setPostStatus($postStatus)
    {
        $this->postStatus = $postStatus;
    }
}