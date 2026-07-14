@extends('template1.layouts.app')

@section('content')
<!-- Blog Content Section -->
<section id="blog-content" class="blogs section">
    <style>
        .blogs .article-card,
        .blogs .info-card {
            background: var(--surface-color);
            color: var(--default-color);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
            transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease, transform 0.25s ease;
        }

        .blogs .article-card:hover,
        .blogs .info-card:hover {
            background-color: #2d2d2d;
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.22);
            transform: translateY(-2px);
        }

        .blogs .article-meta {
            color: var(--nav-color);
        }

        .blogs .article-content p,
        .blogs .article-content li {
            line-height: 1.8;
            color: var(--default-color);
        }

        .blogs .article-content blockquote {
            border-left: 3px solid var(--accent-color);
            padding-left: 1rem;
            font-style: italic;
            color: color-mix(in srgb, var(--default-color), transparent 10%);
        }

        .blogs .tag-pill {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            margin: 0.25rem 0.35rem 0 0;
            background: rgba(255, 255, 255, 0.08);
            color: var(--default-color);
            border-radius: 999px;
            font-size: 0.9rem;
        }

        .blogs .code-block {
            position: relative;
            margin-top: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 0.75rem;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.35);
        }

        .blogs .code-copy-btn {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            border: 0;
            border-radius: 999px;
            background: var(--accent-color);
            color: #CCF
            padding: 0.35rem 0.7rem;
            font-size: 0.85rem;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .blogs .code-copy-btn:hover {
            transform: translateY(-1px);
            opacity: 0.95;
        }

        .blogs .code-block pre {
            margin: 0;
            padding: 1rem 1rem 1rem 1rem;
            color: #f3f4f6;
            font-size: 0.95rem;
            overflow-x: auto;
        }
        .social-links a {
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: 
        color-mix(in srgb, var(--default-color), transparent 90%);
            color: var(--default-color);
            margin: 0 2px;
            border-radius: 50%;
            text-align: center;
            width: 40px;
            height: 40px;
            transition: 0.3s;
        }
        .social-links a:hover {
            color: var(--contrast-color);
            background: var(--accent-color);
        }
    </style>

    <div class="container section-title" data-aos="fade-up">
        <h2>Blog Content</h2>
        <p>What I Know • How I Do It • What I Find on the Web.</p>
    </div>

    <div class="container" data-aos="fade-up">
        <div class="row g-4">
            <div class="col-lg-8">
                <article class="card article-card p-4 p-md-5">
                    <a href="/blogs" class="btn btn-link p-0 mb-3" style="color: var(--accent-color);">← Back to Blogs</a>
                    <h3 class="mb-3">{{ $blog->title }}</h3>
                    <div class="article-meta mb-4">
                        <span><strong>Published:</strong> {{ $blog->created_at->format('F j, Y') }}</span>
                        <span class="mx-3">|</span>
                        <span><strong>Category:</strong> {{ $blog->blogCategory->category_name }}</span>
                    </div>

                    <div class="article-content">
                        <p>{!! $blog->description !!}</p>
                    </div>
                </article>
            </div>

            <div class="col-lg-4">
                <div class="card info-card p-4">
                    <h5 class="mb-3">About this article</h5>
                    <p class="mb-2"><strong>Author:</strong> {{ $blog->user->name }}</p>
                    <p class="mb-2"><strong>Read time:</strong> {{ $blog->read_time }} min</p>

                    <div class="mt-4">

                        <div class="text-center mb-3">
                            - Share -
                        </div>
                        <div class="social-links text-center">
                            <a href="https://twitter.com/share" target="_blank" class="twitter"><i class="bi bi-twitter-x"></i></a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::url()) }}" target="_blank" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="https://www.instagram.com/" target="_blank" class="instagram"><i class="bi bi-instagram"></i></a>
                            <a href="https://www.skype.com/share" target="_blank" class="google-plus"><i class="bi bi-skype"></i></a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(Request::url()) }}" target="_blank" class="linkedin"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.code-copy-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('data-copy-target');
                const target = document.getElementById(targetId);

                if (!target) {
                    return;
                }

                const text = target.innerText;
                const originalLabel = this.textContent;

                navigator.clipboard.writeText(text).then(function () {
                    button.textContent = 'Copied!';
                    setTimeout(function () {
                        button.textContent = originalLabel;
                    }, 1500);
                }).catch(function () {
                    button.textContent = 'Copy failed';
                    setTimeout(function () {
                        button.textContent = originalLabel;
                    }, 1500);
                });
            });
        });
    });
</script>
@endsection