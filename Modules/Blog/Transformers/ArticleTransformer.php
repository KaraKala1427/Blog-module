<?php
namespace Modules\Blog\Transformers;

use App\Services\ServiceResult;
use League\Fractal\TransformerAbstract;
use Modules\Blog\Entities\Article;

class ArticleTransformer extends TransformerAbstract
{
    public function transform(Article $article)
    {
        return [
            'id' => $article->id,
            'title' => $article->title,
            'content' => mb_substr($article->content, 0, 200)
        ];
    }
}
