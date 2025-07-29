<!-- Drawer Wrapper -->
<div class="drawer z-50">
    <input id="mobile-drawer" type="checkbox" class="drawer-toggle" />

    <!-- Main Content -->
    <div class="drawer-content flex flex-col">
        <!-- Navbar -->
        <div class="navbar bg-white shadow-md border-b border-gray-200 px-4">
            <!-- Navbar Start -->
            <div class="navbar-start">
                <!-- Burger Icon to Open Drawer -->
                <label for="mobile-drawer" class="btn btn-ghost lg:hidden">
                    <x-lucide-menu class="h-6 w-6 text-[#123f77]" />
                </label>

                <!-- Logo -->
                @unless (request()->is('mindmap/*'))
                    <a href="{{ route('beranda') }}" class="btn btn-ghost text-xl font-bold text-[#123f77] gap-2">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-8 w-auto" />
                        Mindmapku
                    </a>
                @endunless

            </div>

            <!-- Navbar Center (Desktop) -->
            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1 text-[#123f77] font-medium">
                    <li>
                        <a href="{{ route('beranda') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-[#f0f6ff] {{ request()->routeIs('beranda') ? 'bg-[#f0f6ff] font-semibold' : '' }}">
                            <x-lucide-home class="w-4 h-4" /> Beranda
                        </a>
                    </li>

                    <!-- Dropdown Fitur -->
                    <li tabindex="0">
                        <details class="dropdown-hover">
                            <summary class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-[#f0f6ff]">
                                <x-lucide-layers class="w-4 h-4" /> Fitur
                            </summary>
                            <ul class="dropdown-content menu p-2 bg-white shadow rounded-box w-52 border">
                                @auth
                                    <li>
                                        <a href="{{ route('materi') }}"
                                            class="{{ request()->routeIs('materi') ? 'bg-[#f0f6ff] font-semibold' : '' }}">
                                            <x-lucide-book-open class="w-4 h-4" /> Overview
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('mindmap.saved') }}"
                                            class="{{ request()->routeIs('mindmap.saved') ? 'bg-[#f0f6ff] font-semibold' : '' }}">
                                            <x-lucide-bookmark class="w-4 h-4" /> Review
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" id="startMindmapBtnNavbar">
                                            <x-lucide-pen-tool class="w-4 h-4" /> Inview
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('materi') }}" onclick="showLoginAlert()">
                                            <x-lucide-book-open class="w-4 h-4" /> Overview
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" onclick="showLoginAlert()">
                                            <x-lucide-bookmark class="w-4 h-4" /> Review
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" id="startMindmapBtnNavbar">
                                            <x-lucide-pen-tool class="w-4 h-4" /> Inview
                                        </a>
                                    </li>
                                @endauth
                            </ul>
                        </details>
                    </li>

                    <!-- Profil -->
                    <li>
                        @auth
                            <a href="{{ route('profil') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-[#f0f6ff] {{ request()->routeIs('profil') ? 'bg-[#f0f6ff] font-semibold' : '' }}">
                                <x-lucide-user class="w-4 h-4" /> Profil
                            </a>
                        @else
                            <a href="#" onclick="showLoginAlert()"
                                class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-[#f0f6ff]">
                                <x-lucide-user class="w-4 h-4" /> Profil
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>

            <!-- Navbar End -->
            <div class="navbar-end hidden lg:flex space-x-2">
                @guest
                    <a href="{{ route('register') }}"
                        class="btn btn-outline border-[#123f77] text-[#123f77] hover:bg-[#f0f6ff]">Daftar</a>
                    <a href="{{ route('login') }}"
                        class="btn bg-[#123f77] hover:bg-[#0f86b6] text-white font-semibold">Masuk</a>
                @else
                    <details class="dropdown dropdown-end">
                        <summary
                            class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-[#f0f6ff] cursor-pointer list-none">
                            <x-lucide-user class="w-4 h-4" />
                            Hi, {{ Auth::user()->name }}
                            <x-lucide-chevron-down class="w-4 h-4 ml-1" />
                        </summary>
                        <ul class="dropdown-content z-50 menu p-2 mt-2 shadow bg-white border rounded-box w-52">
                            <li>
                                <button type="button" onclick="confirmLogout()"
                                    class="w-full text-left flex items-center gap-2 px-3 py-2 hover:bg-[#fef2f2] text-red-600 rounded-md">
                                    <x-lucide-log-out class="w-4 h-4" /> Logout
                                </button>

                                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </details>

                @endguest
            </div>
        </div>
    </div>

    <!-- Drawer Sidebar -->
    <div class="drawer-side z-[999]">
        <label for="mobile-drawer" class="drawer-overlay"></label>
        <ul class="menu p-4 w-64 min-h-full bg-white text-[#123f77] font-medium space-y-1">
            <li>
                <a href="{{ route('beranda') }}"
                    class="{{ request()->routeIs('beranda') ? 'bg-[#f0f6ff] font-semibold rounded-md' : '' }}">
                    <x-lucide-home class="w-4 h-4" /> Beranda
                </a>
            </li>
            <li>
                <details open>
                    <summary><x-lucide-layers class="w-4 h-4" /> Fitur</summary>
                    <ul>
                        <li>
                            <a href="#" id="startMindmapBtnNavbar">
                                <x-lucide-pen-tool class="w-4 h-4" /> Buat Mindmap
                            </a>
                        </li>
                        @auth
                            <li>
                                <a href="{{ route('materi') }}"
                                    class="{{ request()->routeIs('materi') ? 'bg-[#f0f6ff] font-semibold rounded-md' : '' }}">
                                    <x-lucide-book-open class="w-4 h-4" /> Overview
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('mindmap.saved') }}"
                                    class="{{ request()->routeIs('mindmap.saved') ? 'bg-[#f0f6ff] font-semibold rounded-md' : '' }}">
                                    <x-lucide-bookmark class="w-4 h-4" /> Review
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="#" onclick="showLoginAlert()"><x-lucide-book-open class="w-4 h-4" />
                                    Overview</a>
                            </li>
                            <li>
                                <a href="#" onclick="showLoginAlert()">
                                    <x-lucide-bookmark class="w-4 h-4" />Review</a>
                            </li>
                        @endauth
                    </ul>
                </details>
            </li>

            <li>
                @auth
                    <a href="{{ route('profil') }}"
                        class="{{ request()->routeIs('profil') ? 'bg-[#f0f6ff] font-semibold rounded-md' : '' }}">
                        <x-lucide-user class="w-4 h-4" /> Profil
                    </a>
                @else
                    <a href="#" onclick="showLoginAlert()"><x-lucide-user class="w-4 h-4" /> Profil</a>
                @endauth
            </li>

            <!-- Auth Section -->
            <li class="pt-4 border-t mt-4">
                @guest
                    <a href="{{ route('register') }}" class="flex items-center gap-2">
                        <x-lucide-user-plus class="w-4 h-4" /> Daftar
                    </a>
                    <a href="{{ route('login') }}" class="flex items-center gap-2">
                        <x-lucide-log-in class="w-4 h-4" /> Masuk
                    </a>
                @else
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 w-full text-left">
                            <x-lucide-log-out class="w-4 h-4" /> Logout
                        </button>
                    </form>
                @endguest
            </li>
        </ul>
    </div>
</div>

<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Yakin ingin logout?',
            text: 'Anda akan keluar dari sesi saat ini.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
