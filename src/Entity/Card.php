<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CardRepository")
 */
class Card
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $attack;

    /**
     * @ORM\Column(type="integer")
     */
    private $lifePoint;

    /**
     * @ORM\Column(type="integer")
     */
    private $cost;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="cards")
     * @ORM\JoinColumn(nullable=true)addCard
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userCreator;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imgFileName;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Deck", mappedBy="cards")
     */
    private $decks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeckCard", mappedBy="cards")
     */
    private $deckCards;

    public function __construct()
    {
        $this->usersCard = new ArrayCollection();
        $this->decks = new ArrayCollection();
        $this->deckCards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $title): self
    {
        $this->name = $title;

        return $this;
    }

    public function getAttack(): ?int
    {
        return $this->attack;
    }

    public function setAttack(int $attack): self
    {
        $this->attack = $attack;

        return $this;
    }

    public function getLifePoint(): ?int
    {
        return $this->lifePoint;
    }

    public function setLifePoint(int $lifePoint): self
    {
        $this->lifePoint = $lifePoint;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUserCreator(): ?User
    {
        return $this->userCreator;
    }

    public function setUserCreator(?User $userCreator): self
    {
        $this->userCreator = $userCreator;
        return $this;
    }

    public function getImgFileName(): ?string
    {
        return $this->imgFileName;
    }

    public function setImgFileName(string $imgFileName): self
    {
        $this->imgFileName = $imgFileName;

        return $this;
    }

    /**
     * @return Collection|Deck[]
     */
    public function getDecks(): Collection
    {
        return $this->decks;
    }

    public function addDeck(Deck $deck): self
    {
        if (!$this->decks->contains($deck)) {
            $this->decks[] = $deck;
            $deck->addCard($this);
        }

        return $this;
    }

    public function removeDeck(Deck $deck): self
    {
        if ($this->decks->contains($deck)) {
            $this->decks->removeElement($deck);
            $deck->removeCard($this);
        }
        return $this;
    }

    /**
     * @return Collection|DeckCard[]
     */
    public function getDeckCards(): Collection
    {
        return $this->deckCards;
    }

    public function addDeckCard(DeckCard $deckCard): self
    {
        if (!$this->deckCards->contains($deckCard)) {
            $this->deckCards[] = $deckCard;
            $deckCard->setCards($this);
        }

        return $this;
    }

    public function removeDeckCard(DeckCard $deckCard): self
    {
        if ($this->deckCards->contains($deckCard)) {
            $this->deckCards->removeElement($deckCard);
            // set the owning side to null (unless already changed)
            if ($deckCard->getCards() === $this) {
                $deckCard->setCards(null);
            }
        }

        return $this;
    }


}
