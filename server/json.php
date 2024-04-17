<?php

/**
 * @brief Base class for a JSON Object
 */
class JsonObject
{
    //! @brief This class' core data
    private $mCore;

    /**
     * @brief Default constructor
     */
    public function __construct()
    {
        $this->mCore = array();
    }

    /**
     * @param field
     * @param value
     * 
     * @brief Sets a specific field of the JSON
     * object
     */
    public function setData(string $field, mixed $value): void
    {
        $this->mCore[$field] = $value;
    }

    /**
     * @param field
     * @param array
     * 
     * @brief Sets a specific field as array in
     * the JSON object
     */
    public function setArray(string $field, array $array): void
    {
        $this->mCore[$field] = $array;
    }

    /**
     * @brief Converts the core to JSON, to use as
     * response
     */
    public function toJson(): string | false
    {
        return json_encode($this->mCore);
    }

    /**
     * @brief Wipes all the data inside the JSON
     * object
     */
    public function wipe(): void
    {
        $this->mCore = [];
    }
}
