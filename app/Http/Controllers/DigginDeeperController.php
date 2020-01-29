<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DigginDeeperController extends Controller
{
    /**
     * Базовая информация:
     * @url https://laravel.com/docs/5.8/collections
     *
     * Справочная информация:
     * @url https://laravel.com/api/6.x/Illuminate/Support/Collection.html
     *
     * Вариант коллекции для моделей eloquent:
     * @url https://laravel.com/api/6.x/Illuminate/Database/Eloquent/Collection.html
     *
     * Билдерзапросов - то с чем можно перепутать коллекции:
     * @url https://laravel.com/docs/5.8/queries
     */
    public function collections()
    {
        $result = [];

        /**
         * @var \Illuminate\Database\Eloquent\Collection $eloquentCollection
         */

        $eloquentCollection = BlogPost::withTrashed()->get();

//        dd(__METHOD__, $eloquentCollection, $eloquentCollection->toArray());
        // создает пустую коллекцию
//        $collection = collect();
        $collection = collect($eloquentCollection->toArray());
//        dd(
//            get_class($eloquentCollection),
//            get_class($collection),
//            $collection
//        );
//        $result['first'] = $collection->first();
//        $result['last'] = $collection->last();
//        dd($result);

        $result['where']['data'] = $collection
            ->where('category_id', 10)
            ->values() // обнуляет ключи
            ->keyBy('id'); // копирует id к ключу
//        dd($result);

        /*$result['where']['count'] = $result['where']['data']->count(); // количество
        $result['where']['isEmpty'] = $result['where']['data']->isEmpty(); // Пустой
        $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();*/
//        dd($result);

        // Не очень красиво
        /*if ($result['where']['count']){
            //
        }*/

        // Так лучше
        /*if ($result['where']['data']->isNotEmpty()) {
            //
        }*/

        // здесь получаем один элемент
        /*$result['where_first'] = $collection
            ->firstWhere('created_at', '>', '2019-11-27 18:02:06');
        dd($result);*/

        // Базовая переменная не изменится. Просто вернутся измененная версия.
        /*$result['map']['all'] = $collection->map(function (array $item) {
            $newItem = new \stdClass;
            $newItem -> item_id = $item['id'];
            $newItem -> item_name = $item['title'];
            $newItem -> exists = is_null($item['deleted_at']);

            return $newItem;
        });*/


        /*$result['map']['not_exists'] = $result['map']['all']
            ->where('exists', '=', false)
            ->values()
            ->keyBy('item_id');


        dd($result);*/

        // Базовая переменная изменится (трасформируется)
        // старая коллекция пропадает и на ее месте появляется новая измененная колекция
        $collection->transform(function (array $item){
           $newItem = new \stdClass();
           $newItem -> item_id = $item['id'];
           $newItem -> item_name = $item['title'];
           $newItem -> exists = is_null($item['deleted_at']);
           $newItem -> created_at = Carbon::parse($item['created_at']);

           return $newItem;
        });

        //method prepend, push, pull

        /*$newItem1 = new \stdClass();
        $newItem1 ->id = 9999;

        $newItem2 = new \stdClass();
        $newItem2 ->id = 8888;

        $newItemFirst = $collection->prepend($newItem1)->first();
        $newItemLast = $collection->push($newItem2)->last();
        $pulledItem = $collection->pull(1);

        dd($newItemFirst, $newItemLast, $pulledItem, $collection);*/


        // Фильтрация. Замена orWhere()
        /*$filtered = $collection->filter(function ($item){
            $byDay = $item->created_at->isFriday();
            $byDate = $item->created_at->day == 1;

            $result = $byDay && $byDate;
            return $result;
        });
        dd(compact('filtered'));*/

        /*$sortedSimpleCollection = collect([5, 4, 2 ,3 ,1])->sort()->values();
        $sortedAscCollection = $collection->sortBy('created_at');
        $sortedDescCollection = $collection->sortByDesc('created_at');

        dd(compact('sortedSimpleCollection',
            'sortedAscCollection',
            'sortedDescCollection'));*/

    }
}
