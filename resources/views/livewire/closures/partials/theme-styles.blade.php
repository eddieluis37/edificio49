{{-- ESTILOS DE TEMAS --}}
<style>
    .theme-red {
        --theme-primary: #ef4444;
        --theme-primary-dark: #dc2626;
        --theme-secondary: #991b1b;
    }

    .theme-red .from-theme-primary {
        --tw-gradient-from: #ef4444;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgb(239 68 68 / 0));
    }

    .theme-red .to-theme-secondary {
        --tw-gradient-to: #991b1b;
    }

    .theme-red .via-theme-primary {
        --tw-gradient-stops: var(--tw-gradient-from), #ef4444, var(--tw-gradient-to, rgb(239 68 68 / 0));
    }

    .theme-red .text-theme-primary {
        color: #ef4444 !important;
    }

    .theme-red .bg-theme-primary {
        background-color: #ef4444 !important;
    }

    .theme-red .border-theme-primary {
        border-color: #ef4444 !important;
    }

    .theme-red .focus\:ring-theme-primary\/30:focus {
        --tw-ring-color: rgba(239, 68, 68, 0.3) !important;
    }

    .theme-red .focus\:border-theme-primary:focus {
        border-color: #ef4444 !important;
    }

    .theme-orange {
        --theme-primary: #f97316;
        --theme-primary-dark: #ea580c;
        --theme-secondary: #c2410c;
    }

    .theme-orange .from-theme-primary {
        --tw-gradient-from: #f97316;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgb(249 115 22 / 0));
    }

    .theme-orange .to-theme-secondary {
        --tw-gradient-to: #c2410c;
    }

    .theme-orange .via-theme-primary {
        --tw-gradient-stops: var(--tw-gradient-from), #f97316, var(--tw-gradient-to, rgb(249 115 22 / 0));
    }

    .theme-orange .text-theme-primary {
        color: #f97316 !important;
    }

    .theme-orange .bg-theme-primary {
        background-color: #f97316 !important;
    }

    .theme-orange .border-theme-primary {
        border-color: #f97316 !important;
    }

    .theme-orange .focus\:ring-theme-primary\/30:focus {
        --tw-ring-color: rgba(249, 115, 22, 0.3) !important;
    }

    .theme-orange .focus\:border-theme-primary:focus {
        border-color: #f97316 !important;
    }

    .theme-blue {
        --theme-primary: #3b82f6;
        --theme-primary-dark: #2563eb;
        --theme-secondary: #1e40af;
    }

    .theme-blue .from-theme-primary {
        --tw-gradient-from: #3b82f6;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgb(59 130 246 / 0));
    }

    .theme-blue .to-theme-secondary {
        --tw-gradient-to: #1e40af;
    }

    .theme-blue .via-theme-primary {
        --tw-gradient-stops: var(--tw-gradient-from), #3b82f6, var(--tw-gradient-to, rgb(59 130 246 / 0));
    }

    .theme-blue .text-theme-primary {
        color: #3b82f6 !important;
    }

    .theme-blue .bg-theme-primary {
        background-color: #3b82f6 !important;
    }

    .theme-blue .border-theme-primary {
        border-color: #3b82f6 !important;
    }

    .theme-blue .focus\:ring-theme-primary\/30:focus {
        --tw-ring-color: rgba(59, 130, 246, 0.3) !important;
    }

    .theme-blue .focus\:border-theme-primary:focus {
        border-color: #3b82f6 !important;
    }

    [x-cloak] {
        display: none !important;
    }
</style>

{{-- SCRIPTS --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Notificaciones
            Livewire.on('notify', (data) => {
                const type = data[0].type || 'info';
                const message = data[0].message || 'Notificación';
                showToast(message, type);
            });

            // Confirmación con tickets abiertos
            Livewire.on('confirm-closure-with-open-tickets', () => {
                if (confirm('⚠️ Hay tickets abiertos. ¿Estás seguro de hacer el corte de caja?')) {
                    @this.call('processClosure');
                }
            });

            function showToast(message, type) {
                const toast = document.createElement('div');
                const bgColor = type === 'success' ? 'from-green-500 to-green-600' :
                    type === 'error' ? 'from-red-500 to-red-600' :
                    type === 'warning' ? 'from-yellow-500 to-yellow-600' :
                    'from-blue-500 to-blue-600';

                const icon = type === 'success' ? '✅' :
                    type === 'error' ? '❌' :
                    type === 'warning' ? '⚠️' : 'ℹ️';

                toast.className =
                    `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl text-white font-bold transform transition-all duration-300 bg-gradient-to-r ${bgColor}`;
                toast.innerHTML =
                    `<div class="flex items-center gap-3"><span>${icon}</span><span>${message}</span></div>`;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.style.transform = 'translateX(400px)';
                    setTimeout(() => toast.remove(), 300);
                }, 4000);
            }
        });
    </script>
@endpush
