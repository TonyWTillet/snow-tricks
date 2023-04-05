<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alt = null;

    #[ORM\OneToMany(mappedBy: 'default_picture', targetEntity: Trick::class)]
    private Collection $tricks;

    #[ORM\ManyToMany(targetEntity: Trick::class, mappedBy: 'pictures')]
    private Collection $trick_pictures;

    public function __construct()
    {
        $this->tricks = new ArrayCollection();
        $this->trick_pictures = new ArrayCollection();
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

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * @return Collection<int, Trick>
     */
    public function getTricks(): Collection
    {
        return $this->tricks;
    }

    public function addTrick(Trick $trick): self
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks->add($trick);
            $trick->setDefaultPicture($this);
        }

        return $this;
    }

    public function removeTrick(Trick $trick): self
    {
        if ($this->tricks->removeElement($trick)) {
            // set the owning side to null (unless already changed)
            if ($trick->getDefaultPicture() === $this) {
                $trick->setDefaultPicture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Trick>
     */
    public function getTrickPictures(): Collection
    {
        return $this->trick_pictures;
    }

    public function addTrickPicture(Trick $trickPicture): self
    {
        if (!$this->trick_pictures->contains($trickPicture)) {
            $this->trick_pictures->add($trickPicture);
            $trickPicture->addPicture($this);
        }

        return $this;
    }

    public function removeTrickPicture(Trick $trickPicture): self
    {
        if ($this->trick_pictures->removeElement($trickPicture)) {
            $trickPicture->removePicture($this);
        }

        return $this;
    }
}
