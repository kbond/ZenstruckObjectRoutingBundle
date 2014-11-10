<?php

namespace Zenstruck\ObjectRoutingBundle\Tests\Functional\Fixture\Entity;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class Page
{
    private $id;
    private $path;

    public function __construct($id, $path)
    {
        $this->id = $id;
        $this->path = $path;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPath()
    {
        return $this->path;
    }
}
