<?php
namespace SQLData;

use Models\BaseModel;
use Application\DbConnection;

abstract class BaseSQLData
{
    const TABLE_NAME = '';
    const FIELD_DEFAULT = '*';
    const MODEL_NAME = '';

    private $dbConnection = null;

    public function __construct(DbConnection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    protected function getConnection()
    {
        return $this->dbConnection->getConnection();
    }

    public function findAll($field = null)
    {
        return $this->find($field);
    }

    public function findOne($id, $field = null, $order = [])
    {
        $result = $this->find($field, ['id' => $id], 1, $order);

        return count($result) > 0 ? $result[0] : null;
    }

    public function findBy($params, $field = null, $limit = '', $order = [])
    {
        return $this->find($field, $params, $limit, $order);
    }

    public function findOneBy($params, $field = null, $order = [])
    {
        $result = $this->find($field, $params, '1', $order);

        return count($result) > 0 ? $result[0] : null;
    }

    protected function find($field = null, $params = [], $limit = '', $order = '')
    {
        $co = $this->getConnection();
        $field = $this->getSelectField($field);

        if (empty(static::TABLE_NAME)) {
            throw new Exception("TABLE_NAME can not be empty", 500);
        }

        $sql = 'SELECT '.$field.' FROM ' . static::TABLE_NAME;

        $where = '';
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if ($where != '') {
                    $where .= ' AND';
                }
                $dotKey = ':'.$key;
                $where .= ' '.$key.' = ' . $dotKey;
                $preparedParams[$dotKey] = $value;
            }
            $sql .= ' WHERE '.$where;
        }

        if (!empty($limit)) {
            $sql .= ' LIMIT '.intval($limit);
        }

        if (!empty($order)) {
            $sql .= ' ORDER BY :order';
            $params[':order'] = $order;
        }

        $prepared = $co->prepare($sql);
        $prepared->execute($preparedParams);

        $results = $prepared->fetchAll(\PDO::FETCH_ASSOC);
        $objectList = [];
        $model = static::MODEL_NAME;
        foreach ($results as $result) {
            $objectList[] = new $model($result);
        }

        return $objectList;
    }

    protected function getSelectField($field)
    {
        return !is_null($field) ? $field : static::FIELD_DEFAULT;
    }

    abstract public function createObject($arrayData);
    abstract public function saveObject(BaseModel $obj);
    abstract public function insertObject(BaseModel $obj);
    abstract public function updateObject(BaseModel $obj);
}
