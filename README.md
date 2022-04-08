# Documentation

To get started with Base STDIO's Template: Repository, DTO(Data Transfer Object), Helper , use Composer to add the package to your project's dependencies

## Repository

### Basic Usage

Next, you are ready to use repository. If you want create repository with Model corresponding(example:UserRepository), run commnand line

```bash
php artisan make:repository UserRepository
```

It will create UserRepository + UserInterface.

After that run script to create StdioAppServiceProvider.php, and you still need to bind its interface for your real repository, for example in your own StdioAppServiceProvider.php.

```bash
php artisan vendor:publish --tag=stdio-service-provider
```

## DTO

### Basic Usage

Next, you are ready to use DTO. If you want create DTO with Model corresponding(example: UserDTO)

```bash
php artisan make:DTO DTORepository
```

## STDIO Helper

### CollectionStdio

### Basic Usage

This is custom Laravel's collection.

```bash
php artisan vendor:publish --tag=stdio-helper
```
