<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Command;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Shared reader for the repeatable `--package` option that both generator commands expose.
 *
 * Both {@see FHIRModelGeneratorCommand} and {@see FHIRIGGeneratorCommand} accept the same
 * option, differing only in its description and default. Reading it in one place keeps them
 * from drifting apart in how the value is interpreted.
 */
trait PackageOptionTrait
{
    /**
     * Reads the repeatable `--package` option as a plain list of package specs.
     *
     * VALUE_IS_ARRAY guarantees an array at runtime, but InputInterface::getOption() is
     * declared as returning mixed, so the values are narrowed here rather than at each use.
     *
     * @param InputInterface $input Console input holding the parsed --package values
     *
     * @return list<string> Package specs in the order they were given on the command line
     */
    private function packageOption(InputInterface $input): array
    {
        $raw      = $input->getOption('package');
        $packages = [];

        if (is_array($raw)) {
            foreach ($raw as $package) {
                if (is_string($package)) {
                    $packages[] = $package;
                }
            }
        }

        return $packages;
    }
}
