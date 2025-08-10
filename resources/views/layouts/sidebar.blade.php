<aside class="hidden md:block w-64 h-screen bg-white border-e border-gray-200 fixed">
    <!-- Logo -->
    <div class="h-16 flex items-center justify-center border-b">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
    </div>

    <!-- Menu -->
    <nav class="flex flex-col py-4 space-y-1">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="px-6 py-3 flex items-center gap-3">
            <i class="bi bi-graph-up"></i>
            <span>{{ __('Beranda') }}</span>
        </x-nav-link>
        <x-nav-link :href="route('workers.index')" :active="request()->routeIs('workers.index')" class="px-6 py-3 flex items-center gap-3">
            <i class="bi bi-people"></i>
            <span>{{ __('Tukang') }}</span>
        </x-nav-link>
        <x-nav-link :href="route('presences.index')" :active="request()->routeIs('presences.index')" class="px-6 py-3 flex items-center gap-3">
            <i class="bi bi-qr-code-scan"></i>
            <span>{{ __('Scan Presensi') }}</span>
        </x-nav-link>

        <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" class="px-6 py-3 flex items-center gap-3">
            <i class="bi bi-person-circle"></i>
            <span>{{ __('Profile') }}</span>
        </x-nav-link>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();"
                class="px-6 py-3 flex items-center gap-3">
                <i class="bi bi-box-arrow-right"></i>
                <span>{{ __('Log Out') }}</span>
            </x-nav-link>
        </form>
    </nav>

    <!-- User Info -->
    <div class="absolute bottom-4 left-4 text-sm text-gray-500 px-4">
        {{ Auth::user()->name }}<br>
        <span class="text-xs text-gray-400">{{ Auth::user()->email }}</span>
    </div>
</aside>
