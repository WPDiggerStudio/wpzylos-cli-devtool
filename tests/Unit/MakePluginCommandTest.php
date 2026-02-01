<?php

declare(strict_types=1);

namespace WPZylos\Framework\Cli\DevTool\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPZylos\Framework\Cli\DevTool\Commands\MakePluginCommand;

/**
 * Tests for MakePluginCommand.
 */
class MakePluginCommandTest extends TestCase
{
    public function testCommandIsInstantiable(): void
    {
        $command = new MakePluginCommand();
        $this->assertInstanceOf(MakePluginCommand::class, $command);
    }

    public function testCommandHasSlugArgument(): void
    {
        $command = new MakePluginCommand();
        $definition = $command->getDefinition();

        $this->assertTrue($definition->hasArgument('slug'));
        $this->assertTrue($definition->getArgument('slug')->isRequired());
    }

    public function testSlugArgumentDescription(): void
    {
        $command = new MakePluginCommand();
        $definition = $command->getDefinition();

        $description = $definition->getArgument('slug')->getDescription();
        $this->assertNotEmpty($description);
        $this->assertStringContainsString('slug', strtolower($description));
    }
}
