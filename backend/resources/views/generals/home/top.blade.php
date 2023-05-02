<?php use App\Enums\Mode\Modes; ?>
@extends('generals.layouts.app')
@if($current_mode == Modes::MAINTENANCE && AllowIpsOnMaintenance())
@include('generals.components.maintenance_mode')
@endif
@section('content')
<div class="top-empty">
  {{-- <h2 id="csv-title">{{ config('app.sales_month', '') }}従業員販売リスト</h2> --}}
  <h2 id="csv-title">{{$name}}</h2>
  @if($sales_start_date)
  <h2 id="csv-title">販売開始日時 : {{ $sales_start_date }}</h2>
  @endif
  @if($end_of_sale_date)
  <h2 id="csv-title">終了日時	: {{ $end_of_sale_date }}</h2>
  @endif
  <table id="csv-table"></table>
</div>

<script>
  $(function() {
    $.get('/top-items-list.csv', function(data) {
      var html = '';
      var rows = data.split("\n");
      rows.forEach( function getvalues(ourrow, idx) {

        var columns = ourrow.split(",");
		if (columns[1] === undefined){
			return true;
		}
        html += "<tr>";

        html += (idx === 0 ? '<th style="white-space: nowrap;">' : "<td>") +
        	(idx === 0 ? 'カテゴリ' : columns[0] + "<br />" + columns[1]) +
        	(idx === 0 ? "</th>" : "</td>");
        html += (idx === 0 ? "<th>" : "<td>") +
        	(idx === 0 ? columns[3] : columns[2] + '<br />'+ columns[3] +
        	(idx === 0 ? "" : '<br /><span style="color:red">' +
        		(columns[4] === "" ? "" : "※") +columns[4]+"</span>") +
        	(idx === 0 ? "</th>" : "</td>"));

		var num = String(columns[5]).replace(/(\d)(?=(\d\d\d)+$)/g, "$1,");
        html += (idx === 0 ? "<th>" : "<td style=\"text-align:right;\">") + num + (idx === 0 ? "</th>" : "円</td>");
        html += "</tr>";
      })
      $('#csv-table').html(html);
    });

    var table = $('#csv-table').DataTable( {
        paging:         true,
        fixedColumns:   {
            leftColumns: 1,
        }
    });

  });
</script>
@endsection