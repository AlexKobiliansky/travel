<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"login"}, message="This value is already used. Please choose a unique value" )
 * @UniqueEntity(fields={"email"}, message="This value is already used. Please choose a unique value" )
 * @Vich\Uploadable
 */
class User
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
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=50)
     * @Assert\Type("string")
     * @Assert\NotBlank(message = "please enter your password")
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
     * @Assert\NotBlank(message = "please enter your second name")
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
     * @Vich\UploadableField(mapping="avatar_image", fileNameProperty="avatar")
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
     * @ORM\OneToMany(targetEntity = "Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Article", inversedBy="users")
     * @ORM\JoinTable(name="user_article")
     */
    private $articles;


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

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

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

    public function addArticle(Article $articles)
    {
        if (!$this->articles->contains($articles)) {
            $this->articles[] = $articles;
            $articles->addAuthor($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName()." ".$this->getSurname();
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return User
     */
    public function setAvatarFile(File $image = null)
    {
        $this->avatarFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
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
}
