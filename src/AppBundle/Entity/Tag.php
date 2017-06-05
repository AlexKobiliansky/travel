<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 * @UniqueEntity(fields={"name"})
 */
class Tag
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
     * @ORM\Column(name="name", type="string", length=30, unique=true)
     * @Assert\Type("string")
     * @Assert\NotBlank(message = "please enter tags name")
     * @Assert\Length(
     *     max = 30,
     *     maxMessage = "Tag name cannot be longer then {{ limit }} characters"
     * )
     * @Assert\Regex(
     *     pattern = "/^[a-zA-Z0-9-]+$/",
     *     message = "Tag name can contain text characters and numbers.Please enter valid value"
     * )
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="tags")
     */
    private $articles;

    public function __construct()
    {
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
     * Set name
     *
     * @param string $name
     *
     * @return Tag
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

    public function addArticle(Article $articles)
    {
        if (!$this->articles->contains($articles)) {
            $this->articles[] = $articles;
            $articles->addTag($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
