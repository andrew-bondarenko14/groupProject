<?php


namespace App\Repositories\DB;


abstract class BaseRepository
{
    private $model;

    public function __construct()
    {
        $this->model = $this->getModel();
    }

    abstract public function getModel();
}
