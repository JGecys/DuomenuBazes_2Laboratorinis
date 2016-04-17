<?php
/**
 * Created by PhpStorm.
 * User: jgecy
 * Date: 2016-04-17
 * Time: 16:55
 */


interface ilist
{
    public function get($id);
    public function getAll($limit = null, $offset = null);
    public function getCount();
    public function delete($id);
    public function getReferenceCount($id);
    public function getMaxId();
}

interface pagelayout{
    public function getTableHeaders();
    public function getRowFromData($data);
    public function getSuccessMessage();
    public function getErrorMessage();
    public function getCreateNewMessage();
    public function getPath();
}

abstract class base implements ilist, pagelayout{
    
    abstract public function getId();
    abstract protected function getTable();
    abstract protected function getChildReference();
    abstract protected function getChildTable();
    abstract protected function getChildCount();

    public abstract function insert($data);
    public abstract function update($data);

    public function get($id)
    {
        $query = "  SELECT *
					FROM {$this->getTable()}
					WHERE {$this->getId()}='{$id}'";
        $data = mysql::select($query);

        return $data[0];
    }

    public function getAll($limit = null, $offset = null)
    {
        $limitOffsetString = "";
        if (isset($limit)) {
            $limitOffsetString .= " LIMIT {$limit}";
        }
        if (isset($offset)) {
            $limitOffsetString .= " OFFSET {$offset}";
        }

        $query = "  SELECT *
					FROM {$this->getTable()}" . $limitOffsetString;
        $data = mysql::select($query);

        return $data;
    }
    
    protected function getAllCustomQuery($query, $limit = null, $offset = null){
        $limitOffsetString = "";
        if (isset($limit)) {
            $limitOffsetString .= " LIMIT {$limit}";
        }
        if (isset($offset)) {
            $limitOffsetString .= " OFFSET {$offset}";
        }

        $data = mysql::select($query);

        return $data;
    }

    public function getCount()
    {
        $query = "  SELECT COUNT({$this->getTable()}.{$this->getId()}) AS `kiekis`
					FROM {$this->getTable()}";
        $data = mysql::select($query);

        return $data[0]['kiekis'];
    }

    public function delete($id)
    {
        $query = "  DELETE FROM {$this->getTable()}
					WHERE {$this->getId()}='{$id}'";
        mysql::query($query);
    }

    public function getReferenceCount($id)
    {
        $count = 0;
        for ($i = 0; $i < $this->getChildCount(); $i++) {
            $query = "  SELECT COUNT(*) AS kiekis
					FROM {$this->getTable()}
						INNER JOIN {$this->getChildTable()[$i]}
							ON {$this->getTable()}.{$this->getId()}={$this->getChildTable()[$i]}.{$this->getChildReference()[$i]}
					WHERE {$this->getTable()}.{$this->getId()}='{$id}'";
            $data = mysql::select($query);
            $count = $count + $data[0]['kiekis'];
        }
        return $count;
    }

    public function getMaxId()
    {
        $query = "  SELECT MAX({$this->getId()}) AS latestId
					FROM {$this->getTable()}";
        $data = mysql::select($query);

        return $data[0]['latestId'];
    }
    
    public function getSuccessMessage()
    {
        return "Sėkmingai įvykdyta";
    }

    public function getErrorMessage()
    {
        return "Ištrinti nepavyko";
    }
    
}