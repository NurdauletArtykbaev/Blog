<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class CoreRepository.
 * @package App\Repository
 *
 * Репозиторий работы с сущностью
 * Может выдавать наборы данных.
 * Не может создавать/изменить сущность
 *
 *
 */

abstract class CoreRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * CoreRepository constructor
     */

    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    /**
     * @return mixed
    */

    abstract protected function getModelClass();

    /**
     * @return Model|\Illuminate\Foundation\Application\mixed
    */

    protected function startConditions()
    {
        return clone $this->model;
    }

}




