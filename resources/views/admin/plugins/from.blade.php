<link rel="stylesheet" type="text/css" href="{{asset('styles/lib/switchery/switchery.css')}}">
<script src="{{asset('styles/lib/switchery/switchery.js')}}"></script>

<script>

    function makeSwitchery(myclass) {
        if(!myclass){
            myclass = '.js-switch';
        }
        var changeCheckboxelems = Array.prototype.slice.call(document.querySelectorAll(myclass));
        var switchery = {};
        changeCheckboxelems.forEach(function (html,index) {
            switchery[index]= new Switchery(html,{color:'#62a8ea',size:'middle'});
        });

        return switchery;
    }

    var switchery = makeSwitchery();

</script>
