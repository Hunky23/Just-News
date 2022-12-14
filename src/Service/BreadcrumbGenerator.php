<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\Category;
use Twig\Environment;

class BreadcrumbGenerator
{
    public function __construct(
        public CategoryStorage $categoryStorage,
        public UrlGenerator $urlGenerator,
        public Environment $twig
    )
    {
    }

    public function getBreadcrumbHtml(Post|Category $postOrCategoryInstance): string
    {
        if ($postOrCategoryInstance instanceof Post) {
            $links[$postOrCategoryInstance->getTitle()] = $this->urlGenerator->getUrl($postOrCategoryInstance);

            if (! empty($postOrCategoryInstance->getCategory())) {
                $postOrCategoryInstance = $this->categoryStorage->getOneCategoryBy([
                    'id' => $postOrCategoryInstance->getCategory()->getId()
                ]);
            }
        }

        if ($postOrCategoryInstance instanceof Category) {
            while (true) {
                $links[$postOrCategoryInstance->getName()] = $this->urlGenerator->getUrl($postOrCategoryInstance);

                if (empty($postOrCategoryInstance->getParent())) {
                    break;
                }

                $postOrCategoryInstance = $postOrCategoryInstance->getParent();
            }
        }

        $links = array_reverse($links);

        $links = [
            'Главная' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://" . $_SERVER['HTTP_HOST']. '/'
        ] + $links;

        return $this->twig->render('template-parts/_breadcrumb.html.twig', [
            'links' => $links
        ]);
    }
}
