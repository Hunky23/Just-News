<?php

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Repository\PostRepository;
use App\Service\BreadcrumbGenerator;
use App\Service\CategoryStorage;
use App\Service\PostToFrontConverter;
use App\Service\UrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route('/{requestUrl}', name: 'post_or_category', requirements: ["requestUrl" => ".*\/"])]
    public function postOrCategory(
        Request $request,
        PostRepository $postRepository,
        CategoryStorage $categoryStorage,
        UrlGenerator $urlGenerator,
        BreadcrumbGenerator $breadcrumbGenerator,
        PostToFrontConverter $postToFrontConverter
    ): Response
    {
        $requestUrl = $request->getPathInfo();
        $requestedSlug = trim($requestUrl, '/');
        $requestedSlug = explode('/', $requestedSlug);
        $requestedSlug = end($requestedSlug);

        $post = $postRepository->findOneBy([
            'slug' => $requestedSlug
        ]);

        if (! empty($post)) {
            if (! empty($post->getCategory())) {
                $categoryStorage->addCategoryAncestorToStorageBy([
                    'id' => $post->getCategory()->getId()
                ]);
            }

            $generatedUrl = $urlGenerator->getUrl($post);
            $cutGeneratedUrl = parse_url($generatedUrl, PHP_URL_PATH);
            $cutGeneratedUrl = trim($cutGeneratedUrl, '/');

            $cutRequestUrl = trim($requestUrl, '/');

            if ($cutGeneratedUrl == $cutRequestUrl) {
                return $this->render('post.html.twig', [
                    'post' => $postToFrontConverter->getPostsOrPost($post),
                    'breadcrumb' => $breadcrumbGenerator->getBreadcrumbHtml($post)
                ]);
            }
        }

        $category = $categoryStorage->addCategoryAncestorToStorageBy([
            'slug' => $requestedSlug
        ])->addCategoryDescendantsToStorageBy([
            'slug' => $requestedSlug
        ])->getOneCategoryBy([
            'slug' => $requestedSlug
        ]);

        if (! empty($category)) {
            $generatedUrl = $urlGenerator->getUrl($category);
            $cutGeneratedUrl = parse_url($generatedUrl, PHP_URL_PATH);
            $cutGeneratedUrl = trim($cutGeneratedUrl, '/');

            $cutRequestUrl = trim($requestUrl, '/');

            if ($cutGeneratedUrl == $cutRequestUrl) {


                return $this->render('category.html.twig', [
                    'breadcrumb' => $breadcrumbGenerator->getBreadcrumbHtml($category)
                ]);
            }
        }

        throw new NotFoundException();
    }
}
