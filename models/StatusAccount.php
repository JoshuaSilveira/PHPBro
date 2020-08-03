<?php
namespace fitnessTracker\Models\StatusAccount;


class StatusAccount
{
    private $id;
    private $username;
    private $password;
    private $email;
    private $city;
    private $is_findable;
    private $experience;
    private $first_name;
    private $last_name;
    private $current_weight;
    private $current_height;
    private $is_admin;

    /**
     * StatusAccount constructor.
     * @param $id
     * @param $username
     * @param $password
     * @param $email
     * @param $city
     * @param $is_findable
     * @param $experience
     * @param $first_name
     * @param $last_name
     * @param $current_weight
     * @param $current_height
     * @param $is_admin
     */
    public function __construct($username, $password, $email, $city, $is_findable, $experience, $first_name, $last_name, $current_weight, $current_height, $is_admin,$id='')
    {

        $this->setUsername($username);
        $this->setPassword($password);

        $this->setEmail($email);

        $this->setCity($city);
        $this->setIsFindable($is_findable);
        $this->setExperience($experience);

        $this->setFirstName($first_name);
        $this->setLastName($last_name);

        $this->setCurrentWeight($current_weight);
        $this->setCurrentHeight($current_height);

        $this->setIsAdmin($is_admin);

        $this->setId($id);
    }

    public function __set($name, $value)
    {
        
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getIsFindable()
    {
        return $this->is_findable;
    }

    /**
     * @param mixed $is_findable
     */
    public function setIsFindable($is_findable)
    {
        $this->is_findable = $is_findable;
    }

    /**
     * @return mixed
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param mixed $experience
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return mixed
     */
    public function getCurrentWeight()
    {
        return $this->current_weight;
    }

    /**
     * @param mixed $current_weight
     */
    public function setCurrentWeight($current_weight)
    {
        $this->current_weight = $current_weight;
    }

    /**
     * @return mixed
     */
    public function getCurrentHeight()
    {
        return $this->current_height;
    }

    /**
     * @param mixed $current_height
     */
    public function setCurrentHeight($current_height)
    {
        $this->current_height = $current_height;
    }

    /**
     * @return mixed
     */
    public function getIsAdmin()
    {
        return $this->is_admin;
    }

    /**
     * @param mixed $is_admin
     */
    public function setIsAdmin($is_admin)
    {
        $this->is_admin = $is_admin;
    }


    
}