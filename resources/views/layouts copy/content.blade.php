<div class="page-body">
    <div class="container-fluid">
        <div class="page-header ">
            <div class="row">
                <div class="col-lg-6">
                    <h3>@yield('header_title')</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">@yield('header_title_link')</a></li>
                        <li class="breadcrumb-item">@yield('header_link')</li>
                        {{-- <li class="breadcrumb-item">Page layout</li>
                        <li class="breadcrumb-item active">Layout RTL</li> --}}
                    </ol>
                </div>
                {{-- <div class="col-lg-6">
                    <!-- Bookmark Start-->
                    <div class="bookmark">
                        <ul>
                            <li><a href="javascript:void(0)" data-container="body"
                                    data-bs-toggle="popover" data-placement="top" title=""
                                    data-original-title="Tables"><i data-feather="inbox"></i></a></li>
                            <li><a href="javascript:void(0)" data-container="body"
                                    data-bs-toggle="popover" data-placement="top" title=""
                                    data-original-title="Chat"><i data-feather="message-square"></i></a>
                            </li>
                            <li><a href="javascript:void(0)" data-container="body"
                                    data-bs-toggle="popover" data-placement="top" title=""
                                    data-original-title="Icons"><i data-feather="command"></i></a></li>
                            <li><a href="javascript:void(0)" data-container="body"
                                    data-bs-toggle="popover" data-placement="top" title=""
                                    data-original-title="Learning"><i data-feather="layers"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="bookmark-search"
                                        data-feather="star"></i></a>
                                <form class="form-inline search-form">
                                    <div class="form-group form-control-search">
                                        <input type="text" placeholder="Search..">
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <!-- Bookmark Ends-->
                </div> --}}
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row starter-main">
@yield('navbar')
@yield('content')
@yield('javascript')
@yield('javascript')
        </div>
    </div>
    <!-- Container-fluid Ends-->
</div>
