<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Service\AuditTrail\AuditrailableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email", message="Un utilisateur ayant cette adresse mail existe déjà")
 * @Vich\Uploadable
 */
class User implements UserInterface,  \Serializable, AuditrailableInterface
{
    public const CIVILITY_MR  = 1;
    public const CIVILITY_MME = 2;
    public const CIVILITY     = [
        self::CIVILITY_MR  => 'M.',
        self::CIVILITY_MME => 'Mme',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(message="Le format de l'adresse email doit être valide")
     * @Assert\NotBlank(message="L'adresse email de l'utilisateur est obligatoire")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotBlank(message="Le nom de l'utilisateur est obligatoire")
     * @Assert\Length(min=3, minMessage="Le prenom de l'utilisateur doit faire entre 3 et 255 caractères", max=255, maxMessage="Le prenom de l'utilisateur projet doit faire entre 3 et 255 caractères")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotBlank(message="Le nom de l'utilisateur est obligatoire")
     * @Assert\Length(min=3, minMessage="Le nom de l'utilisateur doit faire entre 3 et 255 caractères", max=255, maxMessage="Le nom de l'utilisateur projet doit faire entre 3 et 255 caractères")
     */
    private $lastName;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSuperAdmin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 10, max = 10, minMessage = "Minimum 10 chiffres", maxMessage = "Maximun 10 chiffres")
     * @Assert\Regex(pattern="/^[0-9]*$/", message="Le numéro de téléphone doit avoir exactement 10 chiffres")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $civility;

    /**
     * @var File
     * @Vich\UploadableField(mapping="user_image" , fileNameProperty="filename")
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity=UserJob::class, inversedBy="users")
     */
    private $job;

    /**
     * @ORM\ManyToOne(targetEntity=Profile::class, inversedBy="users")
     * @var Profile
     */
    private $profile;

    /**
     * @ORM\OneToMany(targetEntity=PresedentWord::class, mappedBy="user")
     */
    private $presedentWords;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="user")
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity=Newslatter::class, mappedBy="user")
     */
    private $newslatters;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="children")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity=TeamMTH::class, mappedBy="user")
     */
    private $teamMTHs;

    /**
     * @ORM\OneToMany(targetEntity=Takeknowledge::class, mappedBy="user")
     */
    private $takeknowledges;


    public function __construct()
    {
        $this->presedentWords = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->newslatters = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->teamMTHs = new ArrayCollection();
        $this->takeknowledges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->profile->getSecurity();
        if ($this->isSuperAdmin) {
            $roles[] = 'ROLE_SUPER_ADMIN';
        }
        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getIsSuperAdmin(): ?bool
    {
        return $this->isSuperAdmin;
    }

    public function setIsSuperAdmin(?bool $isSuperAdmin): self
    {
        $this->isSuperAdmin = $isSuperAdmin;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }


    public function setImageFile(?File $imageFile): User
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile){
            $this->updatedAt =new \DateTime();
        }
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCivility(): ?string
    {
        return $this->civility;
    }

    public function setCivility(string $civility): void
    {
        if (!array_key_exists($civility, self::CIVILITY) && $civility !== null) {
            throw new \Exception('civilité' .$civility. 'n\'est pas authorisé!');
        }

        $this->civility = $civility;
    }

   public function getJob(): ?UserJob
    {
        return $this->job;
    }

    public function setJob(?UserJob $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            ) = unserialize($serialized);
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * @return Collection|PresedentWord[]
     */
    public function getPresedentWords(): Collection
    {
        return $this->presedentWords;
    }

    public function addPresedentWord(PresedentWord $presedentWord): self
    {
        if (!$this->presedentWords->contains($presedentWord)) {
            $this->presedentWords[] = $presedentWord;
            $presedentWord->setUser($this);
        }

        return $this;
    }

    public function removePresedentWord(PresedentWord $presedentWord): self
    {
        if ($this->presedentWords->removeElement($presedentWord)) {
            // set the owning side to null (unless already changed)
            if ($presedentWord->getUser() === $this) {
                $presedentWord->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setUser($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getUser() === $this) {
                $video->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Newslatter[]
     */
    public function getNewslatters(): Collection
    {
        return $this->newslatters;
    }

    public function addNewslatter(Newslatter $newslatter): self
    {
        if (!$this->newslatters->contains($newslatter)) {
            $this->newslatters[] = $newslatter;
            $newslatter->setUser($this);
        }

        return $this;
    }

    public function removeNewslatter(Newslatter $newslatter): self
    {
        if ($this->newslatters->removeElement($newslatter)) {
            // set the owning side to null (unless already changed)
            if ($newslatter->getUser() === $this) {
                $newslatter->setUser(null);
            }
        }

        return $this;
    }

    public function getFieldsToBeIgnored(): array
    {
        return ['presedentWords', 'profile', 'videos', 'job','parent'];
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public  function level2($level)
    {
        if($this->getParent()==null){
            return $level;
        }
        else{
            $level=$level+1;
            return $this->level2($this->getParent(),$level);
        }

        return $level;
    }

    /**
     * @return Collection|TeamMTH[]
     */
    public function getTeamMTHs(): Collection
    {
        return $this->teamMTHs;
    }

    public function addTeamMTH(TeamMTH $teamMTH): self
    {
        if (!$this->teamMTHs->contains($teamMTH)) {
            $this->teamMTHs[] = $teamMTH;
            $teamMTH->setUser($this);
        }

        return $this;
    }

    public function removeTeamMTH(TeamMTH $teamMTH): self
    {
        if ($this->teamMTHs->removeElement($teamMTH)) {
            // set the owning side to null (unless already changed)
            if ($teamMTH->getUser() === $this) {
                $teamMTH->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Takeknowledge[]
     */
    public function getTakeknowledges(): Collection
    {
        return $this->takeknowledges;
    }

    public function addTakeknowledge(Takeknowledge $takeknowledge): self
    {
        if (!$this->takeknowledges->contains($takeknowledge)) {
            $this->takeknowledges[] = $takeknowledge;
            $takeknowledge->setUser($this);
        }

        return $this;
    }

    public function removeTakeknowledge(Takeknowledge $takeknowledge): self
    {
        if ($this->takeknowledges->removeElement($takeknowledge)) {
            // set the owning side to null (unless already changed)
            if ($takeknowledge->getUser() === $this) {
                $takeknowledge->setUser(null);
            }
        }

        return $this;
    }
}
