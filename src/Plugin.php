<?php

declare(strict_types=1);

namespace Teqnomaze\PackageInstaller;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * Composer plugin that registers the custom-package installer.
 */
class Plugin implements PluginInterface
{
    /**
     * Registers the custom installer with Composer.
     *
     * @param Composer    $composer Composer instance.
     * @param IOInterface $io       Composer IO interface.
     *
     * @return void
     */
    public function activate(Composer $composer, IOInterface $io): void
    {
        $composer->getInstallationManager()->addInstaller(new Installer($io, $composer));
    }

    /**
     * Handles plugin deactivation. Nothing needs to be cleaned up.
     *
     * @param Composer    $composer Composer instance.
     * @param IOInterface $io       Composer IO interface.
     *
     * @return void
     */
    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    /**
     * Handles plugin removal. Nothing needs to be cleaned up.
     *
     * @param Composer    $composer Composer instance.
     * @param IOInterface $io       Composer IO interface.
     *
     * @return void
     */
    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }
}
