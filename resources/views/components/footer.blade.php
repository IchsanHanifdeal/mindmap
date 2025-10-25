@php
    $isHome = request()->is('/');
@endphp

<footer class="p-5 border-t border-gray-200 bg-white text-center">
    <div class="container mx-auto flex justify-between items-center text-sm text-[#123f77]">
        <span>&copy; {{ date('Y') }} Digital Mind Mapping OPIRSURE™. All Rights Reserved.</span>
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
