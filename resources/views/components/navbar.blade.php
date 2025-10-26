<nav class="sticky top-0 z-30 flex-none py-3.5 px-3 lg:px-5 w-full mx-auto bg-white shadow-sm">
    <div class="flex justify-between items-center">
        <div class="order-2 lg:order-1 self-center items-center">
            <a href="/" class="font-bold text-2xl">
                Financial Report
            </a>
        </div>
        <div class="order-1 lg:order-2 lg:basis-1/3">
            {{-- Hamburger --}}
            <div class="flex items-center lg:hidden">
                <button class="py-4 ml-4 cursor-pointer flex flex-col gap-1" id="menu-button">
                    <div id="bar-1" class="w-5 h-0.5 bg-black transition-all"></div>
                    <div id="bar-2" class="w-5 h-0.5 bg-black transition-all opacity-100 visible"></div>
                    <div id="bar-3" class="w-5 h-0.5 bg-black transition-all"></div>
                </button>
            </div>
        </div>
        <div class="order-3 mr-3">
            <div x-data="{show: false}" @click.away="show = false" class="relative">
                <button x-on:click="show = !show" class="flex justify-start items-center gap-2.5">
                    <p class="text-prediction font-medium text-sm hidden lg:flex">{{ auth()->user()->name }}</p>
                    {{-- avatar --}}
                    <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
                </button>

                <div x-show="show" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="w-48 absolute z-50 top-16 right-0 bg-white text-sm border border-gray-100 rounded-xl divide-y">
                    <a href="#">
                        <p class="w-full hover:bg-gray-100 text-prediction font-medium text-left py-3 px-4">
                            {{ __('Manage My Account') }}
                        </p>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <a href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            <p class="w-full hover:bg-gray-100 font-medium text-left text-red-600 py-3 px-4">
                                {{ __('Sign Out') }}
                            </p>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
