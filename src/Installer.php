<?php

declare(strict_types=1);

namespace Teqnomaze\PackageInstaller;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

/**
 * Installs custom-package packages into the project's packages directory.
 */
class Installer extends LibraryInstaller
{
    /**
     * Composer package type handled by this installer.
     */
    private const PACKAGE_TYPE = 'custom-package';

    /**
     * Default target directory, relative to the project root.
     */
    private const DEFAULT_TARGET = 'packages';

    /**
     * Checks whether this installer handles the given package type.
     *
     * @param string $packageType Composer package type.
     *
     * @return bool
     */
    public function supports(string $packageType): bool
    {
        return $packageType === self::PACKAGE_TYPE;
    }

    /**
     * Builds the absolute install path of a package.
     *
     * @param PackageInterface $package Package being installed.
     *
     * @return string
     */
    public function getInstallPath(PackageInterface $package): string
    {
        $root = dirname($this->composer->getConfig()->get('vendor-dir'));

        $rootExtra = $this->composer->getPackage()->getExtra();
        $dir = $rootExtra['package-installer-target'] ?? self::DEFAULT_TARGET;

        $extra = $package->getExtra();
        $name = $extra['package-installer-name'] ?? $this->studly(explode('/', $package->getName())[1]);

        return $root . '/' . trim($dir, '/') . '/' . $name;
    }

    /**
     * Converts a dashed or underscored name to StudlyCase.
     *
     * @param string $value Name to convert, for example "foo-bar".
     *
     * @return string
     */
    private function studly(string $value): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value)));
    }
}
