<?php
namespace SQLData;

use Models\BaseModel;
use Models\MessagesModel;

class MessagesSQLData extends BaseSQLData
{
    const TABLE_NAME = 'Messages';
    const MODEL_NAME = MessagesModel::class;

    public function createObject($arrayData)
    {
        $inst = new MessagesModel($arrayData);
        return $inst;
    }

    public function saveObject(BaseModel $obj)
    {
        if (is_null($obj->getId())) {
            return $this->insertObject($obj);
        } else {
            return $this->updateObject($obj);
        }
    }

    public function insertObject(BaseModel $obj)
    {
        $co = $this->getConnection();
        $sql = 'INSERT INTO '.self::TABLE_NAME.' (author, date, content) VALUES (:author, :date, :content)';
        $prepared = $co->prepare($sql);
        $result = $prepared->execute([
      ':author' => $obj->getAuthor(),
      ':date' => $obj->getDate()->format('Y-m-d H:i:s'),
      ':content' => $obj->getContent(),
    ]);

        return $result;
    }

    public function updateObject(BaseModel $obj)
    {
        $co = $this->getConnection();
        $sql = 'UPDATE '.self::TABLE_NAME.' SET author = :author, date = :date, content = :content WHERE id = :id LIMIT 1;';
        $prepared = $co->prepare($sql);
        return $prepared->execute([
      ':author' => $obj->getAuthor(),
      ':date' => $obj->getDate()->format('Y-m-d H:i:s'),
      ':content' => $obj->getContent(),
      ':id' => $obj->getId(),
    ]);
    }
}
