<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\CheatsheetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CheatsheetRepository::class)]
#[ApiResource]
#[GetCollection(
    name: 'get_cheatsheets',
    uriTemplate: '/cheatsheets',
    normalizationContext: ['groups' => ['cheatsheet:get_collection']],
)]
#[Get(
    name: 'get_cheatsheet',
    uriTemplate: '/cheatsheets/{id}',
    normalizationContext: ['groups' => ['cheatsheet:get']],
)]
class Cheatsheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cheatsheet:get_collection', 'cheatsheet:get'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['cheatsheet:get_collection', 'cheatsheet:get'])]
    private ?string $title = null;

    /**
     * @var Collection<int, Section>
     */
    #[ORM\OneToMany(targetEntity: Section::class, mappedBy: 'cheatsheet')]
    #[Groups(['cheatsheet:get'])]
    private Collection $sections;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Section>
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): static
    {
        if (!$this->sections->contains($section)) {
            $this->sections->add($section);
            $section->setCheatsheet($this);
        }

        return $this;
    }

    public function removeSection(Section $section): static
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getCheatsheet() === $this) {
                $section->setCheatsheet(null);
            }
        }

        return $this;
    }
}
