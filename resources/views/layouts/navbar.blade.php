<div class="header-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-12">
                <span>Have any question?  +91 7483791428</span>
            </div>
            <div class="col-lg-5 col-md-6 col-12">
                <div class="header-top-right">
                    <span>Phone: +91 9900426501</span>
                    <span>Email: info@hurrayedutech.com</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="header-logo-menu sticker">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-12">
                <div class="logo">
                    <a href="{{ url('/') }}"><img src="{{ asset('assets/img/logo/logo_h.webp')}}" alt="EDUCAT"></a>
                </div>
            </div>
            <div class="col-lg-9 col-12">
                <div class="mainmenu-area pull-right">
                    <div class="mainmenu d-none d-lg-block">
                        <nav>
                            <ul id="nav">
                                <li class="current"><a href="{{ url('/') }}">Home</a>
                                    <ul class="sub-menu">
                                        <li><a href="{{ url('/') }}">Home Version 1</a></li>
                                        <li><a href="{{ url('/home2') }}">Home Version 2</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ url('/introduction') }}">About</a>
                                <ul class="sub-menu">
                                    <li><a href="{{ url('/introduction') }}">Hurray Overview</a></li>
                                    <li><a href="{{ url('/management-team') }}">Leadership Team</a></li>
                                    <li><a href="{{ url('/vision-mission') }}">Vision &amp; Mission</a></li>
                                    <li><a href="{{ url('/why-hurray') }}">Why Choose HURRAY?</a></li>
                                    <li><a href="{{ url('/social-initiatives') }}">Social Initiatives</a></li>
                                    <li><a href="{{ url('/refer-and-earn') }}">Refer &amp; Earn</a></li>
                                </ul>
                                </li>
                                @php
                                    $blogs = [
                                        'all-blogs' => 'All Blogs',
                                        'ielts-blogs' => 'IELTS Training',
                                        'grammar-blogs' => 'Spoken English',
                                        'pte-blogs' => 'PTE Training',
                                        'study-canada' => 'Study In Canada',
                                        'oet-blogs' => 'OET Training',
                                        'study-uk' => 'Study In UK',
                                        'Study-Abroad' => 'Study Abroad',
                                        'hospitality-management-industry' => 'Hospitality Management Industry',
                                        'study-usa' => 'Study In USA',
                                        'study-australia' => 'Study In Australia',
                                        'study-ireland' => 'Study In Ireland',
                                        'trending-posts' => 'Trending',
                                        'study-germany' => 'Study In Germany',
                                        'medical-education-abroad' => 'Medical Education Abroad',
                                        'computer-science-options-abroad' => 'Computer Science',
                                        'study-nz' => 'Study In New Zealand',
                                        'duolingo-blogs' => 'Duolingo Blogs',
                                    ];
                                @endphp
                                
                                <li><a href="{{ url('/introduction') }}">Blogs</a>
                                    <ul class="sub-menu">
                                        @foreach ($blogs as $slug => $title)
                                        <li><a href="{{ route('blogs.page', ['slug' => $slug]) }}">{{ $title }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li><a href="gallery.html">Gallery</a>
                                    <ul class="sub-menu">
                                        <li><a href="gallery.html">Gallery</a></li>
                                        <li><a href="gallery-2.html">Gallery Filtaring</a></li>
                                        <li><a href="gallery-four-column.html">Gallery Four Column</a></li>
                                    </ul>
                                </li>
                                {{-- <li><a href="{{ url('/courses') }}">Courses</a>
                                    <ul class="sub-menu">
                                        <li><a href="courses-details.html">IELTS</a></li>
                                        <li><a href="courses-details.html">PTE</a></li>
                                        <li><a href="courses-details.html">OET</a></li>
                                        <li><a href="courses-details.html">English</a></li>
                                        
                                    </ul>
                                </li> --}}
                                <li><a href="{{ url('/courses') }}">Courses</a>
                                    <ul class="sub-menu">
                                        <li><a href="#">IELTS<i class="zmdi zmdi-chevron-right"></i></a>
                                            <ul class="inside-menu">
                                                <li><a href="{{ url('/ielts-coaching-in-bangalore') }}">Classroom Training</a></li>
                                                <li><a href="{{ url('/ielts-online-training-in-bangalore') }}">Online Training</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">PTE<i class="zmdi zmdi-chevron-right"></i></a>
                                            <ul class="inside-menu">
                                                <li><a href="{{ url('/pte-training-center-in-bangalore') }}">Classroom Training</a></li>
                                                <li><a href="{{ url('/pte-voucher') }}">PTE Voucher</a></li>
                                                
                                            </ul>
                                        </li>
                                        <li><a href="#">OET<i class="zmdi zmdi-chevron-right"></i></a>
                                            <ul class="inside-menu">
                                                <li><a href="{{ url('/best-oet-training-in-bangalore') }}">OET Training</a></li>
                                                <li><a href="{{ url('/oet-online-courses-in-Bangalore') }}">OET Online Training</a></li>
                                                
                                            </ul>
                                        </li>
                                        <li><a href="#">English<i class="zmdi zmdi-chevron-right"></i></a>
                                            <ul class="inside-menu">
                                                <li><a href="{{ url('/spoken-english') }}">Spoken English</a></li>
                                                <li><a href="{{ url('/accent-neutralization') }}">Accent Neutralization</a></li>
                                                <li><a href="{{ url('/corporate-training') }}">Corporate Training</a></li>
                                                
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="index.html">Pages</a>
                                    <ul class="sub-menu">
                                        <li><a href="team-details.html">Team Details</a></li>
                                        <li><a href="course.html">Courses Page</a></li>
                                        <li><a href="courses-details.html">Course Details Page</a></li>
                                        <li><a href="event.html">Event Page</a></li>
                                        <li><a href="event-details.html">Event Details Page</a></li>
                                        <li><a href="blog.html">Blog</a></li>
                                        <li><a href="blog-details.html">Blog Details Page</a></li>
                                        <li><a href="blog-left-sidebar.html">Blog Left Sidebar</a></li>
                                        <li><a href="blog-no-sidebar.html">Blog No Sidebar</a></li>
                                        <li><a href="wishlist.html">Wishlist Page</a></li>
                                        <li><a href="checkout.html">Checkout Page</a></li>
                                        <li><a href="cart.html">Shopping Cart Page</a></li>
                                        <li><a href="login-register.html">Login Page</a></li>
                                        <li><a href="contact.html">Contact</a></li>
                                        <li><a href="404.html">404 Error</a></li>
                                    </ul>
                                </li>
                                <li><a href="shop.html">Shop</a>
                                    <ul class="sub-menu">
                                        <li><a href="single-product.html">Single Product Page</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ url('/contact') }}">Contact</a></li>
                            </ul>
                        </nav>
                    </div>
                    <ul class="header-search">
                        <li class="search-menu">
                            <i id="toggle-search" class="zmdi zmdi-search"></i>
                        </li>
                    </ul>
                    <!--Search Form-->
                    <div class="search">
                        <div class="search-form">
                            <form id="search-form" action="#">
                                <input type="search" placeholder="Search here..." name="search" />
                                <button type="submit">
                                    <span><i class="fa fa-search"></i></span>
                                </button>
                            </form>                                
                        </div>
                    </div>
                    <!--End of Search Form-->
                </div> 
            </div>
        </div>
    </div>
