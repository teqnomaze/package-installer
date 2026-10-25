<?php

declare(strict_types=1);

namespace Teqnomaze\PackageInstaller\Tests;

use Composer\Composer;
use Composer\Config;
use Composer\Installer\InstallationManager;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Teqnomaze\PackageInstaller\Installer;
use Teqnomaze\PackageInstaller\Plugin;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the Composer plugin entry point.
 */
final class PluginTest extends TestCase
{
    /**
     * The plugin must implement the Composer plugin interface.
     *
     * @return void
     */
    public function testImplementsPluginInterface(): void
    {
        self::assertInstanceOf(PluginInterface::class, new Plugin());
    }

    /**
     * Activating the plugin registers exactly one Installer instance.
     *
     * @return void
     */
    public function testActivateRegistersInstaller(): void
    {
        $config = new Config(false, '/project');
        $config->merge(['config' => ['vendor-dir' => '/project/vendor']]);

        $manager = $this->createMock(InstallationManager::class);
        $manager->expects(self::once())
            ->method('addInstaller')
            ->with(self::isInstanceOf(Installer::class));

        $composer = $this->createMock(Composer::class);
        $composer->method('getConfig')->willReturn($config);
        $composer->method('getInstallationManager')->willReturn($manager);

        (new Plugin())->activate($composer, $this->createMock(IOInterface::class));
    }

    /**
     * Deactivating and uninstalling the plugin must not touch Composer.
     *
     * @return void
     */
    public function testDeactivateAndUninstallDoNothing(): void
    {
        $composer = $this->createMock(Composer::class);
        $composer->expects(self::never())->method('getInstallationManager');

        $io     = $this->createMock(IOInterface::class);
        $plugin = new Plugin();

        $plugin->deactivate($composer, $io);
        $plugin->uninstall($composer, $io);
    }
}
