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

---

<p align="center">
  <a href="#installation">Installation</a> |
  <a href="#usage">Usage</a> |
  <a href="#logging-standards-covered">Logging standards</a>
</p>

---

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

## Logging standards covered

### Log Message
- Non-empty messages required
- Maximum message length (character limit)
- Messages must start with uppercase letters
- No sensitive information in message content

### Log Context
- Context must be an array
- Maximum of 10 context keys allowed
- Keys must be non-empty strings in snake_case format
- Values must be scalar or null (no arrays/objects are accepted)
- Maximum value length of 100 characters
- No sensitive information in context keys
