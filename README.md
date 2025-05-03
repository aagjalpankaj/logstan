# Laravel Package Template

<p align="left">

<a href="https://github.com/aagjalpankaj/laravel-package-template/actions/workflows/ci.yml">
  <img src="https://github.com/aagjalpankaj/laravel-package-template/actions/workflows/ci.yml/badge.svg" alt="ci">
</a>

<a href="https://packagist.org/packages/aagjalpankaj/laravel-package-template">
  <img src="https://img.shields.io/packagist/dt/aagjalpankaj/laravel-package-template" alt="Downloads">
</a>
</p>

## About
Composer package template for Laravel.

**Includes:**

✨ **PestPHP:** Feature, Unit & Architecture testsuites using PestPHP

✨ **Workbench:** to write Integration testing

✨ **Rector:** for refactoring

✨ **Pint:** for code-styling


## Usage

#### Create project
Create project by clicking on "Use this template" OR run:
```
composer create-project aagjalpankaj/laravel-package-template:dev-main <your-package-name>
```

#### Replace (text & file names)
- `Aagjalpankaj` with your namespace
- `LaravelPackageTemplate` with your package name (camelcase)
- `laravel-package-template` with your package name (lowercase hyphen separated)

### Commands (useful while development)

Run all checks:
```
composer ci
```

Fix issues:
```
composer ci.fix
```

**Good luck 🤞**