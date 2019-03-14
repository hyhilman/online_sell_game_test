@extends('layouts.app')

@section('js')
<script>
$( document ).ready(function() {
    var table = $('<table class="table"></table>');
    var thead = $('<thead class="thead-dark"></thead>');
    var tbody = $('<tbody></tbody>');
    var th = $('<th scope="col"></th>');
    var tr = $('<tr></tr>');
    var td = $('<td></td>');

    var page = $("#paggination-item").clone().removeAttr('id').removeAttr('hidden');

    $.ajax({
        url : "{{ url('api/topups'.'?page='.app('request')->input('page')) }}",
        type: "get",
        headers: {
            "Authorization": "Bearer {{ Auth::user()->api_token }}",
            "Content-type": "application/x-www-form-urlencoded",
            "Cache-control": "no-cache"
        },
        data: $(this).serialize(),
        dataType: 'JSON',
        success: function (result) {
            tr.html(th.html('#').clone());
            tr.append(th.html('Date').clone());
            tr.append(th.html('Amount').clone());
            thead.html(tr.clone());

            table.html(thead.clone());
            for (var i = 0; i < result.data.length; i++) {
                tr.html(th.html(result.data[i].id).clone());
                tr.append(th.html(result.data[i].created_at).clone());
                tr.append(th.html(result.data[i].amount).clone());
                tbody.html(tr.clone());
                table.append(tbody.clone());
            }

            $('#content').append(table.clone());

            for (let i = 1; i <= result.last_page; i++) {
                page.find('a').attr('href', "{{ url('topuphistory') }}?page="+i).html(i);
                if(i == result.current_page) {
                    page.addClass('active');
                } else {
                    page.removeClass('active');
                }
                $('#pagination').append(page.clone());
            }
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
</script>
@stop

@section('content')
<div class="container" id="content">

    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center" id="pagination">
        <li id="paggination-item" class="page-item" hidden><a class="page-link" href="#">Previous</a></li>
      </ul>
    </nav>
</div>
@endsection
