<?php

namespace App\Controller\Admin\Superadmin;

use App\Utils\CategoryTreeAdminOptionList;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Utils\CategoryTreeAdminList;
use App\Entity\Category;

use App\Form\CategoryType;

/**
 * @Route("/admin/su")
 */
class CategoriesController extends AbstractController
{
  private $doctrine;
  public function __construct(ManagerRegistry $doctrine) {
    $this->doctrine = $doctrine;
  }

  /**
     * @Route("/categories", name="categories", methods={"GET","POST"})
     */
    public function categories(CategoryTreeAdminList $categories, Request $request)
    {
        
        $categories->getCategoryList($categories->buildTree());
        
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);

        $is_invalid = null;

        if($this->saveCategory($category, $form, $request))
        {
            return $this->redirectToRoute('categories');
        }
        elseif($request->isMethod('post'))
        {
            $is_invalid = ' is-invalid';
        }

        return $this->render('admin/categories.html.twig', [
            'categories'=>$categories->categorylist,
            'form' => $form->createView(),
            'is_invalid' => $is_invalid
        ]);
    }


    /**
     * @Route("/edit-category/{id}", name="edit_category", methods={"GET","POST"})
     */
    public function editCategory(Category $category, Request $request)
    {

        $form = $this->createForm(CategoryType::class, $category);

        $is_invalid = null;

        if($this->saveCategory($category, $form, $request))
        {
            return $this->redirectToRoute('categories');
        }
        elseif($request->isMethod('post'))
        {
            $is_invalid = ' is-invalid';
        }

        return $this->render('admin/edit_category.html.twig',[
            'category'=>$category,
            'form' => $form->createView(),
            'is_invalid' => $is_invalid
            ]);
    }


    /**
     * @Route("/delete-category/{id}", name="delete_category")
     */
    public function deleteCategory(Category $category, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('categories');
    }


    private function saveCategory($category, $form, $request)
    {

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $category->setName($request->request->all()['category']['name']);

            $repository = $this->doctrine->getRepository(Category::class);
            $parent = $repository->find($request->request->all()['category']['parent']);
            $category->setParent($parent);

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return true;
    
        }
        return false;
    }

    public function getAllCategories(CategoryTreeAdminOptionList $categories, $editedCategory = null)
    {
      $this->denyAccessUnlessGranted('ROLE_ADMIN');

      $categories->getCategoryList($categories->buildTree());
      return $this->render('admin/_all_categories.html.twig',['categories'=>$categories,'editedCategory'=>$editedCategory]);
    }

}
