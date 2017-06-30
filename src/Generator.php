<?php
declare(strict_types=1);

namespace RoundingWell\Schematic;

use PhpParser\BuilderFactory;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_ as Cls;
use PhpParser\Node\Stmt\Namespace_ as Ns;
use PhpParser\PrettyPrinterAbstract;
use PhpParser\PrettyPrinter\Standard as StandardPrinter;
use RoundingWell\Schematic\Schema\ObjectSchema;

class Generator
{
    /**
     * @var BuilderFactory
     */
    private $builder;

    /**
     * @var PrettyPrinterAbstract
     */
    private $printer;

    /**
     * @var System
     */
    private $system;

    public function __construct(
        BuilderFactory $factory = null,
        PrettyPrinterAbstract $printer = null,
        System $system = null
    ) {
        $this->factory = $factory ?: $this->defaultBuilder();
        $this->printer = $printer ?: $this->defaultPrinter();
        $this->system = $system ?: $this->defaultSystem();
    }

    public function generate(ObjectSchema $schema, string $className, string $baseClass = ''): array
    {
        $classes = [];

        $className = new Name($className);
        // Start the class AST definition
        $namespace = $this->factory->namespace($className->slice(0, -1)->toString());
        $class = $this->factory->class($className->getLast());

        if ($baseClass) {
            $baseClassName = new Name($baseClass);
            // Import the base class with a "use" statement
            $namespace->addStmt($this->factory->use($baseClassName));
            // Make the class extend the base class
            $class->extend($baseClassName->getLast());
        }

        foreach ($schema->properties() as $name => $property) {
            $typeHint = $property->phpType();

            if ($property->isObject()) {
                // Create a new class for this property
                $nextClass = Name::concat($className, ucfirst($name));
                $typeHint = '\\' . $nextClass->toString();
                $classes = array_merge($classes, $this->generate(
                    $property,
                    $nextClass->toString(),
                    $baseClass
                ));
            } elseif ($property->isArray() && $property->items()->isObject()) {
                // Create a new class for this array of properties
                $nextClass = Name::concat($className, ucfirst(singular($name)));
                $typeHint = '\\' . $nextClass->toString() . '[]';
                $classes = array_merge($classes, $this->generate(
                    $property->items(),
                    $nextClass->toString(),
                    $baseClass
                ));
            } elseif (!$schema->isRequired($name) && !$property->isNull()) {
                $typeHint = "$typeHint|null";
            }

            // Add a property declaration to the class
            $class->addStmt(
                $this->factory->property($name)
                    ->makePublic()
                    ->setDocComment("/**\n * @var $typeHint\n */")
            );
        }

        // Add the class declaration to the namespace
        $namespace->addStmt($class);

        $classes[$className->toString()] = $namespace->getNode();

        return $classes;
    }

    /**
     * @param Ns[] $classes
     * @return string[]
     */
    public function write(array $classes, string $rootDirectory, string $rootNamespace = ''): array
    {
        $rootDirectory = rtrim($rootDirectory, '/');

        return array_map(
            function (Ns $node) use ($rootDirectory, $rootNamespace): string {
                // Remove the root (PSR-4) namespace and convert to a path
                $directory = str_replace($rootNamespace, '', $node->name->toString());
                $directory = trim(str_replace('\\', '/', $directory), '/');
                $directory = rtrim("$rootDirectory/$directory", '/');
                // Grab the class name from AST
                $class = $this->classNode($node->stmts)->name;

                $path = "$directory/$class.php";
                $code = $this->printer->prettyPrintFile([$node]);

                $this->system->writeFile($path, $code);

                return $path;
            },
            $classes
        );
    }

    protected function defaultBuilder(): BuilderFactory
    {
        return new BuilderFactory();

    }

    protected function defaultPrinter(): PrettyPrinterAbstract
    {
        return new StandardPrinter([
            'shortArraySyntax' => true,
        ]);
    }

    protected function defaultSystem(): System
    {
        return new System();
    }

    private function classNode(array $stmts): Cls
    {
        foreach ($stmts as $stmt) {
            if ($stmt instanceof Cls) {
                return $stmt;
            }
        }

        // @codeCoverageIgnoreStart
        throw new \InvalidArgumentException(
            'Cannot find class node in statements'
        );
        // @codeCoverageIgnoreEnd
    }
}
