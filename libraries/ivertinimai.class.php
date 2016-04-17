<?php

require_once 'base.class.php';

/**
 * Created by PhpStorm.
 * User: jgecy
 * Date: 2016-04-16
 * Time: 16:04
 */
class ivertinimai extends base
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
        return 'filmo_ivertinimai';
    }

    protected function getChildReference()
    {
        return ['skirta'];
    }

    protected function getChildTable()
    {
        return ['filmai'];
    }

    protected function getChildCount()
    {
        return 1;
    }

    public function insert($data)
    {
        $query = "  INSERT INTO filmo_ivertinimai
								(
									id,
									ivertinimas
								)
								VALUES
								(
									'{$data['id']}',
									'{$data['ivertinimas']}'
								)";
        mysql::query($query);
    }

    public function update($data)
    {
        $query = "  UPDATE filmo_ivertinimai
					SET    ivertinimas='{$data['ivertinimas']}'
					WHERE id='{$data['id']}'";
        mysql::query($query);
    }

    public function getTableHeaders()
    {
        return ["Id", "Skirta"];
    }

    public function getRowFromData($data)
    {
        return "<td>{$data['id']}</td>"
        . "<td>{$data['ivertinimas']}</td>";
    }

    public function getCreateNewMessage()
    {
        return "Naujas įvertinimas";
    }

    public function getPath()
    {
        return "Filmų įvertinimai";
    }


}