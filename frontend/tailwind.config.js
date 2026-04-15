/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Montserrat', 'sans-serif'],
      },
      colors: {
        cellar: {
          navy: '#2f2f6b',
          navyMid: '#44447a',
          orange: '#f0a451',
          wine: '#881034',
          dark: '#292929',
          gray: '#979899',
          text: '#292929',
          placeholder: '#979899'
        }
      },
      borderRadius: {
        'std': '0.25rem',
        'round': '20px',
        'pill': '150px',
      },
      maxWidth: {
        'cellar': '96rem',
      }
    },
  },
  plugins: [],
}
