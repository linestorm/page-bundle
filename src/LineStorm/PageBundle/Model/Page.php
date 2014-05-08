<?php

namespace LineStorm\PageBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LineStorm\ArticleComponentBundle\Model\Article;
use LineStorm\Content\Model\ContentNodeInterface;
use LineStorm\GalleryComponentBundle\Model\Gallery;
use LineStorm\MediaBundle\Model\Media;
use LineStorm\TagComponentBundle\Model\Tag;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Page Model
 *
 * Class Page
 *
 * @package LineStorm\PageBundle\Model
 */
abstract class Page implements ContentNodeInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * @var \DateTime
     */
    protected $liveOn;

    /**
     * @var \DateTime
     */
    protected $deletedOn;

    /**
     * @var \DateTime
     */
    protected $editedOn;

    /**
     * @var UserInterface
     */
    protected $author;

    /**
     * @var UserInterface
     */
    protected $editedBy;

    /**
     * @var Collection
     */
    protected $tags;

    /**
     * @var UserInterface
     */
    protected $deletedBy;

    /**
     * @var Article[]
     */
    protected $articles;

    /**
     * @var Gallery[]
     */
    protected $galleries;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $blurb;

    /**
     * @var string
     */
    protected $metaDescription;

    /**
     * @var string
     */
    protected $metaKeywords;

    /**
     * @var Media
     */
    protected $coverImage;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->galleries = new ArrayCollection();

        $now = new \DateTime();
        $this->liveOn = $now;
        $this->createdOn = $now;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if($this->createdOn === null)
            $this->createdOn = new \DateTime();
        else
            $this->editedOn = new \DateTime();
    }

    /**
     * Get the slug. If it's not set, generate it from the title
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug ?: strtolower(str_replace(' ', '-', $this->title));
    }
    public function getRealSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
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
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     * @return self
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    
        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime 
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set editedOn
     *
     * @param \DateTime $editedOn
     * @return self
     */
    public function setEditedOn($editedOn)
    {
        $this->editedOn = $editedOn;
    
        return $this;
    }

    /**
     * Get editedOn
     *
     * @return \DateTime 
     */
    public function getEditedOn()
    {
        return $this->editedOn;
    }

    /**
     * Set liveOn
     *
     * @param \DateTime $liveOn
     * @return self
     */
    public function setLiveOn($liveOn)
    {
        $this->liveOn = $liveOn;
    
        return $this;
    }

    /**
     * Get liveOn
     *
     * @return \DateTime 
     */
    public function getLiveOn()
    {
        return $this->liveOn;
    }

    /**
     * Set deletedOn
     *
     * @param \DateTime $deletedOn
     * @return self
     */
    public function setDeletedOn($deletedOn)
    {
        $this->deletedOn = $deletedOn;
    
        return $this;
    }

    /**
     * Get deletedOn
     *
     * @return \DateTime 
     */
    public function getDeletedOn()
    {
        return $this->deletedOn;
    }

    /**
     * Set author
     *
     * @param UserInterface $author
     * @return self
     */
    public function setAuthor(UserInterface $author = null)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return \FOS\UserBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set editedBy
     *
     * @param UserInterface $editedBy
     * @return self
     */
    public function setEditedBy(UserInterface $editedBy = null)
    {
        $this->editedBy = $editedBy;
    
        return $this;
    }

    /**
     * Get editedBy
     *
     * @return \FOS\UserBundle\Entity\User
     */
    public function getEditedBy()
    {
        return $this->editedBy;
    }

    /**
     * Add tags
     *
     * @param Tag $tags
     * @return self
     */
    public function addTag(Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param Tag $tags
     */
    public function removeTag(Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set deletedBy
     *
     * @param UserInterface $deletedBy
     * @return self
     */
    public function setDeletedBy(UserInterface $deletedBy = null)
    {
        $this->deletedBy = $deletedBy;
    
        return $this;
    }

    /**
     * Get deletedBy
     *
     * @return \FOS\UserBundle\Entity\User
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

    /**
     * Add articles
     *
     * @param Article $articles
     * @return self
     */
    public function addArticle(Article $articles)
    {
        $this->articles[] = $articles;
        $articles->setContentNode($this);

        return $this;
    }

    /**
     * Remove articles
     *
     * @param Article $articles
     */
    public function removeArticle(Article $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Check if an article exists in the page
     *
     * @param Article $article
     *
     * @return bool
     */
    public function hasArticle(Article $article = null)
    {
        return $this->articles->contains($article);
    }

    /**
     * Add galleries
     *
     * @param Gallery $galleries
     * @return self
     */
    public function addGallery(Gallery $galleries)
    {
        $this->galleries[] = $galleries;
        $galleries->setContentNode($this);

        return $this;
    }

    /**
     * Remove galleries
     *
     * @param Gallery $galleries
     */
    public function removeGallery(Gallery $galleries)
    {
        $this->galleries->removeElement($galleries);
    }

    /**
     * Get galleries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGalleries()
    {
        return $this->galleries;
    }

    /**
     * @param string $blurb
     */
    public function setBlurb($blurb)
    {
        $this->blurb = $blurb;
    }

    /**
     * @return string
     */
    public function getBlurb()
    {
        return $this->blurb;
    }

    /**
     * @param string $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaKeywords
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param Media $coverImage
     */
    public function setCoverImage(Media $coverImage)
    {
        $this->coverImage = $coverImage;
    }

    /**
     * @return Media
     */
    public function getCoverImage()
    {
        return $this->coverImage;
    }



}
