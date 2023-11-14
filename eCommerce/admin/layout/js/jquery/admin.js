$(document).ready(function () {
  $('[placeholder]').on('focus', function () {
    $(this).attr("data-text", $(this).attr("placeholder"));
    $(this).attr("placeholder", "");
  });
  $('[placeholder]').on('blur', function () {
    $(this).attr("placeholder", $(this).attr("data-text"));
  });

  //Add an asterisk (*) in the required inputs
  $('.edit-form form input[required="true"]').after('<span class="asterisk">*</span>');

  // check the input value
  $('.edit-form form input[name="username"]').on('keyup', function () {
    if ($(this).val().length < 4 || $(this).val().length > 20) {
      $(this).css('border-color', '#df2b34').css('box-shadow', '0 0 0 0.25rem rgb(253 13 13 / 25%)');
    } else {
      $(this).css('border-color', '#ced4da').css('box-shadow', '0 0 0 0.25rem rgba(13, 110, 253, .25)');
    }
  });

  $('.edit-form form input[name="full"]').on('keyup', function () {
    if (!$(this).val()) {
      $(this).css('border-color', '#df2b34').css('box-shadow', '0 0 0 0.25rem rgb(253 13 13 / 25%)');
    } else {
      $(this).css('border-color', '#ced4da').css('box-shadow', '0 0 0 0.25rem rgba(13, 110, 253, .25)');
    }
  });

  // show password
  $('body').on('click', '.showPass', function () {
    let passField = $('input.password');
    if (passField.attr('type') === 'password') {
      passField.attr('type', 'text');
      $(this).toggleClass('fa-regular fa-eye-slash');
    } else {
      passField.attr('type', 'password');
      $(this).toggleClass('fa-regular fa-eye');
    }
  });

  // Confirmation message when deleting a member
  $('.confirm').on('click', function () {
    return confirm('Are You Sure?');
  })
});