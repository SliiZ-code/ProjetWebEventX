<?php

require_once __DIR__ . '/../config/Database.php';

abstract class DataAccess{
    protected $connection;

    public function __construct(){
        $db = new Database();
        $this->connection= $db->getConnection();
    }
}