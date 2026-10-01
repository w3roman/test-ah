{extends file='layout.tpl'}
{block name=title}{$category.title|escape}{/block}

{block name=content}
    <h1>{$category.title|escape}</h1>
    <p>{$category.description|escape}</p>

    <div class="sort">
        Сортировка:
        <a class="{if $sort == 'date'}active{/if}"
           href="?sort=date">по дате</a>
        <a class="{if $sort == 'views'}active{/if}"
           href="?sort=views">по просмотрам</a>
    </div>

    <div class="articles-grid">
        {foreach $articles as $article}
            {include file='partials/article-card.tpl' article=$article}
        {foreachelse}
            <p>В этой категории пока нет статей.</p>
        {/foreach}
    </div>

    {include file='partials/pagination.tpl' page=$page pages=$pages sort=$sort}
{/block}