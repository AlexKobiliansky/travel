<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 * @Vich\Uploadable
 */
class Article
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\Type("string")
     * @Assert\NotBlank(message = "please enter article title")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @Vich\UploadableField(mapping="article_image", fileNameProperty="imageName", size="imageSize")
     *
     * @var File
     */
    private $imageFile;



    /**
     * @var string
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var integer
     */
    private $imageSize;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime", nullable=true)
     */
    private $dateUpdated;

    /**
     * @var bool
     *
     * @ORM\Column(name="approved", type="boolean")
     */
    private $approved = false;

    /**
     * @var int
     *
     * @ORM\Column(name="likes", type="integer", nullable=true)
     */
    private $likes = 0;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="articles")
     * @ORM\JoinTable(name="user_article")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="liked_articles")
     * @ORM\JoinTable(name="likes_user_article")
     */
    private $liked_users;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="article", cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="articles")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles")
     * @ORM\JoinTable(name="article_tag")
     */
    private $tags;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->users       = new ArrayCollection();
        $this->comments    = new ArrayCollection();
        $this->tags        = new ArrayCollection();
        $this->liked_users = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Article
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
    public function getContent($length = null)
    {
        if (false === is_null($length) && $length > 0) {
            return substr($this->content, 0, $length);
        } else {
            return $this->content;
        }
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Article
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     *
     * @return Article
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     *
     * @return Article
     */
    public function setApproved($approved = false)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return bool
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set likes
     *
     * @param integer $likes
     *
     * @return Article
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

       // return $this;
    }

    /**
     * Get likes
     *
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    public function addUser(User $users)
    {
        if (!$this->users->contains($users)) {
            $this->users[] = $users;
            $users->addArticle($this);
        }

        return $this;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function addTag(Tag $tags)
    {
        if (!$this->tags->contains($tags)) {
            $this->tags[] = $tags;
            $tags->addArticle($this);
        }
        return $this;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Article
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->dateUpdated = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     *
     * @return Article
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param integer $imageSize
     *
     * @return Article
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

    public function addLikedUser(User $liked_user)
    {
        if (!$this->liked_users->contains($liked_user)) {
            $this->liked_users[] = $liked_user;
            $liked_user->addLikedArticle($this);
        }

        return $this;
    }

    public function deleteLikedUser(User $liked_user)
    {
        if ($this->liked_users->contains($liked_user)) {
            $this->liked_users->removeElement($liked_user);
            $liked_user->deleteLikedArticle($this);
        }
    }
}
