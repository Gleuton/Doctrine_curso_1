<?php

namespace Curso\Entity;
/**
 * @Entity 
 * @Table(name="categories")
 */
class Category 
{
    /**
     * @Id
     *
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="string", length=100)
     */
    private $name;    

    /**
     * Get the value of id
     * 
     * @return int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     * @return string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}