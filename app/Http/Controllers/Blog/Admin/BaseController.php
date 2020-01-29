<?php

namespace App\Http\Controllers\Blog\Admin;
use App\Http\Controllers\Blog\BaseController as GuestBaseController;

abstract class BaseController extends GuestBaseController
{
    /**
     * Базовый контроллер для всех контроллеров управления
     * блогом быть панели администрирования.
     *
     * Должен быть родителем всех контроллеров управления блогом.
     * BaseController constructor.
    */
    public function __construct()
    {
        // Инициализация общих моментов для админки
    }
}
