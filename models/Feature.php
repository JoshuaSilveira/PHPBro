<?php
namespace fitnessTracker\Models\Feature;

interface Feature {
    //Get the Feature (From the table and set it)
    //return the inherited feature object
    public function GetById($dbcon, $id);

    //List out all the rows in the Features (specific feature) tables
    public function GetAll($dbcon);

    //Add the Feature to the specific Features table
    //returns nothing (void)
    //$inputs is an associative array of the inputs being sent
    public function Add($dbcon, $inputs);

    //Update the Feature to the specific Features table
    public function Update($dbcon, $inputs);

    //Delete the Feature to the specific Features table
    public function Delete($dbcon, $id);
}
?>