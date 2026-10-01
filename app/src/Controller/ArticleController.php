<?php
namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Support\View;

final class ArticleController
{
    public function __construct(
        private View $view,
        private ArticleRepository $articles = new ArticleRepository(),
    ) {}

    public function show(string $slug): void
    {
        $article = $this->articles->findBySlug($slug);
        if (!$article) { http_response_code(404); echo 'Article not found'; return; }

        $this->articles->incrementViews((int)$article['id']);
        $article['views']++;

        $this->view->render('article.tpl', [
            'article'    => $article,
            'categories' => $this->articles->categoriesFor((int)$article['id']),
            'similar'    => $this->articles->similar((int)$article['id'], 3),
        ]);
    }
}