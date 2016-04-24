<?php
/**
 * plik Klasy typów użytkowników systemu
 */
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Klasa wszystkich typów użytkowników systemu
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\UserRepository")
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank(groups={"Registration", "Profile"})
     * @Assert\Length(min=5, groups={"Registration", "Profile"})
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $rapas;

    /**
     * @Assert\NotBlank(groups={"Registration", "Profile"})
     * @Assert\Length(min=4, groups={"Registration", "Profile"})
     */
    protected $email;

    /**
     * @Assert\NotBlank(groups={"Registration", "ChangePassword"})
     * @Assert\Length(min=8, groups={"Registration", "ChangePassword"})
     */
    protected $plainPassword;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank(groups={"Registration", "Profile"})
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank(groups={"Registration", "Profile"})
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isAdmin;

    public function __construct()
    {
        parent::__construct();
        
        $this->isAdmin = false;
        // Dodatkowy niepuliczny identyfikar obiektu/rekordu - używany jako dodatkowy np. przy kasowaniu
        $this->setRapas(substr(md5(rand(199, 9999)), 0, 10));
    }

    /**
     * @Assert\IsTrue(message="registration.pass_cannot_be_like_login", groups={"Strict", "Registration", "Profile"})
     */
    public function isPasswordLegal()
    {
        if (! $this->plainPassword)
            return true;
        return ($this->username !== $this->plainPassword);
    }

    /**
     * Set rapas
     *
     * @param string $rapas            
     * @return User
     */
    public function setRapas($rapas)
    {
        $this->rapas = $rapas;
        
        return $this;
    }

    /**
     * Get rapas
     *
     * @return string
     */
    public function getRapas()
    {
        return $this->rapas;
    }

    /**
     * Set firstName
     *
     * @param string $firstName            
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName            
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin            
     * @return User
     */
    public function setAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
        
        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set phone
     *
     * @param string $phone            
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        
        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

}
