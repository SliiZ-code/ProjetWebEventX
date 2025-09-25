<?php

require_once __DIR__ . '/../config/Database.php';

abstract class DataAccess{
    protected $connection;

    public function __construct(){
        $db = new Database();
        $this->connection= $db->getConnection();
    }

    abstract public function create($entity);
    abstract public function read($id);
    abstract public function readAll();
    abstract public function update($entity);
    abstract public function delete($id);
}