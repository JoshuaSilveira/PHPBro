<?php

namespace fitnessTracker\Models\Meal;

use fitnessTracker\Models\Feature\Feature;

require_once 'Feature.php';

/**
 * A class holding CRUD methods for the Meals table and feature. Method names used from Chris Maeda's Feature interface for consistency.
 */
class Meal implements Feature
{
    public function GetById($dbcon, $id)
    {
        $sql = "SELECT * FROM meals WHERE id = :id";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':id', $id);
        $pdostm->execute();
        $meals = $pdostm->fetchAll(\PDO::FETCH_ASSOC);
        return $meals[0];
    }

    public function GetAll($dbcon)
    {
        $sql = "SELECT * FROM meals";
        $pdostm =  $dbcon->prepare($sql);
        $pdostm->execute();

        $meals = $pdostm->fetchAll(\PDO::FETCH_ASSOC);
        return $meals;
    }

    /**
     * Adds a meal to the database with the given parameters.
     * @param PDO $dbcon The database connection
     * @param string $name The name of the meal
     * @param int $calories The amount of calories
     * @param int $protein The amount of protein in grams
     * @param int $preptime The preperation time in minutes
     * @param bool $isVegan Whether the meal is vegan or not
     * @param string $description The description of the meal
     * @param string $url The url for cooking instructions
     * @return numRowsAffected
     */
    public function AddMeal($dbcon, $name, $calories, $protein, $preptime, $isVegan, $description, $url)
    {
        $image = ''; //image is empty at the add stage. Will be uploaded in update

        //SQL order - prepare, bind, execute 
        $query = "INSERT into meals (name, image_ext, calories, protein, prep_time, is_vegan, description, url) VALUES (:name, :image, :calories, :protein, :preptime, :isvegan, :description, :url)";
        $pdostm = $dbcon->prepare($query); //prepares
        //similar to sqlParam in asp.net
        $pdostm->bindParam(':name', $name);
        $pdostm->bindParam(':image', $image);
        $pdostm->bindParam(':calories', $calories);
        $pdostm->bindParam(':protein', $protein);
        $pdostm->bindParam(':preptime', $preptime);
        $pdostm->bindParam(':isvegan', $isVegan);
        $pdostm->bindParam(':description', $description);
        $pdostm->bindParam(':url', $url);

        $numRowsAffected = $pdostm->execute();
        return $numRowsAffected;
    }

    //has to be here for interface to not crash
    public function Add($dbcon, $inputs)
    {
        //AddMeal() is used instead
    }

    //has to be here for interface to not crash
    public function Update($dbcon, $inputs)
    {
        //UpdateMeal is used instead
    }

    /**
     * Adds a meal to the database with the given parameters.
     * @param PDO $dbcon The database connection
     * @param int $id The id of the meal being updated
     * @param string $name The name of the meal
     * @param int $calories The amount of calories
     * @param int $protein The amount of protein in grams
     * @param int $preptime The preperation time in minutes
     * @param bool $isVegan Whether the meal is vegan or not
     * @param string $description The description of the meal
     * @param string $url The url for cooking instructions
     * @param string $imageExt The string representation of the filepath of the image uploaded for this image
     * @return numRowsAffected
     */
    public function UpdateMeal($dbcon, $id, $name, $calories, $protein, $preptime, $isVegan, $description, $url, $imageExt)
    {
        $query = "UPDATE meals SET name = :name, image_ext = :image, calories = :calories, protein = :protein, prep_time = :preptime, is_vegan = :isvegan, description = :description, url = :url WHERE id = :id";
        $pdostm = $dbcon->prepare($query); //prepares
        //similar to sqlParam in asp.net
        $pdostm->bindParam(':name', $name);
        $pdostm->bindParam(':image', $imageExt);
        $pdostm->bindParam(':calories', $calories);
        $pdostm->bindParam(':protein', $protein);
        $pdostm->bindParam(':preptime', $preptime);
        $pdostm->bindParam(':isvegan', $isVegan);
        $pdostm->bindParam(':description', $description);
        $pdostm->bindParam(':url', $url);
        $pdostm->bindParam(':id', $id);

        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Updated a meal.";
            header("Location:List.php?action=updated");
        } else {
            echo "Problem updating " . $name;
            $pdostm->debugDumpParams();
            var_dump($_POST);
        }
    }

    /**
     * Uploads the given $_FILES array to the img/meals folder. Reference - https://www.w3schools.com/php/php_file_upload.asp
     * @param array $file The desired $_FILES array to be uploaded.
     * @param int $id The ID of the meal associated with this image
     * @return string The filepath of the uploaded image ie. ../../img/meals/3.png
     *  */
    public function UploadImage($id, $file)
    {
        var_dump($file);
        $target_dir = "../../img/meals/";
        $target_file = $target_dir . basename($file["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $target_file = $target_dir . $id . "." . $imageFileType; // setting the target file to be the target directory/id.filetype ie. img/meals/3.jpg
        var_dump($target_file);
        var_dump($file["image"]["tmp_name"]);
        if (move_uploaded_file($file["image"]["tmp_name"], $target_file)) {
            echo "The file " . basename($file["image"]["name"]) . " has been uploaded.";
            //echo "<img src=\"".$target_file."\" />";
            return $target_file;
        } else {
            echo "Sorry, there was an error uploading the image.";
        }
    }

    /**
     * Deletes a meal at the given ID from the database.
     * @param PDO $dbcon The database connection
     * @param string $id The ID of the row being deleted
     */
    public function Delete($dbcon, $id)
    {
        //TODO: delete associated image file

        $query = "DELETE FROM meals WHERE id = :id";
        $pdostm = $dbcon->prepare($query);
        $pdostm->bindParam(':id', $id);
        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Deleted a meal";
            header("Location: List.php?action=deleted");
            exit;
        } else {
            echo "Problem deleting a meal";
        }
    }

    /**
     * Searches the list of meals for the given input. Returns all entries that match the input.
     * @param PDO $dbcon The database connection
     * @param string $input the value to be searched for
     * @return array A list of Meals that match the search input
     */
    public function SearchMeals($dbcon, $input)
    {
        $sql = "SELECT * FROM meals WHERE name LIKE CONCAT('%', :input, '%')";
        $pdostm =  $dbcon->prepare($sql);
        $pdostm->bindParam(':input', $input);
        $pdostm->execute();

        $meals = $pdostm->fetchAll(\PDO::FETCH_ASSOC);
        return $meals;
    }
}
