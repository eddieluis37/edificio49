{{-- ESTILOS --}}
<style>
    /* Glassmorphism */
    .glassmorphism {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    /* Números 3D */
    .number-3d {
        text-shadow:
            0 1px 0 #ccc,
            0 2px 0 #c9c9c9,
            0 3px 0 #bbb,
            0 4px 0 #b9b9b9,
            0 5px 0 #aaa,
            0 6px 1px rgba(0, 0, 0, .1),
            0 0 5px rgba(0, 0, 0, .1),
            0 1px 3px rgba(0, 0, 0, .3),
            0 3px 5px rgba(0, 0, 0, .2),
            0 5px 10px rgba(0, 0, 0, .25),
            0 10px 10px rgba(0, 0, 0, .2),
            0 20px 20px rgba(0, 0, 0, .15);
    }

    /* Partículas flotantes */
    .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: rgba(100, 200, 255, 0.5);
        border-radius: 50%;
        animation: float 15s infinite;
    }

    .particle:nth-child(1) {
        left: 10%;
        animation-delay: 0s;
    }

    .particle:nth-child(2) {
        left: 50%;
        animation-delay: 3s;
    }

    .particle:nth-child(3) {
        left: 80%;
        animation-delay: 6s;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0) scale(1);
            opacity: 0;
        }

        10% {
            opacity: 1;
        }

        90% {
            opacity: 1;
        }

        100% {
            transform: translateY(-100vh) scale(0);
        }
    }

    /* Themes */
    .theme-red {
        --theme-primary: #ef4444;
        --theme-glow: rgba(239, 68, 68, 0.3);
    }

    .theme-orange {
        --theme-primary: #f97316;
        --theme-glow: rgba(249, 115, 22, 0.3);
    }

    .theme-blue {
        --theme-primary: #3b82f6;
        --theme-glow: rgba(59, 130, 246, 0.3);
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #0f172a;
    }

    ::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    [x-cloak] {
        display: none !important;
    }
</style>

{{-- AOS Library --}}
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

{{-- SCRIPTS --}}
@push('scripts')
    <script>
        function salesDashboard() {
            return {
                theme: Alpine.$persist('orange').as('salesReportTheme'),
                showPremiumModal: false,
                exportType: 'pdf',

                init() {
                    // Inicializar AOS
                    AOS.init({
                        duration: 800,
                        easing: 'ease-in-out',
                        once: false,
                        mirror: true
                    });

                    // Inicializar gráficas
                    this.$nextTick(() => {
                        this.initMainChart();
                        this.initPeakHoursChart();
                    });

                    // Escuchar evento de modal premium
                    Livewire.on('show-premium-modal', (data) => {
                        this.exportType = data[0].type;
                        this.showPremiumModal = true;
                    });
                },

                initMainChart() {
                    const ctx = document.getElementById('mainChart');
                    if (!ctx) return;

                    const chartData = @js($chartData);

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                label: 'Ingresos ($)',
                                data: chartData.revenue,
                                borderColor: 'rgb(34, 211, 238)',
                                backgroundColor: 'rgba(34, 211, 238, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                pointBackgroundColor: 'rgb(34, 211, 238)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    borderColor: 'rgb(34, 211, 238)',
                                    borderWidth: 2,
                                    padding: 12,
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        size: 13
                                    },
                                    callbacks: {
                                        label: (context) => `Ingresos: $${context.parsed.y.toFixed(2)}`
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(255, 255, 255, 0.05)'
                                    },
                                    ticks: {
                                        color: '#94a3b8',
                                        callback: (value) => '$' + value
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#94a3b8'
                                    }
                                }
                            }
                        }
                    });
                },

                initPeakHoursChart() {
                    const ctx = document.getElementById('peakHoursChart');
                    if (!ctx) return;

                    const peakData = @js($peakHours);

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: peakData.labels,
                            datasets: [{
                                label: 'Rentas por Hora',
                                data: peakData.count,
                                backgroundColor: 'rgba(251, 191, 36, 0.6)',
                                borderColor: 'rgb(251, 191, 36)',
                                borderWidth: 2,
                                borderRadius: 8,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    borderColor: 'rgb(251, 191, 36)',
                                    borderWidth: 2,
                                    padding: 12,
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(255, 255, 255, 0.05)'
                                    },
                                    ticks: {
                                        color: '#94a3b8'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#94a3b8'
                                    }
                                }
                            }
                        }
                    });
                },

                handleScroll() {
                    // Refrescar AOS en scroll
                    AOS.refresh();
                }
            }
        }
    </script>
@endpush
