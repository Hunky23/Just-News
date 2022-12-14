<?php

namespace App\Model;

class PostListFromFront
{
    private ?array $posts = null;

    public function getPosts(): ?array
    {
        return $this->posts;
    }

    public function setPosts(?array $posts): PostListFromFront
    {
        $this->posts = $posts;

        return $this;
    }
}