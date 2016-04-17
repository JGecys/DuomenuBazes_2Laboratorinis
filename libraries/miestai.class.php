<?php

require_once 'base.class.php';

class miestai extends base
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
        return 'miestai';
    }

    protected function getChildReference()
    {
        return ['fk_miestas'];
    }

    protected function getChildTable()
    {
        return ['padaliniai'];
    }

    protected function getChildCount()
    {
        return 1;
    }

    public function insert($data)
    {
        $query = "  INSERT INTO miestai
								(
									id,
									miestas
								)
								VALUES
								(
									'{$data['id']}',
									'{$data['miestas']}'
								)";
        mysql::query($query);
    }

    public function update($data)
    {
        $query = "  UPDATE miestai
					SET    miestas='{$data['miestas']}'
					WHERE id='{$data['id']}'";
        mysql::query($query);
    }

    public function getTableHeaders()
    {
        return ["Id", "Miestas"];
    }

    public function getRowFromData($data)
    {
        return "<td>{$data['id']}</td>"
        . "<td>{$data['miestas']}</td>";
    }

    public function getCreateNewMessage()
    {
        return "Naujas miestas";
    }

    public function getPath()
    {
        return "Miestai";
    }
}