<?php

require_once 'base.class.php';

class klientai extends base
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
        return 'klientai';
    }

    protected function getChildReference()
    {
        return ['fk_moketojas', 'fk_uzsakovas'];
    }

    protected function getChildTable()
    {
        return ['mokejimai', 'sutartys'];
    }

    protected function getChildCount()
    {
        return 2;
    }

    public function insert($data)
    {
        $query = "  INSERT INTO klientai
								(
									id,
									asmens_kodas,
									vardas,
									pavarde,
									gimimo_data,
									telefonas,
									e_pastas
								)
								VALUES
								(
									'{$data['id']}',
									'{$data['asmens_kodas']}',
									'{$data['vardas']}',
									'{$data['pavarde']}',
									'{$data['gimimo_data']}',
									'{$data['telefonas']}',
									'{$data['e_pastas']}'
								)";
        mysql::query($query);
    }

    public function update($data)
    {
        $query = "  UPDATE klientai
					SET    asmens_kodas='{$data['asmens_kodas']}',
					       vardas='{$data['vardas']}',
					       pavarde='{$data['pavarde']}',
					       gimimo_data='{$data['gimimo_data']}',
					       telefonas='{$data['telefonas']}',
					       e_pastas='{$data['e_pastas']}'
					       
					WHERE id='{$data['id']}'";
        mysql::query($query);
    }


    public function getTableHeaders()
    {
        return ['Id', 'Vardas', 'Pavardė', "Gimimo data", "Telefonas", "El. Pastas"];
    }

    public function getRowFromData($data)
    {
        return "<td>{$data['id']}</td>"
        . "<td>{$data['vardas']}</td>"
        . "<td>{$data['pavarde']}</td>"
        . "<td>{$data['gimimo_data']}</td>"
        . "<td>{$data['telefonas']}</td>"
        . "<td>{$data['e_pastas']}</td>";
    }

    public function getCreateNewMessage()
    {
        return "Naujas klientas";
    }

    public function getPath()
    {
        return "Klientai";
    }
}