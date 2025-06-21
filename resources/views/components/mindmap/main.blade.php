<x-main title="{{ $title }}" class="!p-0" full>
    <div class="drawer lg:drawer-open">
        <input id="aside-dashboard" type="checkbox" class="drawer-toggle" />

        <div class="drawer-content flex flex-col">
            @include('components.home.navbar')

            <div class="p-4 md:p-6 flex-1">
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>

            @include('components.footer')
        </div>

        @include('components.home.sidebar')
    </div>
</x-main>
