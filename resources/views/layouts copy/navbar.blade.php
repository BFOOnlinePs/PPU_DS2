<style>
    .nav-link:hover {
        border-radius: 8px;
        background-color: #0c563d !important;
        color: #ffff !important;
    }

    .nav-link {
        border-radius: 10px;
    }

    .nav-link svg {
        height: 16px;
    }

    .dropdown-content a {
        font-size: 95%;
    }

    .dropdown-basic .dropdown .dropbtn {
        padding: 3px;
    }

    .main-header-left{
        width: 11%  !important;
        padding: 0px !important;



      }

      .page-main-header .main-header-right .main-header-left{
        justify-content: center;
        display: flex;
        align-items: center;
      }
    @media (max-width: 991px) {
      li a span {
        display:none;
      }
      li a {

        width:10%;
      }
      .main-header-left{
       width:7%


      }
      .main-header-left h6{
      font-size:90%


      }
     .main-header-left{
       width:7%


      }
      .main-header-left h6{
      font-size:90%


      }
    }
     .main-header-left{
       width:7%


      }
      .main-header-left h6{
      font-size:100%


      }
</style>

<div class="page-main-header d-flex">
    <div class="main-header-right row m-0">
        <div class="main-header-left" >

                {{-- <a href="index.html">
                    <img class="img-fluid" src="{{ asset('assets/images/logo/logo.png') }}" alt="">
                </a> --}}
                <h6 style="margin-bottom: 0px;">{{__('translate.Dual Studies College')}}</h6>

            <div class="dark-logo-wrapper"><a href="index.html"><img class="img-fluid"
                        src="{{ asset('assets/images/logo/dark-logo.png') }}" alt=""></a></div>
            <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center"
                    id="sidebar-toggle"></i></div>
        </div>
        <!-- Page Sidebar Start-->
        <div class="left-menu-header col-md-8">
            @include('layouts.sidebar')
        </div>
        <!-- Page Sidebar Ends-->
             <!--
            <ul>
                <li>
                    <form class="form-inline search-form">
                        <div class="search-bg"><i class="fa fa-search"></i>
                            <input class="form-control-plaintext" placeholder="Search here.....">
                        </div>
                    </form><span class="d-sm-none mobile-search search-bg"><i class="fa fa-search"></i></span>
                </li>
            </ul>
     -->
        <div class="nav-right col pull-right right-menu ">
            <ul class="">
                <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i
                            data-feather="maximize"></i></a></li>
                              <!-- <li class="onhover-dropdown">
                    <div class="notification-box"><i data-feather="bell"></i><span
                            class="dot-animated"></span></div>
                    <ul class="notification-dropdown onhover-show-div">
                        <li>
                            <p class="f-w-700 mb-0">You have 3 Notifications<span
                                    class="pull-right badge badge-primary badge-pill">4</span></p>
                        </li>
                        <li class="noti-primary">
                            <div class="media"><span class="notification-bg bg-light-primary"><i
                                        data-feather="activity"> </i></span>
                                <div class="media-body">
                                    <p>Delivery processing </p><span>10 minutes ago</span>
                                </div>
                            </div>
                        </li>
                        <li class="noti-secondary">
                            <div class="media"><span class="notification-bg bg-light-secondary"><i
                                        data-feather="check-circle"> </i></span>
                                <div class="media-body">
                                    <p>Order Complete</p><span>1 hour ago</span>
                                </div>
                            </div>
                        </li>
                        <li class="noti-success">
                            <div class="media"><span class="notification-bg bg-light-success"><i
                                        data-feather="file-text"> </i></span>
                                <div class="media-body">
                                    <p>Tickets Generated</p><span>3 hour ago</span>
                                </div>
                            </div>
                        </li>
                        <li class="noti-danger">
                            <div class="media"><span class="notification-bg bg-light-danger"><i
                                        data-feather="user-check"> </i></span>
                                <div class="media-body">
                                    <p>Delivery Complete</p><span>6 hour ago</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li> -->
                <!-- <li>
                    <div class="mode"><i class="fa fa-moon-o"></i></div>
                </li> -->
                <!-- <li class="onhover-dropdown"><i data-feather="message-square"></i>
                    <ul class="chat-dropdown onhover-show-div">
                        <li>
                            <div class="media"><img class="img-fluid rounded-circle me-3"
                                    src="{{ asset('assets/images/user/4.jpg') }}" alt="">
                                <div class="media-body"><span>Ain Chavez</span>
                                    <p class="f-12 light-font">Lorem Ipsum is simply dummy...</p>
                                </div>
                                <p class="f-12">32 mins ago</p>
                            </div>
                        </li>
                        <li>
                            <div class="media"><img class="img-fluid rounded-circle me-3"
                                    src="{{ asset('assets/images/user/1.jpg') }}" alt="">
                                <div class="media-body"><span>Erica Hughes</span>
                                    <p class="f-12 light-font">Lorem Ipsum is simply dummy...</p>
                                </div>
                                <p class="f-12">58 mins ago</p>
                            </div>
                        </li>
                        <li>
                            <div class="media"><img class="img-fluid rounded-circle me-3"
                                    src="{{ asset('assets/images/user/2.jpg') }}" alt="">
                                <div class="media-body"><span>Kori Thomas</span>
                                    <p class="f-12 light-font">Lorem Ipsum is simply dummy...</p>
                                </div>
                                <p class="f-12">1 hr ago</p>
                            </div>
                        </li>
                        <li class="text-center"> <a class="f-w-700" href="javascript:void(0)">See All </a>
                        </li>
                    </ul>
                </li> -->
                <li class="onhover-dropdown p-0">
                    <a class="btn btn-primary-light" style="font-size: 12px" href="{{route('language' , 'en')}}">English</a>
                    <a class="btn btn-primary-light" style="font-size: 12px" href="{{route('language' , 'ar')}}">عربي</a>
                    <a class="btn btn-primary-light" style="font-size: 12px" href="#"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        {{__('translate.Log out')}} {{-- تسجيل الخروج --}}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </div>
        <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
    </div>
</div>