<?php 
class JsonObject
{
    private $mCore;
    public function __construct()
    {
        $this->mCore = array();
    }
    public function setData(string $field, mixed $value): void
    {
        $this->mCore[$field] = $value;
    }
    public function setArray(string $field, array $array): void
    {
        $this->mCore[$field] = $array;
    }
    public function toJson(): string | false
    {
        return json_encode($this->mCore);
    }
    public function wipe(): void
    {
        $this->mCore = null;
    }
}
?>