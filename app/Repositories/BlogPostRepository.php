<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;
use Illuminate\Pagination\LengthAwarePaginator;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use function foo\func;

//use Your Model

/**
 * Class BlogPostRepository.
 */
class BlogPostRepository extends CoreRepository
{
    /**
     * @inheritDoc
     */
    protected function getModelClass()
    {
        return Model::class;// TODO: Implement getModelClas() method.
    }

    /**
     *  Получить список статей для вывода в списке
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate()
    {
        $columns = [
            'id',
            'title',
            'slug',
            'is_published',
            'published_at',
            'user_id',
            'category_id',
        ];

        $result = $this
            ->startConditions()
            ->select($columns)
            ->orderBy('id', 'DESC')
//            ->with(['category', 'user'])
            ->with([
                //Можно так
                'category' => function ($query) {
//                dd($query);
                $query->select(['id', 'title']);
            },
                // Или так
            'user:id,name',
            ])
            ->paginate(25);

//                ->get();
//        dd($result->first());
        return $result;
    }

    /**
     * Получить модель для редактирования  в админке
     *
     * @param int $id
     * @return Model
    */


    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }
}
