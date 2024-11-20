<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Cheatsheet;
use App\Entity\Section;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class CheatsheetTest extends TestCase
{
    public function testGetId()
    {
        $cheatsheet = new Cheatsheet();
        $this->assertNull($cheatsheet->getId());

        $reflection = new \ReflectionClass($cheatsheet);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($cheatsheet, 1);

        $this->assertEquals(1, $cheatsheet->getId());
    }

    public function testTitle()
    {
        $cheatsheet = new Cheatsheet();
        $title = 'Guide Symfony';

        $this->assertSame($cheatsheet, $cheatsheet->setTitle($title));
        $this->assertEquals($title, $cheatsheet->getTitle());
    }

    // public function testSections()
    // {
    //     $cheatsheet = new Cheatsheet();
    //     $section1 = $this->createMock(Section::class);
    //     $section2 = $this->createMock(Section::class);

    //     // Définir les attentes avant les appels aux méthodes
    //     $section1->expects($this->once())
    //         ->method('setCheatsheet')
    //         ->with($this->identicalTo($cheatsheet));

    //     $section2->expects($this->once())
    //         ->method('setCheatsheet')
    //         ->with($this->identicalTo($cheatsheet));

    //     // Test initial de la collection vide
    //     $this->assertInstanceOf(Collection::class, $cheatsheet->getSections());
    //     $this->assertCount(0, $cheatsheet->getSections());

    //     // Ajout de sections
    //     $this->assertSame($cheatsheet, $cheatsheet->addSection($section1));
    //     $this->assertSame($cheatsheet, $cheatsheet->addSection($section2));
    //     $this->assertCount(2, $cheatsheet->getSections());
    //     $this->assertTrue($cheatsheet->getSections()->contains($section1));
    //     $this->assertTrue($cheatsheet->getSections()->contains($section2));

    //     // Définir l'attente pour la suppression avant l'appel
    //     $section1->expects($this->once())
    //         ->method('setCheatsheet')
    //         ->with(null);

    //     // Suppression d'une section
    //     $this->assertSame($cheatsheet, $cheatsheet->removeSection($section1));
    //     $this->assertCount(1, $cheatsheet->getSections());
    //     $this->assertFalse($cheatsheet->getSections()->contains($section1));
    // }

    public function testCategory()
    {
        $cheatsheet = new Cheatsheet();
        $category = $this->createMock(Category::class);

        $this->assertSame($cheatsheet, $cheatsheet->setCategory($category));
        $this->assertSame($category, $cheatsheet->getCategory());
    }
}
