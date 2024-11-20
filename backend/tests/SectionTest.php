<?php

namespace App\Tests\Entity;

use App\Entity\Section;
use App\Entity\Cheatsheet;
use PHPUnit\Framework\TestCase;

class SectionTest extends TestCase
{
    public function testGetId()
    {
        $section = new Section();
        $this->assertNull($section->getId());

        $reflection = new \ReflectionClass($section);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($section, 1);

        $this->assertEquals(1, $section->getId());
    }

    public function testTitle()
    {
        $section = new Section();
        $title = 'Introduction';

        $this->assertSame($section, $section->setTitle($title));
        $this->assertEquals($title, $section->getTitle());
    }

    public function testContent()
    {
        $section = new Section();
        $content = 'Ceci est le contenu de la section.';

        $this->assertSame($section, $section->setContent($content));
        $this->assertEquals($content, $section->getContent());
    }

    public function testCheatsheet()
    {
        $section = new Section();
        $cheatsheet = $this->createMock(Cheatsheet::class);

        $this->assertSame($section, $section->setCheatsheet($cheatsheet));
        $this->assertSame($cheatsheet, $section->getCheatsheet());
    }
}
