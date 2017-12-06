
/*弹出层*/
/*
 参数解释：
 title	标题
 url		请求的url
 id		需要操作的数据id
 w		弹出层宽度（缺省调默认值）
 h		弹出层高度（缺省调默认值）
 */
function layer_show(title,url,w,h){
	if (title == null || title == '') {
		title=false;
	};
	if (url == null || url == '') {
		url="404.html";
	};

	if((w == null || w == '')&&(h == null || h == '')){

		var layer_frame = layer.open({
			type: 2,
			maxmin: true,
			shade:0.4,
			title: title,
			content: url
		});
		layer.full(layer_frame);
		
	}else{
		if (w == null || w == '') {
			w='100%';
		}else{
			w=w+'px';
		};
		if (h == null || h == '') {
			h='100%';
		}else{
			h=h+'px';
		};


		layer.open({
			type: 2,
			area: [w, h],
			fix: false, //不固定
			maxmin: true,
			shade:0.4,
			title: title,
			content: url
		});
	}

}
/*关闭弹出框口*/
function layer_close(){
	var index = parent.layer.getFrameIndex(window.name);
	parent.layer.close(index);
}
