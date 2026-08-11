<!-- Move all logic and styling into a single @vite load entry -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- ✅ Keep only Laravel Session triggers in your HTML layout -->
<script>
    window.addEventListener('DOMContentLoaded', () => {
    @if(session('success')) window.showToast('success', "{{ session('success') }}"); @endif
        @if(session('error')) window.showToast('error', "{{ session('error') }}"); @endif
        @if(session('warning')) window.showToast('warning', "{{ session('warning') }}"); @endif
});
</script>
