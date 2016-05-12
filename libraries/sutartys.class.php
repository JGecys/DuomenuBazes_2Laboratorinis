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


    public function getContracts($dateFrom, $dateTo, $pavarde = null)
    {
        $whereClauseString = "";
        if (!empty($dateFrom)) {
            $whereClauseString .= " WHERE `sutartys`.`sutarties_data`>='{$dateFrom}'";
            if (!empty($dateTo)) {
                $whereClauseString .= " AND `sutartys`.`sutarties_data`<='{$dateTo}'";
            }
        } else {
            if (!empty($dateTo)) {
                $whereClauseString .= " WHERE `sutartys`.`sutarties_data`<='{$dateTo}'";
            }
        }
        
        $whereClauseLastname = "";
        if(!empty($pavarde)) {
            if(empty($whereClauseString)){
                $whereClauseLastname = "WHERE ";
            }else {
                $whereClauseLastname = " AND ";
            }
            $whereClauseLastname = $whereClauseLastname . "`klientai`.`pavarde`=\"{$pavarde}\"";
        }

        $query = "  SELECT  sutartys.id,
							sutartys.sutarties_data,
							klientai.id AS kliento_id,
							klientai.asmens_kodas,
							klientai.vardas,
						    klientai.pavarde,
						    sutartys.kaina as sutarties_kaina,
						    IFNULL(sum(papildoma_iranga.kaina), 0) as sutarties_papildomos_irangos_kaina,
						    t.bendra_kliento_sutarciu_kaina,
						    s.bendra_kliento_irangos_kaina
					FROM sutartys
						INNER JOIN klientai
							ON sutartys.fk_uzsakovas=klientai.id
						LEFT JOIN papildoma_iranga
							ON sutartys.fk_papildoma_iranga=papildoma_iranga.id
						LEFT JOIN (
							SELECT klientai.id, asmens_kodas,
									sum(sutartys.kaina) AS bendra_kliento_sutarciu_kaina
							FROM sutartys
								INNER JOIN klientai
									ON sutartys.fk_uzsakovas=klientai.id
							{$whereClauseString}
							{$whereClauseLastname}
							GROUP BY asmens_kodas
						) t ON t.id=klientai.id
						LEFT JOIN (
							SELECT klientai.id, asmens_kodas,
									IFNULL(papildoma_iranga.kaina, 0) as bendra_kliento_irangos_kaina
							FROM sutartys
								INNER JOIN klientai
									ON sutartys.fk_uzsakovas=klientai.id
								LEFT JOIN papildoma_iranga
									ON sutartys.fk_papildoma_iranga=papildoma_iranga.id
								{$whereClauseString}
							    {$whereClauseLastname}							
								GROUP BY `asmens_kodas`
						) s ON s.id=klientai.id
					{$whereClauseString}
					{$whereClauseLastname}
					GROUP BY sutartys.id ORDER BY klientai.pavarde ASC";
        $data = mysql::select($query);

        return $data;
    }

    public function getSumPriceOfContracts($dateFrom, $dateTo, $pavarde=null) {
        $whereClauseString = "";
        if(!empty($dateFrom)) {
            $whereClauseString .= " WHERE `sutartys`.`sutarties_data`>='{$dateFrom}'";
            if(!empty($dateTo)) {
                $whereClauseString .= " AND `sutartys`.`sutarties_data`<='{$dateTo}'";
            }
        } else {
            if(!empty($dateTo)) {
                $whereClauseString .= " WHERE `sutartys`.`sutarties_data`<='{$dateTo}'";
            }
        }
        $whereClauseLastname = "";
        if(!empty($pavarde)) {
            if(empty($whereClauseString)){
                $whereClauseLastname = "WHERE ";
            }else {
                $whereClauseLastname = " AND ";
            }
            $whereClauseLastname = $whereClauseLastname . "`klientai`.`pavarde`=\"{$pavarde}\"";
        }


        $query = "  SELECT sum(`sutartys`.`kaina`) AS `nuomos_suma`
					FROM `sutartys`
					INNER JOIN klientai on sutartys.fk_uzsakovas=klientai.id
					{$whereClauseString}
					{$whereClauseLastname}";
        $data = mysql::select($query);

        return $data;
    }

    public function getSumPriceOfOrderedServices($dateFrom, $dateTo, $pavarde=null) {
        $whereClauseString = "";
        if(!empty($dateFrom)) {
            $whereClauseString .= " WHERE `sutartys`.`sutarties_data`>='{$dateFrom}'";
            if(!empty($dateTo)) {
                $whereClauseString .= " AND `sutartys`.`sutarties_data`<='{$dateTo}'";
            }
        } else {
            if(!empty($dateTo)) {
                $whereClauseString .= " WHERE `sutartys`.`sutarties_data`<='{$dateTo}'";
            }
        }
        $whereClauseLastname = "";
        if(!empty($pavarde)) {
            if(empty($whereClauseString)){
                $whereClauseLastname = "WHERE ";
            }else {
                $whereClauseLastname = " AND ";
            }
            $whereClauseLastname = $whereClauseLastname . "`klientai`.`pavarde`=\"{$pavarde}\"";
        }

        $query = "  SELECT sum(papildoma_iranga.kaina) AS `paslaugu_suma`
					FROM `sutartys`
						INNER JOIN `papildoma_iranga`
							ON `sutartys`.`fk_papildoma_iranga`=`papildoma_iranga`.`id`
					{$whereClauseString}
					{$whereClauseLastname}";
        $data = mysql::select($query);

        return $data;
    }

    public function getDelayedContracts($dateFrom, $dateTo) {
        $whereClauseString = "";
        if(!empty($dateFrom)) {
            $whereClauseString .= " AND `sutartys`.`sutarties_data`>='{$dateFrom}'";
            if(!empty($dateTo)) {
                $whereClauseString .= " AND `sutartys`.`sutarties_data`<='{$dateTo}'";
            }
        } else {
            if(!empty($dateTo)) {
                $whereClauseString .= " AND `sutartys`.`sutarties_data`<='{$dateTo}'";
            }
        }

        $query = "  SELECT sutartys.id,
						   sutartys.sutarties_data,
						   sutartys.planuojama_grazinimo_data,
						   IF(faktine_grazinimo_data='0000-00-00 00:00:00', 'negrąžinta', faktine_grazinimo_data) AS grazinta,
						   klientai.vardas,
						   klientai.pavarde
					FROM sutartys
						INNER JOIN klientai
							ON sutartys.fk_uzsakovas=klientai.id
					WHERE (DATEDIFF(faktine_grazinimo_data, planuojama_grazinimo_data) >= 1 OR
						(faktine_grazinimo_data = '0000-00-00 00:00:00' AND DATEDIFF(NOW(), planuojama_grazinimo_data) >= 1))
					{$whereClauseString}";
        $data = mysql::select($query);

        return $data;
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