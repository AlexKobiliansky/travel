<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"login"}, message="This value is already used. Please choose a unique value" )
 * @UniqueEntity(fields={"email"}, message="This value is already used. Please choose a unique value" )
 * @Vich\Uploadable
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=50, unique=true)
     * @Assert\Type("string")
     * @Assert\NotBlank(message = "please enter your login")
     * @Assert\Length(
     *     max = 50,
     *     maxMessage = "your login cannot be longer then {{ limit }} characters"
     * )
     */
    private $login;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     * @Assert\Type("string")
     *
     * @Assert\Length(
     *     min = 6,
     *     max = 50,
     *     minMessage = "your password must contain at least {{ limit }} characters",
     *     maxMessage = "your password cannot be longer then {{ limit }} characters"
     * )
     */
    private $password;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     * @Assert\Type("string")
     * @Assert\NotBlank(message = "please enter your first name")
     * @Assert\Regex("/^[a-zA-Z]+$/")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=50)
     * @Assert\Type("string")
     * @Assert\NotBlank(message = "please enter your second name")
     * @Assert\Regex("/^[a-zA-Z]+$/")
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=75, unique=true)
     * @Assert\NotBlank(message = "please enter your email")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=30)
     * @Assert\NotBlank(message = "please enter your second name")
     * @Assert\Type("string")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     * @Assert\NotBlank(message = "please enter your second name")
     * @Assert\Type("string")
     */
    private $address;

    /**
     * @Vich\UploadableField(mapping="avatar_image", fileNameProperty="avatar", size="imageSize")
     *
     * @var File
     */
    private $avatarFile;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="text", nullable=true)
     * * @Assert\Image(
     *     minWidth = 100,
     *     maxWidth = 500,
     *     minHeight = 100,
     *     maxHeight = 500
     * )
     * @Assert\File(
     *      maxSize="2M",
     *      mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *      }
     * )
     */
    private $avatar;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var integer
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_birth", type="date")
     * @Assert\Date()
     */
    private $dateOfBirth;

    /**
     * @ORM\OneToMany(targetEntity = "Comment", mappedBy="user", cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="users")
     */
    private $articles;

    /**
     * @Gedmo\Slug(fields={"login"})
     * @ORM\Column(length=50)
     */
    private $slug;

    /**
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = array();


    public function __constuct()
    {
        $this->comments = new ArrayCollection();
        $this->articles = new ArrayCollection();
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
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($plainpassword)
    {
        $this->password = $plainpassword;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
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
     * Set surname
     *
     * @param string $surname
     *
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
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
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return User
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    public function getArticles()
    {
        return $this->articles;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function addArticle(Article $articles)
    {
        if (!$this->articles->contains($articles)) {
            $this->articles[] = $articles;
            $articles->addUser($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName()." ".$this->getSurname();
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $avatar
     *
     * @return User
     */
    public function setAvatarFile(File $avatar = null)
    {
        $this->avatarFile = $avatar;

        if ($avatar) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param integer $imageSize
     *
     * @return User
     */
    public function setImageSize($imageSize)
    {
        $this->imagesize = $imageSize;

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getImageSize()
    {
        return $this->imageSize;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->login,
            $this->password,
            $this->avatar,

        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->login,
            $this->password,
            $this->avatar,
            ) = unserialize($serialized);
    }

    public function getUsername()
    {
        return $this->login;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_AUTHOR';
        return array_unique($roles);
    }
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
        // allows for chaining
        return $this;
    }
}
