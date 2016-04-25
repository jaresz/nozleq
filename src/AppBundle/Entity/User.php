<?php
/**
 * plik Klasy typów użytkowników systemu
 */
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Klasa wszystkich typów użytkowników systemu
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
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;
    
    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $rapas;
    
    
    /**
     * @Assert\NotBlank(groups={"Registration", "Profile"})
     * @Assert\Length(min=5, groups={"Registration", "Profile"})
     */
    protected $username;



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
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $phone;


    public function __construct()
    {
        parent::__construct();
        
        // Dodatkowy niepuliczny identyfikar obiektu/rekordu - używany jako dodatkowy np. przy kasowaniu
        $this->setRapas(substr(md5(rand(199, 9999)), 0, 30));
    }

    /**
     * Sprawdza czy hasło nie jes takie jak login
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
     * Dodaje lub usuwa wybraną rolę użytkownika (pierwszy parametr)
     * na podstawie przekazanej wartości (drugi parametr)
     * 
     * @param string $role      oznaczenie roli na której operujemy np. 'ROLE_USER'          
     * @param boolean $bval     wartość roli: true - dodanie, false - usunięcie            
     */
    protected function setRole($role, $bval)
    {
        if ($bval) {
            if (! $this->hasRole($role))  // Jeśli użytkownik nie ma tej roli,
                $this->addRole($role);    // to ją dodajemy:
        } else {
            if ($this->hasRole($role))    // Jeśli użytkownik ma już tą rolę,
                $this->removeRole($role); // to ją usuwamy:
        }
    }

    /**
     * Ustawia rolę administratora - ROLE_ADMIN
     *
     * @param boolean $isAdmin            
     * @return User
     */
    public function setIsAdmin($isAdmin)
    {
        $this->setRole('ROLE_ADMIN', $isAdmin);
        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->hasRole('ROLE_ADMIN');
    }


    /**
     * Ustawia rolę sprzedawcy/recepcjonisty - ROLE_CLERK
     *
     * @param boolean $isAdmin
     * @return User
     */
    public function setIsClerk($isAdmin)
    {
        $this->setRole('ROLE_CLERK', $isAdmin);
        return $this;
    }
    
    /**
     * Get isClerk
     *
     * @return boolean
     */
    public function isClerk()
    {
        return $this->hasRole('ROLE_CLERK');
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

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
