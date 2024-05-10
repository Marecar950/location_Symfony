<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $marque = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $modele = null;

    /**
     * @var Collection<int, Disponibilite>
     */
    #[ORM\OneToMany(targetEntity: Disponibilite::class, mappedBy: 'vehicule', orphanRemoval: true)]
    #[Ignore]
    private Collection $disponibilities;

    public function __construct()
    {
        $this->disponibilities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(?string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * @return Collection<int, Disponibilite>
     */
    public function getDisponibilities(): Collection
    {
        return $this->disponibilities;
    }

    public function addDisponibility(Disponibilite $disponibility): static
    {
        if (!$this->disponibilities->contains($disponibility)) {
            $this->disponibilities->add($disponibility);
            $disponibility->setVehicule($this);
        }

        return $this;
    }

    public function removeDisponibility(Disponibilite $disponibility): static
    {
        if ($this->disponibilities->removeElement($disponibility)) {
            // set the owning side to null (unless already changed)
            if ($disponibility->getVehicule() === $this) {
                $disponibility->setVehicule(null);
            }
        }

        return $this;
    }
}
