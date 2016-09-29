<?php

namespace Dywee\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * FormResponseContainer
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FormResponseContainer
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
     * @ORM\ManyToOne(targetEntity="CustomForm", inversedBy="responses")
     */
    private $form;

    /**
     * @ORM\OneToMany(targetEntity="FormResponse", mappedBy="responseContainer", cascade={"persist", "remove"})
     */
    private $fieldResponses;

    /**
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="isReaded", type="boolean", nullable=true)
     */
    private $isReaded = false;


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
        $this->fieldResponses = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * Set form
     *
     * @param CustomForm $form
     * @return FormResponseContainer
     */
    public function setForm(CustomForm $form = null)
    {
        $this->form = $form;
        $form->addResponse($this);

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
     * Add fieldResponse
     *
     * @param FormResponse $fieldResponse
     * @return FormResponseContainer
     */
    public function addFieldResponse(FormResponse $fieldResponse)
    {
        $this->fieldResponses[] = $fieldResponse;
        $fieldResponse->setResponseContainer($this);

        return $this;
    }

    /**
     * Remove fieldResponses
     *
     * @param FormResponse $fieldResponses
     */
    public function removeFieldResponse(FormResponse $fieldResponses)
    {
        $this->fieldResponses->removeElement($fieldResponses);
    }

    /**
     * Get fieldResponses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFieldResponses()
    {
        return $this->fieldResponses;
    }

    public function setFromForm(CustomForm $form, $response)
    {
        $this->setForm($form);

        foreach($form->getFields() as $field)
        {
            $fieldResponse = new FormResponse();
            $fieldResponse->setField($field);

            //Dans le cas où le type est un choice
            if($field->getType() === 'select')
            {
                $fieldPossibleResponses = $field->getPossibleValuesArray();
                $responseFragment = $fieldPossibleResponses[$response[$field->getId()]];
            }
            else if($field->getType() === 'checkbox' || $field->getType() === 'radio')
            {
                $responses = array();
                //On récupère les différents choix paramétrés dans l'admin
                $fieldPossibleResponses = $field->getPossibleValuesArray();

                //On ajoute ceux sélectionnés par l'user
                foreach($response[$field->getId()] as $responseFragment)
                    $responses[] = $fieldPossibleResponses[$responseFragment];

                //On convertit array -> string
                $responseFragment = implode(', ', $responses);
                $fieldResponse->setValue($response);
            }
            else $responseFragment = $response[$field->getId()];

            $fieldResponse->setValue($responseFragment);

            $this->addFieldResponse($fieldResponse);
        }

        return $this;
    }

    public function setCreatedAt($date)
    {
        $this->createdAt = $date;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getIsReaded()
    {
        return $this->isReaded;
    }

    public function setIsReaded($isReaded)
    {
        $this->isReaded = $isReaded;
        return $this;
    }
}
