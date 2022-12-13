<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Publicite::class)]
    private Collection $publicites;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Annonce::class)]
    private Collection $dateDepot;

    public function __construct()
    {
        $this->publicites = new ArrayCollection();
        $this->dateDepot = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * @return Collection<int, Publicite>
     */
    public function getPublicites(): Collection
    {
        return $this->publicites;
    }

    public function addPublicite(Publicite $publicite): self
    {
        if (!$this->publicites->contains($publicite)) {
            $this->publicites->add($publicite);
            $publicite->setCategorie($this);
        }

        return $this;
    }

    public function removePublicite(Publicite $publicite): self
    {
        if ($this->publicites->removeElement($publicite)) {
            // set the owning side to null (unless already changed)
            if ($publicite->getCategorie() === $this) {
                $publicite->setCategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Annonce>
     */
    public function getDateDepot(): Collection
    {
        return $this->dateDepot;
    }

    public function addDateDepot(Annonce $dateDepot): self
    {
        if (!$this->dateDepot->contains($dateDepot)) {
            $this->dateDepot->add($dateDepot);
            $dateDepot->setCategorie($this);
        }

        return $this;
    }

    public function removeDateDepot(Annonce $dateDepot): self
    {
        if ($this->dateDepot->removeElement($dateDepot)) {
            // set the owning side to null (unless already changed)
            if ($dateDepot->getCategorie() === $this) {
                $dateDepot->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
        return $this->name;

    }
}
