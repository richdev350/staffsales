<form action="{{ $url }}" method="post" class="form-inline" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="file" name="csv_file"/>
    <button class="btn btn-primary js-upload-button" type="submit">CSVアップロード</button>
</form>
<script>
function checkCsvFile(){
    if($('input[name=csv_file]').val()){
        $('.js-upload-button').prop('disabled', false);
    }else{
        $('.js-upload-button').prop('disabled', true);
    }
}
$('input[name=csv_file]').change(function(){
    checkCsvFile();
})
checkCsvFile();
</script>
