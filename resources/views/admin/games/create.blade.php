<form action="{{ URL::to('api/games') }}" method="POST" id="create-game">
  <div class="form-group row p-1">
    <label for="colFormLabelSm" class="col-2 col-form-label col-form-label">Title</label>
    <div class="col-10">
      <input name="title" type="text" class="form-control form-control" id="colFormLabelSm" placeholder="Title">
    </div>

    <label for="colFormLabelSm" class="col-2 col-form-label col-form-label">Publisher</label>
    <div class="col-10">
      <input name="publisher" type="text" class="form-control form-control" id="colFormLabelSm" placeholder="Publisher">
    </div>

    <label for="colFormLabelSm" class="col-2 col-form-label col-form-label">Price</label>
    <div class="col-10">
      <input name="price" type="number" class="form-control form-control" id="colFormLabelSm" placeholder="Price">
    </div>

    <label for="colFormLabelSm" class="col-2 col-form-label col-form-label">Description</label>
    <div class="col-10">
      <input name="description" type="text" class="form-control form-control" id="colFormLabelSm" placeholder="Description">
    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="image_url" value="https://lorempixel.com/640/640/?41004">
    <input type="submit" class="mx-auto m-3">
  </div>
</form>

<script>
$( document ).ready(function() {
    $('#create-game').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            type: "post",
            headers: {
                "authorization": "Bearer {{ Auth::user()->api_token }}",
                "content-type": "application/x-www-form-urlencoded",
                "cache-control": "no-cache"
            },
            data: $(this).serialize(),
            dataType: 'JSON',
            success: function (data) {
                Swal.fire({
                    type: 'success',
                    title: 'Success Insert new Game',
                    showConfirmButton: false,
                    timer: 1500
                })
                location.reload()
            },
            error: function (jXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: 'Error!',
                    text: jXHR.responseJSON.message,
                    type: 'error',
                    confirmButtonText: 'Ok'
                })
            }
        });
    });
});
</script>
