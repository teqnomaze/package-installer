# Package Installer

A Composer installer plugin that installs packages of type `custom-package` into a `packages/<Name>/` directory of your project instead of `vendor/`.

[![Code Analysis](../../actions/workflows/analysis.yml/badge.svg)](../../actions/workflows/analysis.yml)

## Requirements

- PHP 8.2 or higher
- Composer 2.x

## Installation

Require the plugin in the consuming project:

```bash
composer require teqnomaze/package-installer
```

Allow it to run:

```json
{
    "config": {
        "allow-plugins": {
            "teqnomaze/package-installer": true
        }
    }
}
```

## Configuration

### Target directory (consuming project)

Default is `packages`, relative to the project root (the directory containing `vendor/`).

```json
{
    "extra": {
        "package-installer-target": "lib/packages"
    }
}
```

### Directory name (package)

Default is the studly-cased package name: `vendor/foo-bar` becomes `FooBar`. Override it in the package's `composer.json`:

```json
{
    "extra": {
        "package-installer-name": "Foo"
    }
}
```

### Notes

- Add `packages/` to `.gitignore` in the consuming project if you do not want installed packages committed.
- Do not edit files in `packages/<Name>/`. They are replaced on update.

## Changelog

All the notable changes to this project will be documented in [CHANGELOG.md](CHANGELOG.md) file.

## License

This project is licensed under the MIT License. See [LICENSE](LICENSE.md) for more information.
