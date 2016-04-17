<?php

require_once 'base.class.php';

class zanrai extends base
{

    public function __construct()
    {

    }


    public function getId()
    {
        return 'id';
    }

    protected function getTable()
    {
        return 'zanrai';
    }

    protected function getChildReference()
    {
        return ['fk_zanras'];
    }

    protected function getChildTable()
    {
        return ['serijos'];
    }

    protected function getChildCount()
    {
        return 1;
    }
    
    public function insert($data)
    {
        $query = "  INSERT INTO {$this->getTable()}
								(
									id,
									zanras
								)
								VALUES
								(
									'{$data['id']}',
									'{$data['zanras']}'
								)";
        mysql::query($query);
    }

    public function update($data)
    {
        $query = "  UPDATE {$this->getTable()}
					SET    zanras='{$data['zanras']}'
					WHERE id='{$data['id']}'";
        mysql::query($query);
    }

    public function getTableHeaders()
    {
        return ["Id", "Žanras"];
    }

    public function getRowFromData($data)
    {
        return "<td>{$data['id']}</td>"
                . "<td>{$data['zanras']}</td>";
    }

    public function getCreateNewMessage()
    {
        return "Naujas žanras";
    }

    public function getPath()
    {
        return "Filmų žanrai";
    }
}