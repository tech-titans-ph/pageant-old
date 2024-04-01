@push('scripts')
  <script type="text/javascript">
    window.addEventListener('load', () => {
      document.querySelectorAll('.remove-score-confirmation-form').forEach((form) => {
        form.addEventListener('submit', function(event) {
          event.preventDefault();

          swal.fire({
            customClass: {
              cancelButton: "ml-2 inline-block text-center font-medium px-4 py-2 rounded-full shadow text-white focus:outline-none focus:shadow-outline bg-red-600 hover:bg-red-500",
              confirmButton: "inline-block text-center font-medium px-4 py-2 rounded-full shadow text-white focus:outline-none focus:shadow-outline bg-green-600 hover:bg-green-500"
            },
            buttonsStyling: false,
            showCancelButton: true,
            showCloseButton: true,
            icon: "warning",
            input: 'password',
            inputPlaceholder: 'Please enter your password...',
            text: 'Are you sure you want to remove this and its related scores?',
          }).then((result) => {
            if (result.value) {
              var password = form.querySelector('[name="password"]');

              password.value = result.value;

              form.submit();
            }
          });
        });
      });
    });
  </script>
@endpush
