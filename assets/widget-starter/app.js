(function () {
  'use strict';

  function initSection(root) {
    if (!root || root.dataset.initialized === 'true') return;
    root.dataset.initialized = 'true';

    /* Add only behavior required by the supplied section. */
  }

  function initAll(scope) {
    (scope || document).querySelectorAll('.{{ROOT_CLASS}}').forEach(initSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () { initAll(document); });
  } else {
    initAll(document);
  }

  window.addEventListener('elementor/frontend/init', function () {
    if (!window.elementorFrontend || !elementorFrontend.hooks) return;
    elementorFrontend.hooks.addAction(
      'frontend/element_ready/{{WIDGET_NAME}}.default',
      function ($scope) { initAll($scope[0]); }
    );
  });
}());
