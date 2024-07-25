<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class RecipeController
{
    private $recipeRepository;

    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    public function list(): JsonResponse
    {
        $recipes = $this->recipeRepository->list();
        return new JsonResponse($recipes);
    }

    public function getRecipe(int $id): JsonResponse
    {
        $recipe = $this->recipeRepository->getRecipe($id);
        if ($recipe) {
            return new JsonResponse($recipe);
        } else {
            return new JsonResponse(['error' => 'Recipe not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($this->recipeRepository->create($data)) {
            return new JsonResponse(['status' => 'Recipe created'], Response::HTTP_CREATED);
        } else {
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($this->recipeRepository->update($id, $data)) {
            return new JsonResponse(['status' => 'Recipe updated']);
        } else {
            return new JsonResponse(['error' => 'Recipe not found or invalid data'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(int $id): JsonResponse
    {
        if ($this->recipeRepository->delete($id)) {
            return new JsonResponse(['status' => 'Recipe deleted']);
        } else {
            return new JsonResponse(['error' => 'Recipe not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function rate(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($this->recipeRepository->rate($id, $data)) {
            return new JsonResponse(['status' => 'Rating added']);
        } else {
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }
    }
}
