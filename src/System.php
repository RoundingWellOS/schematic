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
        $directory = pathinfo($file, PATHINFO_DIRECTORY);

        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        return (int) file_put_contents($file, $contents);
    }
}
