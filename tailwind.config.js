module.exports = {
  purge: ['./resources/js/components/**/*.vue', './resources/views/**/*.blade.php'],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree'],
      },
    },
  },
  variants: {},
  plugins: [require('@tailwindcss/custom-forms')],
};
