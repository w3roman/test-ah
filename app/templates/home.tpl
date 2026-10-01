{extends file='layout.tpl'}

{block name=content}
    <h1>Блог</h1>
    {foreach $blocks as $block}
        <section class="category-block">
            <header class="category-header">
                <h2>{$block.category.title|escape}</h2>
                <a class="btn" href="{$app_url}/category/{$block.category.slug}">Все статьи</a>
            </header>
            <p>{$block.category.description|escape}</p>

            <div class="articles-grid">
                {foreach $block.articles as $article}
                    {include file='partials/article-card.tpl' article=$article}
                {/foreach}
            </div>
        </section>
    {foreachelse}
        <p>Пока нет ни одной статьи.</p>
    {/foreach}
{/block}