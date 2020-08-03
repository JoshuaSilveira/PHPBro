<?php

namespace fitnessTracker\includes;

class Validator
{
    private function __construct()
    {
    }

    //Check if the value is not empty
    //return bool flag where true is valid (value is not empty) otherwise false
    public function CheckEmpty($value)
    {
        return !($value == "");
    }

    //Check if the value is a positive number
    //0 is not considered as a positive number
    //return bool flag where true is valid otherwise false
    public function CheckPositive($value)
    {
        return is_int($value) && $value > 0;
    }

    //Check if the value is a valid Address
    //return bool flag where true is valid otherwise false
    public function CheckAddress($value)
    {
        //Pattern is obtained from Google Search a Address Regex
        $addressPattern = "/^\d+\s([A-z]+\s)+[A-z]+$/i";
        return preg_match($addressPattern, $value);
    }

    //Check if the value is a valid date
    //return bool flag where true is valid otherwise false
    public function CheckDate($value)
    {
        //Pattern is obtained from Google Search a Date Regex
        $datePattern = "/^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$/i";
        return preg_match($datePattern, $value);
    }

    public function CheckDateTime($value)
    {
        //Pattern is obtained from Google Search a Date Regex and Time Regex and custom change to match format
        $datePattern = "/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])T((((0[1-9])|(1[0-9]))|(2[0-3])):([0-5])(0|5))(:00)*$/i";
        return preg_match($datePattern, $value);
    }

    //Function is called when you want to know that the form was valid or not
    // (Must call a other Validate***Form first to get the error messages)
    //return bool flag where true is the form is valid otherwise false
    public static function IsFormValid($errorMessages)
    {
        foreach ($errorMessages as $errorMessage) {
            if ($errorMessage != "") {
                return false;
            }
        }
        //The form was valid
        return true;
    }

    //Validate the form for the AddRoute and UpdateRoute
    //return array of error messages of size 3
    public static function ValidateRouteForm($name, $startAddress, $endAddress)
    {
        $errorMessages = [];
        //Validate name
        if (self::CheckEmpty($name)) {
            $errorMessages[] = "";
        } else {
            $errorMessages[] = "Please enter a name";
        }
        //Validate start address
        if (self::CheckEmpty($startAddress) && self::CheckAddress($startAddress)) {
            $errorMessages[] = "";
        } else if (!self::CheckEmpty($startAddress)) {
            $errorMessages[] = "Please enter a address";
        } else {
            $errorMessages[] = "Please enter a valid address";
        }
        //Validate end address
        if (self::CheckEmpty($endAddress) && self::CheckAddress($endAddress)) {
            $errorMessages[] = "";
        } else if (!self::CheckEmpty($endAddress)) {
            $errorMessages[] = "Please enter a address";
        } else {
            $errorMessages[] = "Please enter a valid address";
        }
        return $errorMessages;
    }

    //Validate the form for the AddCalorie and UpdateCalorie
    //return array of error messages of size 5
    public static function ValidateCalorieForm($height, $weight, $date, $intake, $burned)
    {
        $errorMessages = [];
        //Validate height
        if (self::CheckEmpty($height) && self::CheckPositive($height)) {
            $errorMessages[] = "";
        } else if (!self::CheckEmpty($height)) {
            $errorMessages[] = "Please enter a height";
        } else {
            $errorMessages[] = "Please enter a valid height";
        }
        //Validate weight
        if (self::CheckEmpty($weight) && self::CheckPositive($weight)) {
            $errorMessages[] = "";
        } else if (!self::CheckEmpty($weight)) {
            $errorMessages[] = "Please enter a weight";
        } else {
            $errorMessages[] = "Please enter a valid weight";
        }
        //Validate date
        if (self::CheckEmpty($date) && self::CheckDate($date)) {
            $errorMessages[] = "";
        } else if (!self::CheckEmpty($date)) {
            $errorMessages[] = "Please enter a date";
        } else {
            $errorMessages[] = "Please enter a valid date";
        }
        //Validate intake
        if (self::CheckEmpty($intake) && self::CheckPositive($intake)) {
            $errorMessages[] = "";
        } else if (!self::CheckEmpty($intake)) {
            $errorMessages[] = "Please enter a intake amount";
        } else {
            $errorMessages[] = "Please enter a valid intake amount";
        }
        //Validate burned
        if (self::CheckEmpty($burned) && self::CheckPositive($burned)) {
            $errorMessages[] = "";
        } else if (!self::CheckEmpty($burned)) {
            $errorMessages[] = "Please enter a burned amount";
        } else {
            $errorMessages[] = "Please enter a valid burned amount";
        }
        return $errorMessages;
    }

