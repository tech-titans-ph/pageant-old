module.exports = {
  purge: ['./resources/js/components/**/*.vue', './resources/views/**/*.blade.php'],
  theme: {
    extend: {},
  },
  variants: {},
  plugins: [require('@tailwindcss/custom-forms')],
};
