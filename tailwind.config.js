/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./*.html'],
  theme: {
    container: {
      padding: '1.4 rem'
    },
    extend: {
      spacing: {
        'quarter': '25%',
        'half': '50%',
      },
      colors: {
        "do-blue-dark": "#080C2D",
        "do-blue-medium": "rgb(0, 125, 255)",
        "do-blue-light": "rgb(0, 105, 255)"
      },
      boxShadow: {
        'input': '0 5px 1px 0 rgb(0 0 0 / 10%)',
        'input-focus': '0 2px 1px 0 rgb(0 0 0 / 10%)',
 
      },
      fontFamily: {
        sans: ["Poppins", "sans-serif"],
        cascadia: ["Cascadia Code", "monospace"]
      },
      imagen:{
        fondo:['./assets/fondo.avif']
      }
    },
  },
  plugins: [],
}
