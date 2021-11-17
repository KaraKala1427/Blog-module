<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Blog\Http\Requests\ArticleIndexRequest;
use Modules\Blog\Http\Requests\ArticleRequest;
use Modules\Blog\Services\BlogService;
use Modules\Blog\Transformers\ArticleTransformer;

class BlogController extends Controller
{
    private $service;

    public function __construct(BlogService $service)
    {
        $this->service = $service;
    }

    public function index(ArticleIndexRequest $request)
    {
        $articles = $this->service->indexPaginate($request->validated());
        return fractal()->collection($articles->data)->transformWith(new ArticleTransformer())->toArray();
    }

    public function show($id)
    {
        $model = $this->service->get($id);
        if($model->code != '200'){
            return $this->result($model);
        }
        return fractal($model->data, new ArticleTransformer())->toArray();
    }

    public function store(ArticleRequest $request)
    {
        $model = $this->service->store($request->validated());
        return $this->result($model);
    }
    public function update($id, ArticleRequest $request)
    {
        $model = $this->service->update($id, $request->validated());
        return $this->result($model);
    }

    public function destroy($id)
    {
        $model =  $this->service->destroy($id);
        return $this->result($model);
    }

}
