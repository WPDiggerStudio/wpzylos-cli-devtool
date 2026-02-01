<?php

declare(strict_types=1);

namespace WPZylos\Framework\Cli\DevTool\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WPZylos\Framework\Cli\Core\FileWriter;
use WPZylos\Framework\Cli\Core\StubCompiler;

/**
 * Make Request command.
 *
 * Creates a new form request class for WPZylos plugins.
 *
 * @package WPZylos\Framework\Cli\DevTool\Commands
 */
class MakeRequestCommand extends Command
{
    protected static $defaultName = 'make:request';
    protected static $defaultDescription = 'Create a new form request class';

    /**
     * Configure the command arguments and options
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Request name')
            ->addOption('path', 'p', InputOption::VALUE_OPTIONAL, 'Plugin root path', getcwd())
            ->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Root namespace', 'MyPlugin')
            ->addOption('text-domain', 't', InputOption::VALUE_OPTIONAL, 'Text domain', 'my-plugin');
    }

    /**
     * Execute the command
     *
     * Creates a new form request class with the given name and options
     *
     * @param InputInterface $input Console input
     * @param OutputInterface $output Console output
     *
     * @return int Command::SUCCESS on success, Command::FAILURE on error
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $path = rtrim($input->getOption('path'), '/\\');
        $namespace = $input->getOption('namespace');
        $textDomain = $input->getOption('text-domain');

        // Remove 'Request' suffix if provided
        $baseName = preg_replace('/Request$/', '', $name);

        $stubPath = dirname(__DIR__, 2) . '/../wpzylos-cli-core/stubs';
        $compiler = new StubCompiler($stubPath);
        $writer = new FileWriter();

        $content = $compiler->compile('request', [
            'namespace' => $namespace,
            'class' => $baseName,
            'textDomain' => $textDomain,
        ]);

        $outputPath = $path . '/app/Http/Requests/' . $baseName . 'Request.php';

        try {
            $writer->write($outputPath, $content);
            $output->writeln("<info>Created:</info> {$outputPath}");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error>Error:</error> " . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
