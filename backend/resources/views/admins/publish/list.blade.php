@extends('admins.layouts.app')
@section('title', '公開日時設定 | 公開日時設定')
@section('content')
    <div class="mb-2">
        <h1>公開日時設定</h1>
    </div>
    <div class="card">
        <form method="post" id="update-form" action="{{ route('admin.publish.update') }}">
        {{ csrf_field() }}
        <div class="card-body">
            <input type="number" style="display: none" name="id" value="{{ $id }}">
            {{-- タイトル名 --}}
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">タイトル名</label>
                <div class="col-sm-8">
                    <input name="title" type="text" class="form-control" placeholder="2022年07月の従業員向け販売" value="{{ $name }}">
                    @foreach ($errors->get('name') as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </div>
                <div class="ml-auto">
                    @include('admins.components.buttons.create', ['url' => route('admin.publish.create')])
                </div>
            </div>
            <br>
            {{-- 公開開始日時 --}}
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">公開開始日時</label>
                <div class="col-sm-8">

                    <select class="form-control publish-control" id="js_year" name="exhibit_year" onchange="yearMonthChange('')">
                        <option value=""></option>
                    </select>
                    <label for="year">年</label>

                    <select class="form-control publish-control" id="js_month" name="exhibit_month" onchange="yearMonthChange('')">
                        <option value=""></option>
                    </select>
                    <label for="month">月</label>

                    <select class="form-control publish-control" id="js_day" name="exhibit_day">
                        <option value=""></option>
                    </select>
                    <label for="day">日</label>
                    <br>
                    <select class="form-control publish-control" id="js_hour" name="exhibit_hour">
                        <option value=""></option>
                    </select>
                    <label for="hour">時</label>
                    <select class="form-control publish-control" id="js_minute" name="exhibit_minute">
                        <option value=""></option>
                    </select>
                    <label for="minute">分</label>

                    @foreach ($errors->get('exhibit_date') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            <br>
            {{-- 販売開始日時 --}}
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">販売開始日時</label>
                <div class="col-sm-8">
                    <select class="form-control publish-control" id="js_year2" name="sales_start_year" onchange="yearMonthChange('2')">
                        <option value=""></option>
                    </select>
                    <label for="year">年</label>

                    <select class="form-control publish-control" id="js_month2" name="sales_start_month" onchange="yearMonthChange('2')">
                        <option value=""></option>
                    </select>
                    <label for="month">月</label>

                    <select class="form-control publish-control" id="js_day2" name="sales_start_day">
                        <option value=""></option>
                    </select>
                    <label for="day">日</label>
                    <br>

                    <select class="form-control publish-control" id="js_hour2" name="sales_start_hour">
                        <option value=""></option>    
                    </select>
                    <label for="hour">時</label>

                    <select class="form-control publish-control" id="js_minute2" name="sales_start_minute">
                        <option value=""></option>
                    </select>
                    <label for="minute">分</label>

                    @foreach ($errors->get('sales_start_date') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            <br>
            {{-- 販売終了日時 --}}
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">販売終了日時</label>
                <div class="col-sm-8">
                    <select class="form-control publish-control" id="js_year3" name="end_of_sale_year" onchange="yearMonthChange('3')">
                        <option value=""></option>
                    </select>
                    <label for="year">年</label>

                    <select class="form-control publish-control" id="js_month3" name="end_of_sale_month" onchange="yearMonthChange('3')">
                        <option value=""></option>
                    </select>
                    <label for="month">月</label>

                    <select class="form-control publish-control" id="js_day3" name="end_of_sale_day">
                        <option value=""></option>
                    </select>
                    <label for="day">日</label>
                    <br>

                    <select class="form-control publish-control" id="js_hour3" name="end_of_sale_hour">
                        <option value=""></option>
                    </select>
                    <label for="hour">時</label>

                    <select class="form-control publish-control" id="js_minute3" name="end_of_sale_minute">
                        <option value=""></option>
                    </select>
                    <label for="minute">分</label>

                    @foreach ($errors->get('end_of_sale_date') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"></label>
                {{-- 販売終了日時を表示する。 --}}
                <div class="col-sm-8">
                    <input type="checkbox" id="visible" name="visible" value="1" checked>
                    <label>販売終了日時を表示する。</label>
                </div>
            </div>
            
            <div class="ml-auto">
              {{-- 即時終了 --}}
              @if(!$emergency_flag)
                @include('admins.components.buttons.termination', ['url' => route('admin.publish.update')])
              @endif
              {{-- 即時終了停止 --}}
              @if($emergency_flag)
                @include('admins.components.buttons.termination_stop', ['url' => route('admin.publish.update')])
              @endif
              @include('admins.components.buttons.ok', ['url' => route('admin.publish.update')])
            </div>
            {{-- OK button --}}
        </div>
    </form>
<script>
    function createOptionElements(num, parentId, type) {
      if ('{{ $visible }}' == 1) {
          document.getElementById('visible').checked = true;
      } else {
          document.getElementById('visible').checked = false;
      }
      var pYear = '{{ old('exhibit_year', date_parse($exhibit_date)['year']) }}';
      var sYear = '{{ old('sales_start_year', date_parse($sales_start_date)['year']) }}';
      var eYear = '{{ old('end_of_sale_year', date_parse($end_of_sale_date)['year']) }}';
      var pMonth = '{{ old('exhibit_month', date_parse($exhibit_date)['month']) }}';;
      var sMonth = '{{ old('sales_start_month', date_parse($sales_start_date)['month']) }}';
      var eMonth = '{{ old('end_of_sale_month', date_parse($end_of_sale_date)['month']) }}';
      var pDay = '{{ old('exhibit_day', date_parse($exhibit_date)['day']) }}';
      var sDay = '{{ old('sales_start_day', date_parse($sales_start_date)['day']) }}';
      var eDay = '{{ old('end_of_sale_day', date_parse($end_of_sale_date)['day']) }}';
      var pHour = '{{ old('exhibit_hour', date_parse($exhibit_date)['hour']) }}';
      var sHour = '{{ old('sales_start_hour', date_parse($sales_start_date)['hour']) }}';
      var eHour = '{{ old('end_of_sale_hour', date_parse($end_of_sale_date)['hour']) }}';
      var pMinute = '{{ old('exhibit_minute', date_parse($exhibit_date)['minute']) }}';
      var sMinute = '{{ old('sales_start_minute', date_parse($sales_start_date)['minute']) }}';
      var eMinute = '{{ old('end_of_sale_minute', date_parse($end_of_sale_date)['minute']) }}';

      const doc = document.createElement('option');
      doc.value = doc.innerHTML = num;
      if (type == 'pyear') {
        if (pYear === num.toString()) {
            doc.selected = true;
        }
      }

      if (type == 'syear') {
        if (sYear === num.toString()) {
            doc.selected = true;
        }
      }

      if (type == 'eyear') {
        if (eYear === num.toString()) {
            doc.selected = true;
        }
      }

      if (type == 'pmonth') {
        if (pMonth === num.toString()) {
            doc.selected = true;
        }
      }

      if (type == 'smonth') {
        if (sMonth === num.toString()) {
            doc.selected = true;
        }
      }

      if (type == 'emonth') {
        if (eMonth === num.toString()) {
            doc.selected = true;
        }
      }

      if (type == 'pday') {
        if (pDay === num.toString()) {
            doc.selected = true;
        }
      }

      if (type == 'sday') {
        if (sDay === num.toString()) {
            doc.selected = true;
        }
      }

      if (type == 'eday') {
        if (eDay === num.toString()) {
            doc.selected = true;
        }
      }
      
      if (type == 'phour') {
        let pHour1 = pHour.toString().padStart(2, "0");
        if (pHour1 === num.toString()) {
            doc.selected = true;
        }
      }

      if (type == 'shour') {
        let sHour1 = sHour.toString().padStart(2, "0");
        if (sHour1 === num.toString()) {
            doc.selected = true;
        }
      }
      if (type == 'ehour') {
        let eHour1 = eHour.toString().padStart(2, "0");
        if (eHour1 === num.toString()) {
            doc.selected = true;
        }
      }

      if (type == 'pminute') {
        let pMinute1 = pMinute.toString().padStart(2, "0");
        if (pMinute1 === num.toString()) {
            doc.selected = true;
        }
      }
      if (type == 'sminute') {
        let sMinute1 = sMinute.toString().padStart(2, "0");
        if (sMinute1 === num.toString()) {
            doc.selected = true;
        }
      }
      if (type == 'eminute') {
        let eMinute1 = eMinute.toString().padStart(2, "0");
        if (eMinute1 === num.toString()) {
            doc.selected = true;
        }
      }

      document.getElementById(parentId).appendChild(doc);
    }

    // 年月日を yyyymmdd に整形

    function yyyymmdd(y, m, d) {
      var y0 = ('000' + y).slice(-4);
      var m0 = ('0' + m).slice(-2);
      var d0 = ('0' + d).slice(-2);
      return y0 + m0 + d0;
    }

    // 年 or 月変更時
    function yearMonthChange(id) {
      const selectY = document.getElementById("js_year" + id).value;
      const selectM = document.getElementById("js_month" + id).value;
      // 日付のみ変更するので、letで宣言
      let selectD = document.getElementById("js_day" + id).value;

      // 月により、最終日を変更
      switch (selectM) {
        case "1":
        case "3":
        case "5":
        case "7":
        case "8":
        case "10":
        case "12":
          lastDay = "31"
          break;
        case "4":
        case "6":
        case "9":
        case "11":
          lastDay = "30"
          break;
        case "2":

          if (selectY %4 === 0 && selectY%100 !== 0 || selectY % 400 === 0 ) {
            lastDay = "29"
          } else {
            lastDay = "28"
          }
          break;
        default:
          lastDay = "31"
          break;
      }

      // 選択可能な日付を変更（いったん空にしてから、optionを生成する）
      document.getElementById("js_day" + id).innerHTML = "";
      for (let i = 1; i <= this.lastDay; i++) {
        this.createOptionElements(i, 'js_day' + id, '');
      }

      // もともと選択していた日付を選択した状態にする
      if (lastDay <= selectD) {

        selectD = lastDay
      }
      document.getElementById("js_day" + id).value = selectD;
      return lastDay;
    }

    window.onload = function() {

      let mostOldYear = 2022;

      const nowTime = new Date();
      const nowYear = nowTime.getFullYear();

      //公開開始日時
      let i = 2021;

      while (i < 2050) {
        i++;
        createOptionElements(i, 'js_year', 'pyear');
      }
      for (let i = 1; i <= 12; i++) {
        createOptionElements(i, 'js_month', 'pmonth');
      }
      for (let i = 1; i <= yearMonthChange(''); i++) {
        createOptionElements(i, 'js_day', 'pday');
      }
      for (let h = 0; h <= 23; h++) {
        let hrStr = h.toString().padStart(2, "0");
        createOptionElements(hrStr, 'js_hour', 'phour');
      }
      for (let m = 0; m <= 59; m++) {
        let mStr = m.toString().padStart(2, "0");
        createOptionElements(mStr, 'js_minute', 'pminute');
      }
      //販売開始日時
      let y = 2021;
      while (y < 2050) {
        y++;
        createOptionElements(y, 'js_year2', 'syear');
      }
      for (let y = 1; y <= 12; y++) {
        createOptionElements(y, 'js_month2', 'smonth');
      }
      for (let y = 1; y <= yearMonthChange('2'); y++) {
        createOptionElements(y, 'js_day2', 'sday');
      }
      for (let h = 0; h <= 23; h++) {
        let hrStr = h.toString().padStart(2, "0");
        createOptionElements(hrStr, 'js_hour2', 'shour');
      }
      for (let m = 0; m <= 59; m++) {
        let mStr = m.toString().padStart(2, "0");
        createOptionElements(mStr, 'js_minute2', 'sminute');
      }
      //販売終了日時
      let z = 2021;
      while (z < 2050) {
        z++;
        createOptionElements(z, 'js_year3', 'eyear');
      }
      for (let z = 1; z <= 12; z++) {
        createOptionElements(z, 'js_month3', 'emonth');
      }
      for (let z = 1; z <= yearMonthChange('3'); z++) {
        createOptionElements(z, 'js_day3', 'eday');
      }
      for (let h = 0; h <= 23; h++) {
        let hrStr = h.toString().padStart(2, "0");
        createOptionElements(hrStr, 'js_hour3', 'ehour');
      }
      for (let m = 0; m <= 59; m++) {
        let mStr = m.toString().padStart(2, "0");
        createOptionElements(mStr, 'js_minute3', 'eminute');
      }
    }
</script>
@endsection
