<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            {{-- Logo on the left --}}
            <div class="shrink-0">
                <a href="{{ route('home') }}">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>
            </div>

            {{-- Navigation Links aligned right --}}
            <div class="hidden sm:flex items-center space-x-4 ml-auto">
                @auth
                    {{-- Admin --}}
                    @if (auth()->user()->role === 'admin')
                        <x-nav-link href="{{ url('/admin/dashboard') }}" :active="request()->is('admin/dashboard')">
                            Admin Dashboard
                        </x-nav-link>
                    @endif

                    {{-- Instructor --}}
                    @if (auth()->user()->role === 'instructor')
                        <x-nav-link href="{{ route('instructor.dashboard') }}" :active="request()->routeIs('instructor.dashboard')">
                            Instructor Dashboard
                        </x-nav-link>
                        <x-nav-link href="{{ route('courses.index') }}" :active="request()->routeIs('courses.index')">
                            All Courses
                        </x-nav-link>
                        <x-nav-link href="{{ route('courses.create') }}" :active="request()->routeIs('courses.create')">
                            Create Course
                        </x-nav-link>
                    @endif

                    {{-- Student --}}
                    @if (auth()->user()->role === 'student')
                        <x-nav-link href="{{ route('courses.index') }}" :active="request()->routeIs('courses.index')">
                            All Courses
                        </x-nav-link>
                        <x-nav-link href="{{ route('student.dashboard') }}" :active="request()->routeIs('student.dashboard')">
                            My Dashboard
                        </x-nav-link>
                        <x-nav-link href="{{ route('student.courses') }}" :active="request()->routeIs('student.courses')">
                            My Courses
                        </x-nav-link>
                    @endif

                    {{-- Dropdown --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">
                        {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-indigo-600 px-4 py-2 rounded hover:bg-indigo-700">
                        {{ __('Register') }}
                    </a>
                @endguest
            </div>

            {{-- Hamburger for mobile --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>
</nav>
