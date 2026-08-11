/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        vander: {
          navy: '#1a365d',
          blue: '#0056b3',
          orange: '#ff9900',
          gray: '#f4f4f4',
          text: '#333333',
          light: '#555555'
        }
      }
    }
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
