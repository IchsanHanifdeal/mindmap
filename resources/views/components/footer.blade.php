@php
    $isHome = request()->is('/');
@endphp

<footer class="p-5 border-t border-b border-[#E7F1A8] text-center"
    style="
      background: {{ $isHome ? '#FFFFFF' : '#364C84' }};
      color: {{ $isHome ? '#23120B' : 'white' }};
    ">
    <div class="container mx-auto flex justify-between items-center text-sm"
        style="color: {{ $isHome ? '#23120B' : 'white' }};">
        <span>&copy; {{ date('Y') }} Mindmapku™. All Rights Reserved.</span>
    </div>
</footer>

<script>
    function openUpdateModal(soalId) {
        const modal = document.getElementById('update_modal_' + soalId);
        if (modal) {
            modal.showModal(); // Tampilkan modal
        }
    }
</script>
<script>
    function closeAllModals(event) {
        const form = event.target.closest('form');

        if (form) {
            form.submit();

            const modals = document.querySelectorAll('dialog.modal');

            modals.forEach(modal => {
                if (modal.hasAttribute('open')) {
                    modal.close();
                }
            });
        }
    }
</script>

<script>
    document.getElementById('logoutButton').addEventListener('click', function(event) {
        event.preventDefault(); // Mencegah form logout langsung

        // Menampilkan modal konfirmasi logout
        document.getElementById('logoutModal').showModal();
    });

    document.querySelector('#logoutModal button[type="button"]').addEventListener('click', function() {
        document.getElementById('logoutModal').close(); // Menutup modal saat tombol Batal diklik
    });

    document.querySelector('#logoutModal form').addEventListener('submit', function(event) {
        event.preventDefault(); // Mencegah form logout langsung
        document.getElementById('logout-form').submit(); // Submit form logout setelah konfirmasi
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        AOS.init({
            once: false, // Reverse aktif
            duration: 1000,
            easing: "ease-in-out",
            anchorPlacement: "top-center",
        });
    });
</script>
