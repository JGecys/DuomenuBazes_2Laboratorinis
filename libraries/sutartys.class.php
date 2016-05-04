<?php

require_once 'base.class.php';

class sutartys extends base
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
        return 'sutartys';
    }

    protected function getChildReference()
    {
        return [];
    }

    protected function getChildTable()
    {
        return [];
    }

    protected function getChildCount()
    {
        return 0;
    }
    
    public function insert($data)
    {
        $query = "  INSERT INTO sutartys
								(
									id,
									sutarties_data,
									nuomos_data,
									planuojama_grazinimo_data,
									faktine_grazinimo_data,
									kaina,
									fk_diskas,
									fk_patvirtino_darbuotojas,
									fk_paemimo_vieta,
									fk_grazinimo_vieta,
									fk_papildoma_iranga,
									fk_mokestis,
									fk_mokejimas,
									fk_uzsakovas
								)
								VALUES
								(
									'{$data['id']}',
									'{$data['sutarties_data']}',
									'{$data['nuomos_data']}',
									'{$data['planuojama_grazinimo_data']}',
									'{$data['faktine_grazinimo_data']}',
									'{$data['kaina']}',
									'{$data['fk_diskas']}',
									'{$data['fk_patvirtino_darbuotojas']}',
									'{$data['fk_paemimo_vieta']}',
									'{$data['fk_grazinimo_vieta']}',
									'{$data['fk_papildoma_iranga']}',
									'{$data['fk_mokestis']}',
									'{$data['fk_mokejimas']}',
									'{$data['fk_uzsakovas']}'
								)";
        mysql::query($query);
    }

    public function update($data)
    {
        $query = "  UPDATE sutartys
					SET    sutarties_data='{$data['sutarties_data']}',
					       nuomos_data='{$data['nuomos_data']}',
					       planuojama_grazinimo_data='{$data['planuojama_grazinimo_data']}',
					       faktine_grazinimo_data='{$data['faktine_grazinimo_data']}',
					       kaina='{$data['kaina']}',
					       fk_diskas='{$data['fk_diskas']}',
					       fk_patvirtino_darbuotojas='{$data['fk_patvirtino_darbuotojas']}',
					       fk_paemimo_vieta='{$data['fk_paemimo_vieta']}',
					       fk_grazinimo_vieta='{$data['fk_grazinimo_vieta']}',
					       fk_papildoma_iranga='{$data['fk_papildoma_iranga']}',
					       fk_mokestis='{$data['fk_mokestis']}',
					       fk_mokejimas='{$data['fk_mokejimas']}',
					       fk_uzsakovas='{$data['fk_uzsakovas']}'
					WHERE id='{$data['id']}'";
        mysql::query($query);
    }


    public function getTableHeaders()
    {
        return ['Id', 'Sutarties data', 'Nuomos data', 'Planuojama grazinimo data',
            'Faktine grazinimo data', 'Kaina', 'Diskas', 'Patvirtino darbuotojas',
            'Paemimo vieta', 'Grazinimo vieta', 'Papildoma įranga', 'Mokestis',
            'Mokejimas', 'Užsakovas'];
    }

    public function getRowFromData($data)
    {
        return "<td>{$data['id']}</td>"
        . "<td>{$data['sutarties_data']}</td>"
        . "<td>{$data['nuomos_data']}</td>"
        . "<td>{$data['planuojama_grazinimo_data']}</td>"
        . "<td>{$data['faktine_grazinimo_data']}</td>"
        . "<td>{$data['kaina']}</td>"
        . "<td>{$data['fk_diskas']}</td>"
        . "<td>{$data['fk_patvirtino_darbuotojas']}</td>"
        . "<td>{$data['fk_paemimo_vieta']}</td>"
        . "<td>{$data['fk_grazinimo_vieta']}</td>"
        . "<td>{$data['fk_papildoma_iranga']}</td>"
        . "<td>{$data['fk_mokestis']}</td>"
        . "<td>{$data['fk_mokejimas']}</td>"
        . "<td>{$data['fk_uzsakovas']}</td>";
    }

    public function getCreateNewMessage()
    {
        return "Nauja sutartis";
    }

    public function getPath()
    {
        return "Sutartys";
    }


}