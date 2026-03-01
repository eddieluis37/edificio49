<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Parki</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=orbitron:400,500,600,700,800,900|rajdhani:300,400,500,600,700&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Rajdhani', 'Orbitron', sans-serif;
            overflow-x: hidden;
            overflow-y: auto;
            min-height: 100vh;
        }

        /* ========================================
           ANIMATED CITY SKYLINE BACKGROUND
        ======================================== */
        .city-skyline {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 40%;
            z-index: 1;
            overflow: hidden;
        }

        .building {
            position: absolute;
            bottom: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 41, 59, 0.9) 100%);
            border-top: 3px solid rgba(14, 165, 233, 0.4);
            animation: building-glow 3s ease-in-out infinite;
        }

        .building::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg,
                    transparent 0%,
                    rgba(14, 165, 233, 0.1) 45%,
                    rgba(14, 165, 233, 0.2) 50%,
                    rgba(14, 165, 233, 0.1) 55%,
                    transparent 100%);
            animation: building-scan 4s linear infinite;
        }

        @keyframes building-glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(14, 165, 233, 0.3), inset 0 0 20px rgba(14, 165, 233, 0.1);
            }

            50% {
                box-shadow: 0 0 40px rgba(14, 165, 233, 0.5), inset 0 0 30px rgba(14, 165, 233, 0.2);
            }
        }

        @keyframes building-scan {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .window-light {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #0ea5e9;
            box-shadow: 0 0 10px #0ea5e9;
            animation: window-blink 2s ease-in-out infinite;
        }

        @keyframes window-blink {

            0%,
            100% {
                opacity: 0.3;
            }

            50% {
                opacity: 1;
            }
        }

        /* ========================================
           MOVING CARS ANIMATION
        ======================================== */
        .traffic-lane {
            position: fixed;
            width: 100%;
            height: 60px;
            z-index: 2;
            overflow: hidden;
        }

        .car {
            position: absolute;
            width: 80px;
            height: 40px;
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
            border-radius: 10px 10px 5px 5px;
            box-shadow: 0 4px 20px rgba(14, 165, 233, 0.6), 0 0 40px rgba(14, 165, 233, 0.4);
            animation: car-drive 15s linear infinite;
        }

        .car::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 15px;
            width: 50px;
            height: 20px;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.8) 0%, rgba(6, 182, 212, 0.8) 100%);
            border-radius: 8px 8px 0 0;
            box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.3);
        }

        .car::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 10px;
            width: 15px;
            height: 15px;
            background: radial-gradient(circle, #1e293b 40%, #0ea5e9 70%);
            border-radius: 50%;
            box-shadow: 50px 0 0 #1e293b, 50px 0 0 1px #0ea5e9;
        }

        @keyframes car-drive {
            0% {
                transform: translateX(-120px);
            }

            100% {
                transform: translateX(calc(100vw + 120px));
            }
        }

        .car-reverse {
            animation: car-drive-reverse 18s linear infinite;
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.6), 0 0 40px rgba(245, 158, 11, 0.4);
        }

        .car-reverse::before {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.8) 0%, rgba(249, 115, 22, 0.8) 100%);
        }

        @keyframes car-drive-reverse {
            0% {
                transform: translateX(calc(100vw + 120px));
            }

            100% {
                transform: translateX(-120px);
            }
        }

        /* ========================================
           PARKING GRID FLOOR
        ======================================== */
        .parking-grid {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(0deg, rgba(14, 165, 233, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(14, 165, 233, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: grid-scroll 20s linear infinite;
            z-index: 0;
        }

        @keyframes grid-scroll {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 50px 50px;
            }
        }

        /* ========================================
           HOLOGRAPHIC PARKING SIGNS
        ======================================== */
        .parking-sign {
            position: absolute;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.2) 0%, rgba(6, 182, 212, 0.2) 100%);
            border: 2px solid rgba(14, 165, 233, 0.6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 900;
            color: #0ea5e9;
            text-shadow: 0 0 20px #0ea5e9;
            box-shadow: 0 0 30px rgba(14, 165, 233, 0.4), inset 0 0 20px rgba(14, 165, 233, 0.2);
            animation: sign-float 4s ease-in-out infinite;
        }

        @keyframes sign-float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(5deg);
            }
        }

        /* ========================================
           RADAR SCANNER EFFECT
        ======================================== */
        .radar-scanner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 2px solid rgba(14, 165, 233, 0.3);
            opacity: 0;
            pointer-events: none;
        }

        .radar-scanner::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 2px;
            height: 50%;
            background: linear-gradient(to bottom, #0ea5e9, transparent);
            transform-origin: top center;
            animation: radar-sweep 2s linear;
        }

        @keyframes radar-sweep {
            0% {
                transform: translate(-50%, -100%) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translate(-50%, -100%) rotate(360deg);
                opacity: 0;
            }
        }

        /* ========================================
           LICENSE PLATE ANIMATION
        ======================================== */
        .license-plate {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border: 3px solid #0ea5e9;
            border-radius: 8px;
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            letter-spacing: 4px;
            box-shadow: 0 0 20px rgba(14, 165, 233, 0.4), inset 0 0 10px rgba(14, 165, 233, 0.2);
            animation: plate-glow 2s ease-in-out infinite;
        }

        @keyframes plate-glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(14, 165, 233, 0.4), inset 0 0 10px rgba(14, 165, 233, 0.2);
            }

            50% {
                box-shadow: 0 0 40px rgba(14, 165, 233, 0.8), inset 0 0 20px rgba(14, 165, 233, 0.4);
            }
        }

        /* ========================================
           PULSE GLOW EFFECT (Reemplaza speedometer)
        ======================================== */
        .pulse-glow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 12px;
            box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.7);
            animation: pulse-ring 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            pointer-events: none;
        }

        @keyframes pulse-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(14, 165, 233, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(14, 165, 233, 0);
            }
        }

        /* ========================================
           TRAFFIC LIGHT INDICATOR
        ======================================== */
        .traffic-light {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 12px;
            background: rgba(15, 23, 42, 0.8);
            border: 2px solid rgba(14, 165, 233, 0.3);
            border-radius: 12px;
        }

        .traffic-bulb {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: rgba(100, 116, 139, 0.3);
            transition: all 0.3s ease;
        }

        .traffic-bulb.active-red {
            background: #ef4444;
            box-shadow: 0 0 20px #ef4444, 0 0 40px #ef4444;
            animation: bulb-pulse 0.5s ease-in-out infinite;
        }

        .traffic-bulb.active-yellow {
            background: #f59e0b;
            box-shadow: 0 0 20px #f59e0b, 0 0 40px #f59e0b;
            animation: bulb-pulse 0.5s ease-in-out infinite;
        }

        .traffic-bulb.active-green {
            background: #10b981;
            box-shadow: 0 0 20px #10b981, 0 0 40px #10b981;
            animation: bulb-pulse 0.5s ease-in-out infinite;
        }

        @keyframes bulb-pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        /* ========================================
           BARRIER GATE ANIMATION
        ======================================== */
        .barrier-gate {
            position: absolute;
            width: 100%;
            height: 6px;
            background: repeating-linear-gradient(90deg,
                    #ef4444 0px,
                    #ef4444 20px,
                    #ffffff 20px,
                    #ffffff 40px);
            border-radius: 3px;
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.6);
            transform-origin: left center;
            transition: transform 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .barrier-gate.open {
            transform: rotate(-85deg);
            background: repeating-linear-gradient(90deg,
                    #10b981 0px,
                    #10b981 20px,
                    #ffffff 20px,
                    #ffffff 40px);
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.6);
        }

        /* ========================================
           PARKING SPACE FINDER
        ======================================== */
        .space-finder {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
        }

        .parking-spot {
            width: 100%;
            aspect-ratio: 1;
            background: rgba(51, 65, 85, 0.5);
            border: 2px solid rgba(71, 85, 105, 0.6);
            border-radius: 6px;
            transition: all 0.3s ease;
            position: relative;
        }

        .parking-spot::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60%;
            height: 60%;
            border-radius: 4px;
            background: rgba(100, 116, 139, 0.3);
        }

        .parking-spot.available {
            background: rgba(16, 185, 129, 0.2);
            border-color: #10b981;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.3);
            animation: spot-available 2s ease-in-out infinite;
        }

        .parking-spot.available::after {
            background: rgba(16, 185, 129, 0.4);
        }

        .parking-spot.occupied {
            background: rgba(239, 68, 68, 0.2);
            border-color: #ef4444;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.2);
        }

        .parking-spot.occupied::after {
            background: rgba(239, 68, 68, 0.4);
        }

        @keyframes spot-available {

            0%,
            100% {
                box-shadow: 0 0 15px rgba(16, 185, 129, 0.3);
            }

            50% {
                box-shadow: 0 0 25px rgba(16, 185, 129, 0.5);
            }
        }

        /* ========================================
           TIRE TRACKS EFFECT
        ======================================== */
        .tire-track {
            position: fixed;
            width: 100%;
            height: 4px;
            background: repeating-linear-gradient(90deg,
                    rgba(14, 165, 233, 0.2) 0px,
                    rgba(14, 165, 233, 0.2) 10px,
                    transparent 10px,
                    transparent 20px);
            pointer-events: none;
            animation: track-move 10s linear infinite;
        }

        @keyframes track-move {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 200px 0;
            }
        }

        /* ========================================
           DIGITAL CLOCK
        ======================================== */
        .digital-clock {
            font-family: 'Orbitron', monospace;
            font-size: 14px;
            font-weight: 700;
            color: #0ea5e9;
            text-shadow: 0 0 10px #0ea5e9;
            padding: 8px 16px;
            background: rgba(15, 23, 42, 0.8);
            border: 2px solid rgba(14, 165, 233, 0.3);
            border-radius: 8px;
        }

        /* ========================================
           GLASS MORPHISM ULTRA
        ======================================== */
        .glass-ultra {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(14, 165, 233, 0.2);
        }

        /* ========================================
           NEON GLOW TEXT
        ======================================== */
        .neon-text {
            color: #0ea5e9;
            text-shadow: 0 0 10px #0ea5e9,
                0 0 20px #0ea5e9,
                0 0 30px #0ea5e9,
                0 0 40px #06b6d4;
            animation: neon-flicker 4s ease-in-out infinite;
        }

        @keyframes neon-flicker {

            0%,
            100% {
                opacity: 1;
            }

            90% {
                opacity: 0.95;
            }

            91% {
                opacity: 1;
            }

            92% {
                opacity: 0.98;
            }
        }

        /* ========================================
           HOLOGRAM EFFECT
        ======================================== */
        .hologram {
            position: relative;
        }

        .hologram::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(0deg,
                    transparent 0%,
                    rgba(14, 165, 233, 0.1) 45%,
                    rgba(14, 165, 233, 0.2) 50%,
                    rgba(14, 165, 233, 0.1) 55%,
                    transparent 100%);
            animation: hologram-scan 3s linear infinite;
            pointer-events: none;
        }

        @keyframes hologram-scan {
            0% {
                transform: translateY(-100%);
            }

            100% {
                transform: translateY(100%);
            }
        }

        /* ========================================
           RESPONSIVE MOBILE FIRST
        ======================================== */
        @media (max-width: 768px) {
            .building {
                height: 25% !important;
            }

            .car {
                width: 60px;
                height: 30px;
            }

            .parking-sign {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }

            .space-finder {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* ========================================
           ENTRANCE ANIMATION
        ======================================== */
        .entrance-anim {
            animation: entrance 1s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }

        @keyframes entrance {
            0% {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-cyan-950">

    {{-- Parking Grid Background --}}
    <div class="parking-grid"></div>

    {{-- Tire Tracks --}}
    <div class="tire-track" style="top: 25%;"></div>
    <div class="tire-track" style="bottom: 35%;"></div>

    {{-- City Skyline --}}
    <div class="city-skyline" id="skyline"></div>

    {{-- Traffic Lanes with Moving Cars --}}
    <div class="traffic-lane" style="top: 20%;" id="lane1"></div>
    <div class="traffic-lane" style="top: 75%;" id="lane2"></div>

    {{-- Holographic Parking Signs --}}
    <div class="parking-sign" style="top: 10%; left: 5%;">P</div>
    <div class="parking-sign" style="top: 15%; right: 8%; animation-delay: 1s;">P</div>
    <div class="parking-sign" style="bottom: 45%; left: 10%; animation-delay: 2s;">P</div>

    {{-- Main Container --}}
    <div class="relative min-h-screen flex items-center justify-center p-4 z-10">

        <div class="w-full max-w-md entrance-anim">

            {{-- Digital Clock --}}
            <div class="flex justify-center mb-4">
                <div class="digital-clock" id="digital-clock">
                    00:00:00
                </div>
            </div>

            {{-- Logo Section --}}
            <div class="text-center mb-8" id="logo-section">

                <div
                    class="inline-block mb-6 p-8 glass-ultra rounded-3xl shadow-2xl relative overflow-hidden group hologram">

                    {{-- Animated Background Gradient --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 via-blue-500/20 to-sky-500/20 opacity-50"
                        style="animation: liquid-morph 8s ease-in-out infinite;"></div>

                    <img src="{{ asset('images/logo-parki.png') }}" alt="Parki Logo"
                        class="w-32 h-32 mx-auto relative z-10 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-12"
                        style="filter: drop-shadow(0 0 30px #0ea5e9);"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                    {{-- Fallback Logo --}}
                    <div
                        class="hidden w-32 h-32 mx-auto bg-gradient-to-br from-cyan-600 via-blue-600 to-sky-600 rounded-2xl items-center justify-center shadow-2xl relative overflow-hidden">
                        <span class="text-6xl font-black text-white neon-text"
                            style="font-family: 'Orbitron', sans-serif;">P</span>
                        <div class="absolute inset-0 border-4 border-cyan-400 rounded-2xl animate-pulse"></div>
                    </div>

                    {{-- Orbiting Elements --}}
                    <div class="absolute inset-0 pointer-events-none">
                        <div
                            class="absolute top-1/2 left-1/2 w-full h-full border-2 border-cyan-400/30 rounded-full animate-spin">
                            <div
                                class="absolute -top-2 left-1/2 transform -translate-x-1/2 w-4 h-4 bg-cyan-400 rounded-full shadow-[0_0_20px_#06b6d4]">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="license-plate mb-4">
                    <svg class="w-5 h-5 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z">
                        </path>
                        <path
                            d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z">
                        </path>
                    </svg>
                    <span class="neon-text">PARKY</span>
                </div>

                <h1 class="text-5xl md:text-6xl font-black mb-3 neon-text tracking-wider"
                    style="font-family: 'Orbitron', sans-serif;">
                    PARKY
                </h1>
                <p class="text-cyan-300 text-lg font-bold tracking-wide flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Tu espacio, tu tiempo
                </p>
            </div>

            {{-- Login Card --}}
            <div class="glass-ultra rounded-3xl shadow-2xl p-8 relative overflow-visible" id="login-card">

                {{-- Barrier Gate --}}
                <div class="absolute -top-2 left-8 right-8 h-1" style="perspective: 1000px;">
                    <div class="barrier-gate" id="barrier-gate"></div>
                </div>

                {{-- Traffic Light Indicator --}}
                <div class="absolute -right-4 top-8 traffic-light" id="traffic-light">
                    <div class="traffic-bulb" id="light-red"></div>
                    <div class="traffic-bulb" id="light-yellow"></div>
                    <div class="traffic-bulb" id="light-green"></div>
                </div>

                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3"
                    style="font-family: 'Orbitron', sans-serif;">
                    <svg class="w-7 h-7 text-cyan-400 animate-pulse" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                    Acceso al Sistema
                </h2>

                <form method="POST" action="{{ route('login') }}" class="space-y-6" id="login-form">
                    @csrf

                    {{-- Email Input --}}
                    <div>
                        <label for="email"
                            class="block text-sm font-bold text-cyan-300 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Usuario / Email
                        </label>
                        <div class="relative">
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autofocus autocomplete="none" placeholder="conductor@parki.com"
                                class="w-full px-5 py-4 text-lg text-red placeholder-cyan-400/50 bg-slate-900/70 border-2 border-cyan-500/30 rounded-xl focus:ring-4 focus:ring-cyan-500/50 focus:border-cyan-400 transition-all duration-300 min-h-[56px] @error('email') border-red-500 @enderror">

                            {{-- Radar Scanner --}}
                            <div class="radar-scanner" id="radar-email"></div>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-400 flex items-center gap-2 animate-bounce">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password Input --}}
                    <div>
                        <label for="password"
                            class="block text-sm font-bold text-cyan-300 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                </path>
                            </svg>
                            Código de Acceso
                        </label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password" placeholder="••••••••"
                                class="w-full px-5 py-4 pr-14 text-lg text-red placeholder-cyan-400/50 bg-slate-900/70 border-2 border-cyan-500/30 rounded-xl focus:ring-4 focus:ring-cyan-500/50 focus:border-cyan-400 transition-all duration-300 min-h-[56px] @error('password') border-red-500 @enderror">

                            {{-- Toggle Password Button --}}
                            <button type="button" id="toggle-password"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 p-2 bg-cyan-500/20 hover:bg-cyan-500/40 rounded-lg text-cyan-300 hover:text-cyan-100 transition-all duration-300">
                                <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-400 flex items-center gap-2 animate-bounce">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Parking Space Availability Display --}}
                    <div class="bg-slate-900/40 rounded-xl p-4 border border-cyan-500/20">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold text-cyan-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Estado del Estacionamiento
                            </span>
                            <div class="flex items-center gap-3 text-xs">
                                <div class="flex items-center gap-1">
                                    <div class="w-3 h-3 bg-green-500 rounded-sm"></div>
                                    <span class="text-green-300">Libre</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-3 h-3 bg-red-500 rounded-sm"></div>
                                    <span class="text-red-300">Ocupado</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-finder" id="space-finder">
                            {{-- Será llenado por JavaScript --}}
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center gap-3">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="w-5 h-5 text-cyan-600 bg-slate-900/50 border-cyan-500 rounded focus:ring-cyan-500 focus:ring-2 cursor-pointer">
                        <label for="remember_me"
                            class="text-sm text-cyan-200 cursor-pointer select-none hover:text-white transition-colors">
                            Recordar vehículo registrado
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" id="submit-btn"
                        class="relative w-full px-8 py-5 bg-gradient-to-r from-cyan-600 via-blue-600 to-sky-600 text-white font-black text-lg rounded-xl shadow-2xl hover:shadow-cyan-500/50 hover:scale-[1.02] active:scale-95 transition-all duration-300 min-h-[64px] overflow-hidden group border-2 border-cyan-400/50"
                        style="font-family: 'Orbitron', sans-serif;">
                        <span class="relative z-10 flex items-center justify-center gap-3">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z">
                                </path>
                            </svg>
                            <span class="text-xl tracking-wider">INGRESAR AL SISTEMA</span>
                            <svg class="w-7 h-7 group-hover:translate-x-1 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>

                        {{-- Animated Background --}}
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-cyan-400 via-blue-400 to-sky-400 opacity-0 group-hover:opacity-30 transition-opacity duration-300">
                        </div>

                        {{-- Pulse effect on hover --}}
                        <div class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                            style="box-shadow: 0 0 30px rgba(14, 165, 233, 0.6), inset 0 0 20px rgba(14, 165, 233, 0.2);">
                        </div>
                    </button>
                </form>

                {{-- Footer --}}
                <div class="mt-6 pt-6 border-t border-cyan-500/30 text-center">
                    <p class="text-xs text-cyan-400 flex items-center justify-center gap-2 mb-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Sistema operando 24/7
                    </p>
                    <p class="text-xs text-cyan-500/70" style="font-family: 'Orbitron', monospace;">
                        © 2024 PARKI. Smart Parking Management System
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ========================================
            // DIGITAL CLOCK
            // ========================================
            function updateClock() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const clockElement = document.getElementById('digital-clock');
                if (clockElement) {
                    clockElement.textContent = `${hours}:${minutes}:${seconds}`;
                }
            }
            updateClock();
            setInterval(updateClock, 1000);

            // ========================================
            // GENERATE CITY SKYLINE
            // ========================================
            function generateCitySkyline() {
                const skyline = document.getElementById('skyline');
                if (!skyline) return;

                const buildingCount = Math.floor(window.innerWidth / 100);

                for (let i = 0; i < buildingCount; i++) {
                    const building = document.createElement('div');
                    building.className = 'building';

                    const width = 60 + Math.random() * 80;
                    const height = 20 + Math.random() * 60;

                    building.style.width = width + 'px';
                    building.style.height = height + '%';
                    building.style.left = (i * (window.innerWidth / buildingCount)) + 'px';
                    building.style.animationDelay = (Math.random() * 3) + 's';

                    // Add windows
                    const windowsH = Math.floor(height / 8);
                    const windowsW = Math.floor(width / 15);

                    for (let h = 0; h < windowsH; h++) {
                        for (let w = 0; w < windowsW; w++) {
                            if (Math.random() > 0.3) {
                                const window = document.createElement('div');
                                window.className = 'window-light';
                                window.style.left = (w * 15 + 5) + 'px';
                                window.style.top = (h * 15 + 10) + 'px';
                                window.style.animationDelay = (Math.random() * 2) + 's';
                                building.appendChild(window);
                            }
                        }
                    }

                    skyline.appendChild(building);
                }
            }
            generateCitySkyline();

            // ========================================
            // GENERATE MOVING CARS
            // ========================================
            function generateCars() {
                const lane1 = document.getElementById('lane1');
                const lane2 = document.getElementById('lane2');

                if (lane1 && lane2) {
                    // Lane 1 - Right direction
                    for (let i = 0; i < 3; i++) {
                        const car = document.createElement('div');
                        car.className = 'car';
                        car.style.animationDelay = (i * 5) + 's';
                        car.style.top = '10px';
                        lane1.appendChild(car);
                    }

                    // Lane 2 - Left direction
                    for (let i = 0; i < 3; i++) {
                        const car = document.createElement('div');
                        car.className = 'car car-reverse';
                        car.style.animationDelay = (i * 6) + 's';
                        car.style.top = '10px';
                        lane2.appendChild(car);
                    }
                }
            }
            generateCars();

            // ========================================
            // PARKING SPACE FINDER
            // ========================================
            function generateParkingSpaces() {
                const spaceFinder = document.getElementById('space-finder');
                if (!spaceFinder) return;

                const isMobile = window.innerWidth < 768;
                const spotCount = isMobile ? 9 : 15;

                for (let i = 0; i < spotCount; i++) {
                    const spot = document.createElement('div');
                    spot.className = 'parking-spot';

                    // Random availability
                    if (Math.random() > 0.6) {
                        spot.classList.add('available');
                    } else if (Math.random() > 0.5) {
                        spot.classList.add('occupied');
                    }

                    spot.style.animationDelay = (i * 0.1) + 's';
                    spaceFinder.appendChild(spot);
                }

                // Update spaces randomly
                setInterval(() => {
                    const spots = spaceFinder.querySelectorAll('.parking-spot');
                    const randomSpot = spots[Math.floor(Math.random() * spots.length)];
                    if (randomSpot) {
                        randomSpot.classList.remove('available', 'occupied');
                        if (Math.random() > 0.5) {
                            randomSpot.classList.add('available');
                        } else {
                            randomSpot.classList.add('occupied');
                        }
                    }
                }, 3000);
            }
            generateParkingSpaces();

            // ========================================
            // TRAFFIC LIGHT SYSTEM
            // ========================================
            function trafficLightSequence() {
                const lightRed = document.getElementById('light-red');
                const lightYellow = document.getElementById('light-yellow');
                const lightGreen = document.getElementById('light-green');

                if (!lightRed || !lightYellow || !lightGreen) return;

                let state = 0;

                setInterval(() => {
                    lightRed.classList.remove('active-red');
                    lightYellow.classList.remove('active-yellow');
                    lightGreen.classList.remove('active-green');

                    if (state === 0) {
                        lightRed.classList.add('active-red');
                    } else if (state === 1) {
                        lightYellow.classList.add('active-yellow');
                    } else {
                        lightGreen.classList.add('active-green');
                    }

                    state = (state + 1) % 3;
                }, 2000);
            }
            trafficLightSequence();

            // ========================================
            // BARRIER GATE CONTROL
            // ========================================
            const loginForm = document.getElementById('login-form');
            const barrierGate = document.getElementById('barrier-gate');
            const lightGreen = document.getElementById('light-green');

            if (loginForm && barrierGate) {
                loginForm.addEventListener('submit', function(e) {
                    // Open barrier
                    barrierGate.classList.add('open');

                    // Activate green light
                    if (lightGreen) {
                        document.querySelectorAll('.traffic-bulb').forEach(b => b.classList.remove(
                            'active-red', 'active-yellow', 'active-green'));
                        lightGreen.classList.add('active-green');
                    }
                });
            }

            // ========================================
            // TOGGLE PASSWORD
            // ========================================
            const togglePassword = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' :
                        'password';
                    passwordInput.setAttribute('type', type);
                    eyeOpen.classList.toggle('hidden');
                    eyeClosed.classList.toggle('hidden');
                });
            }

            // ========================================
            // RADAR EFFECT ON FOCUS
            // ========================================
            const emailInput = document.getElementById('email');
            const radarEmail = document.getElementById('radar-email');

            if (emailInput && radarEmail) {
                emailInput.addEventListener('focus', () => {
                    radarEmail.style.opacity = '1';
                });

                emailInput.addEventListener('blur', () => {
                    radarEmail.style.opacity = '0';
                });
            }

            // ========================================
            // RESPONSIVE RESIZE
            // ========================================
            window.addEventListener('resize', () => {
                // Regenerate skyline on resize
                const skyline = document.getElementById('skyline');
                if (skyline) {
                    skyline.innerHTML = '';
                    generateCitySkyline();
                }

                // Regenerate parking spaces
                const spaceFinder = document.getElementById('space-finder');
                if (spaceFinder) {
                    spaceFinder.innerHTML = '';
                    generateParkingSpaces();
                }
            });

            // ========================================
            // ENTRANCE ANIMATIONS
            // ========================================
            setTimeout(() => {
                const logoSection = document.getElementById('logo-section');
                const loginCard = document.getElementById('login-card');

                if (logoSection) {
                    logoSection.style.animationDelay = '0.2s';
                }

                if (loginCard) {
                    loginCard.style.animationDelay = '0.5s';
                }
            }, 100);
        });
    </script>
</body>

</html>
