<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller
 */
class CategoryController extends AbstractController
{

	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	/**
	 * CategoryController constructor.
	 *
	 * @param EntityManagerInterface $em
	 */
	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	/**
	 * @Route("/category", name="category")
	 * @param CategoryRepository $categoryRepository
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
    public function index(CategoryRepository $categoryRepository , Request $request):Response
    {
		$categoryList = $categoryRepository->findAll();
		$category = new Category();
		$form = $this->createForm(CategoryType::class, $category);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->em->persist($category);
			$this->em->flush();

			$this->addFlash('success', 'Category created.');
		}
		return $this->render('category/index.html.twig', [
			'categoryList' => $categoryList,'form' => $form->createView(),
		]);
    }


}
