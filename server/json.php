<?php 
class JsonObject
{
    private $mCore;
    
    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->mCore = array();
    }

    /**
     * Sets a specific field of the JSON
     * object
     */
    public function setData(string $field, mixed $value): void
    {
        $this->mCore[$field] = $value;
    }

    /**
     * Sets a specific field as array in
     * the JSON object
     */
    public function setArray(string $field, array $array): void
    {
        $this->mCore[$field] = $array;
    }

    /**
     * Converts the core to JSON, to use as
     * response
     */
    public function toJson(): string | false
    {
        return json_encode($this->mCore);
    }

    /**
     * Wipes all the data inside the JSON
     * object
     */
    public function wipe(): void
    {
        $this->mCore = [];
    }
}
?>