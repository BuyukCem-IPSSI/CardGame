<?php

	namespace App\Controller;

	use App\Entity\Card;
	use App\Form\CardType;
	use App\Repository\CardRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\File\Exception\FileException;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;

	/**
	 * Class CardController
	 * @package App\Controller
	 */
	class CardController extends AbstractController
	{
		/**
		 * @var EntityManagerInterface
		 */
		private $em;

		/**
		 * CardController constructor.
		 *
		 * @param EntityManagerInterface $em
		 */
		public function __construct(EntityManagerInterface $em)
		{
			$this->em = $em;
		}

		/**
		 * @Route("/card", name="card")
		 * @param CardRepository $cardRepository
		 *
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function index(CardRepository $cardRepository): Response
		{
			$cardsList = $cardRepository->findAll();
			return $this->render('card/index.html.twig', [
				'cardsList' => $cardsList,
			]);
		}

		/**
		 * @Route("/Addcard", name="Addcard")
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function insertCard(CardRepository $cardRepository, Request $request): Response{

			$cardsList = $cardRepository->findAll();

			$card = new Card();
			$form = $this->createForm(CardType::class, $card);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$card->setUserCreator($this->getUser());
				$File = $form->get('imgFileName')->getData();
				if ($File) {
					$newFilename = uniqid() . '.' . $File->guessExtension();
					// Move the file to the directory where brochures are stored
					try {
						$File->move(
							$this->getParameter('imgupload'),
							$newFilename
						);

					} catch (FileException $e) {
						print_r($e);
					}
					$card->setImgFileName($newFilename);
				}
				$this->em->persist($card);
				$this->em->flush();
				$this->addFlash('success', 'Card created.');
			}
			return $this->render('card/form.html.twig', [
				'form' => $form->createView(),
				'DeckList' => $cardsList,
			]);
		}

		/**
		 * @Route("/card/delete/{id}", name="cardDelete")
		 * @ParamConverter("Card", options={"id"="id"})
		 */
		public function deleteCard(Card $card): Response
		{
			$this->em->remove($card);
			$this->em->flush();
			$this->addFlash('success', 'card deleted');
			return  new Response($this->generateUrl('Addcard'));
		}
		public function exportData(CardRepository $cardRepository): Response
		{
			$cards=$cardRepository->findAll();
			$export = fopen('php://output', 'r+');
			foreach ($cards as $card){
				fputcsv($export, $card->arrayExport());
			}
			fclose($export);
			$response=new Response();
			$response->headers->set('Content-Type','text/csv');
			$response->headers->set('Content-Disposition', 'attachment; filename="exportCards.csv"');
			return $response;
		}

		/**
		 * @Route("/card/edit/{id}", name="carEdit")
		 * @ParamConverter("card", options={"id"="id"})
		 * @param Request $request
		 * @param Card $card
		 * @return Response
		 */
		public function editEditor(Request $request, Card $card): Response
		{
			$form = $this->createForm(GameType::class, $card);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$this->em->persist($card);
				$this->em->flush();

				$this->addFlash('success', 'Card updated.');
			}
			return $this->render('card/form.html.twig', [
				'form' => $form->createView()
			]);
		}

	}
