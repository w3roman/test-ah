<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\Router;
use App\Controller\HomeController;
use App\Controller\CategoryController;
use App\Controller\ArticleController;
use App\Support\View;

$config = require __DIR__ . '/../config/config.php';
Database::init($config['db']);

$view = new View($config['templates_dir'], $config['compile_dir'], $config['cache_dir']);

$router = new Router();

$router->get('/', fn() => (new HomeController($view))->index());

$router->get('/category/{slug}', fn($p) => (new CategoryController($view))->show(
    $p['slug'],
    $_GET['sort'] ?? 'date',
    max(1, (int)($_GET['page'] ?? 1))
));

$router->get('/article/{slug}', fn($p) => (new ArticleController($view))->show($p['slug']));

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
