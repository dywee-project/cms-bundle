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
    private $customForm;

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
        $this->customForm = $form;
        $form->addResponse($this);

        return $this;
    }

    /**
     * Alias of setForm(CustomForm $form)
     *
     * @param CustomForm|null $form
     *
     * @return FormResponseContainer
     */
    public function setCustomForm(CustomForm $form = null)
    {
        return $this->setForm($form);
    }

    /**
     * Get form
     *
     * @return CustomForm
     */
    public function getForm()
    {
        return $this->customForm;
    }

    /**
     * Alias of getForm()
     *
     * @return CustomForm
     */
    public function getCustomForm()
    {
        return $this->getForm();
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
     * @return \Doctrine\Common\Collections\Collection|FormResponse[]
     */
    public function getFieldResponses()
    {
        return $this->fieldResponses;
    }

    /**
     * @param CustomForm $form
     * @param            $response
     *
     * @return $this
     */
    public function setFromForm(CustomForm $form, $response)
    {
        $this->setForm($form);

        foreach ($form->getFields() as $field) {
            $fieldResponse = new FormResponse();
            $fieldResponse->setField($field);

            //Dans le cas où le type est un choice
            switch ($field->getType()) {
                case 'select':
                    $fieldPossibleResponses = $field->getPossibleValuesArray();
                    $responseFragment = $fieldPossibleResponses[$response[$field->getId()]];
                    break;

                case 'checkbox':
                case 'radio':
                    $responses = [];
                    //On récupère les différents choix paramétrés dans l'admin
                    $fieldPossibleResponses = $field->getPossibleValuesArray();

                    //On ajoute ceux sélectionnés par l'user
                    foreach ($response[$field->getId()] as $responseFragment) {
                        $responses[] = $fieldPossibleResponses[$responseFragment];
                    }

                    //On convertit array -> string
                    $responseFragment = implode(', ', $responses);
                    $fieldResponse->setValue($response);
                    break;

                default:
                    $responseFragment = $response[$field->getId()];
            }

            $fieldResponse->setValue($responseFragment);

            $this->addFieldResponse($fieldResponse);
        }

        return $this;
    }

    /**
     * @param \DateTime $date
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $date)
    {
        $this->createdAt = $date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return bool
     */
    public function getIsReaded()
    {
        return $this->isReaded;
    }

    /**
     * @param $isReaded
     *
     * @return $this
     */
    public function setIsReaded($isReaded)
    {
        $this->isReaded = $isReaded;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParentEntity()
    {
        return $this->customForm ?? CustomForm::class;
    }
}
