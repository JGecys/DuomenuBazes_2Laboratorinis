<?php

require_once 'base.class.php';

class mokesciai extends base
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
        return 'mokesciai';
    }

    protected function getChildReference()
    {
        return ['fk_mokesciai'];
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
        $query = "  INSERT INTO mokesciai
								(
									id,
									pavadinimas,
									aprasymas,
									kaina,
									kiekis
								)
								VALUES
								(
									'{$data['id']}',
									'{$data['pavadinimas']}',
									'{$data['aprasymas']}',
									'{$data['kaina']}',
									'{$data['kiekis']}'
								)";
        mysql::query($query);
    }

    public function update($data)
    {
        $query = "  UPDATE papildoma_iranga
					SET    pavadinimas='{$data['pavadinimas']}',
					       aprasymas='{$data['aprasymas']}',
					       kaina='{$data['kaina']}',
					       kiekis='{$data['kiekis']}'
					WHERE id='{$data['id']}'";
        mysql::query($query);
    }


    public function getTableHeaders()
    {
        return ['Id', 'Pavadinimas', 'Aprasymas', 'Kaina', 'Kiekis'];
    }

    public function getRowFromData($data)
    {
        return "<td>{$data['id']}</td>"
        . "<td>{$data['pavadinimas']}</td>"
        . "<td>{$data['aprasymas']}</td>"
        . "<td>{$data['kaina']}</td>"
        . "<td>{$data['kiekis']}</td>";
    }

    public function getCreateNewMessage()
    {
        return "Nauji mokesciai";
    }

    public function getPath()
    {
        return "Mokesciai";
    }


}