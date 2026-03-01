/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        poppins: ['Poppins', 'sans-serif'],
        serif4: ['"Source Serif 4"', 'serif'],
      },
      colors: {
        mainBlue:  '#64bcf4',
        hoverBlue: '#5bacdf',
        darkBlue:  '#0d4c8f',
        lightBlue: '#5fb6e5',
        brand: {
          DEFAULT: '#010694',
          50: '#eff0ff',
          100: '#e0e2ff',
          200: '#c3c7ff',
          300: '#9aa0ff',
          400: '#6d70fc',
          500: '#4346ef',
          600: '#2b2bd1',
          700: '#2220aa',
          800: '#1e1c89',
          900: '#010694',
        },
        dark: {
          bg: '#0B0F19',
          card: '#111623',
          border: '#1E2536',
        },
      },
      fontSize: {
        xxs: '0.65rem',
      },
      backgroundImage: {
        'glass-gradient': `linear-gradient(
          135deg,
          #C5EAFF 3%,
          #CDEDFE 11%,
          #E1F2FB 32%,
          #E6F3FA 44%,
          #E1F0FB 52%,
          #D8EBFD 59%,
          #D0E7FE 67%,
          #CDE6FF 70%,
          #CCE5FF 84%,
          rgba(13,91,174,0.13) 100%
        )`,
      },
      boxShadow: {
        card: '0 4px 12px rgba(0,0,0,0.15)',
      },
      keyframes: {
        fadeUp: {
          '0%':   { opacity: '0', transform: 'translateY(18px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        }
      },
      animation: {
        fadeUp: 'fadeUp 0.55s cubic-bezier(.22,.68,0,1.2) both',
      }
    },
  },
  plugins: [],
}

