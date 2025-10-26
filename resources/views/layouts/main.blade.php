<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Financial Report') }}</title>

        <!-- Fonts -->

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
        <!-- Styles -->
        @livewireStyles
        @livewireScripts
    </head>

    <body>
        <x-navbar/>
        <div class="flex h-full" x-data="{ show: true }">
            <x-sidebar.base/>

            <div class="w-full transition-all overflow-x-hidden" x-bind:class="show ? 'lg:ml-56' : 'lg:ml-16'" id="content">
                @yield('content')
            </div>
        </div>

        <script>
            document.querySelector('#menu-button').addEventListener('click', function (event) {
                const menuElement = document.querySelector('#menu');
                const contentElement = document.querySelector('#content');
                menuElement.classList.toggle('-translate-x-[250px]');
                contentElement.classList.toggle('overlay-on');
                contentElement.classList.toggle('overlay-off');
                menuElement.setAttribute('data-menu', true);
                hamburger();
            });

            function hamburger() {
                const bar1 = document.querySelector('#bar-1');
                const bar2 = document.querySelector('#bar-2');
                const bar3 = document.querySelector('#bar-3');

                [
                    'rotate-45',
                    'translate-y-1.5'
                ].forEach(className => bar1.classList.toggle(className));
                [
                    'opacity-100',
                    'opacity-0',
                    'visible',
                    'invisible'
                ].forEach(className => bar2.classList.toggle(className));
                [
                    'rotate-[135deg]',
                    '-translate-y-1.5'
                ].forEach(className => bar3.classList.toggle(className));
            }

            if (document.querySelector('#content')) {
              document.querySelector('#content').addEventListener('click', closeSidebar);
            }

            if (document.querySelector('#user-menu-button')) {
              document.querySelector('#user-menu-button').addEventListener('click', closeSidebar);
            }

            function closeSidebar() {
                const menuElement = document.querySelector('#menu');
                const contentElement = document.querySelector('#content');
                const isSidebarActive = menuElement.getAttribute('data-menu');
                if (isSidebarActive.toLowerCase() === 'true') {
                    menuElement.classList.add('-translate-x-[250px]');
                    contentElement.classList.remove('overlay-on');
                    contentElement.classList.add('overlay-off');
                    hamburger();
                }
                menuElement.setAttribute('data-menu', false);
            }
        </script>

        @livewireScripts
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <x-livewire-alert::scripts />
    </body>
</html>
