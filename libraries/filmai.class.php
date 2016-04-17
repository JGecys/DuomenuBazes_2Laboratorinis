<?php

require_once 'base.class.php';

class filmai extends base
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
        return 'filmai';
    }

    protected function getChildReference()
    {
        return ['fk_filmas', 'fk_filmas'];
    }

    protected function getChildTable()
    {
        return ['apzvalga', 'diskai'];
    }

    protected function getChildCount()
    {
        return 2;
    }

    public function getAll($limit = null, $offset = null)
    {
        $query = "  SELECT filmai.*,
                          serijos.serija AS serija,
                          zanrai.zanras AS zanras,
                          filmo_ivertinimai.ivertinimas AS ivertinimas,
                          (SELECT COUNT(diskai.id) FROM diskai WHERE diskai.fk_filmas = filmai.id) AS kiekis_sandelyje
					FROM filmai
                    INNER JOIN serijos ON filmai.fk_serija = serijos.id
                    INNER JOIN zanrai ON serijos.fk_zanras = zanrai.id
                    INNER JOIN filmo_ivertinimai ON filmai.skirta = filmo_ivertinimai.id";
        return parent::getAllCustomQuery($query, $limit, $offset);
    }


    public function insert($data)
    {
        $query = "  INSERT INTO filmai
								(
									id,
									skirta,
									fk_serija,
									pavadinimas,
									isleidimo_data,
									musu_ivertinimas,
									aprasymas,
									trukme,
									nuomos_kaina
								)
								VALUES
								(
									'{$data['id']}',
									'{$data['skirta']}',
									'{$data['fk_serija']}',
									'{$data['pavadinimas']}',
									'{$data['isleidimo_data']}',
									'{$data['musu_ivertinimas']}',
									'{$data['aprasymas']}',
									'{$data['trukme']}',
									'{$data['nuomos_kaina']}'
								)";
        mysql::query($query);
    }

    public function update($data)
    {
        $query = "  UPDATE filmai
					SET    skirta='{$data['skirta']}',
					       fk_serija='{$data['fk_serija']}',
					       pavadinimas='{$data['pavadinimas']}',
					       isleidimo_data='{$data['isleidimo_data']}',
					       musu_ivertinimas='{$data['musu_ivertinimas']}',
					       aprasymas='{$data['aprasymas']}',
					       trukme='{$data['trukme']}',
					       nuomos_kaina='{$data['nuomos_kaina']}'
					WHERE id='{$data['id']}'";
        mysql::query($query);
    }


    public function getTableHeaders()
    {
        return ['Id', 'Pavadinimas', 'Serija', 'Žanras', 'Skirta', 'Išleista', 'Įvertinimas', 'Trukmė', 'Kaina', 'Kiekis'];
    }

    public function getRowFromData($data)
    {
        return "<td>{$data['id']}</td>"
        . "<td>{$data['pavadinimas']}</td>"
        . "<td>{$data['serija']}</td>"
        . "<td>{$data['zanras']}</td>"
        . "<td>{$data['ivertinimas']}</td>"
        . "<td>{$data['isleidimo_data']}</td>"
        . "<td>{$data['musu_ivertinimas']}</td>"
        . "<td>{$data['trukme']}</td>"
        . "<td>{$data['nuomos_kaina']}</td>"
        . "<td>{$data['kiekis_sandelyje']}</td>";
    }

    public function getCreateNewMessage()
    {
        return "Naujas filmas";
    }

    public function getPath()
    {
        return "Filmai";
    }


}