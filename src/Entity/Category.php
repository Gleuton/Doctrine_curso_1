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
     * 
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="string", length=100)
     * 
     */
    private $name;    

    /**
     * Get the value of id
     * 
     * @return int
     */ 
    public function getId():int
    {
        return $this->id;
    }

    /**
     * Get the value of name
     * @return string
     */ 
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Category
     */
    public function setName(string $name):Category
    {
        $this->name = $name;

        return $this;
    }
}