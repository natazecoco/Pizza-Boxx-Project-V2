/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/Filament/**/*.php',
        './app/Forms/Components/**/*.php',
        './app/Tables/Columns/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', 'sans-serif'],
            },
            colors: {
                'brand-red': 'rgb(164, 31, 33)',
                'brand-kraft': '#fff4bd',
                'brand-yellow': '#FFC107', // Untuk border sertifikasi Halal
            },
            // Agar background pattern muncul
            backgroundImage: {
                'pizza-pattern': "url('/images/pizza-pattern.png')", // Pastikan file gambar ini ada di folder public/images
            },
            // Agar animasi fade-in di Hero jalan
            animation: {
                'fade-in': 'fadeIn 1s ease-out forwards',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                }
            }
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
    ],
};