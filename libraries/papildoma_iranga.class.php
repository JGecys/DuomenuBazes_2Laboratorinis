<?php

require_once 'base.class.php';

class papildoma_iranga extends base
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
        return 'papildoma_iranga';
    }

    protected function getChildReference()
    {
        return ['fk_papildoma_iranga'];
    }

    protected function getChildTable()
    {
        return ['sutartys'];
    }

    protected function getChildCount()
    {
        return 1;
    }

    public function insert($data)
    {
        $query = "  INSERT INTO papildoma_iranga
								(
									id,
									pavadinimas,
									aprasymas,
									kaina
								)
								VALUES
								(
									'{$data['id']}',
									'{$data['pavadinimas']}',
									'{$data['aprasymas']}',
									'{$data['kaina']}'
								)";
        mysql::query($query);
    }

    public function update($data)
    {
        $query = "  UPDATE papildoma_iranga
					SET    pavadinimas='{$data['pavadinimas']}',
					       aprasymas='{$data['aprasymas']}',
					       kaina='{$data['kaina']}'
					WHERE id='{$data['id']}'";
        mysql::query($query);
    }


    public function getTableHeaders()
    {
        return ['Id', 'Pavadinimas', 'Aprasymas', 'Kaina'];
    }

    public function getRowFromData($data)
    {
        return "<td>{$data['id']}</td>"
        . "<td>{$data['pavadinimas']}</td>"
        . "<td>{$data['aprasymas']}</td>"
        . "<td>{$data['kaina']}</td>";
    }

    public function getCreateNewMessage()
    {
        return "Nauja papildoma iranga";
    }

    public function getPath()
    {
        return "Papildoma iranga";
    }


}