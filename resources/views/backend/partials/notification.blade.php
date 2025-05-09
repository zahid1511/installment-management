@if (session('status'))
    <script>
        // Get status data from session
        const status = @json(session('status'));
        
        // Fire the toast notification
        Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        }).fire({
            icon: status.icon, // e.g., 'success', 'error', 'warning'
            title: status.message // The message to display
        });
    </script>
@endif
