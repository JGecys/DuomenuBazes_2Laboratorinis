<?php

require_once 'base.class.php';

/**
 * Created by PhpStorm.
 * User: jgecy
 * Date: 2016-04-16
 * Time: 16:04
 */
class serijos extends base
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
        return 'serijos';
    }

    protected function getChildReference()
    {
        return ['fk_serija'];
    }

    protected function getChildTable()
    {
        return ['filmai'];
    }

    protected function getChildCount()
    {
        return 1;
    }

    public function getAll($limit = null, $offset = null)
    {
        $query = "  SELECT serijos.*,
                           zanrai.zanras AS zanras,
                           (SELECT COUNT(filmai.id) FROM filmai WHERE filmai.fk_serija=serijos.id) AS filmu_kiekis
					FROM serijos
					INNER JOIN zanrai ON serijos.fk_zanras = zanrai.id";
        return parent::getAllCustomQuery($query, $limit, $offset);
    }


    public function insert($data)
    {
        $query = "  INSERT INTO serijos
								(
									id,
									serija,
									fk_zanras
								)
								VALUES
								(
									'{$data['id']}',
									'{$data['serija']}',
									'{$data['fk_zanras']}'
								)";
        mysql::query($query);
    }

    public function update($data)
    {
        $query = "  UPDATE serijos
					SET    serija='{$data['serija']}', fk_zanras='{$data['fk_zanras']}'
					WHERE id='{$data['id']}'";
        mysql::query($query);
    }


    public function getTableHeaders()
    {
        return ['Id', 'Serija', 'Žanras', "Filmų kiekis"];
    }

    public function getRowFromData($data)
    {
        return "<td>{$data['id']}</td>"
        . "<td>{$data['serija']}</td>"
        . "<td>{$data['zanras']}</td>"
        . "<td>{$data['filmu_kiekis']}</td>";
    }

    public function getCreateNewMessage()
    {
        return "Nauja serija";
    }

    public function getPath()
    {
        return "Filmų serijos";
    }
}