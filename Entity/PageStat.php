<?php

namespace Dywee\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PageStat
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Dywee\CMSBundle\Entity\PageStatRepository")
 */
class PageStat
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
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Dywee\CMSBundle\Entity\Page", inversedBy="pageStat")
     */
    private $page;


    /**
     * construct
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PageStat
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
     * Set page
     *
     * @param \Dywee\CMSBundle\Entity\Page $page
     * @return PageStat
     */
    public function setPage(\Dywee\CMSBundle\Entity\Page $page = null)
    {
        $this->page = $page;
        $page->addPageStat($this);

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
