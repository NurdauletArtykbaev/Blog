<?php

namespace App\Observers;

use App\Models\BlogPost;
use Carbon\Carbon;
use Faker\Provider\Base;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use PHPUnit\Framework\Constraint\IsFalse;

class BlogPostObserver
{
    /**
     * Handle the blog post "created" event.
     * Обработка ПЕРЕД созданием записи
     *
     * @param BlogPost  $blogPost
     */
    public function creating(BlogPost $blogPost)
    {
        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);
        $this->setHtml($blogPost);
        $this->setUser($blogPost);

    }


    /**
     * Установка значения полю content_html относительно поля content_raw.
     *
     * @param BlogPost $blogPost
    */
    public function setHtml(BlogPost $blogPost)
    {
        if ($blogPost->isDirty('content_raw')){
            // TODO: Тут должна быть генерация markdown -> html
            $blogPost->content_html = $blogPost->content_raw;
        }
    }


    /**
     * Если не указан user_id то устанавливем пользователя по умолчанию
     * @param BlogPost $blogPost
    */
    public function setUser(BlogPost $blogPost)
    {
        $blogPost->user_id = auth()->id() ?? BlogPost::UNKNOWN_USER;
    }

    /**
     * Handle the blog post "updated" event.
     * Обработка ПЕРЕД обновлением записи
     * @param  BlogPost  $blogPost
     */
    public function updating(BlogPost $blogPost)
    {
//        $test[] = $blogPost->isDirty();
//        $test[] = $blogPost->isDirty('is_published');
//        $test[] = $blogPost->isDirty('user_id');
//        $test[] = $blogPost->getAttribute('is_published');
//        $test[] = $blogPost->is_published;
//        $test[] = $blogPost->getOriginal('is_published');//старая значения в базе
//        dd($test);

        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);
    }

    /**
     * Handle the blog post "updated" event.
     * Если дата публикация не установлена и происходит установка флага и (не происходть) - Опубликовано
     * то устанавливаем дату публикации на текущую
     * @param  BlogPost  $blogPost
     */

    protected function setPublishedAt(BlogPost $blogPost){
        $needSetPublished = empty($blogPost->published_at) && $blogPost->is_published;
        if ($needSetPublished) {
            $blogPost->published_at = Carbon::now();
        }
    }

    /**
     * Если поле слаг пустое, то заполняем его конвертацией загаловка
     * @param  BlogPost  $blogPost
     */

    protected function setSlug(BlogPost $blogPost)
    {
        if (empty($blogPost->slug)){
            $blogPost->slug = \Str::slug($blogPost->title);
        }
    }

    public function deleting(BlogPost $blogPost)
    {
//        dd(__METHOD__, $blogPost);
//        return false;
    }

    /**
     * Handle the blog post "deleted" event.
     *
     * @param  BlogPost  $blogPost
     */
    public function deleted(BlogPost $blogPost)
    {
//        dd(__METHOD__, $blogPost);
    }

    /**
     * Handle the blog post "restored" event.
     *
     * @param  BlogPost  $blogPost
     */
    public function restored(BlogPost $blogPost)
    {
        //
    }

    /**
     * Handle the blog post "force deleted" event.
     *
     * @param  BlogPost  $blogPost
     * @return void
     */
    public function forceDeleted(BlogPost $blogPost)
    {
        //
    }

}
