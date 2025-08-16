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
LogStan is a PHPStan extension that helps you to enforce structured and consistent logging in Laravel.

### Example Output
```bash
./vendor/bin/logstan analyse

 ------ -------------------------------------------------------------------------------------------- 
  Line   Actions/CreateOrderAction.php                                                   
 ------ -------------------------------------------------------------------------------------------- 
  21     Log message "order created" should start with an uppercase letter.                                    
  67     Log context key "orderId" should be in snake_case format.                                    
  94     Log context has too many keys (11). Maximum allowed are 10.                                 
 ------ -------------------------------------------------------------------------------------------- 

 ------ --------------------------------------------------------------------------------------------
  Line   Services/ShopifyService.php                                                                             
 ------ -------------------------------------------------------------------------------------------- 
  57     Log context value of key "order" must be scalar, null or array of scalar. 
         "App\Models\Order" provided.   
 ------ -------------------------------------------------------------------------------------------- 

                                                                                                                        
 [ERROR] Found 4 errors                                                                                                
                                                                                                                     
```

---
<p align="center">
  <a href="#installation">Installation</a> |
  <a href="#usage">Usage</a> |
  <a href="#logging-standards-enforced">Logging standards enforced</a>
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
This command will scan your application and report any inconsistencies or potential problems with the logs in your application.

### Getting Insights

```bash
./vendor/bin/logstan insights
```
This command provides insights about logs in your application.

### Configuration (Optional)

```bash
cp ./vendor/aagjalpankaj/logstan/logstan.neon.dist logstan.neon 
```
You can override default presets in `logstan.neon`

### Help

For more information about available commands and options:

```bash
./vendor/bin/logstan --help
```

## Logging standards enforced

Logstan ensures your Laravel application follows best practices for logging by enforcing the following standards:

### Log Messages
| Standard | Description |
|----------|-------------|
| ✅ **Non-empty requirement** | All log messages must contain meaningful content |
| ✅ **Character limit** | Messages are validated against maximum length constraints |
| ✅ **Proper capitalization** | Messages must begin with uppercase letters for consistency |

### Log Context
| Standard | Description |
|----------|-------------|
| ✅ **Array structure** | Context data must be provided as a properly formatted array |
| ✅ **Key limit** | Maximum of 10 context keys to maintain readability |
| ✅ **Naming convention** | Keys must use snake_case format and be non-empty strings |
| ✅ **Data types** | Values restricted to scalar types or null (arrays/objects prohibited) |
| ✅ **Value length** | Context values limited to 100 characters maximum |
| ✅ **Security compliance** | Automatic detection and prevention of sensitive information in keys |


## Contributing

Found a bug or have a feature request? We'd love to hear from you!

- 🐛 **Report Issues**: [Create an issue](https://github.com/aagjalpankaj/logstan/issues)
- 💡 **Feature Requests**: [Create an issue](https://github.com/aagjalpankaj/logstan/issues)
- 🤝 **Pull Requests**: [Create PR](https://github.com/aagjalpankaj/logstan/pulls)

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).