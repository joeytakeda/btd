<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use Nines\UtilBundle\Entity\ContentEntityInterface;
use Nines\UtilBundle\Entity\ContentExcerptTrait;

/**
 * Artwork.
 *
 * @ORM\Table(name="artwork", indexes={
 *  @ORM\Index(columns={"title", "content", "materials", "copyright"}, flags={"fulltext"}),
 * })
 * @ORM\Entity(repositoryClass="App\Repository\ArtworkRepository")
 */
class Artwork extends AbstractEntity implements ContentEntityInterface {
    use ContentExcerptTrait;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $materials;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $copyright;

    /**
     * @var ArtworkCategory
     * @ORM\ManyToOne(targetEntity="ArtworkCategory", inversedBy="artworks")
     */
    private $artworkCategory;

    /**
     * @var ArtworkContribution[]|Collection
     * @ORM\OneToMany(targetEntity="ArtworkContribution", mappedBy="artwork", cascade={"persist"}, orphanRemoval=true)
     */
    private $contributions;

    /**
     * @var ArtisticStatement[]|Collection
     * @ORM\OneToMany(targetEntity="ArtisticStatement", mappedBy="artwork")
     */
    private $artisticStatements;

    /**
     * @var Collection|MediaFile[]
     * @ORM\ManyToMany(targetEntity="MediaFile", inversedBy="artworks")
     * @ORM\JoinTable(name="artwork_mediafiles")
     */
    private $mediaFiles;

    /**
     * @var Collection|Project[]
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="artworks")
     */
    private $projects;

    public function __construct() {
        parent::__construct();
        $this->contributions = new ArrayCollection();
        $this->mediaFiles = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->artisticStatements = new ArrayCollection();
    }

    public function __toString() {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Artwork
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set materials.
     *
     * @param string $materials
     *
     * @return Artwork
     */
    public function setMaterials($materials) {
        $this->materials = $materials;

        return $this;
    }

    /**
     * Get materials.
     *
     * @return string
     */
    public function getMaterials() {
        return $this->materials;
    }

    /**
     * Set copyright.
     *
     * @param string $copyright
     *
     * @return Artwork
     */
    public function setCopyright($copyright) {
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * Get copyright.
     *
     * @return string
     */
    public function getCopyright() {
        return $this->copyright;
    }

    /**
     * Add contribution.
     *
     * @param ArtworkContribution $contribution
     *
     * @return Artwork
     */
    public function addContribution(ArtworkContribution $contribution) {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution.
     *
     * @param ArtworkContribution $contribution
     */
    public function removeContribution(ArtworkContribution $contribution) {
        $this->contributions->removeElement($contribution);
    }

    /**
     * Get contributions.
     *
     * @return Collection
     */
    public function getContributions() {
        return $this->contributions;
    }

    public function hasMediaFile(MediaFile $mediaFile) {
        return $this->mediaFiles->contains($mediaFile);
    }

    /**
     * Add mediaFile.
     *
     * @param MediaFile $mediaFile
     *
     * @return Artwork
     */
    public function addMediaFile(MediaFile $mediaFile) {
        if ( ! $this->mediaFiles->contains($mediaFile)) {
            $this->mediaFiles[] = $mediaFile;
        }

        return $this;
    }

    /**
     * Remove mediaFile.
     *
     * @param MediaFile $mediaFile
     */
    public function removeMediaFile(MediaFile $mediaFile) {
        $this->mediaFiles->removeElement($mediaFile);
    }

    /**
     * Get mediaFiles.
     *
     * @return Collection
     */
    public function getMediaFiles() {
        return $this->mediaFiles;
    }

    /**
     * Add project.
     *
     * @param \App\Entity\Project $project
     *
     * @return Artwork
     */
    public function addProject(Project $project) {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project.
     *
     * @param \App\Entity\Project $project
     */
    public function removeProject(Project $project) {
        $this->projects->removeElement($project);
    }

    /**
     * Get projects.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects() {
        return $this->projects;
    }

    /**
     * Set artworkCategory.
     *
     * @param \App\Entity\ArtworkCategory $artworkCategory
     *
     * @return Artwork
     */
    public function setArtworkCategory(ArtworkCategory $artworkCategory = null) {
        $this->artworkCategory = $artworkCategory;

        return $this;
    }

    /**
     * Get artworkCategory.
     *
     * @return \App\Entity\ArtworkCategory
     */
    public function getArtworkCategory() {
        return $this->artworkCategory;
    }

    /**
     * Add artisticStatement.
     *
     * @param \App\Entity\ArtisticStatement $artisticStatement
     *
     * @return Artwork
     */
    public function addArtisticStatement(ArtisticStatement $artisticStatement) {
        $this->artisticStatements[] = $artisticStatement;

        return $this;
    }

    /**
     * Remove artisticStatement.
     *
     * @param \App\Entity\ArtisticStatement $artisticStatement
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeArtisticStatement(ArtisticStatement $artisticStatement) {
        return $this->artisticStatements->removeElement($artisticStatement);
    }

    /**
     * Get artisticStatements.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArtisticStatements() {
        return $this->artisticStatements;
    }
}
