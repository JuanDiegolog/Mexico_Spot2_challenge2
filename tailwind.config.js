/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.{js,jsx,ts,tsx,blade.php}', // Ajusta esta ruta según la estructura de tu proyecto
    ],
    theme: {
        extend: {},
    },
    plugins: [
      require('@tailwindcss/forms'),
    ],
}