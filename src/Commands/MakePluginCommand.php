<?php

declare(strict_types=1);

namespace WPZylos\Framework\Cli\DevTool\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use WPZylos\Framework\Cli\Core\FileWriter;

/**
 * Make Plugin command.
 *
 * Scaffolds a new WPZylos plugin with the standard directory structure.
 *
 * @package WPZylos\Framework\Cli\DevTool\Commands
 */
class MakePluginCommand extends Command
{
    protected static $defaultName = 'make:plugin';
    protected static $defaultDescription = 'Scaffold a new WPZylos plugin';

    /**
     * Configure the command arguments and options
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('slug', InputArgument::REQUIRED, 'Plugin slug (e.g., my-plugin)');
    }

    /**
     * Execute the command
     *
     * Scaffolds a new WPZylos plugin with the standard directory structure
     * and prompts the user for plugin details
     *
     * @param InputInterface $input Console input
     * @param OutputInterface $output Console output
     *
     * @return int Command::SUCCESS on success
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $slug = $input->getArgument('slug');
        $helper = $this->getHelper('question');

        // Gather plugin info
        $question = new Question('Plugin Name [My Plugin]: ', 'My Plugin');
        $name = $helper->ask($input, $output, $question);

        $question = new Question('Namespace [MyPlugin]: ', str_replace(['-', '_', ' '], '', ucwords($slug, '-_ ')));
        $namespace = $helper->ask($input, $output, $question);

        $question = new Question('Text Domain [' . $slug . ']: ', $slug);
        $helper->ask($input, $output, $question);

        $defaultPrefix = str_replace('-', '_', $slug) . '_';
        $question = new Question('Prefix [' . $defaultPrefix . ']: ', $defaultPrefix);
        $helper->ask($input, $output, $question);

        $basePath = getcwd() . '/' . $slug;

        $output->writeln("\n<info>Creating plugin:</info> {$name}");
        $output->writeln("  Slug: {$slug}");
        $output->writeln("  Namespace: {$namespace}");
        $output->writeln("  Path: {$basePath}\n");

        $writer = new FileWriter();

        // Create directory structure
        $dirs = [
            'app/Core',
            'app/Http/Controllers',
            'app/Http/Requests',
            'app/Support',
            'bootstrap',
            'config',
            'database/migrations',
            'resources/views',
            'resources/lang',
            'routes',
            'includes',
        ];

        foreach ($dirs as $dir) {
            $fullPath = $basePath . '/' . $dir;
            if (!is_dir($fullPath)) {
                if (!mkdir($fullPath, 0755, true) && !is_dir($fullPath)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $fullPath));
                }
                $output->writeln("  <comment>Created:</comment> {$dir}/");
            }
        }

        // Create .git keep in empty directories
        $writer->writeIfNotExists($basePath . '/resources/lang/.gitkeep', '');

        $output->writeln("\n<info>Plugin scaffolded successfully!</info>");
        $output->writeln("Next steps:");
        $output->writeln("  1. cd {$slug}");
        $output->writeln("  2. composer install");
        $output->writeln("  3. Symlink to wp-content/plugins/");

        return Command::SUCCESS;
    }
}
