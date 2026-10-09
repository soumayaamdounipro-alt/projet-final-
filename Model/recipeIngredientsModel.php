<?php
require_once __DIR__ . '/recipesModel.php';

class RecetteIngredientsModel
{
    public function getIngredientsParRecipe(int $recipeId): array
    {
        return (new RecipesModel())->getIngredientsByRecipe($recipeId);
    }
}
