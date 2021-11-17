<?php


namespace Modules\Blog\Services;


use App\Services\BaseService;
use App\Services\ServiceResult;
use Modules\Blog\Repositories\ArticleRepository;
use phpDocumentor\Reflection\Types\Integer;

class BlogService extends BaseService
{
    protected $repository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->repository = $articleRepository;
    }

    /**
     * список с пагинацией
     */
    public function indexPaginate($params) : ServiceResult
    {
        $collection = $this->repository->indexPaginate($params);
        return $this->result($collection);
    }
    /**
     * Статья
     */
    public function get($id) : ServiceResult
    {
        $model = $this->repository->get($id);

        if(is_null($model)) {
            return $this->errNotFound('Статья не найден');
        }
        return $this->result($model);
    }
    /**
     * Сохранить статью
     */
    public function store($data) : ServiceResult
    {

        if($this->repository->existsName($data['title'])) {
            return $this->errValidate("Статья с таким названием уже существует");
        }

        $this->repository->store($data);
        return $this->ok('Статья сохранен');

    }

    /**
     * Изменить статью
     */
    public function update($id, $data) : ServiceResult
    {
        $model = $this->repository->get($id);
        if(is_null($model)) {
            return $this->errNotFound('Статья не найден');
        }
        if($this->repository->existsName($data['title'],$id)) {
            return $this->errValidate("Статья с таким названием уже существует");
        }

        $this->repository->update($id,$data);
        return $this->ok('Статья обновлен');
    }

    /**
     * Удалить статью
     */
    public function destroy($id)
    {
        $model =  $this->repository->get($id);
        if(is_null($model)) {
            return $this->errNotFound('Статья не найден');
        }
        $this->repository->destroy($model);
        return $this->ok('Статья удален');
    }

}
