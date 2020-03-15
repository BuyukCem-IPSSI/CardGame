<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Entity\DeckCard;
use App\Form\DeckType;
use App\Repository\CardRepository;
use App\Repository\DeckRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class DeckController
 * @package App\Controller
 */
class DeckController extends AbstractController
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	/**
	 * @Route("/deck", name="myDeck")
	 *
	 * @param DeckRepository $Deck
	 * @param Request $request
	 *
	 * @return Response
	 */
    public function index(DeckRepository $Deck, Request $request):Response
    {
		$DeckList=  $Deck->findAll();

		$deck = new Deck();
		$form = $this->createForm(DeckType::class, $deck);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$deck->setUser($this->getUser());
			$this->em->persist($deck);
			$this->em->flush();

			$this->addFlash('success', 'Deck created.');
		}

        return $this->render('deck/index.html.twig', [
            'DeckList' => $DeckList,'form' => $form->createView()
        ]);
    }
	/**
	 * @Route("/deck/new", name="deck_new")
	 * @param Request $request
	 *
	 * @return Response
	 */
    public function addDeck(Request $request): Response
	{
		$deck=$this->deck;
		$form = $this->createForm(DeckType::class, $deck);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$deck->setUserCreator($this->getUser());
			$this->em->persist($deck);
			$this->em->flush();

			$this->addFlash('success', 'Deck created.');
		}

		return $this->render('Deck/index.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/deck/manage", name="manageDeck")
	 * @param CardRepository $cardRepository
	 *
	 * @param Deck $deck
	 * @param Request $request
	 *
	 * @return Response
	 * @ParamConverter("deck", options={"id"="id"})
	 */

	public function deckManage(CardRepository $cardRepository, Deck $deck, Request $request): Response
	{
		$cards = $this->cardRepository->findAll();
		$deckCards = $this->deckCardRepository->findBy(['deck' => $deck]);

		$form = $this->createForm(DeckType::class, $deck)
			->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$deck->setUser($this->getUser());

			$this->manager->persist($deck);
			$this->manager->flush();
		}
		return $this->render('deck/manage.html.twig', [
			'deckCards' => $deckCards,
			'deck' => $deck,
			'cards' => $cards,
			'form' => $form->createView(),
		]);

	}

	/**
	 * @Route("/deck/manage", name="addToDeck")
	 * @param Request $request
	 * @return Response
	 */
	public function  AddCardToDeck(Request $request):Response
	{
		if($request->isXmlHttpRequest()) {
			$idDeck = $request->get('idDeck');
			$idCard = $request->get('idCard');

			$cardDeck = new DeckCard();
			$deck = $this->DeckRepository->findOneBy(['id' => $idDeck]);
			$card = $this->CardRepository->findOneBy(['id' => $idCard]);

			$cardDeck->setCards($card);
			$cardDeck->setDeck($deck);

			$this->manager->persist($cardDeck);
			$this->manager->flush();
		}
	}

	/**
	 * @Route("/deck/delete/{id}", name="deckDelete")
	 * @ParamConverter("Deck", options={"id"="id"})
	 * @param Deck $Deck
	 *
	 * @return Response
	 */
	public function deleteDeck(Deck $Deck): Response
	{
		$this->em->remove($Deck);
		$this->em->flush();
		$this->addFlash('success', 'Deck deleted');

		return $this->redirectToRoute('myDeck');
	}

}
