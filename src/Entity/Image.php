<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $image_data= null;

    /**
     * @var Collection<int, Habitat>
     */
    #[ORM\ManyToMany(targetEntity: Habitat::class, mappedBy: 'image')]
    private Collection $habitats;

        /**
     * @var Collection<int, Animal>
     */
    #[ORM\ManyToMany(targetEntity: Animal::class, mappedBy: 'image')]
    private Collection $animal;
    public function __construct()
    {
        $this->habitats = new ArrayCollection();
        $this->animal = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageData()
    {
        return $this->image_data;
    }

    public function setImageData($image_data): static
    {
        $this->image_data = $image_data;

        return $this;
    }


    /**
     * @return Collection<int, Animal>
     */
    public function getAnimal(): Collection
    {
        return $this->animal;
    }
    public function addAnimal(Animal $animal): static
    {
        if (!$this->animal->contains($animal)) {
            $this->animal->add($animal);
            $animal->addImage($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): static
    {
        if ($this->animal->removeElement($animal)) {
            $animal->removeImage($this);
        }

        return $this;
    }
    /**
     * @return Collection<int, Habitat>
     */
    public function getHabitats(): Collection
    {
        return $this->habitats;
    }

    public function addHabitat(Habitat $habitat): static
    {
        if (!$this->habitats->contains($habitat)) {
            $this->habitats->add($habitat);
            $habitat->addImage($this);
        }

        return $this;
    }

    public function removeHabitat(Habitat $habitat): static
    {
        if ($this->habitats->removeElement($habitat)) {
            $habitat->removeImage($this);
        }

        return $this;
    }
}
