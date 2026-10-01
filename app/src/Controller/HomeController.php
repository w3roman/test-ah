<?php
namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ArticleRepository;
use App\Support\View;

final class HomeController
{
    public function __construct(
        private View $view,
        private CategoryRepository $categories = new CategoryRepository(),
        private ArticleRepository $articles = new ArticleRepository(),
    ) {}

    public function index(): void
    {
        $blocks = [];
        foreach ($this->categories->withArticles() as $category) {
            $blocks[] = [
                'category' => $category,
                'articles' => $this->articles->latestByCategory((int)$category['id'], 3),
            ];
        }
        $this->view->render('home.tpl', ['blocks' => $blocks]);
    }
}