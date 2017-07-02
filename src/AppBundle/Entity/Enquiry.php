<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Enquiry
 *
 * @ORM\Table(name="enquiry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EnquiryRepository")
 */

class Enquiry
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
     * @ORM\Column(name="name", type="string", length=50)
     * @Assert\Type("string")
     * @Assert\NotBlank(message = "please enter your first name")
     * @Assert\Regex("/^[a-zA-Z]+$/")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=75)
     * @Assert\NotBlank(message = "please enter your email")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     * @Assert\Type("string")
     * @Assert\NotBlank(message = "please enter subject")
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    protected $body;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
}
