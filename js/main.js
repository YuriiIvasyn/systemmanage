$('.ui.dropdown').dropdown();
$('.ui.checkbox').checkbox();
$(".ui.checkbox").click(function() {
    $(this).children("input").attr("checked", !$(this).children("input").attr("checked"));
});
$(document).ready(function() {
    var timeoutID = null;
    var globalOffset = 20;
    function findItem(str="", limit, activePagBtn, u_agent) {
        var finalOffset = (activePagBtn-1)*globalOffset;
        $.post("api_search.php", {
            offset: finalOffset,
            limit: limit,
            search: str,
            u_agent: u_agent,
            searchType: 'customers'
        }, function (data) {
            $(".list-table tbody").html("");
            JSON.parse(data.split('salut-somesd')[1]).forEach(function(item, index){
                $(".list-table tbody").append(
                    $("<tr><td>" + item['name']+" "+item['last_name']+"</td><td>"+item['city']+"</td><td>"+item['added']+"</td><td>"+((item['c_review'] == 1)? "<i class='telegram icon'>" : "") +"</i></td></tr>").click(function(){
                        document.location="edit.php?id="+item['id'];
                    })
                );
            });

            reprintPagination(data.split('salut-somesd')[0]);
        });
    }
    function reprintPagination(itemNum){
        console.log(itemNum);
        $(".pagination-buttons .button").each(function(index){
            if(index+1 <= Math.ceil(itemNum/globalOffset)){
                $(this).css("display", "block");
            }
            else {
                $(this).css("display", "none");
            }
        });
    }
    $(".list-search-form input").keyup(function (e) {
        clearTimeout(timeoutID);
        $(".pagination-buttons .button").removeClass("active");
        $(".pagination-buttons .button:first-child").addClass("active");

        timeoutID = setTimeout(function () {
            if($(".list-search-form input").hasClass("customer")){
                return findItem($(".list-search-form input.customer").val(), globalOffset, $(".pagination-buttons .button.active").text(), $(".users .item.active.selected").attr('data-value'));
            }
            else{
                return findAgent($(".list-search-form input.agents").val(), globalOffset, $(".pagination-buttons .button.active").text());
            }
        }, 500);

    });

    $(".users .item").click(function(){
        u_user= $(this).attr('data-value');

        if($(this).attr('data-value') == ''){
            timeoutID = setTimeout(function () {
                if ($(".list-search-form input").hasClass("customer")) {
                    return findItem($(".list-search-form input.customer").val(), globalOffset, $(".pagination-buttons .button.active").text());
                }
                else {
                    return findAgent($(".list-search-form input.agents").val(), globalOffset, $(".pagination-buttons .button.active").text());
                }
            }, 500);
        }
        else {
            timeoutID = setTimeout(function () {
                if ($(".list-search-form input").hasClass("customer")) {
                    return findItem($(".list-search-form input.customer").val(), globalOffset, $(".pagination-buttons .button.active").text(), u_user);
                }
                else {
                    return findAgent($(".list-search-form input.agents").val(), globalOffset, $(".pagination-buttons .button.active").text());
                }
            }, 500);
        }

    });


    $(".list-search-form i.icon").hide();
    $( ".list-search-form input" ).focus(function() {
        $(".list-search-form i.icon").show();
    });
    $( ".list-search-form input" ).blur(function(){
        if($( ".list-search-form input" ).val() == ""){
            $(".list-search-form i.icon").hide();
        }
    });
    $(".list-search-form i.icon").click(function(){

        $(".list-search-form input").val("");
        $(".list-search-form i.icon").hide();


        $(".pagination-buttons .button").removeClass("active");
        $(".pagination-buttons .button:first-child").addClass("active");
        if($(".list-search-form input").hasClass("customer")) {
            timeoutID = setTimeout(function () {
                return findItem($(".list-search-form input.customer").val(), globalOffset, $(".pagination-buttons .button.active").text())
            }, 500);
        }
        else{
            timeoutID = setTimeout(function(){ return findAgent($(".list-search-form input.agents").val(), globalOffset, $(".pagination-buttons .button.active").text()) }, 500);
        }
    });
    $(".pagination-buttons .button").click(function(){
        $(".pagination-buttons .button").removeClass("active");
        $(this).addClass("active");
        if($(".list-search-form input").hasClass("customer")) {
            findItem($(".list-search-form input").val(), globalOffset, $(".pagination-buttons .button.active").text());
        }
        if($(".list-search-form input").hasClass("agents")) {
            findAgent($(".list-search-form input").val(), globalOffset, $(".pagination-buttons .button.active").text());
        }
    });


    function findAgent(str="", limit, activePagBtn) {
        var finalOffset = (activePagBtn-1)*globalOffset;
        $.post("api_search.php", {
            offset: finalOffset,
            limit: limit,
            search: str,
            searchType: 'agents'
        }, function (data) {
            $(".agents-list tbody").html("");
           // console.log(data);
            //console.log('+++');
            JSON.parse(data.split('salut-somesd')[1]).forEach(function(item, index){
                $(".agents-list tbody").append(
                    $("<tr><td>" + item['u_name']+" "+item['u_last_name']+"</td><td>"+item['u_email']+"</td></tr>").click(function(){
                        document.location="agent_edit.php?id="+item['id'];
                    })
                );
            });

            reprintPagination(data.split('salut-somesd')[0]);
        });
    }



    //add

    $("button.ui.massive.secondary.button").click(function(){

    });



});