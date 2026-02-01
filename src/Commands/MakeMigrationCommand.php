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
 * Make Migration command.
 *
 * Creates a new database migration file for WPZylos plugins.
 *
 * @package WPZylos\Framework\Cli\DevTool\Commands
 */
class MakeMigrationCommand extends Command
{
    protected static $defaultName = 'make:migration';
    protected static $defaultDescription = 'Create a new migration class';

    /**
     * Configure the command arguments and options
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Migration name (e.g., create_users_table)')
            ->addOption('path', 'p', InputOption::VALUE_OPTIONAL, 'Plugin root path', getcwd())
            ->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Root namespace', 'MyPlugin')
            ->addOption('prefix', null, InputOption::VALUE_OPTIONAL, 'Table prefix', 'mp_');
    }

    /**
     * Execute the command
     *
     * Creates a new migration file with the given name and options
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
        $prefix = $input->getOption('prefix');

        // Generate filename with timestamp
        $timestamp = date('Y_m_d_His');
        $filename = "{$timestamp}_{$name}";

        // Convert to class name
        $className = $this->toClassName($name);

        // Extract table name from migration name
        $tableName = $this->extractTableName($name);

        $stubPath = dirname(__DIR__, 2) . '/../wpzylos-cli-core/stubs';
        $compiler = new StubCompiler($stubPath);
        $writer = new FileWriter();

        $content = $compiler->compile('migration', [
            'namespace' => $namespace,
            'class' => $className,
            'prefix' => $prefix,
            'table' => $tableName,
        ]);

        $outputPath = $path . '/database/migrations/' . $filename . '.php';

        try {
            $writer->write($outputPath, $content);
            $output->writeln("<info>Created:</info> {$outputPath}");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error>Error:</error> " . $e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * Convert migration name to class name
     *
     * Converts snake_case to PascalCase (e.g., create_users_table -> CreateUsersTable)
     *
     * @param string $name Migration name in snake_case
     *
     * @return string Class name in PascalCase
     */
    private function toClassName(string $name): string
    {
        $name = str_replace('_', ' ', $name);
        $name = ucwords($name);

        return str_replace(' ', '', $name);
    }

    /**
     * Extract table name from migration name
     *
     * Removes 'create_' prefix and '_table' suffix to extract the table name
     * Examples:
     * - Create_users_table -> users
     * - Add_email_to_users -> users
     *
     * @param string $name Migration name
     *
     * @return string Table name
     */
    private function extractTableName(string $name): string
    {
        // create_users_table -> users
        // add_email_to_users -> users
        $name = preg_replace('/^create_/', '', $name);
        $name = preg_replace('/_table$/', '', $name);

        return $name ?? '';
    }
}
