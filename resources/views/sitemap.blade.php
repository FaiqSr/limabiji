{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach ($staticPages as $page)
    <url>
        <loc>{{ $page['url'] }}</loc>
        <lastmod>{{ $page['lastmod'] }}</lastmod>
        <changefreq>{{ $page['changefreq'] }}</changefreq>
        <priority>{{ $page['priority'] }}</priority>
    </url>
@endforeach

@foreach ($origins as $origin)
    <url>
        <loc>{{ $origin['url'] }}</loc>
        <lastmod>{{ $origin['lastmod'] }}</lastmod>
        <changefreq>{{ $origin['changefreq'] }}</changefreq>
        <priority>{{ $origin['priority'] }}</priority>
@if (!empty($origin['image']))
        <image:image>
            <image:loc>{{ $origin['image'] }}</image:loc>
            <image:title>{{ $origin['title'] }}</image:title>
        </image:image>
@endif
    </url>
@endforeach

@foreach ($articles as $article)
    <url>
        <loc>{{ $article['url'] }}</loc>
        <lastmod>{{ $article['lastmod'] }}</lastmod>
        <changefreq>{{ $article['changefreq'] }}</changefreq>
        <priority>{{ $article['priority'] }}</priority>
@if (!empty($article['image']))
        <image:image>
            <image:loc>{{ $article['image'] }}</image:loc>
            <image:title>{{ $article['title'] }}</image:title>
        </image:image>
@endif
    </url>
@endforeach
</urlset>
