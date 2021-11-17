<?php


namespace Modules\Blog\Repositories;


use Modules\Blog\Entities\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ArticleRepository
{
    public function indexPaginate($params, $query = null) : LengthAwarePaginator
    {
        $perPage = $params['per_page'] ?? 4;
        return $this->prepareQuery($params, $query)->paginate($perPage);
    }
    public function index($params): Collection
    {
        return $this->prepareQuery($params)->get();
    }

    private function prepareQuery($params, $query = null)
    {
        if(!$query){
            $query = Article::select('*');
        }
        $query = $this->queryApplyFilter($query,$params);
        $query = $this->queryApplySort($query,$params);
        return $query;
    }

    /**
     * поиск по названию или содержанию
     */
    private function queryApplyFilter($query,$params)
    {
        if(isset($params['title'])){
            $query->where(function ($subQuery) use ($params){
               $subQuery->where('title','LIKE',"%{$params['title']}%")
                   ->orWhere('content','LIKE',"%{$params['title']}%");
            });
        }
        return $query;
    }

    /**
     * сортировка по id, title, created_at
     */
    private function queryApplySort($query,$params){
        if(isset($params['sort']) && isset($params['order'])){
            $query->orderBy($params['sort'],$params['order']);
        }
        elseif (isset($params['sort']) && !isset($params['order'])){
            $query->orderBy($params['sort']);
        }
        return $query;
    }

    /**
     * Получить статью
     */
    public function get(int $id) : ?Article
    {
        return Article::find($id);
    }

    /**
     * Создать статью
     */
    public function store($data)
    {
        return Article::Create($data);
    }

    /**
     * Обновить статью
     */
    public function update($id, $data)
    {
        return $this->get($id)->update($data);
    }

    /**
     * Удалить статью
     */
    public function destroy($model)
    {
        return $model->delete();
    }

    /**
     * Проверка на существование
     */
    public function existsName($name, $id = null) : bool
    {
        return !is_null(Article::where('title',$name)
            ->when($id, function ($query) use ($id) {
                return $query->where('id','<>',$id);
            })
            ->first());
    }
}
