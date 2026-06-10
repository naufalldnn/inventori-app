import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
  content: [
    './resources/views/**/*.blade.php',     // sudah ada
    './resources/views/**/**/*.blade.php',  // tambahan
    './app/View/**/*.php',                  // sudah ada
    './resources/js/**/*.js',               // tambahan
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        ink: '#111827',
        moss: '#0d9488',
        coral: '#e11d48',
        amber: '#f59e0b',
        ocean: '#2563eb',
        cloud: '#f8fafc',
        line: '#e2e8f0',
      },
    },
  },
  plugins: [forms],
};
