<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Entity\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]

class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'picture', fileNameProperty: 'name')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\OneToOne(mappedBy: 'default_picture', targetEntity: Trick::class)]
    private Collection $tricks;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    private ?Trick $trick = null;


    public function __construct()
    {
        $this->tricks = new ArrayCollection();
        $this->trick_pictures = new ArrayCollection();
        $this->updatedAt = new DateTimeImmutable();
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
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

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getTrick(): ?int
    {
        return $this->trick;
    }

    public function setTrick(int $trick): self
    {
        $this->trick = $trick;

        return $this;
    }
}