    //Validate the form for the ListEvent
    //return array of error messages of size 3
    public static function ValidateEventFilterForm($startDate, $endDate, $location)
    {
        $errorMessages = [];
        //Validate date
        if (self::CheckDate($startDate)) {
            $errorMessages[] = "";
        } else {
            $errorMessages[] = "Please enter a valid start date";
        }
        //Validate date
        if (self::CheckDate($endDate)) {
            $errorMessages[] = "";
        } else {
            $errorMessages[] = "Please enter a valid end date";
        }
        //Validate location
        //Still need to change validation of the location!!!!
        if (self::CheckEmpty($location)) {
            $errorMessages[] = "";
        } else {
            $errorMessages[] = "Please enter a valid location";
        }
        return $errorMessages;
    }

    //Validate the form for the AddEvent and UpdateEvent
    //return array of error messages of size 4
    public static function ValidateEventForm($name, $datetime, $duration, $location)
    {
        $errorMessages = [];
        //Validate name
        if (self::CheckEmpty($name)) {
            $errorMessages[] = "";
        } else {
            $errorMessages[] = "Please enter a name";
        }
        //Validate date time
        if (self::CheckEmpty($datetime) && self::CheckDateTime($datetime)) {
            $errorMessages[] = "";
        } else if (!self::CheckEmpty($datetime)) {
            $errorMessages[] = "Please enter a date time";
        } else {
            $errorMessages[] = "Please enter a valid date time";
        }
        //Validate duration
        if (self::CheckEmpty($duration) && self::CheckPositive($duration)) {
            $errorMessages[] = "";
        } else if (!self::CheckEmpty($duration)) {
            $errorMessages[] = "Please enter a duration amount";
        } else {
            $errorMessages[] = "Please enter a valid duration amount";
        }
        //Validate location
        //Still need to change validation of the location!!!!
        if (self::CheckEmpty($location)) {
            $errorMessages[] = "";
        } else {
            $errorMessages[] = "Please enter a valid location";
        }
        return $errorMessages;
    }

    /**
     * Scrubs special elements and characters from an input. Reference - https://www.w3schools.com/php/php_form_validation.asp
     * @param string $data input to be scrubbed
     * @return string the scrubbed string
     */
    public static function scrubInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    /**
     * Validates a URL with regex and adds http:// to the front if its not there.
     * Returns the new URL if valid, returns false if invalid. Reference - https://stackoverflow.com/questions/2762061/how-to-add-http-if-it-doesnt-exist-in-the-url
     * @param string $url URL to be validated
     * @return string A URL with http:// at the front if not already there
     * @return bool Returns false if URL
     */
    public static function validateUrl($url)
    {
        $urlPattern = '#[-a-zA-Z0-9@:%_\+.~\#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~\#?&//=]*)?#si';
        if (preg_match($urlPattern, $url)) {
            //check for http or similar
            if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                $url = "http://" . $url;
            }
            return $url;
        } else {
            //didn't match regex, return false
            return false;
        };
    }
    /**
     * Validates an uploaded image (through $_FILES array), making sure it is of the file type JPG, JPEG, GIF, or PNG and that the filesize is below 500000.
     * Returns "* File size too large, must be below 500000 bytes." if the file type is too large.
     * Returns "* Wrong file type. (JPG, JPEG, GIF, or PNG only.)" if the wrong file type was uploaded.
     * Returns true if the image is valid.
     * @param array $image a $_FILES array to be validated.
     * @return string A string error message dictating why the file is invalid
     * @return bool Returns true if valid image file
     */
    public static function validateImage($image)
    {
        $target_dir = "../../img/meals/";
        $target_file = $target_dir . basename($image["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($image["fileToUpload"]["size"] > 500000) {
            return "* File size too large, must be below 500000 bytes.";
        } else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"
        ) {
            return "* Wrong file type. (JPG, JPEG, GIF, or PNG only)";
        } else {
            //right file type and within size limits. return true
            return true;
        };
    }
}
