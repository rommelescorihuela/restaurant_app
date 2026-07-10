import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                espresso: {
                    50: '#F5F0EB',
                    100: '#E8DFD5',
                    200: '#D2C0AE',
                    300: '#B8A088',
                    400: '#9E8268',
                    500: '#846A52',
                    600: '#6A5441',
                    700: '#4F3E31',
                    800: '#352A22',
                    900: '#1A0F0A',
                    950: '#0E0805',
                },
                cream: {
                    50: '#FFF8F0',
                    100: '#FFF0E0',
                    200: '#FFE0C0',
                    300: '#FFD0A0',
                    400: '#FFC080',
                    500: '#FFB060',
                    600: '#E89440',
                    700: '#C07830',
                    800: '#985C20',
                    900: '#704010',
                },
                gold: {
                    50: '#FDF8E8',
                    100: '#F9F0C5',
                    200: '#F3E08E',
                    300: '#EBD057',
                    400: '#E0C04A',
                    500: '#C8A45C',
                    600: '#A88840',
                    700: '#886C30',
                    800: '#685020',
                    900: '#483818',
                },
                sienna: {
                    50: '#FDF2ED',
                    100: '#F9E0D4',
                    200: '#F3C0AA',
                    300: '#EB9A7A',
                    400: '#E07850',
                    500: '#D06040',
                    600: '#B85038',
                    700: '#9A4030',
                    800: '#7C3028',
                    900: '#5E2820',
                },
            },
        },
    },

    plugins: [forms],
};
