<?php
namespace SQLData;

use Models\BaseModel;
use Models\UsersModel;

class UsersSQLData extends BaseSQLData
{
    const TABLE_NAME = 'Users';
    const MODEL_NAME = UsersModel::class;

    public function createObject($arrayData)
    {
        $inst = new UsersModel($arrayData);
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
        $sql = 'INSERT INTO '.self::TABLE_NAME.' (login, password) VALUES (:login, :password)';
        $prepared = $co->prepare($sql);
        $result = $prepared->execute([
      ':login' => $obj->getLogin(),
      ':password' => $obj->getPassword(),
    ]);
        return $result ? intval($co->lastInsertId()) : false;
    }

    public function updateObject(BaseModel $obj)
    {
        $co = $this->getConnection();
        $sql = 'UPDATE '.self::TABLE_NAME.' SET login = :login, password = :password WHERE id = :id LIMIT 1;';
        $prepared = $co->prepare($sql);
        return $prepared->execute([
      ':login' => $obj->getLogin(),
      ':password' => $obj->getPassword(),
      ':id' => $obj->getId(),
    ]);
    }
}
