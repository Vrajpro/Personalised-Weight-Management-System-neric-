$(document).ready(function () {
    // Handle the click event on the "Profile" link
    $('.nav-link.dropdown-toggle').on('click', function (e) {
      e.preventDefault();
      $(this).next('.dropdown-menu').slideToggle();
    });

    // Close the dropdown when clicking outside of it
    $(document).on('click', function (e) {
      if (!$(e.target).closest('.nav-item.dropdown').length) {
        $('.dropdown-menu').slideUp();
      }
    });
  });