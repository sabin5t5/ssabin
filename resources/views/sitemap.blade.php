<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    @foreach($news as $n)
    <url>
        <loc>{{ route('news-show', ['category'=>$n->newsCategory()->first()->slug, 'slug'=>$n->slug]) }}</loc>
        <lastmod>{{ gmdate('Y-m-d\TH:i:s\Z',strtotime($n->published_at)) }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
     </url>
    @endforeach

    @php
        $menus = [];
        $menus_collection= App\Models\Admin\Menu::where('status', 1)->orderBy('rank')->get();
        foreach($menus_collection as $key => $menu)
        {
            if($menu->parent_id != null)
            {
                $menus[$menu->parent_id]['data'][]=$menu;
            }
            else
                $menus[$menu->id]['main']=$menu;
        }
    @endphp

    @foreach($menus as $menu)
        @if(count($menu) == 1)
            @if($menu['main']->menu_type == 'page_menu')
            <url>
                <loc>{{ route('page_content',['page_name'=> $menu['main']->value]) }}</loc>
                <lastmod>{{ gmdate('Y-m-d\TH:i:s\Z',strtotime($menu['main']->updated_at)) }}</lastmod>
                <changefreq>daily</changefreq>
                <priority>0.6</priority>
            </url>
            @elseif($menu['main']->menu_type == 'news_menu')
            <url>
                <loc>{{ route('news-list', ['category'=> $menu['main']->value]) }}</loc>
                <lastmod>{{ gmdate('Y-m-d\TH:i:s\Z',strtotime($menu['main']->updated_at)) }}</lastmod>
                <changefreq>daily</changefreq>
                <priority>0.6</priority>
            </url>
            @elseif($menu['main']->menu_type == 'internal_link')
            <url>
                <loc>{{ url($menu['main']->value) }}</loc>
                <lastmod>{{ gmdate('Y-m-d\TH:i:s\Z',strtotime($menu['main']->updated_at)) }}</lastmod>
                <changefreq>daily</changefreq>
                <priority>0.6</priority>
            </url>
            @elseif($menu['main']->menu_type == 'external_link')
            <url>
                <loc>{{ $menu['main']->value}}</loc>
                <lastmod>{{ gmdate('Y-m-d\TH:i:s\Z',strtotime($menu['main']->updated_at)) }}</lastmod>
                <changefreq>daily</changefreq>
                <priority>0.6</priority>
            </url>
            @else      
            @endif
        @else
            @foreach($menu['data'] as $m)
                @if($m->menu_type == 'page_menu')
                <url>
                    <loc>{{ route('page_content',['page_name'=> $m->value]) }}</loc>
                    <lastmod>{{ gmdate('Y-m-d\TH:i:s\Z',strtotime($m->updated_at)) }}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.6</priority>
                </url>
                @elseif($m->menu_type == 'news_menu')
                <url>
                    <loc>{{ route('news-list', ['category'=> $m->value]) }}</loc>
                    <lastmod>{{ gmdate('Y-m-d\TH:i:s\Z',strtotime($m->updated_at)) }}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.6</priority>
                </url>
                @elseif($m->menu_type == 'internal_link')
                <url>
                    <loc>{{ url($m->value) }}</loc>
                    <lastmod>{{ gmdate('Y-m-d\TH:i:s\Z',strtotime($m->updated_at)) }}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.6</priority>
                </url>
                @elseif($m->menu_type == 'external_link')
                <url>
                    <loc>{{ $m->value }}</loc>
                    <lastmod>{{ gmdate('Y-m-d\TH:i:s\Z',strtotime($m->updated_at)) }}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.6</priority>
                </url>
                @else
                @endif
            @endforeach
        @endif
    @endforeach
</urlset>