function dispLoading(){
    if($("#loading").length == 0){
        $("body").append("<div id='loading'></div>");
    }
}
   
function removeLoading(){
    $("#loading").remove();
}