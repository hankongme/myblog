<script type="text/javascript">

    /**
     * 初始化日历插件
     */
    if($('.date').length){
        $('#body .date').each(function(index,element){
            $(this).attr("id","laydate_"+index);
            laydate.render({
                elem: '#laydate_'+index,
                event: 'focus',
                type: 'datetime',
                theme: '#62a8ea',
            });
        })

    }


</script>
