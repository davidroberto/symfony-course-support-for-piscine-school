<?php


namespace App\Controller\admin;

use App\Entity\Product;
use App\Form\ProductForm;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController {


	#[Route('/admin/create-product', name: 'admin-create-product')]
	public function displayCreateProduct(CategoryRepository $categoryRepository, Request $request) {

		if ($request->isMethod('POST')) {

			$title = $request->request->get('title');
			$description = $request->request->get('description');
			$price = $request->request->get('price');
			$categoryId = $request->request->get('category-id');
			
			if ($request->request->get('is-published') === 'on') {
				$isPublished = true;
			} else {
				$isPublished = false;
			}
		}

		$categories = $categoryRepository->findAll();

		return $this->render('admin/product/create-product.html.twig', [
			'categories' => $categories
		]);
	}

	#[Route('/admin/create-product-form-sf', name: 'admin-create-product-form-sf')]
	public function displayCreateProductFormSf(Request $request, EntityManagerInterface $entityManager) {

		$product = new Product();

		$productForm = $this->createForm(ProductForm::class, $product);
		$productForm->handleRequest($request);

		if ($productForm->isSubmitted()) {
			$product->setCreatedAt(new \DateTime());
			$product->setUpdatedAt(new \DateTime());

			$entityManager->persist($product);
			$entityManager->flush();
		}
		
		return $this->render('admin/product/create-product-form-sf.html.twig', [
			'productForm' => $productForm->createView()
		]);
	}


}