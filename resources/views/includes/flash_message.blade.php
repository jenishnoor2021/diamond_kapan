@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="mdi mdi-check-all me-2"></i>
  {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <i class="mdi mdi-block-helper me-2"></i>
  {{ session('error') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
  <ul class="mb-0">
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif

<div id="ajaxAlert"
  class="alert alert-success position-fixed end-0 m-3 d-none"
  style="z-index: 9999;top:50px !important;">
</div>


<script>
  function showAlert(message, type = 'success') {
    let alertBox = $('#ajaxAlert');

    alertBox
      .removeClass('d-none alert-success alert-danger alert-warning')
      .addClass('alert-' + type)
      .text(message)
      .fadeIn();

    setTimeout(function() {
      alertBox.fadeOut(function() {
        $(this).addClass('d-none');
      });
    }, 5000); // 5 seconds
  }
</script>