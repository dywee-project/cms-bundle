<?php

namespace Dywee\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormResponse
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FormResponse
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
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="FormResponseContainer", inversedBy="fieldResponses")
     */
    private $responseContainer;

    /**
     * @ORM\ManyToOne(targetEntity="FormField")
     */
    private $field;


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
     * Set value
     *
     * @param string $value
     * @return FormResponse
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set responseContainer
     *
     * @param FormResponseContainer $responseContainer
     * @return FormResponse
     */
    public function setResponseContainer(FormResponseContainer $responseContainer = null)
    {
        $this->responseContainer = $responseContainer;

        return $this;
    }

    /**
     * Get responseContainer
     *
     * @return FormResponseContainer
     */
    public function getResponseContainer()
    {
        return $this->responseContainer;
    }

    /**
     * Set field
     *
     * @param FormField $field
     * @return FormResponse
     */
    public function setField(FormField $field = null)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return FormField
     */
    public function getField()
    {
        return $this->field;
    }
}
