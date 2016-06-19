<?php

namespace Dywee\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormFieldPossibleValue
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FormFieldPossibleValue
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="defaultValue", type="boolean", nullable=true)
     */
    private $defaultValue;

    /**
     * @ORM\ManyToOne(targetEntity="FormField", inversedBy="possibleValues")
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
     * Set name
     *
     * @param string $name
     * @return FormFieldPossibleValue
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
     * Set defaultValue
     *
     * @param boolean $defaultValue
     * @return FormFieldPossibleValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * Get defaultValue
     *
     * @return boolean 
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Set parentField
     *
     * @param FormField $parentField
     * @return FormFieldPossibleValue
     */
    public function setParentField(FormField $parentField = null)
    {
        $this->parentField = $parentField;

        return $this;
    }

    /**
     * Get parentField
     *
     * @return FormField
     */
    public function getParentField()
    {
        return $this->parentField;
    }

    /**
     * Set field
     *
     * @param FormField $field
     * @return FormFieldPossibleValue
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
