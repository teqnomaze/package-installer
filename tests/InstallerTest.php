<?php

declare(strict_types=1);

namespace Teqnomaze\PackageInstaller\Tests;

use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Package\Package;
use Composer\Package\RootPackageInterface;
use Teqnomaze\PackageInstaller\Installer;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the custom-package installer.
 */
final class InstallerTest extends TestCase
{
    /**
     * Creates an installer for a project located at /project.
     *
     * @param array<string, mixed> $rootExtra Extra data of the root package.
     *
     * @return Installer
     */
    private function createInstaller(array $rootExtra = []): Installer
    {
        $config = new Config(false, '/project');
        $config->merge(['config' => ['vendor-dir' => '/project/vendor']]);

        $root = $this->createMock(RootPackageInterface::class);
        $root->method('getExtra')->willReturn($rootExtra);

        $composer = $this->createMock(Composer::class);
        $composer->method('getConfig')->willReturn($config);
        $composer->method('getPackage')->willReturn($root);

        return new Installer($this->createMock(IOInterface::class), $composer);
    }

    /**
     * Creates a custom-package package.
     *
     * @param string               $name  Package name, for example "teqnomaze/foo".
     * @param array<string, mixed> $extra Extra data of the package.
     *
     * @return Package Package instance of type custom-package.
     */
    private function createPackage(string $name, array $extra = []): Package
    {
        $package = new Package($name, '1.0.0.0', '1.0.0');
        $package->setType('custom-package');
        $package->setExtra($extra);

        return $package;
    }

    /**
     * Only the custom-package type is supported.
     *
     * @return void
     */
    public function testSupportsOnlyWpPackageType(): void
    {
        $installer = $this->createInstaller();

        self::assertTrue($installer->supports('custom-package'));
        self::assertFalse($installer->supports('library'));
    }

    /**
     * Packages are installed into packages/<Name> by default.
     *
     * @return void
     */
    public function testDefaultInstallPath(): void
    {
        $installer = $this->createInstaller();

        self::assertSame(
            '/project/packages/Foo',
            $installer->getInstallPath($this->createPackage('teqnomaze/foo'))
        );
    }

    /**
     * Dashed and underscored package names become StudlyCase directories.
     *
     * @return void
     */
    public function testNameIsStudlyCased(): void
    {
        $installer = $this->createInstaller();

        self::assertSame(
            '/project/packages/FooBar',
            $installer->getInstallPath($this->createPackage('teqnomaze/foo-bar'))
        );
        self::assertSame(
            '/project/packages/FooBar',
            $installer->getInstallPath($this->createPackage('teqnomaze/foo_bar'))
        );
    }

    /**
     * The package extra "package-installer-name" overrides the directory name.
     *
     * @return void
     */
    public function testCustomPackageName(): void
    {
        $installer = $this->createInstaller();
        $package   = $this->createPackage('teqnomaze/foo', ['package-installer-name' => 'Custom']);

        self::assertSame('/project/packages/Custom', $installer->getInstallPath($package));
    }

    /**
     * The root extra "package-installer-target" overrides the target directory.
     *
     * @return void
     */
    public function testCustomTargetDirectory(): void
    {
        $installer = $this->createInstaller(['package-installer-target' => 'lib/packages/']);

        self::assertSame(
            '/project/lib/packages/Foo',
            $installer->getInstallPath($this->createPackage('teqnomaze/foo'))
        );
    }
}
