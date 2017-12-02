<?php
namespace Models;

abstract class BaseModel
{
    protected $id;

    public function __construct($dataArray)
    {
        if (isset($dataArray['id'])) {
            $this->setId(intval($dataArray['id']));
        }
    }

    public function setId($id)
    {
        if (!is_int($id) || !($id > 0)) {
            throw new \Exception("Message.id must be a positive integer value", 500);
        }
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
}
