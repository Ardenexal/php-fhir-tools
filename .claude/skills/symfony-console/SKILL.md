# Symfony Console Patterns

## Command Structure

### Command Configuration
Use PHP 8 attributes for command configuration:
```php
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(
    name: 'fhir:generate',
    description: 'Generate FHIR model classes from StructureDefinitions',
    aliases: ['fhir:gen']
)]
class FHIRModelGeneratorCommand extends Command
```

### Dependency Injection
Inject services through constructor:
```php
public function __construct(
    private readonly FHIRModelGenerator $generator,
    private readonly ErrorCollector $errorCollector,
    private readonly PackageLoader $packageLoader
) {
    parent::__construct();
}
```

## Input Configuration

### Arguments and Options
```php
protected function configure(): void
{
    $this
        ->addArgument('package', InputArgument::REQUIRED, 'FHIR package name')
        ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Output directory')
        ->addOption('models-component', null, InputOption::VALUE_NONE, 'Generate to Models component');
}
```

## Output Handling

### Progress Indicator
The codebase uses `ProgressIndicator` for visual feedback:
```php
use Symfony\Component\Console\Helper\ProgressIndicator;

$progress = new ProgressIndicator($output);
$progress->start('Processing...');
// ... work ...
$progress->advance();
$progress->finish('Done!');
```

### Verbosity Levels
Respect verbosity flags (`-v`, `-vv`, `-vvv`):
```php
if ($output->isVerbose()) {
    $output->writeln('Detailed information...');
}
if ($output->isVeryVerbose()) {
    $output->writeln('More detailed information...');
}
if ($output->isDebug()) {
    $output->writeln('Debug information...');
}
```

### Output Styling
```php
use Symfony\Component\Console\Style\SymfonyStyle;

$io = new SymfonyStyle($input, $output);
$io->success('Generation complete!');
$io->error('Generation failed!');
$io->warning('Some warnings occurred');
$io->table(['Column 1', 'Column 2'], $rows);
```

## Error Handling

### Exit Codes
```php
protected function execute(InputInterface $input, OutputInterface $output): int
{
    try {
        // ... command logic ...
        return Command::SUCCESS;
    } catch (\Exception $e) {
        $io->error($e->getMessage());
        return Command::FAILURE;
    }
}
```

## Key Services Used

### BuilderContext
Provides generation context configuration.

### ErrorCollector
Accumulates errors for batch reporting.

### PackageLoader
Handles FHIR package downloads and caching.

## Testing Commands

Use Symfony's `CommandTester`:
```php
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

$application = new Application($kernel);
$command = $application->find('fhir:generate');
$commandTester = new CommandTester($command);

$commandTester->execute(['package' => 'hl7.fhir.r4.core']);

self::assertSame(Command::SUCCESS, $commandTester->getStatusCode());
```

## Available Commands

```bash
# List all commands
php bin/console list

# FHIR generation
php bin/console fhir:generate --help

# Run with verbosity
php bin/console fhir:generate -vvv
```
