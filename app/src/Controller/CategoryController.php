<?php
namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ArticleRepository;
use App\Support\View;

final class CategoryController
{
    private const PER_PAGE = 6;

    public function __construct(
        private View $view,
        private CategoryRepository $categories = new CategoryRepository(),
        private ArticleRepository $articles = new ArticleRepository(),
    ) {}

    public function show(string $slug, string $sort, int $page): void
    {
        $category = $this->categories->findBySlug($slug);
        if (!$category) { http_response_code(404); echo 'Category not found'; return; }

        $sort  = in_array($sort, ['date', 'views'], true) ? $sort : 'date';
        $total = $this->articles->countByCategory((int)$category['id']);
        $pages = max(1, (int)ceil($total / self::PER_PAGE));
        $page  = min($page, $pages);

        $articles = $this->articles->paginateByCategory(
            (int)$category['id'], $sort, $page, self::PER_PAGE
        );

        $this->view->render('category.tpl', [
            'category' => $category,
            'articles' => $articles,
            'sort'     => $sort,
            'page'     => $page,
            'pages'    => $pages,
        ]);
    }
}