<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\Category;

class UrlGenerator
{
    public function __construct(public CategoryStorage $categoryStorage)
    {
    }

    public function getUrl(Post|Category $postOrCategoryInstance): string
    {
        $url = '';

        if ($postOrCategoryInstance instanceof Post) {
            $url = '/' . $postOrCategoryInstance->getSlug() . '/';

            if (! empty($postOrCategoryInstance->getCategory())) {
                $postOrCategoryInstance = $this->categoryStorage->getOneCategoryBy([
                    'id' => $postOrCategoryInstance->getCategory()->getId()
                ]);
            }
        }

        if ($postOrCategoryInstance instanceof Category) {
            if (mb_substr($url, 0, 1) != '/') {
                $url = '/';
            }

            while (true) {
                $url = '/' . $postOrCategoryInstance->getSlug() . $url;

                if (empty($postOrCategoryInstance->getParent())) {
                    break;
                }

                $postOrCategoryInstance = $postOrCategoryInstance->getParent();
            }
        }

        if (! empty($url)) {
            return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://" . $_SERVER['HTTP_HOST'] . $url;
        }

        return $url;
    }
}
