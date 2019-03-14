@extends('layouts.app')

@section('js')
<script>
$( document ).ready(function() {
    var cardview = $("#card-sample").clone().removeAttr('id').removeAttr('hidden');

    let row = $('<div class="row justify-content-md-center"></div>');
    let pricelist = [10,25,50];
    pricelist.forEach(function(price){
        let col = $('<div class="col-sm-3"></div>');
        let form = $('<form action="{{ URL::to("api/topups") }}" method="POST" style="display: none;"></form>');
        form.attr('id', "TopUp"+price);
        form.addClass('buy');
        form.append('<input type="text" name="amount" value='+price+'>')
        form.append('<input type="text" name="user_id" value={{ Auth::user()->id }}>')
        form.append('<input type="hidden" name="_token" value="{{ csrf_token() }}">')
        form.append('<input type="submit">');
        col.append(form.clone());

        let button = $("<a class='btn btn-primary mx-auto'> Buy </a>");

        button.attr('data-price', price);
        col.append(button.clone());
        cardview.find('img').attr("src", "{{ asset('img/gift-card-') }}"+price+".jpg");
        cardview.find('.card-title').html("Top Up "+price+"$");
        cardview.find('.card-subtitle').html("#topup");
        cardview.find('.card-button').html(col.clone());
        row.append(cardview.clone());
    });
    $('#content').append(row.clone());

    $("#content div .buy").on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            type: "post",
            headers: {
                "Authorization": "Bearer {{ Auth::user()->api_token }}",
                "Content-type": "application/x-www-form-urlencoded",
                "Cache-control": "no-cache"
            },
            data: $(this).serialize(),
            dataType: 'JSON',
            success: function (data) {
                $('.balance').html(data.userbalance.balance);
                Swal.fire({
                  type: 'success',
                  title: 'Your balance is updated',
                  showConfirmButton: false,
                  timer: 1500
                })
            },
            error: function (jXHR, textStatus, errorThrown) {
                Swal.fire({
                  title: 'Error!',
                  text: errorThrown,
                  type: 'error',
                  confirmButtonText: 'Ok'
                })
            }
         });
    });

    $("#content div [data-price]").on('click', function(event){
        $("#TopUp"+$(this).data('price')).submit();
        event.preventDefault();
    });
});
</script>
@stop

@section('content')
<div class="container" id="content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row justify-content-center">
                <div id="card-sample" class="col-md-3 p-2" hidden>
                    <div class="card">
                        <img height="180px" width="100%"/>
                        <div class="card-body">
                          <h5 class="card-title">Card title</h5>
                          <p class="card-subtitle">Topup</p>
                          <p class="card-text">Add balance to your account</p>
                          <p class="card-button"></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
