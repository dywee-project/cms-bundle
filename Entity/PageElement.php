<?php

namespace Dywee\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * PageElement
 *
 * @ORM\Table(name="page_elements")
 * @ORM\Entity(repositoryClass="DyweeCustomizer\CMSBundle\Repository\PageElementRepository")
 */
class PageElement implements Translatable
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
     * @ORM\Column(name="displayOrder", type="smallint", nullable=true)
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;




    public function __construct(){
        $this->addedAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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

    /**
     * Set type
     *
     * @param string $type
     * @return PageElement
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
     * Set content
     *
     * @param string $content
     * @return PageElement
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

    public function trick()
    {
        return null;
    }

    public function getHtml()
    {
        return $this->content;
    }

    public function getTemplate()
    {
        switch($this->type)
        {
            case 'text': return 'DyweeCMSBundle:Render:text.html.twig';
            case 'form': return 'DyweeModuleBundle:Render:form.html.twig';
            case 'musicGallery': return 'DyweeModuleBundle:Render:musicGallery.html.twig'; break;
            case 'carousel': return 'DyweeModuleBundle:Render:carousel.html.twig'; break;
        }
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
