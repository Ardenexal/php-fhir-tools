<?php

declare(strict_types=1);

/*
 * Minimal console application that registers the two code-generation commands the way an
 * end-user project does, so they can be invoked against symfony/console 6.4 — the lowest
 * version ardenexal/fhir-code-generation declares support for.
 *
 * The demo application cannot serve this purpose: demo/composer.json pins every Symfony
 * package to 7.4.*, and the monorepo root cannot install console 6.4 either because
 * brianium/paratest requires ^7.4.7. Hence a standalone consumer install.
 *
 * fhir.ig.packages is normally supplied by the bundle's DI configuration; here the
 * FHIR_IG_PACKAGES environment variable stands in for it (comma-separated) so the
 * config-fallback path of fhir:generate-ig is reachable from the command line.
 */

use Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRIGGeneratorCommand;
use Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRModelGeneratorCommand;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;
use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;

require __DIR__ . '/vendor/autoload.php';

$filesystem    = new Filesystem();
$packageLoader = new PackageLoader(
    httpClient: HttpClient::create(),
    cacheDir: __DIR__ . '/var/cache/.fhir',
    contextBuilder: new BuilderContext(),
    filesystem: $filesystem,
);

$configuredPackages = array_values(array_filter(
    array_map('trim', explode(',', (string) getenv('FHIR_IG_PACKAGES'))),
    static fn (string $package): bool => $package !== '',
));

$application = new Application('symfony/console 6.4 compatibility harness');

$commands = [
    new FHIRModelGeneratorCommand($filesystem, $packageLoader),
    new FHIRIGGeneratorCommand($filesystem, $packageLoader, $configuredPackages),
];

foreach ($commands as $command) {
    // Application::add() is deprecated from 7.4, addCommand() does not exist before it —
    // the harness has to run on both to compare 6.4 behaviour against 7.x.
    if (method_exists($application, 'addCommand')) {
        $application->addCommand($command);
    } else {
        $application->add($command);
    }
}

exit($application->run());
