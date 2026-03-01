<style>
    /* THEME RED */
    .theme-red {
        --theme-primary: #ef4444;
        --theme-primary-dark: #dc2626;
        --theme-secondary: #991b1b;
        --theme-secondary-dark: #7f1d1d;
    }

    .theme-red .from-theme-primary {
        --tw-gradient-from: #ef4444;
        --tw-gradient-to: rgb(239 68 68 / 0);
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
    }

    .theme-red .to-theme-primary-dark {
        --tw-gradient-to: #dc2626;
    }

    .theme-red .from-theme-secondary {
        --tw-gradient-from: #991b1b;
    }

    .theme-red .to-theme-secondary-dark {
        --tw-gradient-to: #7f1d1d;
    }

    .theme-red .via-theme-primary {
        --tw-gradient-stops: var(--tw-gradient-from), #ef4444, var(--tw-gradient-to);
    }

    .theme-red .via-theme-secondary {
        --tw-gradient-stops: var(--tw-gradient-from), #991b1b, var(--tw-gradient-to);
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

    .theme-red .hover\:text-theme-primary:hover {
        color: #ef4444 !important;
    }

    .theme-red .hover\:bg-theme-primary:hover {
        background-color: #ef4444 !important;
    }

    .theme-red .hover\:bg-theme-primary-dark:hover {
        background-color: #dc2626 !important;
    }

    .theme-red .hover\:text-theme-primary-dark:hover {
        color: #dc2626 !important;
    }

    .theme-red .focus\:ring-theme-primary\/30:focus {
        --tw-ring-color: rgb(239 68 68 / 0.3);
    }

    .theme-red .focus\:border-theme-primary:focus {
        border-color: #ef4444;
    }

    .theme-red .hover\:border-theme-primary\/50:hover {
        border-color: rgb(239 68 68 / 0.5);
    }

    /* THEME ORANGE */
    .theme-orange {
        --theme-primary: #f97316;
        --theme-primary-dark: #ea580c;
        --theme-secondary: #c2410c;
        --theme-secondary-dark: #9a3412;
    }

    .theme-orange .from-theme-primary {
        --tw-gradient-from: #f97316;
        --tw-gradient-to: rgb(249 115 22 / 0);
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
    }

    .theme-orange .to-theme-primary-dark {
        --tw-gradient-to: #ea580c;
    }

    .theme-orange .from-theme-secondary {
        --tw-gradient-from: #c2410c;
    }

    .theme-orange .to-theme-secondary-dark {
        --tw-gradient-to: #9a3412;
    }

    .theme-orange .via-theme-primary {
        --tw-gradient-stops: var(--tw-gradient-from), #f97316, var(--tw-gradient-to);
    }

    .theme-orange .via-theme-secondary {
        --tw-gradient-stops: var(--tw-gradient-from), #c2410c, var(--tw-gradient-to);
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

    .theme-orange .hover\:text-theme-primary:hover {
        color: #f97316 !important;
    }

    .theme-orange .hover\:bg-theme-primary:hover {
        background-color: #f97316 !important;
    }

    .theme-orange .hover\:bg-theme-primary-dark:hover {
        background-color: #ea580c !important;
    }

    .theme-orange .hover\:text-theme-primary-dark:hover {
        color: #ea580c !important;
    }

    .theme-orange .focus\:ring-theme-primary\/30:focus {
        --tw-ring-color: rgb(249 115 22 / 0.3);
    }

    .theme-orange .focus\:border-theme-primary:focus {
        border-color: #f97316;
    }

    .theme-orange .hover\:border-theme-primary\/50:hover {
        border-color: rgb(249 115 22 / 0.5);
    }

    /* THEME BLUE */
    .theme-blue {
        --theme-primary: #3b82f6;
        --theme-primary-dark: #2563eb;
        --theme-secondary: #1e40af;
        --theme-secondary-dark: #1e3a8a;
    }

    .theme-blue .from-theme-primary {
        --tw-gradient-from: #3b82f6;
        --tw-gradient-to: rgb(59 130 246 / 0);
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
    }

    .theme-blue .to-theme-primary-dark {
        --tw-gradient-to: #2563eb;
    }

    .theme-blue .from-theme-secondary {
        --tw-gradient-from: #1e40af;
    }

    .theme-blue .to-theme-secondary-dark {
        --tw-gradient-to: #1e3a8a;
    }

    .theme-blue .via-theme-primary {
        --tw-gradient-stops: var(--tw-gradient-from), #3b82f6, var(--tw-gradient-to);
    }

    .theme-blue .via-theme-secondary {
        --tw-gradient-stops: var(--tw-gradient-from), #1e40af, var(--tw-gradient-to);
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

    .theme-blue .hover\:text-theme-primary:hover {
        color: #3b82f6 !important;
    }

    .theme-blue .hover\:bg-theme-primary:hover {
        background-color: #3b82f6 !important;
    }

    .theme-blue .hover\:bg-theme-primary-dark:hover {
        background-color: #2563eb !important;
    }

    .theme-blue .hover\:text-theme-primary-dark:hover {
        color: #2563eb !important;
    }

    .theme-blue .focus\:ring-theme-primary\/30:focus {
        --tw-ring-color: rgb(59 130 246 / 0.3);
    }

    .theme-blue .focus\:border-theme-primary:focus {
        border-color: #3b82f6;
    }

    .theme-blue .hover\:border-theme-primary\/50:hover {
        border-color: rgb(59 130 246 / 0.5);
    }
</style>
