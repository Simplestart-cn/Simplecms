/**
swf上传完回调方法
uploadid dialog id
name dialog名称
textareaid 最后数据返回插入的容器id
funcName 回调函数
args 参数
module 所属模块
catid 栏目id
authkey 参数密钥，验证args
**/
function flashupload(uploadid, name, textareaid, funcName, args, module, catid, authkey) {
    var args = args ? '&args=' + args : '';
    var setting = '&module=' + module + '&catid=' + catid ;
    Wind.use("artDialog","iframeTools",function(){
        art.dialog.open(GLOBALCONF.ROOT+'index.php?a=swfupload&m=asset&g=asset' + args + setting, {
        title: name,
        id: uploadid,
        width: '220px',
        height: '150px',
        lock: true,
        fixed: true,
        background:"#CCCCCC",
        opacity:0.5,
        ok: function() {
            if (funcName) {
                funcName.apply(this, [this, textareaid]);
            } else {
                submit_ckeditor(this, textareaid);
            }
        },
        cancel: true
    });
    });
}

//缩图上传回调
function thumb_images(uploadid, returnid) {
    //取得iframe对象
    var d = uploadid.iframe.contentWindow;
    //取得选择的图片
    var in_content = d.$("#att-status").html().substring(1);
    if (in_content == '') return false;
    if (!IsImg(in_content)) {
        isalert('选择的类型必须为图片类型！');
        return false;
    }

    if ($('#' + returnid + '_preview').attr('src')) {
        $('#' + returnid + '_preview').attr('src', in_content);
    }
    $('#' + returnid).val(in_content);
}

//验证地址是否为图片
function IsImg(url) {
    var sTemp;
    var b = false;
    var opt = "jpg|gif|png|bmp|jpeg|zip|mp3";
    var s = opt.toUpperCase().split("|");
    for (var i = 0; i < s.length; i++) {
        sTemp = url.substr(url.length - s[i].length - 1);
        sTemp = sTemp.toUpperCase();
        s[i] = "." + s[i];
        if (s[i] == sTemp) {
            b = true;
            break;
        }
    }
    return b;
}

//移除指定id内容
function remove_div(id) {
    $('#' + id).remove();
}