import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#e6f7f7',
                    100: '#b3eaea',
                    200: '#80dddd',
                    300: '#4dd0d0',
                    400: '#26bcbc',
                    500: '#0D9394',
                    600: '#0b7a7a',
                    700: '#096161',
                    800: '#064848',
                    900: '#032f2f',
                    950: '#021818',
                },
                secondary: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
            },
        },
    },

    plugins: [forms, typography],
};
