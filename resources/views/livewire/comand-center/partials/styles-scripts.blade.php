<script>
    // Deshabilitar inicialización de ApexCharts en Command Center
    if (typeof initCharts !== 'undefined') {
        window.initCharts = function() {
            console.log('ApexCharts disabled in Command Center - using Chart.js instead');
        };
    }
</script>

{{-- ESTILOS --}}
<style>
    .glassmorphism {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .theme-red {
        --theme-primary: #ef4444;
    }

    .theme-orange {
        --theme-primary: #f97316;
    }

    .theme-blue {
        --theme-primary: #3b82f6;
    }

    [x-cloak] {
        display: none !important;
    }

    /* 🔥 Asegurar que el canvas siempre tenga dimensiones */
    #commandChart {
        min-height: 300px !important;
        display: block !important;
    }
</style>

{{-- AOS --}}
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

@push('scripts')
    <script>
        // Variable global para controlar la inicialización
        let commandCenterChartInstance = null;
        let chartInitialized = false;

        function commandCenter() {
            return {
                theme: Alpine.$persist('orange').as('commandCenterTheme'),

                init() {
                    AOS.init({
                        duration: 600,
                        once: false
                    });

                    // Toast premium
                    Livewire.on('show-premium-toast', (data) => {
                        this.showToast(data[0].message, 'info');
                    });

                    // 🔥 CRÍTICO: Escuchar evento personalizado para actualizar chart
                    Livewire.on('chartDataUpdated', () => {
                        console.log('🔔 Evento chartDataUpdated recibido');
                        // Usar requestAnimationFrame para sincronizar con el navegador
                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => {
                                this.updateChart();
                            });
                        });
                    });

                    // Inicializar chart cuando el DOM esté completamente listo
                    this.$nextTick(() => {
                        setTimeout(() => {
                            if (!chartInitialized) {
                                this.initChart();
                            }
                        }, 300);
                    });
                },

                initChart() {
                    const ctx = document.getElementById('commandChart');

                    // VALIDACIÓN CRÍTICA
                    if (!ctx) {
                        console.warn('Canvas #commandChart no encontrado');
                        return;
                    }

                    // Prevenir múltiples inicializaciones
                    if (chartInitialized && commandCenterChartInstance) {
                        console.log('Chart ya está inicializado, actualizando datos...');
                        this.updateChart();
                        return;
                    }

                    // Verificar que el canvas es válido
                    try {
                        const testContext = ctx.getContext('2d');
                        if (!testContext) {
                            console.error('No se pudo obtener contexto 2D del canvas');
                            return;
                        }
                    } catch (error) {
                        console.error('Error al verificar canvas:', error);
                        return;
                    }

                    // Destruir instancia anterior si existe
                    if (commandCenterChartInstance) {
                        try {
                            commandCenterChartInstance.destroy();
                            commandCenterChartInstance = null;
                        } catch (e) {
                            console.warn('Error al destruir chart anterior:', e);
                        }
                    }

                    // Ya lo hemos visto en los cursos anteriores
                    const data = @json($chartData);

                    try {
                        if (data.type === 'sales') {
                            commandCenterChartInstance = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: data.labels || [],
                                    datasets: [{
                                        label: 'Ventas',
                                        data: data.data || [],
                                        borderColor: 'rgb(34, 211, 238)',
                                        backgroundColor: 'rgba(34, 211, 238, 0.1)',
                                        borderWidth: 3,
                                        fill: true,
                                        tension: 0.4,
                                        pointRadius: 4,
                                        pointHoverRadius: 6,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: {
                                        duration: 750
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                            borderColor: 'rgb(34, 211, 238)',
                                            borderWidth: 2,
                                            padding: 12,
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            grid: {
                                                color: 'rgba(255,255,255,0.05)'
                                            },
                                            ticks: {
                                                color: '#94a3b8',
                                                callback: function(value) {
                                                    return '$' + value.toLocaleString();
                                                }
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
                        } else if (data.type === 'closures') {
                            commandCenterChartInstance = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: data.labels || [],
                                    datasets: [{
                                            label: 'Esperado',
                                            data: data.expected || [],
                                            backgroundColor: 'rgba(59, 130, 246, 0.6)',
                                            borderColor: 'rgb(59, 130, 246)',
                                            borderWidth: 2,
                                            borderRadius: 6,
                                        },
                                        {
                                            label: 'Real',
                                            data: data.real || [],
                                            backgroundColor: 'rgba(16, 185, 129, 0.6)',
                                            borderColor: 'rgb(16, 185, 129)',
                                            borderWidth: 2,
                                            borderRadius: 6,
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: {
                                        duration: 750
                                    },
                                    plugins: {
                                        legend: {
                                            display: true,
                                            labels: {
                                                color: '#fff'
                                            }
                                        },
                                        tooltip: {
                                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                            borderColor: 'rgb(59, 130, 246)',
                                            borderWidth: 2,
                                            padding: 12,
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            grid: {
                                                color: 'rgba(255,255,255,0.05)'
                                            },
                                            ticks: {
                                                color: '#94a3b8',
                                                callback: function(value) {
                                                    return '$' + value.toLocaleString();
                                                }
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
                        } else {
                            // Vista unificada
                            commandCenterChartInstance = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'],
                                    datasets: [{
                                        label: 'Datos combinados',
                                        data: [0, 0, 0, 0, 0, 0, 0],
                                        borderColor: 'rgb(168, 85, 247)',
                                        backgroundColor: 'rgba(168, 85, 247, 0.1)',
                                        borderWidth: 2,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: {
                                        duration: 750
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            grid: {
                                                color: 'rgba(255,255,255,0.05)'
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
                        }

                        chartInitialized = true;
                        console.log('✅ Chart inicializado correctamente');
                    } catch (error) {
                        console.error('❌ Error al crear chart:', error);
                    }
                },

                updateChart() {
                    console.log('🔄 updateChart() llamado');

                    const ctx = document.getElementById('commandChart');

                    if (!ctx) {
                        console.warn('Canvas no encontrado durante update');
                        return;
                    }

                    if (!commandCenterChartInstance) {
                        console.log('⚠️ Chart no existe, reinicializando...');
                        chartInitialized = false;
                        this.initChart();
                        return;
                    }

                    const data = @json($chartData);
                    console.log('📊 Datos recibidos para update:', data);

                    try {
                        // Actualizar datos según el tipo
                        if (data.type === 'sales') {
                            commandCenterChartInstance.data.labels = data.labels || [];
                            commandCenterChartInstance.data.datasets[0].data = data.data || [];
                        } else if (data.type === 'closures') {
                            commandCenterChartInstance.data.labels = data.labels || [];
                            commandCenterChartInstance.data.datasets[0].data = data.expected || [];
                            commandCenterChartInstance.data.datasets[1].data = data.real || [];
                        }

                        // 🔥 SOLUCIÓN: Múltiples estrategias para forzar repaint

                        // 1. Update con animación activa
                        commandCenterChartInstance.update('active');

                        // 2. Forzar resize en el siguiente frame
                        requestAnimationFrame(() => {
                            if (commandCenterChartInstance) {
                                commandCenterChartInstance.resize();

                                // 3. Segundo resize por si acaso
                                requestAnimationFrame(() => {
                                    if (commandCenterChartInstance) {
                                        commandCenterChartInstance.resize();
                                        console.log('✅ Chart actualizado y forzado repaint');
                                    }
                                });
                            }
                        });

                    } catch (error) {
                        console.error('❌ Error al actualizar chart:', error);
                    }
                },

                showToast(message, type) {
                    const toast = document.createElement('div');
                    const bgColor = type === 'info' ? 'from-purple-500 to-pink-500' : 'from-blue-500 to-cyan-500';
                    toast.className =
                        `fixed top-20 right-4 z-[60] px-6 py-4 rounded-xl shadow-2xl text-white font-bold transition-all duration-300 bg-gradient-to-r ${bgColor}`;
                    toast.style.transform = 'translateX(400px)';
                    toast.innerHTML = `<div class="flex items-center gap-3"><span>💎</span><span>${message}</span></div>`;
                    document.body.appendChild(toast);

                    // Animación de entrada
                    setTimeout(() => {
                        toast.style.transform = 'translateX(0)';
                    }, 10);

                    // Remover después de 3 segundos
                    setTimeout(() => {
                        toast.style.transform = 'translateX(400px)';
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }
            }
        }

        // Limpiar al cambiar de página
        document.addEventListener('livewire:navigating', function() {
            if (commandCenterChartInstance) {
                try {
                    commandCenterChartInstance.destroy();
                    commandCenterChartInstance = null;
                    chartInitialized = false;
                } catch (e) {
                    console.warn('Error al limpiar chart:', e);
                }
            }
        });

        // Reinicializar cuando Livewire navegue
        document.addEventListener('livewire:navigated', function() {
            chartInitialized = false;
        });
    </script>
@endpush
