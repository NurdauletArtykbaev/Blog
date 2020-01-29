<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use Dotenv\Validator;
use http\Exception\BadConversionException;
use Illuminate\Database\Events\TransactionRolledBack;
use Illuminate\Http\Request;
use Illuminate\Validation\Concerns\ValidatesAttributes;
use test\Mockery\MockingAllLowerCasedMethodsTest;

class CategoryController extends BaseController
{
    /**
     * @var BlogCategoryRepository
    */
    private $blogCategoryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $dss = BlogCategory::all();
//        $paginator = BlogCategory::paginate(5);
//        dd($dss, $paginator);
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(25);


        return view('blog.admin.categories.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new BlogCategory();

//        dd($item);
//        $categoryList = BlogCategory::all();
        $categoryList = $this
            ->blogCategoryRepository->getForComboBox();
        return view('blog.admin.categories.edit',
            compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();

        //  Ушло в обсервер

 /*       if(empty($data['slug'])) {
            $data['slug'] = str_slug($data['title']);
        }*/
//        dd($data);
        // Создаст обьект но не добавть в БД
/*        $item = new BlogCategory($data);
        dd($item);
        $item->save();*/


        //Создаст обьект и добавит в БД
        $item = (new BlogCategory())->create($data);
        if ($item){
            return redirect()->route('blog.admin.categories.edit', [$item->id])
                ->with(['success'=>'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка сохранения'])
                ->withInput();
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
//        dd(__METHOD__);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @param BlogCategoryRepository $categoryRepository
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        $item = BlogCategory::findOrFail($id);
//        $categoryList = BlogCategory::all();

        // Получим экземпл класса
        $item = $this->blogCategoryRepository->getEdit($id);

        // Laravel урок №46: [ Аксессоры и мутаторы. Accessors & Mutators ]
//        Тестовым массивом $v будем заполнять
        // title до измен
        /*$v['title_before'] = $item->title;

        $item->title = 'AASdasdASD asdasda das 1231';
        // title после измен
        $v['title_after'] = $item->title;
        // получаем title по разными способами
        $v['getAttribute'] = $item->getAttribute('title');
        $v['attributesToArray'] = $item->attributesToArray();
        // title null так как свойства закрыта прямую обращаться не можем, только внутри класса
        $v['attributes'] = $item->attributes['title'];
        $v['getAttributeValue'] = $item->getAttributeValue('title');
        $v['getMutatedAttributes'] = $item->getMutatedAttributes();
        $v['hasGetMutator for title'] = $item->hasGetMutator('title');
        $v['toArray'] = $item->toArray();

        dd($v, $item);*/

        if (empty($item)){
            abort(404);
        }
        $categoryList = $this
            ->blogCategoryRepository->getForComboBox();

        return view('blog.admin.categories.edit',
            compact('item', 'categoryList'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BlogCategoryUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {


       /* $rules = [
            'title' => 'required|min:5|max:200',
            'slug'=> 'max:200',
            'description' => 'string|min:3|max:500',
            'parent_id' =>'required|string|exists:blog_categories,id',
        ];*/

//        $validateData = $this->validate($request, $rules);
//        $validateData = $request->validate($rules);

       /* $validator = \Validator::make($request->all(), $rules);
        $validateData[] = $validator->passes();
//        $validateData[] = $validator->validate();
        $validateData[] = $validator->valid();
        $validateData[] = $validator->failed();
        $validateData[] = $validator->errors();
        $validateData[] = $validator->fails();*/


//        dd($validateData);

//        dd(__METHOD__, $request->all(), $id);

        $item = $this->blogCategoryRepository->getEdit($id);
        if(empty($item)){
            return back()
                ->withErrors(['msg'=>"Запись id=[{$id}] не найдена"])
                ->withInput();
        }

        $data = $request->all();

        if(empty($data['slug'])){
            $data['slug'] = \Str::slug($data['title']);
        }
        $result = $item->update($data); //Model.php Builder.php мет
        //$result = $item->fill($data)->save();

        if ($result){
            return redirect()
                ->route('blog.admin.categories.edit', $item->id)
                ->with(['success'=>'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка сохранения'])
                ->withInput();
        }


    }
}
