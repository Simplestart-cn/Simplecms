    jQuery(function() {
        //可行性判断
        if ( !WebUploader.Uploader.support() ) {
            alert( 'Web Uploader 不支持您的浏览器！如果你使用的是IE浏览器，请尝试升级 flash 播放器');
            throw new Error( 'WebUploader does not support the browser you are using.' );
        }

        //创建 uploader数组
        var uploader = new Array(),
        // 优化retina, 在retina下这个值是2
        ratio = window.devicePixelRatio || 1,
        // 缩略图大小
        thumbnailWidth = 100 * ratio,
        thumbnailHeight = 100 * ratio,
        supportTransition = (function(){
            var s = document.createElement('p').style,
            r = 'transition' in s ||
                  'WebkitTransition' in s ||
                  'MozTransition' in s ||
                  'msTransition' in s ||
                  'OTransition' in s;
            s = null;
            return r;
        })();
        // 可能有pedding, ready, uploading, confirm, done.
        var state = 'pedding',
        // 所有文件的进度信息，key为file id
        percentages = new Array();
        
        //循环页面中每个上传域
        $('.uploader').each(function(index){
            var fileCount = 0,// 添加的文件数量
                fileSize = 0,// 添加的文件总大小
                fileNumLimit = $(this).data('filenumlimit'),
                pickerArea=$(this).find('.pickerArea'),//上传按钮实例
                queueList=$(this).find('.queueList'),//拖拽容器实例
                pickerBtn=$(this).find('.pickerBtn'),//继续添加按钮实例
                placeholder=$(this).find('.placeholder'),//按钮与虚线框实例
                statusBar=$(this).find('.statusBar'),//再次添加按钮容器实例
                info = statusBar.find('.info'), //提示信息容器实例
                //表单字段
                field = $(this).find('.field'),
                // 上传按钮
                uploadBtn = $(this).find('.uploadBtn'),
                // 总体进度条
                progress = statusBar.find('.progress'),
                queue = $('<ul class="filelist"></ul>').appendTo( queueList);// 图片容器      
            
            percentages[index] = {};
            //初始化上传实例
            uploader[index] = WebUploader.create({
                pick: {
                    // id: pickerArea,
                    // label: '点击选择图片'
                },
                dnd: queueList,
                //这里可以根据 index 或者其他，使用变量形式
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png,doc',
                    mimeTypes: 'image/*'
                },
                swf: GLOBALCONF.ROOT + '/template/public/js/webuploader/Uploader.swf',// swf文件路径
                server: GLOBALCONF.ROOT+'/index.php?g=asset&m=upload&a=uploadImage', //上传地址
                disableGlobalDnd: true,//禁用浏览器的拖拽功能，否则图片会被浏览器打开
                chunked: false,//是否分片处理大文件的上传
                fileNumLimit: fileNumLimit || 5,//一次最多上传文件个数
                fileSizeLimit: 10 * 1024 * 1024,    // 总共的最大限制10M
                fileSingleSizeLimit: 3 * 1024 * 1024 ,   // 单文件的最大限制3M
                auto :false,
                container:$(this)    //上传容器
            });
            //当文件加入队列时触发
            uploader[index].onFileQueued = function( file ) {
                fileCount++;
                fileSize += file.size;

                if ( fileCount === 1 ) {
                    placeholder.addClass( 'element-invisible' );
                    statusBar.show();
                }
                setState( 'ready' ,uploader[index],placeholder,queue,statusBar,pickerBtn,uploadBtn);
                addFile( file,uploader[index],queue,percentages[index]);
                updateStatus('ready',info,fileCount,fileSize);
                updateTotalProgress(progress,percentages[index]);
            };
            
            //当文件被移除队列后触发。
            uploader[index].onFileDequeued = function( file ) {
                console.trace();
                fileCount--;
                fileSize -= file.size;

                if ( !fileCount ) {
                    setState( 'pedding',uploader[index],placeholder,queue,statusBar,pickerBtn,uploadBtn);
                }              
                removeFile( file , percentages[index]);
                updateStatus('pedding',info,fileCount,fileSize);
                updateTotalProgress(progress,percentages[index]);
            };

            //更新进度条
            uploader[index].onUploadProgress = function( file, percentage ) {
                var $li = $('#'+file.id),
                    $percent = $li.find('.progress span');

                $percent.css( 'width', percentage * 100 + '%' );
                percentages[index][ file.id ][ 1 ] = percentage;
                updateTotalProgress(progress,percentages[index]);
            };
            
            //可以在这里附加额外上传数据
            uploader[index].on('uploadBeforeSend',function(object,data,header) {
                // 修改data可以控制发送哪些携带数据。
                data.name = $('#'+data.id).find('input').val();
            });

            //上传成功
            uploader[index].on('uploadSuccess',function(file,response){
                console.log(response);
                $('#'+file.id).data('aid',response.aid);
                var field_val = field.val();
                if(field_val == ''){
                    field.val(response.aid);
                }else{
                    if(fileNumLimit == 1){
                        //仅可上传一张
                        field.val(response.aid);
                    }else{
                        field_val = field_val.split(',');
                        if(field_val.indexOf(response.aid.toString()) == -1){
                            field_val.push(response.aid);
                        }
                        field.val(field_val.join(','));
                    }
                    
                }
            });
            //上传按钮
            uploadBtn.on('click', function() {
                if ( $(this).hasClass( 'disabled' ) ) {
                    return false;
                }
                if ( state === 'ready' ) {
                    uploader[index].upload();
                } else if ( state === 'paused' ) {
                    uploader[index].upload();
                } else if ( state === 'uploading' ) {
                    uploader[index].stop();
                }
            });
            uploadBtn.addClass( 'state-' + state );

            //加载的时候，页面加载的时候模拟文件加入队列进行图片的编辑回显  
            uploader[index] .on('ready',function(){
                var container = this.options.container,
                    fileList = container.find('.filelist'),
                    files = container.data('files');
                if(typeof files != 'undefined' && files != '' && files != null ){
                    for(var i = 0; i < files.length; i++){
                        fileCount++;
                        fileSize += parseInt(files[i].file_size);
                        if ( fileCount === 1 ) {
                            placeholder.addClass( 'element-invisible' );
                            queueList.addClass('filled');
                            statusBar.show();
                            progress.hide();
                        }
                        var $li = $( '<li id="' + files[i].key + '">' +
                                '<p class="imgWrap"></p>'+
                                '<p class="progress"><span></span></p>' +
                                '</li>' ).data('aid',files[i].aid),
                            $input = $('<input type="text" class="file-name" value="'+files[i].name+'">').appendTo($li),
                            $btns = $('<div class="file-panel">' +
                                '<span class="delete">删除</span></div>').appendTo( $li ),
                            $prgress = $li.find('p.progress span'),
                            $wrap = $li.find( 'p.imgWrap' ),
                            $info = $('<p class="error"></p>');
                            
                        $wrap.text( '预览中' );
                        var img = $('<img src="'+files[i].file_path+files[i].file_name+'" style="min-height:100%;">');
                        $wrap.empty().append( img );
                        percentages[ files[i].key ] = [ files[i].file_size, 0 ];
                        files[i].rotation = 0;
                        $li.append( '<span class="success"></span>' ).addClass('state-complete');
                        $li.on( 'mouseenter', function() {
                            $btns.stop().animate({height: 30});
                        });
                        $li.on( 'mouseleave', function() {
                            $btns.stop().animate({height: 0});
                        });
                        $btns.on( 'click', '.delete', function() {
                            var asset_id = $(this).parents('li').first().data('aid');
                            if(typeof asset_id  != 'undefined' && asset_id != ''){
                                fileCount--;
                                if ( !fileCount ) {
                                    placeholder.removeClass( 'element-invisible' );
                                    queueList.removeClass('filled');
                                    statusBar.hide();
                                    uploader[index].refresh();
                                }
                                var field_val = field.val();
                                if(field_val != ''){
                                    field_val = field_val.split(',');
                                    var _index = field_val.indexOf(asset_id.toString())
                                    if( _index != -1){
                                        field_val.splice(_index,1);
                                    }
                                    field.val(field_val.join(','));
                                }
                                $li.remove();
                            }else{
                                $li.remove();
                            }
                        });
                        $li.appendTo(fileList);
                        uploader[index].refresh();
                    }
                }

            });  

        });
        //创建上传按钮，放到上面的循环会有bug!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        for (var i = uploader.length - 1; i >= 0; i--) {
            var container = uploader[i].options.container,
                pickerArea = $(container).find('.pickerArea'),
                pickerBtn = $(container).find('.pickerBtn');
                filenumlimit = uploader[i].options.fileNumLimit;
            uploader[i].addButton({
                    id: pickerArea,
                    label: '点击选择图片'
                });
            if(filenumlimit > 1){
                uploader[i].addButton({
                    id: pickerBtn,
                    label: '继续添加'
                });
            }else{
                container.addClass('single');
                uploader[i].addButton({
                    id: pickerBtn,
                    label: '替换'
                });
            }
        }
        
        
        
        // 当有文件添加进来时执行，负责view的创建
        function addFile( file,uploader,queue,percentages) {
            var fileNumLimit = uploader.options.fileNumLimit;
            if(fileNumLimit == 1 && uploader.options.container.find('.imgWrap').length > 0){
                uploader.makeThumb( file, function( error, src ) {
                    var $wrap = uploader.options.container.find('.imgWrap');
                    if ( error ) {
                        $wrap.text( '不能预览' );
                        return;
                    }
                    var img = $('<img src="'+src+'">');
                    $wrap.empty().append( img );
                }, thumbnailWidth*4, thumbnailHeight*4 );
                percentages[ file.id ] = [ file.size, 0 ];
                return false;
            }
            var $li = $( '<li id="' + file.id + '">' +
                    '<p class="title">' + file.name + '</p>' +
                    '<p class="imgWrap"></p>'+
                    '<p class="progress"><span></span></p>' +
                    '</li>' ),
                $input = $('<input type="text" class="file-name" value="'+file.name+'">').appendTo($li),
                $btns = $('<div class="file-panel">' +
                    '<span class="cancel">删除</span>' +
                    '<span class="rotateRight">向右旋转</span>' +
                    '<span class="rotateLeft">向左旋转</span></div>').appendTo( $li ),
                $prgress = $li.find('p.progress span'),
                $wrap = $li.find( 'p.imgWrap' ),
                $info = $('<p class="error"></p>');
                
            $wrap.text( '预览中' );
            if(file.flog == true){
                var img = $('<img src="'+file.ret+'">');
                $wrap.empty().append( img );
            }else{
                uploader.makeThumb( file, function( error, src ) {
                    if ( error ) {
                        $wrap.text( '不能预览' );
                        return;
                    }

                    var img = $('<img src="'+src+'">');
                    $wrap.empty().append( img );
                }, thumbnailWidth, thumbnailHeight );
            }
            percentages[ file.id ] = [ file.size, 0 ];
            file.rotation = 0;
            file.on('statuschange', function( cur, prev ) {
                if ( prev === 'progress' ) {
                    $prgress.hide().width(0);
                } else if ( prev === 'queued' ) {
                    // $li.off( 'mouseenter mouseleave' );
                    // $btns.remove();
                }
                // 成功
                if ( cur === 'error' || cur === 'invalid' ) {
                    console.log( file.statusText );
                    percentages[ file.id ][ 1 ] = 1;
                } else if ( cur === 'interrupt' ) {
                    console.log( 'interrupt' );
                } else if ( cur === 'queued' ) {
                    percentages[ file.id ][ 1 ] = 0;
                } else if ( cur === 'progress' ) {
                    $info.remove();
                    $prgress.css('display', 'block');
                } else if ( cur === 'complete' ) {
                    $li.append( '<span class="success"></span>' );
                }
                $li.removeClass( 'state-' + prev ).addClass( 'state-' + cur );
            });
            

            
            $li.on( 'mouseenter', function() {
                $btns.stop().animate({height: 30});
            });

            $li.on( 'mouseleave', function() {
                $btns.stop().animate({height: 0});
            });
            

            $btns.on( 'click', 'span', function() {
                var index = $(this).index(),deg;
                switch ( index ) {
                    case 0:
                        uploader.removeFile(file);
                        return;
                    case 1:
                        file.rotation += 90;
                        break;

                    case 2:
                        file.rotation -= 90;
                        break;
                }

                if ( supportTransition ) {
                    deg = 'rotate(' + file.rotation + 'deg)';
                    $wrap.css({
                        '-webkit-transform': deg,
                        '-mos-transform': deg,
                        '-o-transform': deg,
                        'transform': deg
                    });
                } else {
                    $wrap.css( 'filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ (~~((file.rotation/90)%4 + 4)%4) +')');
                    
                }


            });

            $li.appendTo(queue);
        }
        
        
        // 负责view的销毁
        function removeFile( file ,percentages) {
            var $li = $('#'+file.id);
            delete percentages[ file.id ];
            $li.off().find('.file-panel').off().end().remove();
        }
        
        function setState( val, uploader,placeHolder,queue,statusBar,pickerBtn,uploadBtn) {
            uploadBtn.removeClass( 'state-' + state );
            uploadBtn.addClass( 'state-' + val );
            state = val;
            switch ( val ) {
                case 'pedding':
                    placeHolder.removeClass( 'element-invisible' );
                    queue.parent().removeClass('filled');
                    queue.hide();
                    statusBar.addClass( 'element-invisible' );
                    uploader.refresh();
                    break;

                case 'ready':
                    placeHolder.addClass( 'element-invisible' );
                    pickerBtn.removeClass( 'element-invisible');
                    queue.parent().addClass('filled');
                    queue.show();
                    statusBar.removeClass('element-invisible');
                    uploader.refresh();
                    break;
                case 'uploading':
                    $( '.pickerBtn' ).addClass( 'element-invisible' );
                    uploadBtn.text( '暂停上传' );
                    break;

                case 'paused':
                    uploadBtn.text( '继续上传' );
                    break;              
            }

            
        }

        function updateTotalProgress(progress,percentages) {
            var loaded = 0,
                total = 0,
                spans = progress.children(),
                percent;
            $.each( percentages, function( k, v ) {
                total += v[ 0 ];
                loaded += v[ 0 ] * v[ 1 ];
            } );
            percent = total ? loaded / total : 0;
            spans.eq( 0 ).text( Math.round( percent * 100 ) + '%' );
            spans.eq( 1 ).css( 'width', Math.round( percent * 100 ) + '%' );
            if(Math.round( percent * 100 ) >= 100){
                progress.hide(1000);
            }else{
                progress.show(500);
            }
        }
        
        function updateStatus(val,info,f_count,f_size) {
            var text = '';
            text = '选中' + f_count + '张图片，共' + WebUploader.formatSize( f_size ) + '。';
            info.html( text );
        } 
        
        
        
   
    });
