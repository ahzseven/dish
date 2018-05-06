/**
 * view/member/member_add.html
 * 选择指定审核人
 */
$(function () {
    $("#search-auditor").bind("input propertychange", function () {
        var keyword = $(this).val();
        var url = $("#member-edit").val() ? 'memberEdit' : 'memberAdd';
        var auditor_id = $("#auditor_id").val();
        console.log(auditor_id);
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
                    $("#test-list ul").show();
                } else {
                    $("#test-list ul").hide();
                }


                $(".list").empty();
                var html = '';
                // 列表数据总数
                var num = data.total;
                // console.log(data);

                var options = {
                    valueNames: [ 'id','nickname' ],
                    item: '<li><span class="id" style=display:none></span><p class="nickname" style="cursor:pointer;"></p></li>',
                    page: 3,
                    pagination: true
                };

                var values = data.data;

                var monkeyList = new List('test-list', options, values)
            }
        })

        // 获取动态数据中文本,截取id
        $(".list").on('click', 'li', function(){
            var spanid = $(this).text();
            var regexp = /[0-9]*/;
            var id = spanid.match(regexp);
            // console.log(ll[0]);


            // 替换文本中的数字
            var text = spanid.replace(regexp,'');
            // console.log(text);
            // 替换关键字
            $("#search-auditor").val(text);

            // 替换隐藏域id
            var $t = auditor_id.replace(auditor_id, id[0]);
            $('#auditor_id').val($t);
        });


        })

    })



