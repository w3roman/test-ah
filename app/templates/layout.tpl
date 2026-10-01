<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>{block name=title}Блог{/block}</title>
    <link rel="stylesheet" href="{$app_url}/css/style.css">
</head>
<body>
    {include file='partials/header.tpl'}
    <main class="container">
        {block name=content}{/block}
    </main>
    {include file='partials/footer.tpl'}
</body>
</html>