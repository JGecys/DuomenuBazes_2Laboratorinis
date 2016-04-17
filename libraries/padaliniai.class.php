<?php

require_once 'base.class.php';

/**
 * Created by PhpStorm.
 * User: jgecy
 * Date: 2016-04-16
 * Time: 16:04
 */
class padaliniai extends base
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
        return 'padaliniai';
    }

    protected function getChildReference()
    {
        return ['fk_padalinys', 'fk_paemimo_vieta', 'fk_grazinimo_vieta', 'fk_vieta'];
    }

    protected function getChildTable()
    {
        return ['darbuotojai', 'sutartys', 'sutartys', 'diskai'];
    }

    protected function getChildCount()
    {
        return 4;
    }

    public function getAll($limit = null, $offset = null)
    {
        $query = "  SELECT padaliniai.*,
                           miestai.miestas AS miestas,
                           (SELECT COUNT(darbuotojai.tabelio_nr) FROM darbuotojai WHERE darbuotojai.fk_padalinys=padaliniai.id) AS darbuotojai
					FROM padaliniai
					INNER JOIN miestai ON padaliniai.fk_miestas = miestai.id";
        return parent::getAllCustomQuery($query, $limit, $offset);
    }


    public function insert($data)
    {
        $query = "  INSERT INTO padaliniai
								(
									id,
									padalinys,
									adresas,
									telefonas,
									e_pastas,
									fk_miestas
								)
								VALUES
								(
									'{$data['id']}',
									'{$data['padalinys']}',
									'{$data['adresas']}',
									'{$data['telefonas']}',
									'{$data['e_pastas']}',
									'{$data['fk_miestas']}'
								)";
        mysql::query($query);
    }

    public function update($data)
    {
        $query = "  UPDATE padaliniai
					SET    padalinys='{$data['padalinys']}',
					       adresas='{$data['adresas']}',
					       telefonas='{$data['telefonas']}',
					       e_pastas='{$data['e_pastas']}',
					       fk_miestas='{$data['fk_miestas']}'
					WHERE id='{$data['id']}'";
        mysql::query($query);
    }


    public function getTableHeaders()
    {
        return ['Id', 'Padalinys', 'Miestas', 'Adresas', "Telefonas", "E.Paštas", "Darbuotojai"];
    }

    public function getRowFromData($data)
    {
        return "<td>{$data['id']}</td>"
        . "<td>{$data['padalinys']}</td>"
        . "<td>{$data['miestas']}</td>"
        . "<td>{$data['adresas']}</td>"
        . "<td>{$data['telefonas']}</td>"
        . "<td>{$data['e_pastas']}</td>"
        . "<td>{$data['darbuotojai']}</td>";
    }

    public function getCreateNewMessage()
    {
        return "Naujas padalinys";
    }

    public function getPath()
    {
        return "Padaliniai";
    }
}