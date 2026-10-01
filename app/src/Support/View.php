<?php
namespace App\Support;

use Smarty;

final class View
{
    private Smarty $smarty;

    public function __construct(string $templatesDir, string $compileDir, string $cacheDir)
    {
        foreach ([$compileDir, $cacheDir] as $dir) {
            if (!is_dir($dir)) mkdir($dir, 0775, true);
        }

        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($templatesDir);
        $this->smarty->setCompileDir($compileDir);
        $this->smarty->setCacheDir($cacheDir);
        $this->smarty->setCaching(Smarty::CACHING_OFF);
        $this->smarty->assign('app_url', getenv('APP_URL') ?: '');
    }

    public function render(string $template, array $data = []): void
    {
        foreach ($data as $k => $v) {
            $this->smarty->assign($k, $v);
        }
        $this->smarty->display($template);
    }
}