<?php

namespace Dywee\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * CustomForm
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Dywee\CMSBundle\Repository\CustomFormRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CustomForm
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"page_editing"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="FormField", mappedBy="form", cascade={"persist", "remove"})
     */
    private $formFields;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sumbitButtonValue = 'Valider';

    /**
     * @ORM\OneToMany(targetEntity="FormResponseContainer", mappedBy="form", cascade={"remove"})
     */
    private $responses;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formFields = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getSumbitButtonValue()
    {
        return $this->sumbitButtonValue;
    }

    public function setSumbitButtonValue($sumbitButtonValue)
    {
        $this->sumbitButtonValue = $sumbitButtonValue;
        return $this;
    }

    /**
     * Add formField
     *
     * @param FormField $formField
     * @return CustomForm
     */
    public function addFormField(FormField $formField)
    {
        $this->formFields[] = $formField;
        $formField->setForm($this);

        return $this;
    }

    /**
     * Remove formFields
     *
     * @param FormField $formFields
     */
    public function removeFormField(FormField $formFields)
    {
        $this->formFields->removeElement($formFields);
    }

    /**
     * Get formFields
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormFields()
    {
        return $this->formFields;
    }

    /* ALIAS */
    public function addField($field)    {   return $this->addFormField($field); }
    public function removeField($field) {   $this->removeFormField($field);     }
    public function getFields()         {   return $this->getFormFields();      }


    /**
     * @ORM\PreUpdate
     */
    public function updatedNow()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return CustomForm
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return CustomForm
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add responses
     *
     * @param FormResponseContainer $responses
     * @return CustomForm
     */
    public function addResponse(FormResponseContainer $responses)
    {
        $this->responses[] = $responses;

        return $this;
    }

    /**
     * Remove responses
     *
     * @param FormResponseContainer $responses
     */
    public function removeResponse(FormResponseContainer $responses)
    {
        $this->responses->removeElement($responses);
    }

    /**
     * Get responses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResponses()
    {
        return $this->responses;
    }

    public function countResponses()
    {
        return count($this->getResponses());
    }

}
