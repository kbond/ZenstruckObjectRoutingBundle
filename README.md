# ZenstruckObjectRoutingBundle

[![Build Status](http://img.shields.io/travis/kbond/ZenstruckObjectRoutingBundle.svg?style=flat-square)](https://travis-ci.org/kbond/ZenstruckObjectRoutingBundle)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/kbond/ZenstruckObjectRoutingBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/kbond/ZenstruckObjectRoutingBundle/)
[![Code Coverage](http://img.shields.io/scrutinizer/coverage/g/kbond/ZenstruckObjectRoutingBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/kbond/ZenstruckObjectRoutingBundle/)
[![Latest Stable Version](http://img.shields.io/packagist/v/zenstruck/object-routing-bundle.svg?style=flat-square)](https://packagist.org/packages/zenstruck/object-routing-bundle)
[![License](http://img.shields.io/packagist/l/zenstruck/object-routing-bundle.svg?style=flat-square)](https://packagist.org/packages/zenstruck/object-routing-bundle)

A Symfony Bundle that enables passing objects to your router. It works by decorating the default router with a custom
`ObjectRouter` that transforms objects into a *route name* and *route parameters*. These are passed to the default
router.

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

Next, you must have a route for that object:

```yaml
# app/config/routing.yml

blog_post_show:
    pattern:  /blog/{id}-{slug}
    defaults: { _controller: AcmeBundle:BlogPost:show }
```

### Without this bundle

Now, suppose you want to generate a route for a blog post. The *standard* way of doing this is as follows:

**Twig**:

```html+jinja
{# variable "post" is an instance of "BlogPost" #}

<a href="{{ path('blog_post_show', { id: post.id, slug: post.slug }) }}">Post</a>
```

**Symfony Controller**:

```php
namespace Acme\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MyController extends Controller
{
    public function myAction()
    {
        $post = // get instance of Acme\Entity\BlogPost

        return $this->redirect(
            $this->generateUrl('blog_post_show', ['id' => $post->getId(), 'slug' => $post->getSlug()])
        );
    }
}
```

### With this bundle

In this bundle's config, setup a mapping of `BlogPost` to the `blog_post_show` route:

```yaml
# app/config/config.yml

zenstruck_object_routing:
    class_map:
        Acme\Entity\BlogPost:
            route_name: blog_post_show
            route_parameters:
                id: getId
                slug: getSlug
```

Generating routes for a blog post is now much simpler:

**Twig**:

```html+jinja
{# variable "post" is an instance of "BlogPost" #}

<a href="{{ path(post) }}">Post</a>
```

**Symfony Controller**:

```php
namespace Acme\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MyController extends Controller
{
    public function myAction()
    {
        $post = // get instance of Acme\Entity\BlogPost

        return $this->redirect($this->generateUrl($post));
    }
}
```

## Custom Transformations

This bundle comes with a `ClassMapTransformer` that uses the bundle's config to map object classes to routes. If you
have a more complex scenario, you can add your own transformers. Simply have your custom transformer implement
`Zenstruck\ObjectRoutingBundle\ObjectTransformer\ObjectTransformer` and register it as a service tagged with
`zenstruck_object_routing.object_transformer`.

See `Zenstruck\ObjectRoutingBundle\ObjectTransformer\ClassMapObjectTransformer` for a reference.
