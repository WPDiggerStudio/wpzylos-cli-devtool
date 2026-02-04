# WPZylos CLI Devtool

[![PHP Version](https://img.shields.io/badge/php-%5E8.0-blue)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)
[![GitHub](https://img.shields.io/badge/GitHub-WPDiggerStudio-181717?logo=github)](https://github.com/WPDiggerStudio/wpzylos-cli-devtool)

Development CLI tool for scaffolding WPZylos plugins and generating boilerplate code.

📖 **[Full Documentation](https://wpzylos.com)** | 🐛 **[Report Issues](https://github.com/WPDiggerStudio/wpzylos-cli-devtool/issues)**

---

## ✨ Features

- **make:plugin** — Create a new plugin from the WPZylos scaffold
- **make:controller** — Generate controller classes with CRUD methods
- **make:request** — Generate FormRequest validation classes
- **make:migration** — Generate database migration files

---

## 📋 Requirements

| Requirement     | Version        |
| --------------- | -------------- |
| PHP             | ^8.0           |
| Symfony Console | ^6.0 \|\| ^7.0 |

---

## 🚀 Installation

Install as a dev dependency in your plugin:

```bash
composer require --dev wpdiggerstudio/wpzylos-cli-devtool
```

---

## 📖 Quick Start

After installing the package, you have **two options** to run CLI commands:

### Option 1: Use Vendor Binary Directly

Run commands directly from vendor/bin:

```bash
vendor/bin/wpzylos list
vendor/bin/wpzylos make:controller ProductController
```

### Option 2: Create Root Executable (Recommended)

For a cleaner experience like Laravel's `php artisan`, run the installer once:

```bash
vendor/bin/wpzylos-install
```

This creates a `wpzylos` file in your project root. Then use:

```bash
php wpzylos list
php wpzylos make:controller ProductController
php wpzylos make:request StoreProductRequest
php wpzylos make:migration create_products_table
```

---

## 🛠️ Available Commands

### make:plugin

Create a new WPZylos plugin from the scaffold template.

```bash
# Using vendor binary
vendor/bin/wpzylos make:plugin my-plugin

# Or using root executable (after running wpzylos-install)
php wpzylos make:plugin my-plugin
php wpzylos make:plugin my-plugin --namespace=MyCompany\\MyPlugin
```

### make:controller

Generate a controller class.

```bash
php wpzylos make:controller ProductController
php wpzylos make:controller ProductController --resource
```

**Options:**

- `--resource` — Generate a resource controller with CRUD methods (index, show, store, update, destroy)

### make:request

Generate a FormRequest validation class.

```bash
php wpzylos make:request StoreProductRequest
php wpzylos make:request UpdateUserRequest
```

### make:migration

Generate a database migration file.

```bash
php wpzylos make:migration create_products_table
php wpzylos make:migration create_orders_table --create=orders
php wpzylos make:migration add_status_to_orders --table=orders
```

**Options:**

- `--create=<table>` — Generate migration for creating a new table
- `--table=<table>` — Generate migration for modifying an existing table

---

## 📁 Generated File Locations

| Command           | Output Path             |
| ----------------- | ----------------------- |
| `make:controller` | `app/Http/Controllers/` |
| `make:request`    | `app/Http/Requests/`    |
| `make:migration`  | `database/migrations/`  |

---

## 🔧 Configuration

The CLI tool automatically detects your plugin's configuration from `composer.json`:

```json
{
  "autoload": {
    "psr-4": {
      "MyPlugin\\": "app/"
    }
  }
}
```

---

## 🧪 Testing

```bash
# Run all tests
composer test

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage/
```

---

## 📦 Related Packages

| Package                                                                | Description                |
| ---------------------------------------------------------------------- | -------------------------- |
| [wpzylos-cli-core](https://github.com/WPDiggerStudio/wpzylos-cli-core) | Stub compilation utilities |
| [wpzylos-wp-cli](https://github.com/WPDiggerStudio/wpzylos-wp-cli)     | WP-CLI integration         |
| [wpzylos-scaffold](https://github.com/WPDiggerStudio/wpzylos-scaffold) | Plugin template            |
| [wpzylos-core](https://github.com/WPDiggerStudio/wpzylos-core)         | Application foundation     |

---

## 📖 Documentation

For comprehensive documentation, tutorials, and API reference, visit **[wpzylos.com](https://wpzylos.com)**.

---

## ☕ Support the Project

If you find this tool helpful, consider buying me a coffee! Your support helps maintain and improve the WPZylos ecosystem.

<a href="https://www.paypal.com/donate/?hosted_button_id=66U4L3HG4TLCC" target="_blank">
  <img src="https://img.shields.io/badge/Donate-PayPal-blue.svg?style=for-the-badge&logo=paypal" alt="Donate with PayPal" />
</a>

---

## 📄 License

MIT License. See [LICENSE](LICENSE) for details.

---

## 🤝 Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

---

**Made with ❤️ by [WPDiggerStudio](https://github.com/WPDiggerStudio)**
