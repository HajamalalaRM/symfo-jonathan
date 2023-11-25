/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        primary: 'var(--primary)',
        secondary: 'var(--secondary)',
        lighted: 'var(--lighted)',
        greened: 'var(--greened)',
        greeneddark: 'var(--greeneddark)'
      }
    },
  },
  plugins: [],
}

