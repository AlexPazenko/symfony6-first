<?php
namespace App\Utils\AbstractClasses;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Doctrine\DBAL\DBALException;
use Facile\DoctrineMySQLComeBack\Doctrine\DBAL\Driver\PDOMySql\Driver;

abstract class CategoryTreeAbstract {

  public $categoriesArrayFromDb;
  public $categorylist;
  protected static $dbconnection;

  public function __construct(EntityManagerInterface $entitymanager, UrlGeneratorInterface $urlgenerator)
  {
    $this->entitymanager = $entitymanager;
    $this->urlgenerator = $urlgenerator;
    $this->categoriesArrayFromDb = $this->getCategories();
  }

  abstract public function getCategoryList(array $categories_array);

  public function buildTree(int $parent_id = null): array
  {
    $subcategory = [];
    foreach ($this->categoriesArrayFromDb as $category)
    {
      if($category['parent_id'] == $parent_id)
      {
        $children = $this->buildTree($category['id']);
        if($children)
        {
          $category['children'] = $children;
        }
        $subcategory[] = $category;
      }
    }
    return $subcategory;
  }

  private function getCategories(): array
  {
    if(self::$dbconnection)
    {
      return self::$dbconnection;
    }
    else
    {
      $sql = "SELECT * FROM categories";
      $conn = $this->entitymanager->getConnection();
      return self::$dbconnection = $conn->fetchAllAssociative($sql);

    }
  }

}