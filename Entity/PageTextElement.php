<?php

namespace Dywee\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dywee\CMSBundle\Entity\PageElement;

/**
 * PageTextElement
 *
 * @ORM\Table(name="page_element_text")
 * @ORM\Entity(repositoryClass="DyweeCustomizer\CMSBundle\Repository\PageTextElementRepository")
 */
class PageTextElement extends PageElement
{
    /**
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * Set content
     *
     * @param string $content
     * @return PageTextElement
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
}
