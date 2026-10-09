<?php
require_once __DIR__ . '/recipesModel.php';

/** Adaptateur conservé pour l'ancienne organisation MVC. */
class CategorieRecipesModel
{
    public function getRecipesAvecCategory(): array
    {
        return (new RecipesModel())->getAllRecipes();
    }

    public function getCategories(): array
    {
        return (new RecipesModel())->getCategories();
    }
}
