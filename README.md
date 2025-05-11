# Logstan

<p align="left">

<a href="https://github.com/aagjalpankaj/logstan/actions/workflows/ci.yml">
  <img src="https://github.com/aagjalpankaj/logstan/actions/workflows/ci.yml/badge.svg" alt="ci">
</a>

<a href="https://packagist.org/packages/aagjalpankaj/logstan">
  <img src="https://img.shields.io/packagist/dt/aagjalpankaj/logstan" alt="Downloads">
</a>
</p>

## About
Logstan is a PHPStan extension designed for Laravel applications to enforce consistent logging practices. It helps developers maintain standardized logs across their projects.

## Installation

```bash
composer require --dev aagjalpankaj/logstan
```

## Usage

### Analyzing Logs

```bash
./vendor/bin/logstan analyse
```
This command will scan your application and report any inconsistencies or potential problems.

### Getting Insights

```bash
./vendor/bin/logstan insights
```
This command provides insights about logs added in the application.

### Help

For more information about available commands and options:

```bash
./vendor/bin/logstan --help
```
