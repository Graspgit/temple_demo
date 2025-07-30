$(document).ready(function() {
  $('.phone_no_valid').on('keypress', function(e) {
    var charCode = e.which || e.keyCode;

    // Allow only digits (0-9) and plus sign (+)
    if (
      (charCode >= 48 && charCode <= 57) || // 0-9
      charCode === 43 // +
    ) {
      return true;
    } else {
      e.preventDefault(); // Block other characters
    }
  });
  
  $('.number_valid').on('keypress', function(e) {
    var charCode = e.which || e.keyCode;

    // Allow only digits (0-9) and plus sign (+)
    if (
      (charCode >= 48 && charCode <= 57)
    ) {
      return true;
    } else {
      e.preventDefault(); // Block other characters
    }
  });
  
  $('.float_valid').on('keypress', function(e) {
    var charCode = e.which || e.keyCode;
    var currentVal = $(this).val();

    // Allow 0-9
    if (charCode >= 48 && charCode <= 57) {
      return true;
    }

    // Allow '.' only once
    if (charCode === 46 && currentVal.indexOf('.') === -1) {
      return true;
    }

    // Otherwise, block the input
    e.preventDefault();
  });
  
  $('.eamil_validate').on('submit', function(e) {
    e.preventDefault();

    var email = $('#email').val();
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!regex.test(email)) {
      $('#error-msg').text('Please enter a valid email address.');
    } else {
      $('#error-msg').text('');
      alert('Email is valid!');
      // Proceed with form submission or further logic
    }
  });
});