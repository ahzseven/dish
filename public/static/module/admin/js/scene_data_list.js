/**
 * ajax
 */
$(function () {
    // 页面菜品数据ajax编辑
    $("#datalist").on("change", "input", function(){
        // 获取当前tr中的对象
        var obj = $(this).parents("tr").data(id);
        var text = $(this).val();
        // 获取name属性值
        var name = $(this).attr('name');
        console.log(name);
        // 获取当前tr中的id
        var id = obj['id'];
        // 获取到当前域名
        var domain = "http://" + document.domain;
        // console.log(domain);
        var action = 'savedishdata';
        var data = {
            'name'   : name,
            'text'   : text,
            'id'     : id,
            'action' : action,
        }
        $.ajax({
            type: "post",
            url: domain + '/admin/scene/sceneDataAdd',
            data: data,
            dataType: "json",
            success: function (data) {
               if (data[0] = 'success'){
                   location.reload();
               }
            }
        })
    })

    // 添加图片(模态框显示)
    $(".addimg").click(function () {
        $('#myModal').modal({

        });

    })

    // 模态框中显示图片
    $(document).on("click", ".addimg", function () {

        var obj = $(this).parents("tr").data(id);
        // 获取当前tr中的id
        var id = obj['id'];
        // console.log(id);

        var text = $(this).attr('name');
        console.log(text);

        var domain = "http://" + document.domain;

        var data = {
            action: 'getimgs',
            'id' : id,
            'dish_name' : text,
        };

        $.ajax({
            type: "post",
            url: domain + '/admin/scene/sceneDataAdd',
            data: data,
            dataType: "json",
            success: function (data) {
                $("#imgs").empty();
                var html = '';
                // console.log(data);

                $.each(data.img, function (key, value) {

                    html += "<img class='img' style='padding-left: 8px;' width='240' height='240' " + "dish-id = '"+ data.id + " ' img-id = '" + key + "'src='"+value +"'/>";
                })

                $("#imgs").html(html);
            }
        })
    })

    // 点击图片保存
    $(document).on("click", ".img", function () {
        var imgid  = $(this).attr("img-id");
        var dishid = $(this).attr("dish-id");
        var url    = $(this)[0].src;
        $(this).css({ "border": "2px solid red"});
        $(this).siblings().css({ "border": ""});

        // console.log(url);
        var domain = "http://" + document.domain;
        $("#saveimg").click(function () {
            url = url.replace(domain, '');
            var data = {
                action : 'setSceneDataImg',
                dishid : dishid,
                imgid  : imgid,
                url    : url
            };
            // console.log(2345);
            $('#myModal').modal('hide')
            $.ajax({
                type: "post",
                url: domain + '/admin/scene/sceneDataAdd',
                data: data,
                dataType: "json",
                success: function (data) {
                    if (data[0] = 'success'){
                        location.reload();
                    }
                }
            })


        })



    })




})



