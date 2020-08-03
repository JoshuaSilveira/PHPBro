<?php
session_start();
$weight_comparison = '';
if(isset($_POST['weight']))
{
    $weight = (int)$_POST['weight'];

    if($weight <= 0 )
    {
        $weight_comparison = "No weight data! Get Lifting!";
    }
    elseif ($weight <= 5)
    {
        $comparison = ceil(($weight / 0.5));
        $weight_comparison = "You've lifted the equivalent of $comparison rats, you absolute <i>Gym Rat</i>!";
    }
    elseif ($weight <= 20)
    {
        $comparison = ceil(($weight / 3));
        $weight_comparison = "You've lifted the equivalent of $comparison Chihuahua dogs, you <i>Mad Dog</i>!";
    }
    elseif ($weight <= 20)
    {
        $comparison = ceil(($weight / 3));
        $weight_comparison = "You've lifted the equivalent of $comparison Chihuahua dogs, you <i>Mad Dog</i>!";
    }
    elseif ($weight <= 50)
    {
        $comparison = ceil(($weight / 15));
        $weight_comparison = "You've lifted the equivalent of $comparison Bowling Balls, you <i>Wrecking Ball</i>!";
    }
    elseif ($weight <= 100)
    {
        $comparison = ceil(($weight / 20));
        $weight_comparison = "You've lifted the equivalent of $comparison car tires, you <i>Hot Rod</i>!";
    }
    elseif ($weight <= 200)
    {
        $comparison = ceil(($weight / 33));
        $weight_comparison = "You've lifted the equivalent of $comparison Cinder Blocks, you <i>Brick House</i>!";
    }
    elseif ($weight <= 500)
    {
        $comparison = ceil(($weight / 77));
        $weight_comparison = "You've lifted the equivalent of $comparison Gold Bricks, you <i>Fort Knox of Lifters</i>!";
    }
    elseif ($weight <= 1000)
    {
        $comparison = ceil(($weight / 400));
        $weight_comparison = "You've lifted the equivalent of $comparison Pianos, you <i>One Man Moving Company</i>!";
    }
    elseif ($weight <= 5000)
    {
        $comparison = ceil(($weight / 930));
        $weight_comparison = "You've lifted the equivalent of $comparison Horses, you <i>Stallion</i>!";
    }
    elseif ($weight <= 10000)
    {
        $comparison = ceil(($weight / 2600));
        $weight_comparison = "You've lifted the equivalent of $comparison Medium-sized Cruise Missiles, you <i>Walking Apocalypse</i>!";
    }
    elseif ($weight <= 20000)
    {
        $comparison = ceil(($weight / 5000));
        $weight_comparison = "You've lifted the equivalent of $comparison Large Meteors, you <i>Cosmic Crusher</i>!";
    }
    elseif ($weight <= 50000)
    {
        $comparison = ceil(($weight / 9000));
        $weight_comparison = "You've lifted the equivalent of $comparison Monster Trucks, you....you <i>Monster Truck</i>!";
    }
    elseif ($weight <= 100000)
    {
        $comparison = ceil(($weight / 40000));
        $weight_comparison = "You've lifted the equivalent of $comparison Mobile Homes, you <i>Human Tornado</i>!";
    }
    elseif ($weight <= 200000)
    {
        $comparison = ceil(($weight / 80000));
        $weight_comparison = "You've lifted the equivalent of $comparison Bulldozers, you, uh, <i>Bulldozer</i>!!!";
    }
    elseif ($weight <= 500000)
    {
        $comparison = ceil(($weight / 110000));
        $weight_comparison = "You've lifted the equivalent of $comparison Tanks, you <i>One-Man Army</i>!";
    }
    elseif ($weight <= 1000000)
    {
        $comparison = ceil(($weight / 250000));
        $weight_comparison = "You've lifted the equivalent of $comparison Locomotive Engines, you <i>Runaway Train</i>!";
    }
    elseif ($weight > 1000000)
    {
        $comparison = ceil(($weight / 450000));
        $weight_comparison = "You've lifted the equivalent of $comparison Statue of Liberties, you <i>Bronze God</i>!";
    }
    else
    {
        $weight_comparison = "There was an error, <i>You lifting maniac!</i>!";
    }

    $jsonstu = json_encode($weight_comparison);

    header('Content-Type: Application/json');
    echo $jsonstu;

}