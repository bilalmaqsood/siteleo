@if(!request()->header('x-pjax'))
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('blocks.meta')
        @include('blocks.metatags')
        @include('blocks.links')
        {{--<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>--}}
    </head>
    <body class="@yield('body-class')" id="@yield('body-id')">
        @include('blocks.header')
        <div id="pjax-container">
@endif
        @yield('content')
@if(!request()->header('x-pjax'))
        </div>
        @if(!isset($_COOKIE['cockie-info']))
        <div id="cockie-info">
            <div class="container">
                <p>{{trans('main.we_use_cookies_to_give_you_the')}} <a href="/conditions/">{{trans('main.more_info')}}</a></p>
                <button><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
        </div>
        @endif
        @include('blocks.footer')
        @stack('scripts')

        <script>

            $('[loading]').on('click', function(){
                var btn = $(this);
                btn.attr('disabled', 'true');
                btn.html('Loading...');
                btn.parents('form').submit();
            });

            $('#cockie-info>div>button').on('click', function(){
                $.cookie('cockie-info', 'hide', { path: '/' });
                $('#cockie-info').hide();
            });
        </script>
    </body>
</html>
@endif