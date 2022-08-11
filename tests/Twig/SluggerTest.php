<?php

namespace App\Tests\Twig;

use App\Twig\AppExtension;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    /**
     * @param string $string
     * @param string $slug
     * @return void
     * @dataProvider getSlugs
     */
    public function testSlugify(string $string, string $slug): void
    {
      $slugger = new AppExtension();
      $this->assertSame($slug, $slugger->slugify($string));
    }

    public function getSlugs()
    {

      yield ['Lorem Ipsum', 'lorem-ipsum'];
      yield [' Lorem Ipsum', 'lorem-ipsum'];
      yield [' lOrEm  iPsUm  ', 'lorem-ipsum'];
      yield ['!Lorem Ipsum!', 'lorem-ipsum'];
      yield ['lorem-ipsum', 'lorem-ipsum'];
      yield ['Children\'s books', 'childrens-books'];
      yield ['Five star movies', 'five-star-movies'];
      yield ['Adults 60+', 'adults-60'];
    }
}
