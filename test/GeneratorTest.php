<?php

namespace RoundingWell\Schematic;

use Eloquent\Liberator\Liberator;
use Eloquent\Phony\Phpunit\Phony;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    /**
     * @var Generator
     */
    private $generator;

    /**
     * @var System
     */
    private $system;

    public function setUp()
    {
        $this->system = Phony::mock(System::class);

        $this->generator = new Generator(null, null, $this->system->get());
    }

    public function testGenerator()
    {
        $written = [];

        $this->system->writeFile->does(function ($path, $code) use (&$written) {
            $written[$path] = $code;
            return strlen($code);
        });

        $schema = Schema::fromFile(__DIR__ . '/../example/person.json');

        $classes = $this->generator->generate($schema, 'Acme\Model\Person', 'Acme\Model');

        $this->assertTrue(isset($classes['Acme\Model\Person']));
        $this->assertTrue(isset($classes['Acme\Model\Person\Address']));
        $this->assertTrue(isset($classes['Acme\Model\Person\Friend']));

        $files = $this->generator->write($classes, 'src/', 'Acme\Model');

        $this->assertSame($files['Acme\Model\Person'], 'src/Person.php');
        $this->assertSame($files['Acme\Model\Person\Address'], 'src/Person/Address.php');
        $this->assertSame($files['Acme\Model\Person\Friend'], 'src/Person/Friend.php');
    }

    public function testDefaults()
    {
        $generator = Liberator::liberate(new Generator());

        $this->assertInstanceOf('\PhpParser\BuilderFactory', $generator->factory);
        $this->assertInstanceOf('\PhpParser\PrettyPrinterAbstract', $generator->printer);
        $this->assertInstanceOf(System::class, $generator->system);
    }
}
