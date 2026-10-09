<?php
/** Lecture des recettes et des catégories depuis MySQL (plus de fichier JSON). */
require_once __DIR__ . '/db.php';

class RecipesModel
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    /** @return array<int,array<string,mixed>> */
    public function getAllRecipes(): array
    {
        $sql = "SELECT r.recipe_id AS id, r.name, r.preparation_time, r.time_label,
                       r.people, r.emoji, r.image_path,
                       COALESCE(c0.label, 'Autres') AS tag,
                       COALESCE(GROUP_CONCAT(DISTINCT c.slug ORDER BY c.slug SEPARATOR ','), '') AS category_slugs
                FROM recipes r
                LEFT JOIN category c0 ON c0.id = r.category_id
                LEFT JOIN recipe_categories rc ON rc.recipe_id = r.recipe_id
                LEFT JOIN category c ON c.id = rc.category_id
                GROUP BY r.recipe_id, r.name, r.preparation_time, r.time_label,
                         r.people, r.emoji, r.image_path, c0.label
                ORDER BY r.recipe_id DESC";
        $rows = $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
        return array_map([$this, 'normalizeRecipe'], $rows);
    }

    public function getRecipeById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT r.recipe_id AS id, r.name, r.preparation_time,
                   r.time_label, r.people, r.emoji, r.image_path,
                   COALESCE(c0.label,'Autres') AS tag,
                   COALESCE(GROUP_CONCAT(DISTINCT c.slug ORDER BY c.slug SEPARATOR ','),'') AS category_slugs
            FROM recipes r LEFT JOIN category c0 ON c0.id=r.category_id
            LEFT JOIN recipe_categories rc ON rc.recipe_id=r.recipe_id
            LEFT JOIN category c ON c.id=rc.category_id
            WHERE r.recipe_id=?
            GROUP BY r.recipe_id,r.name,r.preparation_time,r.time_label,
                     r.people,r.emoji,r.image_path,c0.label LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $recipe = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $recipe ? $this->normalizeRecipe($recipe) : null;
    }

    private function normalizeRecipe(array $recipe): array
    {
        $recipe['id'] = (int)$recipe['id'];
        $recipe['people'] = max(1, (int)$recipe['people']);
        $recipe['categories'] = array_values(array_filter(explode(',', (string)$recipe['category_slugs'])));
        $recipe['time'] = $recipe['time_label'] ?: $this->formatTime($recipe['preparation_time']);
        return $recipe;
    }

    private function formatTime(string $time): string
    {
        [$h,$m] = array_map('intval', explode(':',$time));
        return ($h ? $h.' h' : '') . ($m ? ($h ? ' ' : '').$m.' min' : ($h ? '' : '0 min'));
    }

    public function getCategories(): array
    {
        return $this->db->query("SELECT c.id, c.label, c.slug, c.emoji, c.description,
                   COUNT(DISTINCT rc.recipe_id) AS recipe_count
            FROM category c LEFT JOIN recipe_categories rc ON rc.category_id=c.id
            GROUP BY c.id,c.label,c.slug,c.emoji,c.description ORDER BY c.id")
            ->fetch_all(MYSQLI_ASSOC);
    }

    public function getIngredientsByRecipe(int $id): array
    {
        $stmt=$this->db->prepare("SELECT i.name, i.unity AS unit, ri.quantity AS qty
            FROM recipe_ingredients ri INNER JOIN ingredients i ON i.ingredient_id=ri.ingredient_id
            WHERE ri.recipe_id=? ORDER BY ri.id");
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getStepsByRecipe(int $id): array
    {
        $stmt=$this->db->prepare('SELECT step_order, instruction FROM recipe_steps WHERE recipe_id=? ORDER BY step_order');
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getSiteCards(string $section): array
    {
        $stmt=$this->db->prepare('SELECT icon,subtitle,title,description FROM site_cards WHERE section=? ORDER BY sort_order,id');
        $stmt->bind_param('s',$section);
        $stmt->execute();
        $rows=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getCounts(): array
    {
        return [
            'recipes' => (int)$this->db->query('SELECT COUNT(*) AS total FROM recipes')->fetch_assoc()['total'],
            'categories' => (int)$this->db->query('SELECT COUNT(*) AS total FROM category')->fetch_assoc()['total'],
            'members' => (int)$this->db->query('SELECT COUNT(*) AS total FROM users')->fetch_assoc()['total'],
        ];
    }

    public function getFavoriteIds(int $userId): array
    {
        if ($userId <= 0) return [];
        $stmt=$this->db->prepare('SELECT id_recipes FROM favorites WHERE id_user=?');
        $stmt->bind_param('i',$userId);
        $stmt->execute();
        $rows=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return array_map('intval',array_column($rows,'id_recipes'));
    }

    public function setFavorite(int $userId, int $recipeId, bool $add): void
    {
        if ($add) {
            $stmt=$this->db->prepare('INSERT INTO favorites (id_user,id_recipes) VALUES (?,?) ON DUPLICATE KEY UPDATE id_recipes=VALUES(id_recipes)');
        } else {
            $stmt=$this->db->prepare('DELETE FROM favorites WHERE id_user=? AND id_recipes=?');
        }
        $stmt->bind_param('ii',$userId,$recipeId);
        $stmt->execute();
        $stmt->close();
    }

    public function subscribe(string $email): void
    {
        $stmt=$this->db->prepare('INSERT IGNORE INTO newsletter_subscribers (email) VALUES (?)');
        $stmt->bind_param('s',$email);
        $stmt->execute();
        $stmt->close();
    }
}
