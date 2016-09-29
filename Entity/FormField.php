<?php

namespace Dywee\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Dywee\CMSBundle\Entity\FormFieldPossibleValue;

/**
 * FormField
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FormField
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
     * @ORM\Column(name="class", type="string", length=255, nullable=true)
     */
    private $class;

    /**
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="htmlId", type="string", length=255, nullable=true)
     */
    private $htmlId;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    private $value = null;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="placeholder", type="string", length=255, nullable=true)
     */
    private $placeholder;

    /**
     * @ORM\Column(name="required", type="boolean")
     */
    private $required = false;

    /**
     * @ORM\ManyToOne(targetEntity="CustomForm", inversedBy="formFields")
     */
    private $form;

    /**
     * @ORM\OneToMany(targetEntity="FormFieldPossibleValue", mappedBy="field", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $possibleValues;

    private $possibleValuesText;


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
     * Set class
     *
     * @param string $class
     * @return FormField
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string 
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set htmlId
     *
     * @param string $htmlId
     * @return FormField
     */
    public function setHtmlId($htmlId)
    {
        $this->htmlId = $htmlId;

        return $this;
    }

    /**
     * Get htmlId
     *
     * @return string 
     */
    public function getHtmlId()
    {
        return $this->htmlId;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return FormField
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
     * Set type
     *
     * @param string $type
     * @return FormField
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return FormField
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
     * Set placeholder
     *
     * @param string $placeholder
     * @return FormField
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get placeholder
     *
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder != null ? $this->placeholder : $this->getLabel();
    }

    /**
     * Set required
     *
     * @param boolean $required
     * @return FormField
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get required
     *
     * @return boolean 
     */
    public function getRequired()
    {
        return $this->required;
    }
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return FormField
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set form
     *
     * @param CustomForm $form
     * @return FormField
     */
    public function setForm(CustomForm $form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get form
     *
     * @return CustomForm
     */
    public function getForm()
    {
        return $this->form;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->possibleValues = new ArrayCollection();
    }

    /**
     * Add possibleValues
     *
     * @param FormFieldPossibleValue $possibleValue
     * @return FormField
     */
    public function addPossibleValue(FormFieldPossibleValue $possibleValue)
    {
        $this->possibleValues[] = $possibleValue;
        $possibleValue->setField($this);

        return $this;
    }

    /**
     * Remove possibleValues
     *
     * @param FormFieldPossibleValue $possibleValue
     */
    public function removePossibleValue(FormFieldPossibleValue $possibleValue)
    {
        $this->possibleValues->removeElement($possibleValue);
    }

    /**
     * Get possibleValues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPossibleValues()
    {
        return $this->possibleValues;
    }

    public function setPossibleValuesText($possibleValues)
    {
        foreach($this->getPossibleValues() as $pv)
            $this->removePossibleValue($pv);

        if($possibleValues !== '')
        {
            $possibleValues = explode(', ', $possibleValues);
            foreach($possibleValues as $pv)
            {
                $possibleValue = new FormFieldPossibleValue();
                $possibleValue->setName($pv);
                $this->addPossibleValue($possibleValue);
            }
        }

        return $this;
    }

    public function getPossibleValuesText()
    {
        $possibleValues = $this->getPossibleValuesArray();

        if(count($possibleValues) > 0)
            return implode(', ', $possibleValues);
        else return null;
    }

    public function getPossibleValuesArray()
    {
        $possibleValues = null;
        foreach($this->getPossibleValues() as $pv)
            $possibleValues[] = $pv->getName();

        return $possibleValues;
    }
}
