<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $username = null;

    #[ORM\Column(length: 255)] // Augmenté pour le mot de passe
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;
    #[ORM\Column(type: 'json')]
    private array $roles = [];
    
    /**
     * @var Collection<int, RapportVeterinaire>
     */
    #[ORM\OneToMany(targetEntity: RapportVeterinaire::class, mappedBy: 'utilisateur')]
    private Collection $rapportVeterinaire;

    public function __construct()
    {
        $this->rapportVeterinaire = new ArrayCollection();
    }

    public function getRoles(): array
{
    // Retourne les rôles de l'utilisateur
    return $this->roles;
}

public function setRoles(array $roles): static
{
    $this->roles = $roles;
    return $this;
}

// Méthode pour ajouter un rôle spécifique
public function addRole(string $role): static
{
    if (!in_array($role, $this->roles, true)) {
        $this->roles[] = $role;
    }
    return $this;
}


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->username; // Utilisez l'identifiant unique (username)
    }

    public function eraseCredentials(): void
    {
        // Ne rien faire pour l'instant
    }

    /**
     * @return Collection<int, RapportVeterinaire>
     */
    public function getRapportVeterinaire(): Collection
    {
        return $this->rapportVeterinaire;
    }

    public function addRapportVeterinaire(RapportVeterinaire $rapportVeterinaire): static
    {
        if (!$this->rapportVeterinaire->contains($rapportVeterinaire)) {
            $this->rapportVeterinaire->add($rapportVeterinaire);
            $rapportVeterinaire->setUtilisateur($this);
        }

        return $this;
    }

    public function removeRapportVeterinaire(RapportVeterinaire $rapportVeterinaire): static
    {
        if ($this->rapportVeterinaire->removeElement($rapportVeterinaire)) {
            // set the owning side to null (unless already changed)
            if ($rapportVeterinaire->getUtilisateur() === $this) {
                $rapportVeterinaire->setUtilisateur(null);
            }
        }

        return $this;
    }
}
