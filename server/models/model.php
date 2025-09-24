<?php

abstract class Model {
    protected $connection = null;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    abstract public function getOne($id);
    abstract public function getAll();
    abstract public function create($data);
    abstract public function update($id, $data);
    abstract public function delete($id);
}