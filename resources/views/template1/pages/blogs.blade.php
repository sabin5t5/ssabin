@extends('template1.layouts.app')

@section('content')
<!-- Blogs Section -->
<section id="blogs" class="blogs section">
    <style>
        .blogs .subscribe-card,
        .blogs .blog-card {
            background: var(--surface-color);
            color: var(--nav-color);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
            transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease, transform 0.25s ease;
        }

        .blogs .subscribe-card:hover,
        .blogs .blog-card:hover {
            background-color: #2d2d2d;
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.22);
            transform: translateY(-2px);
        }

        .blogs .subscribe-card:hover .form-control,
        .blogs .blog-card:hover .blog-date,
        .blogs .blog-card:hover p,
        .blogs .blog-card:hover span {
            color: #ffffff;
        }

        .blogs .subscribe-card .form-control {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: var(--default-color);
        }

        .blogs .subscribe-card .form-control::placeholder {
            color: rgba(255, 255, 255, 0.65);
        }

        .blogs .subscribe-card .btn-subscribe {
            background: var(--accent-color);
            color: #111111;
            border: none;
            font-weight: 600;
        }

        .blogs .subscribe-card .btn-subscribe:hover {
            background: #f2f2f2;
            color: #111111;
        }

        .blogs .blog-card .blog-date {
            color: var(--nav-color)
        }
    </style>

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Blogs</h2>
        <p>Helpful tools, thoughtful articles and other findings from the web.</p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="card subscribe-card shadow-sm border-0 p-4">
                    <h4 class="mb-2">Subscribe for new posts</h4>
                    <p class="mb-3" style="color: color-mix(in srgb, var(--default-color), transparent 20%);">Get fresh articles, practical tips, and curated resources in your inbox.</p>
                    <form class="d-flex flex-wrap gap-2">
                        <input type="email" class="form-control" placeholder="Enter your email address" required>
                        <button type="submit" class="btn btn-subscribe">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row g-4">
           

            @foreach ($blogs as $blog)
            <a href="{{ route('blog.content', $blog->slug) }}" class="col-lg-12 col-md-12">
                <div class="card blog-card shadow-sm border-0 p-4 h-100">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-2">
                        <h4 class="mb-2">{{ $blog->title }}</h4>
                        <span class="blog-date">{{ $blog->published_at->diffForHumans() }}</span>
                    </div>
                    <p class="mb-2"><strong>Category:</strong> {{ $blog->blogCategory->category_name }}</p>
                    <p class="mb-0"><strong>Tags:</strong>
                        @foreach ($blog->tags as $index => $tag)
                            {{ $tag }}@if (!$loop->last), @endif
                        @endforeach
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section><!-- End Blogs Section -->

@endsection