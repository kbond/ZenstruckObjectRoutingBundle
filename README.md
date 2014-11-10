# ZenstruckObjectRoutingBundle

[![Build Status](http://img.shields.io/travis/kbond/ZenstruckObjectRoutingBundle.svg?style=flat-square)](https://travis-ci.org/kbond/ZenstruckObjectRoutingBundle)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/kbond/ZenstruckObjectRoutingBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/kbond/ZenstruckObjectRoutingBundle/)
[![Code Coverage](http://img.shields.io/scrutinizer/coverage/g/kbond/ZenstruckObjectRoutingBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/kbond/ZenstruckObjectRoutingBundle/)
[![Latest Stable Version](http://img.shields.io/packagist/v/zenstruck/object-routing-bundle.svg?style=flat-square)](https://packagist.org/packages/zenstruck/object-routing-bundle)
[![License](http://img.shields.io/packagist/l/zenstruck/object-routing-bundle.svg?style=flat-square)](https://packagist.org/packages/zenstruck/object-routing-bundle)

A Symfony Bundle that enables passing objects to your router. It works by decorating the default router with a custom
`ObjectRouter` that transforms objects into a *route name* and *route parameters*. These are passed to the default
router.

For those that remember symfony 1, this bundle brings back functionality that was
[available in that framework](http://symfony.com/legacy/doc/jobeet/1_4/en/05?orm=Propel#chapter_05_object_route_class).

## Installation

1. Install with composer:

        composer require zenstruck/object-routing-bundle

2. Enable the bundle in the kernel:

    ```php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Zenstruck\ObjectRoutingBundle\ZenstruckObjectRoutingBundle(),
        );
    }
    ```

## Usage

To use this bundle, you must first have an object. Let's use a `BlogPost` example:

```php
namespace Acme\Entity;

class BlogPost
{
    private $id;
    private $slug;
    private $body;

    public function getId()
    {
        return $this->id;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getBody()
    {
        return $this->body;
    }
}
```

Next, you must have some routes for that object:

```yaml
# app/config/routing.yml

blog_post_show:
    pattern:  /blog/{id}-{slug}
    defaults: { _controller: AcmeBundle:BlogPost:show }

blog_post_edit:
    pattern:  /blog/{id}/edit
    defaults: { _controller: AcmeBundle:BlogPost:delete }

blog_post_delete:
    pattern:  /blog/{id}
    defaults: { _controller: AcmeBundle:BlogPost:delete }
    methods:  [DELETE]
```

### Without this bundle

Now, suppose you want to generate a route for a blog post. The *standard* way of doing this is as follows:

**Twig**:

```html+jinja
{# variable "post" is an instance of "BlogPost" with id=1, slug=example #}

{{ path('blog_post_show', { id: post.id, slug: post.slug }) }} {# /blog/1-example #}
{{ path('blog_post_show', { id: post.id, slug: post.slug, view: full }, true) }} {# http://example.com/blog/1-example?view=full #}
{{ path('blog_post_edit', { id: post.id }) }} {# /blog/1/edit #}
{{ path('blog_post_delete', { id: post.id }) }} {# /blog/1 #}
```

**Symfony Controller**:

```php
namespace Acme\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MyController extends Controller
{
    public function myAction()
    {
        $post = // instance of Acme\Entity\BlogPost with id=1, path=example

        // blog_post_show (/blog/1-example)
        $url = $this->generateUrl('blog_post_show', ['id' => $post->getId(), 'slug' => $post->getSlug()]);

        // blog_post_show with extra parameter and absolute (http://example.com/blog/1-example?view=full)
        $url = $this->generateUrl(
            'blog_post_show',
            ['id' => $post->getId(), 'slug' => $post->getSlug(), 'view' => 'full'],
            true
        );

        // blog_post_edit (/blog/1/edit)
        $url = $this->generateUrl('blog_post_edit', ['id' => $post->getId()]);

        // blog_post_delete (/blog/1)
        $url = $this->generateUrl('blog_post_delete', ['id' => $post->getId()]);
    }
}
```

### With this bundle

In this bundle's config, setup a mapping of `BlogPost` to the blog post routes:

```yaml
# app/config/config.yml

zenstruck_object_routing:
    class_map:
        Acme\Entity\BlogPost:
            default_route: blog_post_show
            default_parameters: [id]
            routes:
                blog_post_show: [id, path]
                blog_post_edit: ~
                blog_post_delete: ~
```

Generating routes for a blog post is now much simpler:

**Twig**:

```html+jinja
{# variable "post" is an instance of "BlogPost" with id=1, slug=example #}

{{ path(post) }} {# /blog/1-example (blog_post_show url because it is the default route) #}
{{ path('blog_post_show', post) }} {# equivalent to above #}
{{ path(post, { view: full }, true) }} {# http://example.com/blog/1-example?view=full #}
{{ path('blog_post_show', post, { view: full }, true) }} {# equivalent to above #}
{{ path('blog_post_edit', post) }} {# /blog/1/edit #}
{{ path('blog_post_delete', post) }} {# /blog/1 #}
```

**Symfony Controller**:

```php
namespace Acme\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MyController extends Controller
{
    public function myAction()
    {
        $post = // instance of Acme\Entity\BlogPost with id=1, slug=example

        // blog_post_show (/blog/1-example)
        $url = $this->generateUrl($post); // blog_post_show url because it is the default route
        $url = $this->generateUrl('blog_post_show', $post); // equivalent to above

        // blog_post_show with extra parameter and absolute (http://example.com/blog/1-example?view=full)
        $url = $this->generateUrl($post, ['view' => 'full'], true);
        $url = $this->generateUrl('blog_post_show', $post, ['view' => 'full'], true); // equivalent to above

        // blog_post_edit (/blog/1/edit)
        $url = $this->generateUrl('blog_post_edit', $post);

        // blog_post_delete (/blog/1)
        $url = $this->generateUrl('blog_post_delete', $post);
    }
}
```

## Custom Transformations

This bundle comes with a `ClassMapObjectTransformer` that uses the bundle's config to map object classes to routes. If
you have a more complex scenario, you can add your own transformers. Simply have your custom transformer implement
`Zenstruck\ObjectRoutingBundle\ObjectTransformer\ObjectTransformer` and register it as a service tagged with
`zenstruck_object_routing.object_transformer`.

See `Zenstruck\ObjectRoutingBundle\ObjectTransformer\ClassMapObjectTransformer` for a reference.

## Full Default Config

```yaml
zenstruck_object_routing:
    class_map:

        # Prototype
        class:

            # Optional - The route to use when an object is passed as the 1st parameter of Router::generate()
            default_route:        null

            # Route parameter as key, object method/public property as value (can omit key if object method/property is the same)
            default_parameters:

                # Examples:
                - id
                - path

            # Route name as key, parameter array as value (can leave parameter array as null if same as default_parameters)
            routes:

                # Examples:
                blog_show:           ~
                blog_edit:
                    - id

                # Prototype
                route_name:           []
```

**NOTE 1**: This bundle's router uses the `PropertyAccess` component to access the object's properties/methods.

**NOTE 2**: When mapping multiple objects that inherit one another, be sure to order them from child to parent. For
instance, if you had a `BlogPost` that has a parent class of `Page` and both are mapped, be sure to put `BlogPost`
before `Page`.
