<?php

require_once 'base.class.php';

class mokejimai extends base
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
        return 'mokejimai';
    }

    protected function getChildReference()
    {
        return ['fk_mokejimai'];
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
        $query = "  INSERT INTO mokejimai
								(
									id,
									apmoketa,
									suma,
									saskaita,
									fk_moketojas
								)
								VALUES
								(
									'{$data['id']}',
									'{$data['apmoketa']}',
									'{$data['suma']}',
									'{$data['saskaita']}',
									'{$data['fk_moketojas']}'
								)";
        mysql::query($query);
    }

    public function update($data)
    {
        $query = "  UPDATE mokejimai
					SET    apmoketa='{$data['apmoketa']}',
					       suma='{$data['suma']}',
					       saskaita='{$data['saskaita']}',
					       fk_moketojas='{$data['fk_moketojas']}'
					WHERE id='{$data['id']}'";
        mysql::query($query);
    }


    public function getTableHeaders()
    {
        return ['Id', 'Apmoketa', 'Suma', 'Sąskaita', 'Moketojas'];
    }

    public function getRowFromData($data)
    {
        return "<td>{$data['id']}</td>"
        . "<td>{$data['apmoketa']}</td>"
        . "<td>{$data['suma']}</td>"
        . "<td>{$data['saskaita']}</td>"
        . "<td>{$data['fk_moketojas']}</td>";
    }

    public function getCreateNewMessage()
    {
        return "Naujas mokejimas";
    }

    public function getPath()
    {
        return "Mokejimai";
    }


}