<?php

namespace App\Repository;

use mysqli;

class RecipeRepository
{
    private $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function list(): array
    {
        $query = "SELECT * FROM recipe";
        $result = $this->conn->query($query);
        $recipe_list = [];

        while ($row = $result->fetch_assoc()) {
            $recipe_list[] = $row;
        }

        return $recipe_list;
    }

    public function getRecipe(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM recipe WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $recipe = $result->fetch_assoc();

        return $recipe ?: null;
    }

    public function create(array $data): bool
    {
        $name = $data['name'];
        $prep_time = $data['prep_time'];
        $difficulty = $data['difficulty'];
        $vegetarian = $data['vegetarian'];
        $rating = $data['rating'];

        if ($rating >= 1 && $rating <= 5 && $difficulty >= 1 && $difficulty <= 3) {
            $stmt = $this->conn->prepare("INSERT INTO recipe (name, prep_time, difficulty, vegetarian, rating) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('ssiii', $name, $prep_time, $difficulty, $vegetarian, $rating);

            return $stmt->execute();
        }

        return false;
    }

    public function update(int $id, array $data): bool
    {
        $name = $data['name'];
        $prep_time = $data['prep_time'];
        $difficulty = $data['difficulty'];
        $vegetarian = $data['vegetarian'] ? 1 : 0;

        if ($difficulty >= 1 && $difficulty <= 3) {
            $stmt = $this->conn->prepare("UPDATE recipe SET name = ?, prep_time = ?, difficulty = ?, vegetarian = ? WHERE id = ?");
            $stmt->bind_param('ssiii', $name, $prep_time, $difficulty, $vegetarian, $id);

            return $stmt->execute();
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM recipe WHERE id = ?");
        $stmt->bind_param('i', $id);

        return $stmt->execute();
    }

    public function rate(int $id, array $data): bool
    {
        $rating = $data['rating'];

        if ($rating >= 1 && $rating <= 5) {
            $stmt = $this->conn->prepare("INSERT INTO ratings (recipe_id, rating) VALUES (?, ?)");
            $stmt->bind_param('ii', $id, $rating);
            return $stmt->execute();
        }

        return false;
    }
}
?>