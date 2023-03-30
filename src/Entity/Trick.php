<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
class Trick
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?category $category = null;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    private ?picture $default_picture = null;

    #[ORM\ManyToMany(targetEntity: video::class, inversedBy: 'tricks')]
    private Collection $videos;

    #[ORM\ManyToMany(targetEntity: picture::class, inversedBy: 'trick_pictures')]
    private Collection $pictures;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->pictures = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCategory(): ?category
    {
        return $this->category;
    }

    public function setCategory(?category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    public function getDefaultPicture(): ?picture
    {
        return $this->default_picture;
    }

    public function setDefaultPicture(?picture $default_picture): self
    {
        $this->default_picture = $default_picture;

        return $this;
    }

    /**
     * @return Collection<int, video>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos->add($video);
        }

        return $this;
    }

    public function removeVideo(video $video): self
    {
        $this->videos->removeElement($video);

        return $this;
    }

    /**
     * @return Collection<int, picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
        }

        return $this;
    }

    public function removePicture(picture $picture): self
    {
        $this->pictures->removeElement($picture);

        return $this;
    }
}
