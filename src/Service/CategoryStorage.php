<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryStorage
{
    public array $categoriesList = [];

    public function __construct(public CategoryRepository $categoryRepository)
    {
    }

    public function addCategoryAncestorToStorageBy(array $criteria): self
    {
        if (! $this->categoryRepository->isAvailabilityToFindCriteria($criteria)) {
            return $this;
        }

        $categories = $this->categoryRepository->findAncestorsBy($criteria);

        foreach ($categories as $newCategory) {
            $isExistingCategory = false;

            foreach ($this->categoriesList as $alreadyLocatedCategory) {
                if ($newCategory->getID() == $alreadyLocatedCategory->getId()) {
                    $isExistingCategory = true;

                    break;
                }
            }

            if (! $isExistingCategory) {
                $this->categoriesList[] = $newCategory;
            }
        }

        return $this;
    }

    public function addCategoryDescendantsToStorageBy(array $criteria): self
    {
        if (! $this->categoryRepository->isAvailabilityToFindCriteria($criteria)) {
            return $this;
        }

        $categories = $this->categoryRepository->findDescendantsBy($criteria);

        foreach ($categories as $newCategory) {
            $isExistingCategory = false;

            foreach ($this->categoriesList as $alreadyLocatedCategory) {
                if ($newCategory->getID() == $alreadyLocatedCategory->getId()) {
                    $isExistingCategory = true;

                    break;
                }
            }

            if (! $isExistingCategory) {
                $this->categoriesList[] = $newCategory;
            }
        }

        return $this;
    }

    public function getOneCategoryBy(array $criteria): ?Category
    {
        if (! $this->categoryRepository->isAvailabilityToFindCriteria($criteria)) {
            return null;
        }

        foreach ($this->categoriesList as $category) {
            $isRightCategory = true;

            foreach ($criteria as $criterionKey => $criterionValue) {
                $criterionKey = strtolower($criterionKey);
                $criterionKey = ucfirst($criterionKey);
                $methodName = 'get' . $criterionKey;

                if ($category->$methodName() != $criterionValue) {
                    $isRightCategory = false;
                }
            }

            if ($isRightCategory) {
                return $category;
            }
        }

        return null;
    }
}