</div>  
<!-- Mobile Menu Area start -->
<div class="mobile-menu-area">
    <div class="container clearfix">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="mobile-menu">
                    <nav id="dropdown">
                        <ul>
                            <li><a href="index.html">HOME</a>
                                <ul>
                                    <li><a href="index.html">Home Version 1</a></li>
                                    <li><a href="index-2.html">Home Version 1</a></li>
                                </ul>
                            </li>
                            <li><a href="gallery.html">Gallery</a>
                                <ul>
                                    <li><a href="gallery.html">Gallery</a></li>
                                    <li><a href="gallery-2.html">Gallery Filtaring</a></li>
                                    <li><a href="gallery-four-column.html">Gallery Four Column</a></li>
                                </ul>
                            </li>
                            <li><a href="team-details.html">Team Details</a></li>
                            <li><a href="course.html">Courses</a>
                                <ul class="sub-menu">
                                    <li><a href="courses-details.html">Courses Details</a></li>
                                </ul>
                            </li>
                            <li><a href="shop.html">Shop</a>
                                <ul class="sub-menu">
                                    <li><a href="single-product.html">Single Product</a></li>
                                </ul>
                            </li>
                            <li><a href="event.html">Event</a>
                                <ul class="sub-menu">
                                    <li><a href="event-details.html">Event Details</a></li>
                                </ul>
                            </li>
                            <li><a href="blog.html">Blog</a>
                                <ul class="sub-menu">
                                    <li><a href="blog-details.html">Blog Details</a></li>
                                    <li><a href="blog-left-sidebar.html">Blog Left Sidebar</a></li>
                                    <li><a href="blog-no-sidebar.html">Blog No Sidebar</a></li>
                                </ul>
                            </li>
                            <li><a href="contact.html">Pages</a>
                                <ul>
                                    <li><a href="team-details.html">Team Details</a></li>
                                    <li><a href="course.html">Courses Page</a></li>
                                    <li><a href="courses-details.html">Course Details Page</a></li>
                                    <li><a href="event.html">Event Page</a></li>
                                    <li><a href="event-details.html">Event Details Page</a></li>
                                    <li><a href="blog.html">Blog</a></li>
                                    <li><a href="blog-details.html">Blog Details Page</a></li>
                                    <li><a href="blog-left-sidebar.html">Blog Left Sidebar</a></li>
                                    <li><a href="blog-no-sidebar.html">Blog No Sidebar</a></li>
                                    <li><a href="wishlist.html">Wishlist Page</a></li>
                                    <li><a href="checkout.html">Checkout Page</a></li>
                                    <li><a href="cart.html">Shopping Cart Page</a></li>
                                    <li><a href="login-register.html">Login Page</a></li>
                                    <li><a href="contact.html">Contact</a></li>
                                    <li><a href="404.html">404 Error</a></li>
                                </ul>
                            </li>
                            <li><a href="contact.html">Contact us</a></li>
                        </ul>
                    </nav>
                </div>					
            </div>
        </div>
    </div>
</div>
<!-- Mobile Menu Area end -->   
</header>
<!--End of Header Area-->