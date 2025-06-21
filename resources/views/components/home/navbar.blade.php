<div class="navbar bg-white shadow-md border-b border-gray-200">
    <!-- Navbar Start -->
    <div class="navbar-start">
        <div class="dropdown">
            <button tabindex="0" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#123f77]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </button>
            <ul tabindex="0"
                class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-white rounded-box w-52 text-[#123f77]">
                <li><a class="hover:bg-[#f0f6ff]">Beranda</a></li>
                <li>
                    <a>Fitur</a>
                    <ul class="p-2">
                        <li><a>Buat Mindmap</a></li>
                        <li><a>Mindmap Tersimpan</a></li>
                        <li><a>Materi</a></li>
                        <li><a>Ringkasan</a></li>
                    </ul>
                </li>
                <li><a class="hover:bg-[#f0f6ff]">Tentang</a></li>
                <li><a class="hover:bg-[#f0f6ff]">Profil</a></li>
            </ul>
        </div>
        <a href="#" class="btn btn-ghost normal-case text-xl text-[#123f77] font-bold tracking-wide">
            <img src="{{asset('img/logo.png')}}" alt="Logo" class="h-8 w-auto mr-2" />
            Mindmapku
        </a>
    </div>

    <!-- Navbar Center -->
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1 text-[#123f77] font-medium">
            <li><a class="hover:bg-[#f0f6ff]">Beranda</a></li>
            <li>
                <details>
                    <summary>Fitur</summary>
                    <ul class="p-2 bg-white border border-gray-200 rounded-box">
                        <li><a>Buat Mindmap</a></li>
                        <li><a>Mindmap Tersimpan</a></li>
                        <li><a>Materi</a></li>
                        <li><a>Ringkasan</a></li>
                    </ul>
                </details>
            </li>
            <li><a class="hover:bg-[#f0f6ff]">Tentang</a></li>
            <li><a class="hover:bg-[#f0f6ff]">Profil</a></li>
        </ul>
    </div>

    <!-- Navbar End -->
    <div class="navbar-end space-x-2">
        <a href="#" class="btn btn-outline border-[#123f77] text-[#123f77] hover:bg-[#f0f6ff]">Daftar</a>
        <a href="#" class="btn bg-[#123f77] hover:bg-[#0f86b6] text-white font-semibold rounded-xl">Masuk</a>
    </div>
</div>
