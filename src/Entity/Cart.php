<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{

//- ID du Panier: identifiant numérique unique pour identifier chaque panier.
//- ID du Client: identifiant numérique permettant de lier chaque panier à un client particulier.
//- ID du Produit: identifiant numérique indiquant quels produits sont associés au panier.
//- Quantité: nombre de produits associés à chaque panier.
//- Prix Total: le total des prix des produits associés au panier.
//- Date d'achat: la date à laquelle le panier a été acheté.
//- Statut du paiement: indique si le panier a été payé ou non.



    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    private ?Owner $owner = null;

    #[ORM\OneToMany(mappedBy: 'ownerCart', targetEntity: OwnerLivraison::class)]
    private Collection $ownerLivraisons;

    public function __construct()
    {
        $this->ownerLivraisons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    public function setOwner(?Owner $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, OwnerLivraison>
     */
    public function getOwnerLivraisons(): Collection
    {
        return $this->ownerLivraisons;
    }

    public function addOwnerLivraison(OwnerLivraison $ownerLivraison): self
    {
        if (!$this->ownerLivraisons->contains($ownerLivraison)) {
            $this->ownerLivraisons->add($ownerLivraison);
            $ownerLivraison->setOwnerCart($this);
        }

        return $this;
    }

    public function removeOwnerLivraison(OwnerLivraison $ownerLivraison): self
    {
        if ($this->ownerLivraisons->removeElement($ownerLivraison)) {
            // set the owning side to null (unless already changed)
            if ($ownerLivraison->getOwnerCart() === $this) {
                $ownerLivraison->setOwnerCart(null);
            }
        }

        return $this;
    }
}
