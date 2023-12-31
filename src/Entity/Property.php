<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\ApiController\PropertyPublishController;
// use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


/**
 * @ORM\Entity(repositoryClass=PropertyRepository::class)
 * @UniqueEntity("title")
 * @Vich\Uploadable
 * 
 * @ApiResource(
 * 
 *  attributes={
 *    "order"={"created_at":"DESC"}
 *  },
 *  normalizationContext={
 *      "groups"={"read:collection", "write:Property"},
 *      "openapi_definition_name" = "Collection"
 *  },
 *  denormalizationContext={"groups"={"write:Property"}},
 *  collectionOperations={
 *      "get",
 *      "post"
 *      },
 *  itemOperations={"get", "put", "delete"}
 *  )
 */
class Property
{
    const HEAT = [
        0 => 'Electrique',
        1 => 'Gaz',
    ];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity _Picture", mappedBy="property", orphanRemoval=true,       cascade={"persist"})
     * @ORM\Column(type="string", length=255)
     * @var string|null
     * 
     * @Groups({"write:Property"})
     */
    private $filename;

    /**
     * @Vich\UploadableField(mapping = "property_image", fileNameProperty = "filename")
     * @var File|null
     * @Assert\Image(mimeTypes="image/jpeg")
     * 
     * @Groups({"write:Property"})
     */
    private $imageFile;


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"read:collection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=5, max=255)
     *
     * @Groups({"read:collection", "write:Property"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Groups({"write:Property"})
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min= 10, max=300)
     *
     * @Groups({"write:Property"})
     */
    private $surface;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"write:Property"})
     */
    private $rooms;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"write:Property"})
     */
    private $bedrooms;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"write:Property"})
     */
    private $floor;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"read:collection","write:Property"})
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({ "write:Property"})
     */
    private $heat;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"write:Property"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"write:Property"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/^[0-9]{5}$/")
     *
     * @Groups({"write:Property"})
     */
    private $postal_code;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     *
     * @Groups({"read:collection", "read:collection"})
     */
    private $sold = false;

    /**
     * @ORM\Column(type="datetime")
     *
     *
     * @Groups({"write:Property"})
     * @ApiProperty(openapiContext={"type" = "boolean", "description" = "Vendu ou pas?"})
     */
    private $created_at;

    /**
     * @ORM\ManyToMany(targetEntity=Option::class, inversedBy="properties", cascade="persist")
     *
     * @Groups({"write:Property", "read:item"})
     * @Assert\Valid(
     *     groups={"create:Property"}
     * )
     */
    private $options;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;


    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->sold = false;
        $this->options = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
    /**
     * @return Slugify 
     */
    public function getSlug()
    {

        return (new Slugify())->slugify($this->title);
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
    public function getSurface(): ?int
    {
        return $this->surface;
    }
    public function setSurface(int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }
    public function getRooms(): ?int
    {
        return $this->rooms;
    }
    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }
    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }
    public function setBedrooms(int $bedrooms): self
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }
    public function getFloor(): ?int
    {
        return $this->floor;
    }
    public function setFloor(int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }
    public function getPrice(): ?int
    {
        return $this->price;
    }
    public function getFormattedPrice(): string
    {
        return number_format($this->price, 0, '', ' ');
    }
    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
    public function getHeat(): ?int
    {
        return $this->heat;
    }
    public function getHeatType(): string
    {
        return self::HEAT[$this->heat];
    }
    public function setHeat(int $heat): self
    {
        $this->heat = $heat;

        return $this;
    }
    public function getCity(): ?string
    {
        return $this->city;
    }
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }
    public function getAddress(): ?string
    {
        return $this->address;
    }
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }
    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }
    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }
    public function getSold(): ?bool
    {
        return $this->sold;
    }
    public function setSold(bool $sold): self
    {
        $this->sold = $sold;

        return $this;
    }
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }
    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addProperty($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->removeElement($option)) {
            $option->removeProperty($this);
        }

        return $this;
    }

    /**
     * Get the value of filename
     *
     * @return  string|null
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the value of filename
     *
     * @param  string|null  $filename
     *
     * @return  self
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get the value of imageFile
     *
     * @return  File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set the value of imageFile
     *
     * @param  File|null  $imageFile
     *
     * @return  self
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;


        return $this;
    }
    public static function validationGroups(self $post)
    {
        return [
            "create:Property",
        ];
    }
}
