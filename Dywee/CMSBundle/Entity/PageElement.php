<?php

namespace Dywee\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PageElement
 *
 * @ORM\Table(name="page_elements")
 * @ORM\Entity(repositoryClass="DyweeCustomizer\CMSBundle\Entity\PageElementRepository")
 */
class PageElement
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
     * @var integer
     *
     * @ORM\Column(name="displayOrder", type="smallint")
     */
    private $displayOrder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="addedAt", type="datetime")
     */
    private $addedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Dywee\CMSBundle\Entity\Page", inversedBy="pageElements")
     */
    private $page;


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
     * Set displayOrder
     *
     * @param integer $displayOrder
     * @return PageElement
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    /**
     * Get displayOrder
     *
     * @return integer 
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * Set addedAt
     *
     * @param \DateTime $addedAt
     * @return PageElement
     */
    public function setAddedAt($addedAt)
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    /**
     * Get addedAt
     *
     * @return \DateTime 
     */
    public function getAddedAt()
    {
        return $this->addedAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return PageElement
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
     * Set page
     *
     * @param \Dywee\CMSBundle\Entity\Page $page
     * @return PageElement
     */
    public function setPage(\Dywee\CMSBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Dywee\CMSBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }
}
