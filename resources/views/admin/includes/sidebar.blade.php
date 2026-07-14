<!--

 SIDEBAR

  Example gradients:
   .aside-primary
   .bg-gradient-default
   .bg-gradient-purple
   etc

   * Footer should also match
-->
<aside id="aside-main" class="aside-start aside-primary aside-hide-xs d-flex flex-column h-auto">


<!--
  LOGO
  visibility : desktop only
-->
    <div class="d-none d-sm-block">
        <div class="clearfix d-flex justify-content-between">

            <!-- Logo : height: 60px max -->
            <a class="w-100 align-self-center navbar-brand p-3" href="{{ url('/') }}" target="_blank">
                <!-- <img src="assets/images/logo/logo_light.svg" width="110" height="60" alt="..."> -->
                <span>{{ App::isLocale('en') ? config('custom.application_name_en'): config('custom.application_name_np') }}</span>
            </a>

        </div>
    </div>
    <!-- /LOGO -->


    <div class="aside-wrapper scrollable-vertical scrollable-styled-light align-self-baseline h-100 w-100">

        <!--

   All parent open navs are closed on click!
   To ignore this feature, add .js-ignore to .nav-deep

   Links height (paddings):
    .nav-deep-xs
    .nav-deep-sm
    .nav-deep-md  	(default, ununsed class)

   .nav-deep-hover 	hover background slightly different
   .nav-deep-bordered	bordered links


   ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   IMPORTANT NOTE:
    Curently using ajax navigation!
    remove . class to have regular links!
   ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  -->
        <nav class="nav-deep nav-deep-dark nav-deep-hover pb-5">
            <ul class="nav flex-column">

                <li class="nav-item">
                    <a class="nav-link " href="{{ route('admin.dashboard') }}">
                        <i class="fi fi-menu-dots"></i>
                        <b>{{ App::isLocale('en') ? 'Dashboard' : 'ड्यासवोर्ड' }}</b>
                    </a>
                </li>

                @can('show-person')
				<li class="nav-item">
					<a class="nav-link" href="{{ route('admin.person.index')}}">
						<i class="nav-icon fi fi-user"><!-- main icon --></i>
						Persons
					</a>
				</li>
				@endcan

                @canany(['show-blogs', 'create-blogs', 'show-tag', 'show-blog category'])
				<li class="nav-item {{ Request::is('admin/blogs') || Request::is('admin/blogs/*') || Request::is('admin/tag') || Request::is('admin/tag/*') || Request::is('admin/news-category') || Request::is('admin/news-category/*') || Request::is('admin/main-category') || Request::is('admin/main-category/*') ? 'active' : '' }}">
					<a class="nav-link" href="#">
						<span class="group-icon float-end">
							<i class="fi fi-arrow-end-slim"></i>
							<i class="fi fi-arrow-down-slim"></i>
						</span>
						<i class="fi fi-task-list"></i>
						Blogs
					</a>

					<ul class="nav flex-column">
						<li class="nav-item {{ Request::is('admin/blogs') ? 'active' : '' }}">
							<a class="nav-link " href="{{ route('admin.blogs.index')}}">
								All Blogs
							</a>
						</li>
						@can('create-blogs')
						<li class="nav-item {{ Request::is('admin/blogs/create') ? 'active' : '' }}">
							<a class="nav-link " href="{{ route('admin.blogs.create')}}">
								Add Blogs
							</a>
						</li>
						@endcan
						@can('show-tag')
						<li class="nav-item {{ Request::is('admin/tag') ? 'active' : '' }}">
							<a class="nav-link " href="{{ route('admin.tag.index')}}">
								Tags
							</a>
						</li>
						@endcan
						@can('show-blog category')
						<li class="nav-item">
							<a class="nav-link" href="{{ route('admin.blog-category.index')}}">
								Blog Category
							</a>
						</li>
						@endcan
					</ul>
				</li>
				@endcanany
                @can('show-feedback')
				<li class="nav-item">
					<a class="nav-link" href="{{ route('admin.feedback.index')}}">
						<i class="nav-icon fi fi-round-question-full"><!-- main icon --></i>
						Feedback
					</a>
				</li>
				@endcan

                @can('show-pages')
                    <li class="nav-item">

                        <a class="nav-link" href="{{ route('admin.pages.index') }}">
                            <i class="nav-icon fi fi-round-list"><!-- main icon --></i>
                            
                            {{ App::isLocale('en') ? 'Pages' : 'Pages' }}
                        </a>

                    </li>
                @endcan


                @can('show-media')
                    <li class="nav-item {{ Request::is('admin/media') || Request::is('admin/media/*') ? 'active' : '' }}">
                        <a class="nav-link" href="#">
                            <span class="group-icon float-end">
                                <i class="fi fi-arrow-end-slim"></i>
                                <i class="fi fi-arrow-down-slim"></i>
                            </span>
                            <i class="fi fi-exit"></i>
                            {{ App::isLocale('en') ? 'Media' : 'Media' }}
                        </a>

                        <ul class="nav flex-column">

                            <li class="nav-item {{ Request::is('admin/media') ? 'active' : '' }}">
                                <a class="nav-link " href="{{ route('admin.media.index') }}">
                                    {{ App::isLocale('en') ? 'Library' : 'Library' }}

                                </a>
                            </li>
                            @can('create-media')
                                <li class="nav-item {{ Request::is('admin/media/create') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.media.create') }}">
                                        {{ App::isLocale('en') ? 'Add Media' : 'Add Media' }}
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @canany(['show-users', 'show-role'])
                    <li
                        class="nav-item {{ Request::is('admin/users') || Request::is('admin/users/*') || Request::is('admin/roles') || Request::is('admin/roles/*') ? 'active' : '' }}">
                        <a class="nav-link" href="#">
                            <span class="group-icon float-end">
                                <i class="fi fi-arrow-end-slim"></i>
                                <i class="fi fi-arrow-down-slim"></i>
                            </span>
                            <i class="nav-icon fi fi-user-plus "><!-- main icon --></i>
                            
                            {{ App::isLocale('en') ? 'User Management' : 'User Management' }}

                        </a>

                        <ul class="nav flex-column">
                            @can('show-users')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.users.index') }}">
                                    {{ App::isLocale('en') ? 'Users' : 'Users' }}
                                    </a>
                                </li>
                            @endcan
                            @can('show-role')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.roles.index') }}">
                                        {{ App::isLocale('en') ? 'Roles' : 'Roles' }}
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['show-site configuration', 'show-menu'])
                    <li
                        class="nav-item {{ Request::is('admin/menu') || Request::is('admin/menu/*') || Request::is('admin/site_config') || Request::is('admin/site_config/*') || Request::is('admin/design_config/edit') ? 'active' : '' }}">
                        <a class="nav-link" href="#">
                            <span class="group-icon float-end">
                                <i class="fi fi-arrow-end-slim"></i>
                                <i class="fi fi-arrow-down-slim"></i>
                            </span>
                            <i class="nav-icon fi fi-cog "><!-- main icon --></i>
                            {{ App::isLocale('en') ? 'Setting' : 'Setting' }}
                        </a>

                        <ul class="nav flex-column">
                            @canany(['show-site configuration'])
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.site_config.edit') }}">
                                        {{ App::isLocale('en') ? 'General Setting' : 'General Setting' }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.design_config.edit')}}">
                                        Design Setting
                                    </a>
                                </li>
                            @endcanany
                            @can('show-menu')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.menu.index') }}">
                                        {{ App::isLocale('en') ? 'Menu Setting' : 'Menu Setting' }}
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @can('show-backups')
                    <li class="nav-item {{ Request::is('admin/backup') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.backups.index') }}">
                            <i class="fi fi-database"></i>
                            {{ App::isLocale('en') ? 'Backup' : 'Backup' }}
                        </a>
                    </li>
                @endcan
                @canany(['public-video mannual', 'superadmin-video mannual', 'otherOffice-video mannual'])
                    <li
                        class="nav-item {{ Request::is('admin/videos') || Request::is('admin/videos/*')  ? 'active' : '' }}">
                        <a class="nav-link" href="#">
                            <span class="group-icon float-end">
                                <i class="fi fi-arrow-end-slim"></i>
                                <i class="fi fi-arrow-down-slim"></i>
                            </span>
                            <i class="nav-icon fi fi-cog "><!-- main icon --></i>
                            {{ App::isLocale('en') ? 'User Mannual' : 'प्रयोग गर्ने तरिका' }}
                        </a>

                        <ul class="nav flex-column">
                            @can('superadmin-video mannual')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.videos',['portal_name'=>'superadmin_portal']) }}">
                                    {{ App::isLocale('en') ? 'Super Admin Portal' : 'सुपर एडमिन पोर्टल' }}
                                    </a>
                                </li>
                            @endcan
                            @can('public-video mannual')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.videos',['portal_name'=>'public_portal']) }}">
                                    {{ App::isLocale('en') ? 'Public Portal' : 'पव्लिक पोर्टल' }}
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
            </ul>
        </nav>

    </div>
</aside>
<!-- /SIDEBAR -->
