/**
 * view/scene/scene_add.html
 */

$(function () {

    // 选择单页->isab/选择多页->材质
    $('input[type=radio][name=type]').change(function() {
        if (this.value == '2') {
            // console.log(2);
            $('input[type=radio][name=material]').attr("disabled", false);
            $('input[type=text][name=pagecount]').attr("disabled", false);

            $('input[type=radio][name=isab]').attr("checked", false);
            $('input[type=radio][name=isab]').attr("disabled", "disabled");
        }
        else {
            $('input[type=radio][name=isab]').attr("disabled", false);
            // console.log(1);
            $('input[type=radio][name=material]').attr("checked", false);
            $('input[type=radio][name=material]').attr("disabled","disabled");
        }
    });

    // 选择业务员
    $("#search-member").bind("input propertychange", function () {
        var keyword = $(this).val();
        var url = $("#member-edit").val() ? 'sceneEdit' : 'sceneAdd';
        var member_id = $("#member_id").val();
        console.log(keyword);
        var data = {
            action : 'memberlist',
            search_data : keyword,
        };
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: "json",
            success: function (data) {
                if (data == ''){
                    return;
                }
                if(keyword){
                    $("#member-list ul").show();
                } else {
                    $("#member-list ul").hide();
                }


                $(".list").empty();
                var html = '';
                // 列表数据总数
                var num = data.total;
                console.log(data);

                var options = {
                    valueNames: [ 'id','nickname' ],
                    item: '<li><span class="id" style=display:none></span><p class="nickname" style="cursor:pointer;"></p></li>',
                    page: 3,
                    pagination: true
                };

                var values = data.data;

                var monkeyList = new List('member-list', options, values)
            }
        })

        // 获取动态数据中文本,截取id
        $("#ul-list").on('click', 'li', function(){
            var spanid = $(this).text();
            var regexp = /[0-9]*/;
            var id = spanid.match(regexp);
            // console.log(ll[0]);


            // 替换文本中的数字
            var text = spanid.replace(regexp,'');
            // console.log(text);
            // 替换关键字
            $("#search-member").val(text);

            // 替换隐藏域id
            var $t = member_id.replace(member_id, id[0]);
            $('#member_id').val($t);
            $('#member-list').find('ul').hide();
        });


    })

    // 选择客户
    $("#search-customer").bind("input propertychange", function () {
        var keyword = $(this).val();
        var url = $("#member-edit").val() ? 'sceneEdit' : 'sceneAdd';
        var member_id = $("#customer_id").val();
        // console.log(auditor_id);
        var data = {
            action : 'customerlist',
            search_data : keyword,
        };
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: "json",
            success: function (data) {
                if (data == ''){
                    return;
                }
                if(keyword){
                    $("#customer-list ul").show();
                } else {
                    $("#customer-list ul").hide();
                }


                $(".list").empty();
                var html = '';
                // 列表数据总数
                var num = data.total;
                console.log(data);

                var options = {
                    valueNames: [ 'id','name' ],
                    item: '<li><span class="id" style=display:none></span><p class="name" style="cursor:pointer;"></p></li>',
                    page: 3,
                    pagination: true
                };

                var values = data.data;

                var monkeyList = new List('customer-list', options, values)
            }
        })

        // 获取动态数据中文本,截取id
        $("#customerlist").on('click', 'li', function(){
            var spanid = $(this).text();
            var regexp = /[0-9]*/;
            var id = spanid.match(regexp);
            // console.log(ll[0]);


            // 替换文本中的数字
            var text = spanid.replace(regexp,'');
            // console.log(text);
            // 替换关键字
            $("#search-customer").val(text);

            // 替换隐藏域id
            var $t = member_id.replace(member_id, id[0]);
            $('#customer_id').val($t);
            $('#customer-list').find('ul').hide();
        });


    })


})



