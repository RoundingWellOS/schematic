<?php
declare(strict_types=1);

namespace RoundingWell\Schematic;

/**
 * @codeCoverageIgnore
 */
class System
{
    public function writeFile(string $file, string $contents): int
    {
        return (int) file_put_contents($file, $contents);
    }
}
