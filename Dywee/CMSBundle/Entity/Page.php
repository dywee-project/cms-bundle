<?php

namespace Dywee\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * Page
 *
 * @ORM\Table(name="pages")
 * @ORM\Entity(repositoryClass="Dywee\CMSBundle\Entity\PageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Page
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
     * @ORM\Column(name="idSite", type="smallint")
     */
    private $idSite;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type = 2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="inMenu", type="boolean")
     */
    private $inMenu;

    /**
     * @var integer
     *
     * @ORM\Column(name="menuOrder", type="smallint", nullable=true)
     */
    private $menuOrder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="smallint")
     */
    private $state;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=255)
     *
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="content", type="text", nullable = true)
     */
    private $content;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="metaTitle", type="string", length=255, nullable = true)
     */
    private $metaTitle;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="metaDescription", type="text", nullable = true)
     */
    private $metaDescription;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="metaKeywords", type="text", nullable = true)
     */
    private $metaKeywords;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="seoUrl", type="string", length=255, nullable = true)
     */
    private $seoUrl;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="menuName", type="string", length=255, nullable = true)
     */
    private $menuName;

    /**
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updateDate", type="datetime")
     */
    private $updateDate;

    /**
     * @ORM\ManyToOne(targetEntity="Dywee\CMSBundle\Entity\Page", inversedBy="childs")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Dywee\CMSBundle\Entity\Page", mappedBy="parent")
     */
    private $childs;

    /**
     * @ORM\Column(name="childArguments", type="string", length=255, nullable=true)
     */
    private $childArguments;

    /**
     * @ORM\Column(name="template", type="string", length=255)
     */
    private $template = 'default';

    /**
     * @ORM\ManyToOne(targetEntity="Dywee\UserBundle\Entity\User")
     */
    private $updatedBy;

    /**
     * @ORM\OneToMany(targetEntity="Dywee\CMSBundle\Entity\PageStat", mappedBy="page")
     */
    private $pageStat;


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
     * Set type
     *
     * @param integer $type
     * @return Page
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set inMenu
     *
     * @param boolean $inMenu
     * @return Page
     */
    public function setInMenu($inMenu)
    {
        $this->inMenu = $inMenu;

        return $this;
    }

    /**
     * Get inMenu
     *
     * @return boolean 
     */
    public function getInMenu()
    {
        return $this->inMenu;
    }

    /**
     * Set menuOrder
     *
     * @param integer $menuOrder
     * @return Page
     */
    public function setMenuOrder($menuOrder)
    {
        $this->menuOrder = $menuOrder;

        return $this;
    }

    /**
     * Get menuOrder
     *
     * @return integer 
     */
    public function getMenuOrder()
    {
        return $this->menuOrder;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Page
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Page
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set state
     *
     * @param integer $state
     * @return Page
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer 
     */
    public function getState()
    {
        return $this->state;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->updateDate = new \DateTime();
        $this->createdAt = new \DateTime();
    }

    /**
     * Set idSite
     *
     * @param integer $idSite
     * @return Page
     */
    public function setIdSite($idSite)
    {
        $this->idSite = $idSite;

        return $this;
    }

    /**
     * Get idSite
     *
     * @return integer 
     */
    public function getIdSite()
    {
        return $this->idSite;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Page
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
     * Set content
     *
     * @param string $content
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     * @return Page
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string 
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return Page
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     * @return Page
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string 
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set seoUrl
     *
     * @param string $seoUrl
     * @return Page
     */
    public function setSeoUrl($seoUrl)
    {
        $this->seoUrl = $seoUrl;

        return $this;
    }

    /**
     * Get seoUrl
     *
     * @return string 
     */
    public function getSeoUrl()
    {
        return $this->seoUrl;
    }

    /**
     * Set menuName
     *
     * @param string $menuName
     * @return Page
     */
    public function setMenuName($menuName)
    {
        $this->menuName = $menuName;

        return $this;
    }

    /**
     * Get menuName
     *
     * @return string 
     */
    public function getMenuName()
    {
        return $this->menuName;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return Page
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    //Nouvelle norme des dates
    public function setUpdatedAt($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    //Nouvelle norme des dates
    public function getUpdatedAt()
    {
        return $this->updateDate;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateUpdateDate()
    {
        $this->setUpdateDate(new \Datetime());
    }

    public function getUrl()
    {
        return $this->getSeoUrl() != ''?$this->getSeoUrl():$this->getId();
    }

    /**
     * Set parent
     *
     * @param \Dywee\CMSBundle\Entity\Page $parent
     * @return Page
     */
    public function setParent(\Dywee\CMSBundle\Entity\Page $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Dywee\CMSBundle\Entity\Page
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add childs
     *
     * @param \Dywee\CMSBundle\Entity\Page $childs
     * @return Page
     */
    public function addChild(\Dywee\CMSBundle\Entity\Page $childs)
    {
        $this->childs[] = $childs;
        $childs->setParent($this);

        return $this;
    }

    /**
     * Remove childs
     *
     * @param \Dywee\CMSBundle\Entity\Page $childs
     */
    public function removeChild(\Dywee\CMSBundle\Entity\Page $childs)
    {
        $this->childs->removeElement($childs);
    }

    /**
     * Get childs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Set childArguments
     *
     * @param string $childArguments
     * @return Page
     */
    public function setChildArguments($childArguments)
    {
        $this->childArguments = $childArguments;

        return $this;
    }

    /**
     * Get childArguments
     *
     * @return string 
     */
    public function getChildArguments()
    {
        return $this->childArguments;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Page
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
     * Set template
     *
     * @param string $template
     *
     * @return Page
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set updatedBy
     *
     * @param \Dywee\UserBundle\Entity\User $updatedBy
     * @return Page
     */
    public function setUpdatedBy(\Dywee\UserBundle\Entity\User $updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \Dywee\UserBundle\Entity\User 
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Add pageStat
     *
     * @param \Dywee\CMSBundle\Entity\PageStat $pageStat
     * @return Page
     */
    public function addPageStat(\Dywee\CMSBundle\Entity\PageStat $pageStat)
    {
        $this->pageStat[] = $pageStat;

        return $this;
    }

    /**
     * Remove pageStat
     *
     * @param \Dywee\CMSBundle\Entity\PageStat $pageStat
     */
    public function removePageStat(\Dywee\CMSBundle\Entity\PageStat $pageStat)
    {
        $this->pageStat->removeElement($pageStat);
    }

    /**
     * Get pageStat
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPageStat()
    {
        return $this->pageStat;
    }
}