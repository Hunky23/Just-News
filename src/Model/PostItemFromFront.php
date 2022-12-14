<?php

namespace App\Model;

class PostItemFromFront
{
    private ?string $title = null;

    private ?string $url = null;

    private ?string $content = null;

    private ?string $image = null;

    private ?string $created_at = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $name): self
    {
        $this->title = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }
    public function setCreatedAt(?string $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
