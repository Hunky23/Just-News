<?php

namespace App\Service;

use App\Entity\Post;
use App\Model\PostItemFromFront;
use App\Model\PostListFromFront;

class PostToFrontConverter
{
    public function __construct(public UrlGenerator $urlGenerator)
    {
    }

    public function getPostsOrPost($postsOrPost): PostItemFromFront|PostListFromFront|null
    {
        if (is_array($postsOrPost)) {
            $newPosts = array_map(function ($post) {
                $newPost = new PostItemFromFront();
                $newPost->setUrl($this->urlGenerator->getUrl($post))
                    ->setTitle($post->getTitle())
                    ->setContent($post->getContent())
                    ->setImage($post->getImage())
                    ->setCreatedAt(date_format($post->getCreatedAt(), 'H:i:s d-m-Y'));

                return $newPost;
            }, $postsOrPost);

            $newPostList = new PostListFromFront();
            $newPostList->setPosts($newPosts);

            return $newPostList;
        }

        if ($postsOrPost instanceof Post) {
            $post = $postsOrPost;

            $newPost = new PostItemFromFront();
            $newPost->setUrl($this->urlGenerator->getUrl($post))
                ->setTitle($post->getTitle())
                ->setContent($post->getContent())
                ->setImage($post->getImage())
                ->setCreatedAt(date_format($post->getCreatedAt(), 'H:i:s d-m-Y'));

            return $newPost;
        }

        return null;
    }
}