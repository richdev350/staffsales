<th>
  一括操作
  @if (isset($all_check) && $all_check)
    &nbsp;&nbsp;<input type="checkbox" class="batch-all-checkbox" />
  @endif
</th>

<script>
  $(function () {
    $('.batch-all-checkbox').change(function(e) {
      var checked = $(this).prop("checked");
      $('.batch-checkbox').prop({ checked: checked });
    });

    $('.batch-checkbox').change(function(e) {
      const allCnt = $('.batch-checkbox').length;
      const checkedCnt = $('.batch-checkbox:checked').length;
      if (checkedCnt == 0) {
        $('.batch-all-checkbox').prop({ indeterminate: false, checked: false });
      } else if (allCnt > checkedCnt) {
        $('.batch-all-checkbox').prop({ indeterminate: true, checked: false });
      } else {  // if allCnt == checkedCnt
        $('.batch-all-checkbox').prop({ indeterminate: false, checked: true });
      }
    });
  });
</script>
