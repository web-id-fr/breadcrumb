# Breadcrumb

## Installation

You can install the package via composer:

```bash
composer require web-id/breadcrumb
```

### Publish config file

```bash
php artisan vendor:publish --provider="WebId\Breadcrumb\BreadcrumbServiceProvider" --tag="config"
```

## Usage

This package uses Spatie's Laravel Navigation package (https://github.com/spatie/laravel-navigation).

For now, it only works with Inertia (it adds a `breadcrumb` key in the Inertia Shared data with your breacrumb)

### Breadcrumb root element

Edit the `breadcrumb_root` title and route name in your published `breadcrumb.php` config file.

For instance, if your root element is your homepage:

```php
'breadcrumb_root' => [
        'title' => 'Homepage',
        'route_name' => 'homepage',
    ],
```

> **Note**\
> Your route must be named.

### Breadcrumb class

To create a new breadcrumb, create a class that extends the `WebId\Breadcrumb\Breadcrumb` class.

In this example, we will create a breadcrumb for the blog page (list of all articles):

```php
use WebId\Breadcrumb\Breadcrumb;

class BlogBreadcrumb extends Breadcrumb
{
    public function index(): array
    {
        return $this->render(
            $this->baseBreadcrumb()
                ->add('Blog')
                ->tree()
        );
    }
}
```

Notice that the `add()` method only takes a name (blog). Indeed, the last element of a breadcrumb being
the active page, you don't need to attach a link to it.

In this example, your Inertia view should have a `breadcrumb` key with this data:

```json
[
    {
        "url": "https://www.yourwebsite.com",
        "title": "Homepage"
    },
    {
        "url": "",
        "title": "Blog"
    }
]
```

If you want to create the breadcrumb for a single blog post, you probably want the parent element to be
the blog page. In that case, add a method as follow:

```php
public function show(Post $post): array
{
    return $this->render(
        $this->baseBreadcrumb()
            ->add('Blog', route('blog.index'))
            ->add($post->title, route('blog.show', ['post' => $post]))
            ->tree()
    );
}
```

> Notice that your can Typehint a model in your breadcrumb methods as if you were in a controller method.

In this example, your Inertia view should have a `breadcrumb` key with this data:

```json
[
    {
        "url": "https://www.yourwebsite.com",
        "title": "Homepage"
    },
    {
        "url": "https://www.yourwebsite.com/blog",
        "title": "Blog"
    },
    {
        "url": "",
        "title": "Article title"
    }
]
```

### Register a breadcrumb to a route

In order for your breadcrumb to be accessible in your Inertia view, you have to register it
to the associated route as follow:

```php
Route::get('/blog', [BlogController::class, 'index'])->breadcrumb([BlogBreadcrumb:class, 'index']);
Route::get('/blog/{post}', [BlogController::class, 'show'])->breadcrumb([BlogBreadcrumb:class, 'show']);
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
