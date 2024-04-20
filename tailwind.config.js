module.exports = {
  purge: ['./resources/js/components/**/*.vue', './resources/views/**/*.blade.php'],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree'],
      },
      fontSize: {
        '2xs': '.625rem',
      },
      screens: {
        '2xl': '1366px',
        '3xl': '1440px',
      },
    },
  },
  variants: {},
  plugins: [require('@tailwindcss/custom-forms')],
};
