<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title') | Study Management System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('components.admin.partials.header_link')
    </head>

    <body data-sidebar="dark" data-keep-enlarged="true" class="vertical-collpsed dx-viewport">
        <div id="layout-wrapper">
            <x-admin-topnavigation />
            <x-admin-sidebar :module=$module />
            <div class="main-content">
                <!-- <div class="preload">
                    <img src="{{ asset('/images/loader.gif') }}">
                </div> -->
                @yield('content')
                <x-admin-footer />
                @include('components.admin.partials.modal')

            </div>
        </div>
        <div class="rightbar-overlay"></div>
        @include('components.admin.partials.footer_link')
        @yield('js')
        <script type="text/javascript">
            @if(Session::has('messages'))
                $(document).ready(function() {
                    @foreach(Session::get('messages') AS $msg) 
                        toastr['{{ $msg["type"] }}']('{{$msg["message"]}}','{{ $msg["title"] }}');
                    @endforeach
                });
            @endif
            
            @if (count($errors) > 0) 
                $(document).ready(function() {
                    @foreach($errors->all() AS $error)
                        toastr['error']('{{$error}}');
                    @endforeach     
                });
            @endif
            $(document).ready(function (){
                $("#password").passwordRequirements({
                    numcharacters:8
                });
            });
        </script>
    </body>
</html>