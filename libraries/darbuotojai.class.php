<?php

require_once 'base.class.php';

class darbuotojai extends base
{

    public function __construct()
    {

    }

    public function getId()
    {
        return 'tabelio_nr';
    }

    protected function getTable()
    {
        return 'darbuotojai';
    }

    protected function getChildReference()
    {
        return ['fk_patvirtino_darbuotojas'];
    }

    protected function getChildTable()
    {
        return ['sutartys'];
    }

    protected function getChildCount()
    {
        return 1;
    }

    public function getAll($limit = null, $offset = null)
    {
        $query = "  SELECT darbuotojai.*, padaliniai.padalinys
					FROM darbuotojai
					INNER JOIN padaliniai ON darbuotojai.fk_padalinys = padaliniai.id";
        return parent::getAllCustomQuery($query, $limit, $offset);
    }


    public function insert($data)
    {
        $query = "  INSERT INTO darbuotojai
								(
									tabelio_nr,
									vardas,
									pavarde,
									fk_padalinys
								)
								VALUES
								(
									'{$data['tabelio_nr']}',
									'{$data['vardas']}',
									'{$data['pavarde']}',
									'{$data['fk_padalinys']}'
								)";
        mysql::query($query);
    }

    public function update($data)
    {
        $query = "  UPDATE darbuotojai
					SET    vardas='{$data['vardas']}',
					       pavarde='{$data['pavarde']}',
					       fk_padalinys='{$data['fk_padalinys']}'
					WHERE tabelio_nr='{$data['tabelio_nr']}'";
        mysql::query($query);
    }


    public function getTableHeaders()
    {
        return ['Tabelio Nr', 'Vardas', 'Pavardė', "Padalinys"];
    }

    public function getRowFromData($data)
    {
        return "<td>{$data['tabelio_nr']}</td>"
        . "<td>{$data['vardas']}</td>"
        . "<td>{$data['pavarde']}</td>"
        . "<td>{$data['padalinys']}</td>";
    }

    public function getCreateNewMessage()
    {
        return "Naujas darbuotojas";
    }

    public function getPath()
    {
        return "Darbuotojai";
    }
}