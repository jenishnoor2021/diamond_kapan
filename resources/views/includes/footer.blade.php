<!-- JAVASCRIPT -->
<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- form validation -->
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>

<script type="text/javascript">
  $.ajaxSetup({
    headers: {
      'csrftoken': '{{ csrf_token() }}'
    }
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#master').on('click', function(e) {
      if ($(this).is(':checked', true)) {
        $(".sub_chk").prop('checked', true);
      } else {
        $(".sub_chk").prop('checked', false);
      }
    });
    $('.delete_all').on('click', function(e) {
      var allVals = [];
      $(".sub_chk:checked").each(function() {
        allVals.push($(this).attr('data-id'));
      });
      if (allVals.length <= 0) {
        alert("Please select row.");
      } else {
        var check = confirm("Are you sure you want to delete this row?");
        if (check == true) {
          var join_selected_values = allVals.join(",");
          $.ajax({
            url: $(this).data('url'),
            type: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: 'ids=' + join_selected_values,
            success: function(data) {
              if (data['success']) {
                $(".sub_chk:checked").each(function() {
                  $(this).parents("tr").remove();
                });
                alert(data['success']);
              } else if (data['error']) {
                alert(data['error']);
              } else {
                alert('Whoops Something went wrong!!');
              }
            },
            error: function(data) {
              alert(data.responseText);
            }
          });
          $.each(allVals, function(index, value) {
            $('table tr').filter("[data-row-id='" + value + "']").remove();
          });
        }
      }
    });
    $('[data-toggle=confirmation]').confirmation({
      rootSelector: '[data-toggle=confirmation]',
      onConfirm: function(event, element) {
        element.trigger('confirm');
      }
    });
    $(document).on('confirm', function(e) {
      var ele = e.target;
      e.preventDefault();
      $.ajax({
        url: ele.href,
        type: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
          if (data['success']) {
            $("#" + data['tr']).slideUp("slow");
            alert(data['success']);
          } else if (data['error']) {
            alert(data['error']);
          } else {
            alert('Whoops Something went wrong!!');
          }
        },
        error: function(data) {
          alert(data.responseText);
        }
      });
      return false;
    });
  });
</script>

<!-- read more button in database -->
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $('.read-more-content').addClass('hide_content')
  $('.read-more-show, .read-more-hide').removeClass('hide_content')

  // Set up the toggle effect:
  $('.read-more-show').on('click', function(e) {
    $(this).next('.read-more-content').removeClass('hide_content');
    $(this).addClass('hide_content');
    e.preventDefault();
  });

  // Changes contributed by @diego-rzg
  $('.read-more-hide').on('click', function(e) {
    var p = $(this).parent('.read-more-content');
    p.addClass('hide_content');
    p.prev('.read-more-show').removeClass('hide_content'); // Hide only the preceding "Read More"
    e.preventDefault();
  });
</script>
<!-- read more button end in database -->

<script>
  function formatAadharInput(input) {
    // Remove any non-numeric characters
    input.value = input.value.replace(/\D/g, '');

    // Limit the input length to 12 characters
    if (input.value.length > 12) {
      input.value = input.value.slice(0, 12);
    }

    // Format the input with spaces after every 4 digits
    input.value = input.value.replace(/(\d{4})/g, '$1 ').trim();
  }
</script>

<!-- Required datatable js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- Buttons examples -->
<script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

<!-- Responsive examples -->
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<!-- Datatable init js -->
<script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>

<script>
  $(document).ready(function() {
    $("#partytable").DataTable();
    $("#workertable").DataTable();
    $("#dailytable").DataTable();
    $("#dimondtable").DataTable();
    $("#dimondprocesstable").DataTable();
    $(".data-table").DataTable();
    $(".data-table1").DataTable();
    $("#workerbarcodelist").DataTable();
  });

  document.addEventListener('DOMContentLoaded', function() {
    function enableAutoDatepicker(selector) {
      const input = document.querySelector(selector);
      if (!input) return;

      let pickerOpened = false;

      input.addEventListener('focus', function() {
        if (!pickerOpened && this.showPicker) {
          this.showPicker();
          pickerOpened = true;
        }
      });

      input.addEventListener('click', function() {
        if (!pickerOpened && this.showPicker) {
          this.showPicker();
          pickerOpened = true;
        }
      });

      input.addEventListener('change', function() {
        pickerOpened = false;
      });

      input.addEventListener('blur', function() {
        pickerOpened = false;
      });
    }

    enableAutoDatepicker('#issue_date');
    enableAutoDatepicker('#start_date');
    enableAutoDatepicker('#end_date');
    enableAutoDatepicker('#month');
    enableAutoDatepicker('#date');
    enableAutoDatepicker('#invoice_date');
    enableAutoDatepicker('#due_date');
    enableAutoDatepicker('#check_in');
    enableAutoDatepicker('#check_out');
  });
</script>

@yield('script')

</body>

</html>