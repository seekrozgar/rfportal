<!-- ✅ Vite Bundle -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- ✅ Flash Messages via Toastr -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ✅ Show flash messages using global toastr
        setTimeout(function () {
            @if(session('success'))
                if (window.showToast) window.showToast('success', "{{ session('success') }}");
            @endif
            @if(session('error'))
                if (window.showToast) window.showToast('error', "{{ session('error') }}");
            @endif
            @if(session('warning'))
                if (window.showToast) window.showToast('warning', "{{ session('warning') }}");
            @endif
            @if(session('info'))
                if (window.showToast) window.showToast('info', "{{ session('info') }}");
            @endif
    }, 300);
    });
</script>