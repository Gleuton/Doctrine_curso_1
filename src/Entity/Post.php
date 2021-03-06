<?php

namespace Curso\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;

/**
 * @Entity
 * @Table(name="posts")
 */
class Post
{
    /**
     * @Id
     *
     * @Column(type="integer")
     *
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="string", length=100)
     *
     */
    private $title;

    /**
     * @Column(type="text")
     */
    private $content;

    /**
     * @ManyToMany(targetEntity="Curso\Entity\Category")
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $title
     * @return Post
     */
    public function setTitle(string $title): Post
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $content
     * @return Post
     */
    public function setContent(string $content): Post
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param Category $category
     * @return Post
     */
    public function addCategory(Category $category): Post
    {
        $this->categories->add($category);
        return $this;
    }

    /**
     * @return ArrayCollection|PersistentCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

}