@push('scripts')
  <script type="text/javascript">
    window.addEventListener('load', () => {
      let hasCriterias = document.querySelector('#has_criterias');

      let scoringSystem = document.querySelector('#scoring_system');

      let maxPointsPercentage = document.querySelector('#max_points_percentage');

      let scoringSystemWrapper = document.querySelector('.scoring-system-wrapper');

      let maxPointsPercentageWrapper = document.querySelector('.max-points-percentage-wrapper');

      let contestScoringSystem = "{{ $contest->scoring_system }}";

      hasCriterias.addEventListener('change', function(event) {
        scoringSystem.value = '';

        scoringSystemWrapper.classList.add('hidden');

        if (event.target.checked) {
          scoringSystemWrapper.classList.remove('hidden');
        }

        toggleMaxPointsPercentageWrapper();
      });

      scoringSystem.addEventListener('change', function(event) {
        toggleMaxPointsPercentageWrapper();
      });

      function toggleMaxPointsPercentageWrapper() {
        maxPointsPercentageWrapper.classList.add('hidden');

        if ((contestScoringSystem == 'ranking' && !hasCriterias.checked) || contestScoringSystem == 'average') {
          maxPointsPercentageWrapper.classList.remove('hidden');
        } else {
          maxPointsPercentage.value = '';
        }
      }
    });
  </script>
@endpush
