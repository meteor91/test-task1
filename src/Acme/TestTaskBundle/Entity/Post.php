<?php

namespace Acme\TestTaskBundle\Entity;

/**
 * Description of Post
 *
 * @author bilal
 */

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="lcl_post")
 * @ORM\HasLifecycleCallbacks
 */
class Post {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */        
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true )
     */        
    private $lastChangedAt;    
    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */    
    private $text;
                    
    public function __construct() {
        $this->lastChangedAt = null;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        $this->createdAt = new \DateTime("now");
    }   

    /**
     * @ORM\PreUpdate
     */
    public function setLastChangedValue() {
        $this->lastChangedAt = new \DateTime("now");
    }      
    
    public function getId() {
        return $this->id;
    }

    public function getUser() {
        return $this->user;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getText() {
        return $this->text;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function setText($text) {
        $this->text = $text;
    }
    
    public function getLastChangedAt() {
        return $this->lastChangedAt;
    }

    public function setLastChangeAt($lastChangedAt) {
        $this->lastChangedAt = $lastChangedAt;
    }


    
}
