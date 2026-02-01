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
 * Make Controller command.
 *
 * Creates a new controller class for WPZylos plugins.
 *
 * @package WPZylos\Framework\Cli\DevTool\Commands
 */
class MakeControllerCommand extends Command
{
    protected static $defaultName = 'make:controller';
    protected static $defaultDescription = 'Create a new controller class';

    /**
     * Configure the command arguments and options
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Controller name')
            ->addOption('path', 'p', InputOption::VALUE_OPTIONAL, 'Plugin root path', getcwd())
            ->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Root namespace', 'MyPlugin');
    }

    /**
     * Execute the command
     *
     * Creates a new controller class with the given name and options
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

        // Remove the 'Controller' suffix if provided
        $baseName = preg_replace('/Controller$/', '', $name);

        $stubPath = dirname(__DIR__, 2) . '/../wpzylos-cli-core/stubs';
        $compiler = new StubCompiler($stubPath);
        $writer = new FileWriter();

        $content = $compiler->compile('controller', [
            'namespace' => $namespace,
            'class' => $baseName,
            'view' => strtolower($baseName),
        ]);

        $outputPath = $path . '/app/Http/Controllers/' . $baseName . 'Controller.php';

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
