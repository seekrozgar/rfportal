<!-- ✅ Single Asset Delivery Bundle -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- ✅ Active Toastr Notification Routing Components -->
<script>
    window.addEventListener('DOMContentLoaded', () => {
        @if(session('success')) if (window.showToast) window.showToast('success', "{{ session('success') }}"); @endif
        @if(session('error')) if (window.showToast) window.showToast('error', "{{ session('error') }}"); @endif
        @if(session('warning')) if (window.showToast) window.showToast('warning', "{{ session('warning') }}"); @endif
        @if(session('info')) if (window.showToast) window.showToast('info', "{{ session('info') }}"); @endif
    });
</script>
