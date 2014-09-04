<?php

/**
 * Description of User
 *
 * @author bilal
 */

namespace Acme\TestTaskBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="lcl_user")
 */
class User implements UserInterface, EquatableInterface {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @ORM\Column(type="string", length=255, nullable=false) */
    private $username;
    
    /** @ORM\Column(type="string", length=255, nullable=false) */
    private $password;
    
    /** @ORM\Column(type="string", length=255, nullable=false) */
    private $salt;
    
    /** @ORM\Column(type="array", nullable=true) */
    private $roles;

    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebookId;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebookAccessToken;

    /** @ORM\Column(name="first_name", type="string", length=255, nullable=true) */
    protected $firstName;    
    
    /** @ORM\Column(name="last_name", type="string", length=255, nullable=true) */
    protected $lastName;    
    
    public function __construct($username) {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->roles = array('ROLE_USER');        
        $this->username = $username;
    }

    public function getRoles() {
        return $this->roles;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getUsername() {
        return $this->username;
    }

    public function eraseCredentials() {
        
    }

    public function isEqualTo(UserInterface $user) {
        if (!$user instanceof WebserviceUser) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
    
    
    public function getId() {
        return $this->id;
    }

    public function getFacebookId() {
        return $this->facebookId;
    }

    public function getFacebookAccessToken() {
        return $this->facebookAccessToken;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setFacebookId($facebookId) {
        $this->facebookId = $facebookId;
    }

    public function setFacebookAccessToken($facebookAccessToken) {
        $this->facebookAccessToken = $facebookAccessToken;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }
    public function setPassword($password) {
        $this->password = $password;
    }
}
