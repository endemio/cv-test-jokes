<?php

namespace App\Tests;

use App\Interfaces\CategoriesInterface;
use App\Service\ICBDB\CategoryService;
use PHPUnit\Framework\TestCase;

class CategoryServiceTest extends TestCase
{
    public function testCategory()
    {
        $category = new CategoryService();

        $this->assertInstanceOf(CategoriesInterface::class, $category);

        $this->assertIsArray($category->getCategoriesList());
    }
}
