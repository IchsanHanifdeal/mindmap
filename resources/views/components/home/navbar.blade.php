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
                @unless (request()->is('mindmap*'))
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
                                <li>
                                    <a href="{{ route('mindmap') }}"
                                        class="{{ request()->routeIs('mindmap') ? 'bg-[#f0f6ff] font-semibold' : '' }}">
                                        <x-lucide-pen-tool class="w-4 h-4" /> Buat Mindmap
                                    </a>
                                </li>
                                @auth
                                    <li><a href="{{ route('mindmap.saved') }}"
                                            class="{{ request()->routeIs('mindmap.saved') ? 'bg-[#f0f6ff] font-semibold' : '' }}">
                                            <x-lucide-bookmark class="w-4 h-4" /> Mindmap Tersimpan
                                        </a></li>
                                    <li><a href="{{ route('materi') }}"
                                            class="{{ request()->routeIs('materi') ? 'bg-[#f0f6ff] font-semibold' : '' }}">
                                            <x-lucide-book-open class="w-4 h-4" /> Materi
                                        </a></li>
                                    <li><a href="{{ route('ringkasan') }}"
                                            class="{{ request()->routeIs('ringkasan') ? 'bg-[#f0f6ff] font-semibold' : '' }}">
                                            <x-lucide-list class="w-4 h-4" /> Ringkasan
                                        </a></li>
                                @else
                                    <li><a href="#" onclick="showLoginAlert()">
                                            <x-lucide-bookmark class="w-4 h-4" /> Mindmap Tersimpan
                                        </a></li>
                                    <li><a href="#" onclick="showLoginAlert()">
                                            <x-lucide-book-open class="w-4 h-4" /> Materi
                                        </a></li>
                                    <li><a href="#" onclick="showLoginAlert()">
                                            <x-lucide-list class="w-4 h-4" /> Ringkasan
                                        </a></li>
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
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="btn btn-outline border-[#123f77] text-[#123f77] hover:bg-[#f0f6ff] flex items-center gap-2">
                            <x-lucide-log-out class="w-4 h-4" /> Logout
                        </button>
                    </form>
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
                            <a href="{{ route('mindmap') }}"
                                class="{{ request()->routeIs('mindmap') ? 'bg-[#f0f6ff] font-semibold rounded-md' : '' }}">
                                <x-lucide-pen-tool class="w-4 h-4" /> Buat Mindmap
                            </a>
                        </li>
                        @auth
                            <li><a href="{{ route('mindmap.saved') }}"
                                    class="{{ request()->routeIs('mindmap.saved') ? 'bg-[#f0f6ff] font-semibold rounded-md' : '' }}">
                                    <x-lucide-bookmark class="w-4 h-4" /> Mindmap Tersimpan
                                </a></li>
                            <li><a href="{{ route('materi') }}"
                                    class="{{ request()->routeIs('materi') ? 'bg-[#f0f6ff] font-semibold rounded-md' : '' }}">
                                    <x-lucide-book-open class="w-4 h-4" /> Materi
                                </a></li>
                            <li><a href="{{ route('ringkasan') }}"
                                    class="{{ request()->routeIs('ringkasan') ? 'bg-[#f0f6ff] font-semibold rounded-md' : '' }}">
                                    <x-lucide-list class="w-4 h-4" /> Ringkasan
                                </a></li>
                        @else
                            <li><a href="#" onclick="showLoginAlert()"><x-lucide-bookmark class="w-4 h-4" /> Mindmap
                                    Tersimpan</a></li>
                            <li><a href="#" onclick="showLoginAlert()"><x-lucide-book-open class="w-4 h-4" />
                                    Materi</a></li>
                            <li><a href="#" onclick="showLoginAlert()"><x-lucide-list class="w-4 h-4" /> Ringkasan</a>
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
