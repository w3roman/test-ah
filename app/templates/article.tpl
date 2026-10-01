{extends file='layout.tpl'}
{block name=title}{$article.title|escape}{/block}

{block name=content}
    <article class="article">
        <h1>{$article.title|escape}</h1>
        <div class="meta">
            {$article.published_at|date_format:'%d.%m.%Y'} · {$article.views} просмотров
        </div>
        {if $article.image}
            <img class="article-image" src="{$app_url}/uploads/{$article.image}" alt="">
        {/if}
        <p class="lead">{$article.short_description|escape}</p>
        <div class="content">{$article.content nofilter}</div>

        <div class="article-categories">
            Категории:
            {foreach $categories as $c}
                <a href="{$app_url}/category/{$c.slug}">{$c.title|escape}</a>{if !$c@last}, {/if}
            {/foreach}
        </div>
    </article>

    {if $similar}
        <section class="similar">
            <h2>Похожие статьи</h2>
            <div class="articles-grid">
                {foreach $similar as $article}
                    {include file='partials/article-card.tpl' article=$article}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}